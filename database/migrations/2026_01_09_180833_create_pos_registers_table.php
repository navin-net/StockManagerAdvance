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
        Schema::create('pos_registers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('sma_users');
            $table->decimal('cash_in_hand', 10, 2)->default(0);
            $table->decimal('total_cash', 10, 2)->nullable();
            $table->string('status')->default('open'); // open or closed
            $table->string('note',254)->nullable();
            $table->integer('closed_by')->nullable();
            $table->dateTime('date')->nullable();
            $table->dateTime('closed_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pos_registers');
    }
};
