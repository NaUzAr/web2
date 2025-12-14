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
        Schema::create('device_outputs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('device_id')->constrained()->onDelete('cascade');
            $table->string('output_name');       // Nama output (misal: relay_1, pump)
            $table->string('output_label');       // Label tampilan (misal: Relay 1, Pompa Air)
            $table->string('output_type');        // Tipe: boolean, number, percentage
            $table->string('unit')->nullable();   // Satuan (opsional, untuk number/percentage)
            $table->float('default_value')->default(0); // Nilai default
            $table->float('current_value')->default(0); // Nilai saat ini
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('device_outputs');
    }
};
