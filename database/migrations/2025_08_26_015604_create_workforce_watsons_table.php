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
            $table->string('branchreshufflefrom', 1000)->nullable();
            $table->string('branchreshuffleto', 1000)->nullable();
            $table->string('employeesreshuffled', 1000)->nullable();
            $table->string('branchtransferfrom', 1000)->nullable();
            $table->string('branchtransferto', 1000)->nullable();
            $table->string('employeestransferred', 1000)->nullable();
            $table->string('clientremarks')->nullable();
            $table->string('status')->default('pending');
            $table->string('acknowledged')->default(false);
            $table->string('acknowledgeddate')->nullable();
            $table->string('acknowledgedby')->nullable();
            $table->string('assignedto')->nullable();
            $table->string('assignedby')->nullable();
            $table->string('assigneddate')->nullable();
            $table->string('attendedby')->nullable();
            $table->string('attendeddate')->nullable();
            $table->string('adminremarks', 1000)->nullable();
            $table->string('acremarks', 1000)->nullable();
            $table->string('acremarksdate', 255)->nullable();
            $table->string('completeddate', 255)->nullable();
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
