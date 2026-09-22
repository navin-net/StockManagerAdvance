<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->decimal('subtotal', 10, 2)->default(0)->after('cash_register_id');
            $table->decimal('discount', 10, 2)->default(0)->after('subtotal');
            $table->string('discount_type', 20)->nullable()->after('discount');
            $table->decimal('discount_value', 10, 2)->default(0)->after('discount_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sma_sales', function (Blueprint $table) {
            $table->dropColumn(['subtotal', 'discount', 'discount_type', 'discount_value']);
        });
    }
};
