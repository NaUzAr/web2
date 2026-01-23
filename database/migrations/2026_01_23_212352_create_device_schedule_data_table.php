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
        Schema::create('device_schedule_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('device_id')->constrained()->onDelete('cascade');
            $table->string('slot_key');        // sch1, sch2, etc
            $table->time('on_time')->nullable();
            $table->integer('duration')->default(0);
            $table->integer('sector')->default(0);
            $table->string('name')->nullable();
            $table->json('days')->nullable();   // ["Min", "Sen", ...]
            $table->boolean('is_active')->default(false);
            $table->timestamps();

            $table->unique(['device_id', 'slot_key']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('device_schedule_data');
    }
};
