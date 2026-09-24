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
        Schema::create('register_cash_movements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('register_id');
            $table->unsignedBigInteger('user_id');
            $table->enum('type', ['in', 'out']);
            $table->decimal('amount', 15, 2);
            $table->string('reason', 255)->nullable();

            $table->index('register_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('register_cash_movements');
    }
};
