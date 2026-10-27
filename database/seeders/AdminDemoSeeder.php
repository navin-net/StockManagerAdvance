<?php

namespace Database\Seeders;

use App\Models\{Banner, Brand, Categories, Companies, Groups, Payment, Products, Purchase, Sale, SubCategory, Units, User, Warehouses, Qualitys};
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\{DB, Hash};
use Illuminate\Support\Str;

/** Explicit, repeatable demo data for reviewing the local admin interfaces. */
class AdminDemoSeeder extends Seeder
{
    public function run(): void
    {
        if (!app()->environment(['local', 'testing'])) {
            throw new \RuntimeException('AdminDemoSeeder is only available in local/testing environments.');
        }

        DB::transaction(function () {
            $owner = User::where('group_id', 1)->orderBy('id')->firstOrFail();
            // Existing role IDs are part of the application's permission model.
            foreach ([1, 2, 3, 4, 5] as $role) {
                if (!Groups::whereKey($role)->exists()) {
                    throw new \RuntimeException('Create the five application roles before loading demo data.');
                }
            }
            $extraGroups = ['Demo - Inventory Team', 'Demo - Purchasing Team', 'Demo - Sales Team', 'Demo - Accounts Team', 'Demo - Support Team'];
            foreach ($extraGroups as $name) {
                if (Groups::count() >= 10) break;
                Groups::firstOrCreate(['name' => $name]);
            }
            $catalog = [
                ['Pepper & Spice', 'Groceries', 'Seasonings', 'Bag', 'Kampot Pepper Pack', 3.50, 6.00],
                ['Mekong Textiles', 'Clothing', 'Accessories', 'Piece', 'Cotton Krama', 4.00, 8.50],
                ['Rice House', 'Pantry', 'Rice & Grains', 'Kilogram', 'Jasmine Rice', 0.80, 1.50],
                ['Palm Harvest', 'Sweeteners', 'Natural Sugar', 'Jar', 'Palm Sugar Jar', 1.50, 3.25],
                ['Khmer Botanics', 'Wellness', 'Essential Oils', 'Bottle', 'Lemongrass Oil', 5.50, 12.00],
                ['Angkor Refresh', 'Beverages', 'Drinks', 'Can', 'Sparkling Lime', 0.40, 0.90],
                ['Rattan Living', 'Homeware', 'Storage', 'Basket', 'Handwoven Basket', 4.50, 9.50],
                ['Siem Reap Soap', 'Personal Care', 'Bath & Body', 'Bar', 'Natural Soap', 1.20, 3.00],
                ['Tropical Pantry', 'Snacks', 'Dried Fruit', 'Pack', 'Dried Mango', 1.30, 2.75],
                ['Lotus Stationery', 'Office', 'Notebooks', 'Box', 'Recycled Notebook', 1.00, 2.50],
            ];
            $staff = [];
            foreach ($catalog as $index => [$brandName, $categoryName, $subcategoryName, $unitName, $productName, $cost, $price]) {
                $number = $index + 1;
                $key = sprintf('%02d', $number);
                $brand = Brand::firstOrCreate(['code' => 'DEMO-BRAND-' . $key], [
                    'name' => 'Demo - ' . $brandName, 'slug' => 'demo-brand-' . $key, 'description' => 'Sample brand for admin preview.',
                ]);
                $category = Categories::firstOrCreate(['slug' => 'demo-category-' . $key], ['name' => 'Demo - ' . $categoryName]);
                $subcategory = SubCategory::firstOrCreate(['category_id' => $category->id, 'name' => 'Demo - ' . $subcategoryName]);
                $unit = Units::firstOrCreate(['slug' => 'demo-unit-' . $key], ['name' => 'Demo - ' . $unitName]);
                $quality = Qualitys::firstOrCreate(['name' => 'Demo - Grade ' . $key], ['description' => 'Sample quality grade.']);
                $warehouse = Warehouses::firstOrCreate(['name' => 'Demo - Warehouse ' . $key], [
                    'location' => ['Phnom Penh', 'Siem Reap', 'Battambang', 'Kampot', 'Kep'][$index % 5], 'note' => 'Sample warehouse for admin preview.',
                ]);
                $user = User::firstOrCreate(['email' => 'demo.staff.' . $key . '@example.test'], [
                    'name' => 'Demo Staff ' . $key, 'first_name' => 'Demo', 'last_name' => 'Staff ' . $key,
                    'password' => Hash::make(Str::random(48)), 'group_id' => $index % 2 ? 3 : 2,
                    'company_id' => null, 'status' => $index % 4 === 3 ? '0' : '1',
                ]);
                $staff[] = $user;
                Banner::firstOrCreate(['title' => 'Demo - Banner ' . $key], ['status' => $index % 2 === 0, 'link' => '/admin/products']);
                $parties = [];
                foreach (['biller' => 2, 'customer' => 4, 'supplier' => 5] as $kind => $groupId) {
                    $parties[$kind] = Companies::firstOrCreate(['email' => 'demo.' . $kind . '.' . $key . '@example.test'], [
                        'name' => 'Demo - ' . ucfirst($kind) . ' ' . $key, 'group_id' => $groupId,
                        'group_name' => ucfirst($kind), 'warehouse_id' => $warehouse->id,
                        'phone' => '0125500' . $key, 'address' => 'Demo address, Street ' . $number,
                        'city' => $warehouse->location, 'street' => (string) $number, 'number_of_houses' => $key,
                        'country' => 'Cambodia', 'note' => 'Sample ' . $kind . ' for admin preview.',
                    ]);
                }
                $product = Products::firstOrCreate(['code' => 'DEMO-PRODUCT-' . $key], [
                    'name' => 'Demo - ' . $productName, 'second_name' => $productName,
                    'brand_id' => $brand->id, 'category_id' => $category->id, 'subcategory_id' => $subcategory->id,
                    'quality_id' => $quality->id, 'unit_id' => $unit->id,
                    'cost_price' => $cost, 'selling_price' => $price, 'stock_quantity' => 0,
                    'description' => 'Sample product for reviewing inventory, purchases and sales.',
                ]);
                $date = now()->startOfDay()->addHours(9)->addMinutes($index * 15);
                $reason = 'Demo opening stock ' . $key;
                if (!DB::table('stock_adjustments')->where('reason', $reason)->where('product_id', $product->id)->exists()) {
                    $before = (int) $product->stock_quantity;
                    $product->increment('stock_quantity', 100);
                    DB::table('stock_adjustments')->insert([
                        'product_id' => $product->id, 'product_name' => $product->name, 'product_code' => $product->code,
                        'user_id' => $owner->id, 'quantity_before' => $before, 'quantity_change' => 100,
                        'quantity_after' => $before + 100, 'reason' => $reason, 'created_at' => $date, 'updated_at' => $date,
                    ]);
                }
                $purchase = Purchase::firstOrCreate(['reference' => 'DEMO-PUR-' . $key], [
                    'supplier_id' => $parties['supplier']->id, 'total_amount' => round(25 * $cost, 2),
                    'date' => $date->toDateString(), 'status' => $index % 5 === 4 ? 'pending' : 'completed',
                    'payment_status' => 'unpaid', 'note' => 'Sample purchase for admin preview.',
                    'created_at' => $date, 'updated_at' => $date,
                ]);
                if ($purchase->wasRecentlyCreated) {
                    $purchase->forceFill(['created_at' => $date, 'updated_at' => $date])->save();
                    $purchase->items()->create(['product_id' => $product->id, 'quantity' => 25, 'cost_price' => $cost]);
                    if ($purchase->status === 'completed') $product->increment('stock_quantity', 25);
                    if ($purchase->status === 'completed' && $index % 2 === 0) {
                        Payment::create(['purchase_id' => $purchase->id, 'reference' => 'DEMO-PUR-PAY-' . $key,
                            'method' => 'bank', 'amount' => $purchase->total_amount, 'status' => 'completed',
                            'paid_at' => $date, 'created_by' => $owner->id]);
                        $purchase->update(['payment_status' => 'paid']);
                    }
                }
                $quantity = $number % 3 + 1;
                $total = round($quantity * $price, 2);
                $status = 'completed';
                $sale = Sale::firstOrCreate(['reference' => 'DEMO-SALE-' . $key], [
                    'biller_id' => $parties['biller']->id, 'customer_id' => $parties['customer']->id,
                    'warehouse_id' => $warehouse->id, 'user_id' => $owner->id, 'sale_type' => null,
                    'subtotal' => $total, 'total_amount' => $total, 'status' => $status, 'payment_status' => 'pending',
                    'date' => $date, 'created_at' => $date, 'updated_at' => $date,
                ]);
                if ($sale->wasRecentlyCreated) {
                    $sale->forceFill(['created_at' => $date, 'updated_at' => $date])->save();
                    $sale->items()->create(['product_id' => $product->id, 'product_name' => $product->name,
                        'quantity' => $quantity, 'sale_price' => $price, 'subtotal' => $total]);
                    if ($status !== 'cancelled') $product->decrement('stock_quantity', $quantity);
                    if ($status !== 'cancelled') {
                        $amount = $index % 2 ? round($total / 2, 2) : $total;
                        Payment::create(['sale_id' => $sale->id, 'reference' => 'DEMO-SALE-PAY-' . $key,
                            'method' => ['cash', 'card', 'qr'][$index % 3], 'amount' => $amount,
                            'status' => 'completed', 'paid_at' => $date, 'created_by' => $owner->id]);
                        $sale->update(['payment_status' => $amount < $total ? 'partial' : 'completed']);
                    }
                }
            }
            // Closed examples never interfere with the owner's active register.
            for ($i = 1; DB::table('pos_registers')->count() < 10 && $i <= 10; $i++) {
                $reference = sprintf('DEMO-REGISTER-%02d', $i);
                if (DB::table('pos_registers')->where('reference', $reference)->exists()) continue;
                $date = now()->startOfDay()->subDays($i);
                DB::table('pos_registers')->insert([
                    'reference' => $reference, 'user_id' => $staff[($i - 1) % 10]->id,
                    'cash_in_hand' => 50, 'total_cash' => 50, 'status' => 'closed',
                    'note' => 'Sample closed register for admin preview.', 'closed_by' => $owner->id,
                    'opened_at' => $date->copy()->addHours(8), 'closed_at' => $date->copy()->addHours(17),
                ]);
            }
        });
        $this->command?->info('Demo records added. Existing records and original product stock were preserved.');
    }
}
