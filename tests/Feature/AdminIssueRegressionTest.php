<?php

namespace Tests\Feature;

use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SupplierController;
use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class AdminIssueRegressionTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Schema::create('users', function (Blueprint $table) {
            $table->id(); $table->unsignedBigInteger('company_id')->nullable();
        });
        Schema::create('companies', function (Blueprint $table) {
            $table->id(); $table->integer('group_id');
            foreach (['name', 'email', 'phone', 'address', 'city', 'street', 'number_of_houses', 'note', 'logo'] as $field) {
                $table->string($field)->nullable();
            }
        });
        Schema::create('purchases', function (Blueprint $table) {
            $table->id(); $table->unsignedBigInteger('supplier_id');
        });
        Schema::create('products', function (Blueprint $table) {
            $table->id(); $table->string('name'); $table->string('code');
            $table->integer('stock_quantity')->default(0);
            foreach (['brand_id', 'unit_id', 'category_id', 'subcategory_id', 'quality_id'] as $field) {
                $table->unsignedBigInteger($field)->nullable();
            }
            $table->string('image')->nullable(); $table->timestamps();
        });
        foreach (['brands', 'units', 'categories', 'sub_categories', 'qualitys'] as $name) {
            Schema::create($name, function (Blueprint $table) { $table->id(); $table->string('name'); });
        }
        Schema::create('product_images', function (Blueprint $table) {
            $table->id(); $table->unsignedBigInteger('product_id'); $table->string('image_review');
        });
        (require database_path('migrations/2026_10_27_000001_create_stock_adjustments_table.php'))->up();
        DB::table('users')->insert(['id' => 1]);
        $this->actingAs((new User())->forceFill(['id' => 1, 'name' => 'owner', 'group_id' => 1, 'status' => 'active']));
        DB::table('products')->insert(['id' => 1, 'name' => 'Fixture product', 'code' => 'TEST', 'stock_quantity' => 5]);
    }

    private function supplier(int $id, int $group = 5): void
    {
        DB::table('companies')->insert(['id' => $id, 'group_id' => $group, 'name' => 'Fixture', 'email' => "fixture{$id}@example.test"]);
    }

    public function test_adjustments_change_stock_and_record_history(): void
    {
        $this->postJson('/admin/products/adjustment', ['product_id' => 1, 'type' => 'increase', 'quantity' => 3, 'reason' => 'Stock count'])->assertRedirect();
        $this->assertDatabaseHas('products', ['id' => 1, 'stock_quantity' => 8]);
        $this->assertDatabaseHas('stock_adjustments', ['product_id' => 1, 'user_id' => 1, 'quantity_before' => 5, 'quantity_change' => 3, 'quantity_after' => 8]);
        $this->postJson('/admin/products/adjustment', ['product_id' => 1, 'type' => 'decrease', 'quantity' => 2, 'reason' => 'Damaged'])->assertRedirect();
        $this->assertDatabaseHas('products', ['id' => 1, 'stock_quantity' => 6]);
    }

    public function test_adjustments_cannot_make_stock_negative_and_do_not_leave_history(): void
    {
        $this->postJson('/admin/products/adjustment', ['product_id' => 1, 'type' => 'decrease', 'quantity' => 6, 'reason' => 'Count'])->assertUnprocessable()->assertJsonValidationErrors('quantity');
        $this->assertDatabaseHas('products', ['id' => 1, 'stock_quantity' => 5]);
        $this->assertDatabaseCount('stock_adjustments', 0);
    }

    public function test_adjustments_require_a_reason_and_positive_integer_quantity(): void
    {
        $this->postJson('/admin/products/adjustment', ['product_id' => 1, 'type' => 'increase', 'quantity' => 0])->assertUnprocessable()->assertJsonValidationErrors(['quantity', 'reason']);
        $this->assertDatabaseCount('stock_adjustments', 0);
    }

    public function test_supplier_edit_and_update_are_scoped_to_suppliers(): void
    {
        $this->supplier(1);
        $view = (new SupplierController())->edit('1');
        $this->assertSame(1, $view->getData()['supplier']->id);
        $this->putJson('/admin/suppliers/1', ['name' => 'Updated', 'email' => 'fixture1@example.test', 'phone' => '012345678', 'address' => 'New address', 'note' => 'Keep note'])->assertRedirect();
        $this->assertDatabaseHas('companies', ['id' => 1, 'name' => 'Updated', 'note' => 'Keep note']);
        $this->supplier(2, 4);
        $this->putJson('/admin/suppliers/2', [])->assertNotFound();
        $this->deleteJson('/admin/suppliers/2')->assertNotFound();
    }

    public function test_supplier_deletion_retains_suppliers_with_purchases(): void
    {
        $this->supplier(1);
        DB::table('purchases')->insert(['supplier_id' => 1]);
        $this->deleteJson('/admin/suppliers/1')->assertUnprocessable();
        $this->assertDatabaseHas('companies', ['id' => 1]);
    }

    public function test_supplier_bulk_deletion_is_atomic(): void
    {
        $this->supplier(1); $this->supplier(2);
        DB::table('purchases')->insert(['supplier_id' => 2]);
        $this->postJson('/admin/suppliers/bulk-delete', ['ids' => [1, 2]])->assertUnprocessable();
        $this->assertDatabaseCount('companies', 2);
        $this->deleteJson('/admin/suppliers/1')->assertOk();
        $this->assertDatabaseMissing('companies', ['id' => 1]);
    }

    public function test_product_details_preserve_products_with_missing_relationships(): void
    {
        $view = (new ProductController())->show(1);
        $this->assertSame(1, $view->getData()['product']->id);
        $this->assertNull($view->getData()['product']->unit_name);
        $this->assertNull($view->getData()['product']->category_name);
    }

    public function test_missing_avatar_uses_an_existing_fallback(): void
    {
        $user = (new User())->forceFill(['avatar' => 'avatars/missing.jpg']);
        $this->assertSame(asset('placeholder.svg'), $user->avatar_url);
        $this->assertFileExists(public_path('placeholder.svg'));
    }

    public function test_purchase_compatibility_migration_repairs_legacy_schema_and_is_repeatable(): void
    {
        $migration = require database_path('migrations/2026_10_25_000001_complete_purchase_fields.php');
        $migration->up();
        $migration->up();
        foreach (['reference', 'payment_status', 'attachments', 'note'] as $column) {
            $this->assertTrue(Schema::hasColumn('purchases', $column));
        }
    }

    public function test_portfolio_routes_are_removed(): void
    {
        $this->get('/admin/shop/portfolio')->assertNotFound();
        $this->putJson('/admin/shop/portfolio/1', [])->assertNotFound();
        $this->get('/portfolio')->assertNotFound();
    }
}
