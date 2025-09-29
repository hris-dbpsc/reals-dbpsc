<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('employeenumber');
            $table->foreignId('clientid')->nullable()->constrained('clients')->onDelete('set null');
            $table->string('branchname')->nullable();
            $table->string('departmentname')->nullable();
            $table->string('position')->nullable();
            $table->string('firstname');
            $table->string('middlename')->nullable();
            $table->string('lastname');
            $table->string('suffix')->nullable();
            $table->string('dateofbirth')->nullable();
            $table->string('gender')->nullable();
            $table->string('assumptiondate')->nullable();
            $table->string('startdate')->nullable();
            $table->string('enddate')->nullable();
            $table->string('templatename')->nullable();
            $table->string('hiretype')->nullable();
            $table->string('wagetype')->nullable();
            $table->string('paymode')->nullable();
            $table->string('salaryrate')->nullable();
            $table->string('billingrate')->nullable();
            $table->string('positioncategory')->nullable();
            $table->string('leavecredits')->nullable();
            $table->string('civilstatus')->nullable();
            $table->string('address')->nullable();
            $table->string('contact')->nullable();
            $table->string('tin')->nullable();
            $table->string('sssnumber')->nullable();
            $table->string('phicnumber')->nullable();
            $table->string('hdmfnumber')->nullable();
            $table->string('lastpaydate')->nullable();
            $table->string('region')->nullable();
            $table->string('email')->unique();
            $table->string('password')->default(Hash::make('dbpsc'));
            $table->rememberToken();
            $table->string('role')->default('user'); // Default role for users
            $table->string('isactive')->default('1'); // 1 for active, 0 for inactive, 2 for suspended
            $table->string('token')->nullable(); // For password reset or API token
            $table->string('photo')->nullable();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
