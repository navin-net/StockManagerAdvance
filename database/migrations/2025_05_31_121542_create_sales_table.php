<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();

            // Relationships
            $table->unsignedBigInteger('biller_id')->nullable();
            $table->unsignedBigInteger('warehouse_id')->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('cash_register_id')->nullable();

            $table->string('sale_type')->nullable();

            // Financials
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('discount_value', 10, 2)->default(0);
            $table->string('discount_type')->nullable();
            $table->decimal('total_amount', 10, 2);

            // Status
            $table->string('status');
            $table->string('payment_status')->nullable();
            $table->string('payment_method')->nullable();

            $table->date('date');
            $table->timestamps();
        });

        Schema::create('sale_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('restrict');
            $table->string('product_name')->nullable();
            $table->integer('quantity');
            $table->decimal('sale_price', 10, 2);
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales');
                Schema::dropIfExists('sale_items');
    }
};
