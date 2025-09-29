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
        Schema::create('timelog', function (Blueprint $table) {
            // Primary key
            $table->bigIncrements('id');

            // If you have a clients table, this should reference it. Keep nullable to avoid FK issues in mixed data imports.
            $table->unsignedBigInteger('client_id')->nullable()->index();

            // If you store a user id, keep it; for legacy or external systems keep employeenumber as string
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->string('employeenumber', 64)->nullable()->index();

            // Branch or site identifier
            $table->string('branch_name')->nullable()->index();

            // Action type: clock_in / clock_out / manual / break_start / break_end etc.
            // Use enum for clarity; you can extend values later if needed.
            $table->enum('action', ['clock_in', 'clock_out', 'break_start', 'break_end', 'manual', 'other'])->default('manual')->index();

            // The actual timestamp for the log event (use separate column so created_at can be import time)
            $table->timestamp('recorded_at')->useCurrent()->index();
            $table->string('timezone', 64)->nullable();

            // Optional computed duration in seconds (for clock_out rows or aggregated data)
            $table->unsignedInteger('duration_seconds')->nullable();

            // Device and network info for auditing
            $table->string('device', 128)->nullable();
            $table->string('ip_address', 45)->nullable();

            // Optional geolocation or location name
            $table->string('location', 255)->nullable();

            // JSON for any extra metadata (geofence info, verification, raw payload)
            $table->json('meta')->nullable();

            // Timestamps for record management
            $table->timestamps();

            // Useful composite indexes for typical queries (filter by client + employee + time range)
            $table->index(['client_id', 'employeenumber', 'recorded_at'], 'timelog_client_employee_time_idx');
            $table->index(['client_id', 'user_id', 'recorded_at'], 'timelog_client_user_time_idx');
            $table->index(['client_id', 'recorded_at'], 'timelog_client_time_idx');

            // Consider partial/fulltext indexes on location/device/meta if you search them frequently (DB engine dependent)
        });

        // NOTE: For very large datasets consider DB-level partitioning (by RANGE on recorded_at or LIST on client_id),
        // or using a time-series datastore (TimescaleDB, ClickHouse) for analytics. Also consider archiving old rows to a
        // history table.
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('timelog');
    }
};
