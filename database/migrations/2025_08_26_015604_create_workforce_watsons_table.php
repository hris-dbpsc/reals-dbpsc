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
        Schema::create('workforce_watsons', function (Blueprint $table) {
            $table->id();
            $table->string('requestclient')->nullable();
            $table->string('requestdate')->nullable();
            $table->string('requestby')->nullable();
            $table->string('requestemail')->nullable();
            $table->string('requesttype')->nullable();
            $table->string('branchtarget')->nullable();
            $table->string('branchreshufflefrom')->nullable();
            $table->string('branchreshuffleto')->nullable();
            $table->string('employeesreshuffled')->nullable();
            $table->string('branchtransferfrom')->nullable();
            $table->string('branchtransferto')->nullable();
            $table->string('employeestransferred')->nullable();
            $table->string('clientremarks')->nullable();
            $table->string('status')->default('pending');
            $table->string('acknowledge')->default(false);
            $table->string('acknowledgedate')->nullable();
            $table->string('acknowledgeby')->nullable();
            $table->string('attendedby')->nullable();
            $table->string('attendeddate')->nullable();
            $table->string('adminremarks')->nullable();
            $table->string('token')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workforce_watsons');
    }
};
