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
        Schema::create('timeoff', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('leaveclientid');
            $table->unsignedBigInteger('leavebranchid');
            $table->string('leaveby');
            $table->string('leavetype');
            $table->dateTime('leaverequestdate');
            $table->date('leavedatefrom');
            $table->date('leavedateto');
            $table->integer('leavedays');
            $table->text('leavereason')->nullable();
            $table->unsignedBigInteger('leaveapprovedby')->nullable();
            $table->dateTime('leaveapproveddate')->nullable();
            $table->string('leaveattachment')->nullable();
            $table->text('leaveremarks')->nullable();
            $table->string('leavestatus');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
