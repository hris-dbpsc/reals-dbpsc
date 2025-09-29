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
            $table->unsignedBigInteger('clientid');
            $table->boolean('app_1')->default(false);
            $table->boolean('app_2')->default(false);
            $table->boolean('app_3')->default(false);
            $table->boolean('app_4')->default(false);
            $table->boolean('app_5')->default(false);
            $table->boolean('app_6')->default(false);
            $table->boolean('app_7')->default(false);
            $table->boolean('app_8')->default(false);
            $table->boolean('app_9')->default(false);
            $table->boolean('app_10')->default(false);
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
