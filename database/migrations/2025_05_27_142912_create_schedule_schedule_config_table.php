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
        Schema::create('dp_schedule_schedule_config', function (Blueprint $table) {
            $table->id();

            $table->foreignId('schedule_id')->constrained('dp_schedules')->onDelete('cascade');
            $table->foreignId('schedule_config_id')->constrained('dp_schedule_configs')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedule_schedule_config');
    }
};
