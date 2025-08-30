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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('clientname');
            $table->string('clientshortname');
            $table->string('clienttype')->nullable();
            $table->string('clientphoto')->nullable();
            $table->string('clientcontact')->nullable();
            $table->string('clientcontactperson')->nullable();
            $table->string('clientemail')->nullable();
            $table->string('clientaddress')->nullable();
            $table->string('clientcity')->nullable();
            $table->string('clientprovince')->nullable();
            $table->string('clientregion')->nullable();
            $table->string('clientcontractstart')->nullable();
            $table->string('clientcontractend')->nullable();
            $table->string('clientgeolocation')->nullable();
            $table->string('clientstreetview')->nullable();
            $table->string('isactive')->default('1'); // 1 for active, 0 for inactive, 2 for suspended
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
