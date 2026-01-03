<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('devices', function (Blueprint $table) {
            $table->id();
            $table->string('name');          // <--- Pastikan ada ini
            $table->string('mqtt_topic');    // <--- Pastikan ada ini
            $table->string('token')->unique(); // <--- Pastikan ada ini
            $table->string('table_name');    // <--- Pastikan ada ini
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devices');
    }
};
