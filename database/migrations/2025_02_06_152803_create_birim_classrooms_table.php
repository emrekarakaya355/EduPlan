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
        Schema::create('dp_birim_classrooms', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('classroom_id');
            $table->foreign('classroom_id')->references('id')->on('dp_classrooms')->onDelete('cascade');
            $table->unsignedBigInteger('birim_id');
            $table->foreign('birim_id')->references('id')->on('dp_birims')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dp_birim_classrooms');
    }
};
