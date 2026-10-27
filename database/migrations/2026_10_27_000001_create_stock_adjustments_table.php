<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_adjustments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->string('product_name');
            $table->string('product_code');
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->integer('quantity_before');
            $table->integer('quantity_change');
            $table->integer('quantity_after');
            $table->text('reason');
            $table->timestamps();
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('stock_adjustments');
    }
};
