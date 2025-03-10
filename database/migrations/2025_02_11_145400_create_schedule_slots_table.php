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
        Schema::create('dp_schedule_slots', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('schedule_id');
            $table->foreign('schedule_id')->references('id')->on('dp_schedules');
            $table->unsignedBigInteger('course_id');
            $table->foreign('course_id')->references('id')->on('dp_course_classes')->onDelete('cascade');
            $table->unsignedBigInteger('classroom_id');
            $table->foreign('classroom_id')->references('id')->on('dp_classrooms')->onDelete('SET NULL');
            $table->time('start_time')->comment('Dersin başlangıç saati');
            $table->time('end_time')->comment('Dersin bitiş saati');
            $table->check('start_time < end_time');
            $table->unsignedTinyInteger('day')->comment('Haftanın günü (1: Pazartesi, 2: Salı, vb.)');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dp_schedule_slots');
    }
};
