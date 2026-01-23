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
        Schema::create('device_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('device_id')->constrained()->onDelete('cascade');
            $table->string('schedule_name');           // Nama internal (misal: pump_schedule, fan_schedule)
            $table->string('schedule_label')->nullable(); // Label tampilan (misal: Jadwal Pompa)
            $table->string('output_key');               // Output yang dikontrol (misal: pump, fan, valve)
            $table->enum('schedule_mode', ['time', 'time_days', 'time_days_sector'])->default('time_days');
            $table->integer('max_slots')->default(8);   // Jumlah slot jadwal maksimal
            $table->integer('max_sectors')->default(1); // Jumlah sektor (untuk mode time_days_sector)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('device_schedules');
    }
};
