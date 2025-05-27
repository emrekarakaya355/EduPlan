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
        Schema::create('dp_schedule_configs', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->time('start_time');
            $table->time('end_time');
            $table->integer('slot_duration'); // Dakika cinsinden (örn: 45)
            $table->integer('break_duration')->default(0); // mola süresi, dakika
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dp_schedule_configs');
    }
};
