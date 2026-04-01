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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
// Core Identity
    $table->string('name');
    $table->string('first_name')->nullable();
    $table->string('last_name')->nullable();
    $table->string('email')->unique();
    $table->string('password');

    // Profile Details
    $table->date('dob')->nullable();           // Date of Birth
    $table->string('avatar')->nullable();      // Image path
    $table->string('phone')->nullable();
    $table->string('status')->default('active'); // active/inactive

    // Relationships (Foreign Keys)
    $table->unsignedBigInteger('group_id')->nullable();
    $table->unsignedBigInteger('company_id')->nullable();

    // System & Security
    $table->ipAddress('ip_address')->nullable();
    // $table->timestamp
            $table->rememberToken();
            $table->timestamps();
            // $table->foreign('role_id')->references('id')->on('role')->onDelete('set null'); // Add this line



        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
