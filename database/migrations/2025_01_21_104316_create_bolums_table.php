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
        Schema::create('dp_bolums', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('birim_id')->constrained('dp_birims')->onDelete('cascade');
            $table->boolean('active')->default(true);
            $table->unsignedBigInteger('external_id')->unique()->nullable(); // Dış sistemden gelen ID
            $table->timestamps();
            $table->unique(['birim_id', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dp_bolums');
    }
};
