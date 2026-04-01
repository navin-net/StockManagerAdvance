<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();
            $table->foreignId('purchase_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();
            $table->string('reference')->nullable();
            $table->string('method')->nullable();
            $table->decimal('amount', 10, 2);
            $table->enum('status', ['pending', 'completed', 'cancelled'])->default('completed');
            $table->timestamp('paid_at')->nullable();
            $table->string('attachment')->nullable();
            $table->decimal('pos_paid', 10, 2)->nullable();
            $table->decimal('pos_balance', 10, 2)->nullable();
            $table->integer('created_by');


        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
};
