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
        Schema::table('dp_course_classes', function (Blueprint $table) {
            $table->integer('practical_duration')->default(0);
            $table->integer('theoretical_duration')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dp_course_classes', function (Blueprint $table) {
            $table->dropColumn('practical_duration');
            $table->dropColumn('theoretical_duration');
        });
    }
};
