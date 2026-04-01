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

    // Relationships (Foreign Keys)
    $table->unsignedBigInteger('biller_id')->nullable();
    $table->unsignedBigInteger('warehouse_id')->nullable(); // Added
    $table->unsignedBigInteger('customer_id')->nullable();
    $table->unsignedBigInteger('user_id')->nullable();      // Added
    $table->unsignedBigInteger('cash_register_id')->nullable();

    // Financials & Status
    $table->decimal('total_amount', 10, 2);
    $table->string('status');               // e.g., Completed, Pending
    $table->string('payment_status')->nullable(); // Added (e.g., Paid, Partial, Due)

    // Date & System
    $table->date('date');
    $table->timestamps();


});

        Schema::create('sale_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('restrict');
            $table->integer('quantity');
            $table->decimal('sale_price', 10, 2);
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
