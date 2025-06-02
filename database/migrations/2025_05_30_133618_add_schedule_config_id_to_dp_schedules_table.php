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
        Schema::table('dp_schedules', function (Blueprint $table) {
            $table->boolean('show_saturday')->default(false);
            $table->boolean('show_sunday')->default(false);
            $table->foreignId('schedule_config_id')->nullable()->constrained('dp_schedule_configs')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dp_schedules', function (Blueprint $table) {
            $table->dropForeign(['schedule_config_id']);
            $table->dropColumn('schedule_config_id');
            $table->dropColumn(['show_saturday', 'show_sunday']);
        });
    }
};
