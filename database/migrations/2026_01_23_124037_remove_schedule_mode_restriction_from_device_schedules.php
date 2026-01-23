<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Drop the check constraint if it exists (Postgres)
        // We use raw SQL because Schema builder doesn't support dropping check constraints easily
        DB::statement('ALTER TABLE device_schedules DROP CONSTRAINT IF EXISTS device_schedules_schedule_mode_check');

        // Alternatively, we could alter the enum type if it was a real enum, 
        // but the error suggests a check constraint on a varchar/text column.
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Re-add the check constraint with original values
        DB::statement("ALTER TABLE device_schedules ADD CONSTRAINT device_schedules_schedule_mode_check CHECK (schedule_mode IN ('time', 'time_duration', 'time_days', 'time_days_duration', 'time_days_sector'))");
    }
};
