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
        Schema::table('device_sensors', function (Blueprint $table) {
            // mqtt_key: key yang dikirim dari ESP32 (contoh: ni_PH, ni_SUHU)
            // Jika kosong, gunakan sensor_name sebagai fallback
            $table->string('mqtt_key', 50)->nullable()->after('sensor_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('device_sensors', function (Blueprint $table) {
            $table->dropColumn('mqtt_key');
        });
    }
};
