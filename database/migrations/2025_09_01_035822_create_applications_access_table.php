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
        Schema::create('applications_access', function (Blueprint $table) {
            $table->id();
            $table->string('clientid');
            $table->string('app_1')->nullable();
            $table->string('app_2')->nullable();
            $table->string('app_3')->nullable();
            $table->string('app_4')->nullable();
            $table->string('app_5')->nullable();
            $table->string('app_6')->nullable();
            $table->string('app_7')->nullable();
            $table->string('app_8')->nullable();
            $table->string('app_9')->nullable();
            $table->string('app_10')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications_access');
    }
};
