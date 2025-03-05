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
        Schema::create('dp_classrooms', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('building_id');
            $table->foreign('building_id')->references('id')->on('dp_buildings')->onDelete('cascade');
            $table->integer('class_capacity')->default(30);
            $table->integer('exam_capacity')->default(30);
            $table->boolean('is_active')->default(true);
            $table->enum('type', ['Laboratuar', 'Sınıf', 'Atölye', 'Salon', 'Ö.Ü. Odası', 'Seminer Odası', 'Anfi']);
            $table->unique(['name','building_id','type','is_active']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dp_classrooms');
    }
};
