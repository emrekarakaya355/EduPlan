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
        Schema::create('dp_schedules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('program_id');
            $table->foreign('program_id')->references('id')->on('dp_programs')->onDelete('cascade');
            $table->year('year');
            $table->enum('semester', ['Fall', 'Spring', 'Summer']);
            $table->integer('grade');
            $table->integer('interval')->default(1);
            $table->unique(['program_id', 'year', 'semester','grade']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dp_schedules');
    }
};
