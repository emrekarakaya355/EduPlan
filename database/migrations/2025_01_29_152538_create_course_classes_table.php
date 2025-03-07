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
        Schema::create('dp_course_classes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('course_id'); // Ders ID'si
            $table->foreign('course_id')->references('id')->on('dp_courses')->onDelete('cascade');

            $table->unsignedBigInteger('program_id')->nullable();
            $table->foreign('program_id')->references('id')->on('dp_programs')->onDelete('cascade');

            $table->unsignedBigInteger('external_id')->nullable(); // Dış sistemden gelen ID

            $table->string('branch')->nullable(); // Şube Kodu
            $table->integer('grade')->nullable(); // Sınıf seviyesi (1,2,3,4 vs.)

            $table->string('instructorName')->nullable();
            $table->string('instructorSurname')->nullable();
            $table->string('instructorEmail')->nullable();
            $table->string('instructorTitle')->nullable();

            $table->unsignedBigInteger('instructorId')->nullable();

            $table->foreign('instructorId')->references('id')->on('kimlik')->onDelete('cascade');

            $table->integer('duration')->default(4);
            $table->integer('quota')->default(0);
            $table->boolean('isScheduled')->default(false);

            $table->unique([ 'program_id','course_id','branch']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dp_course_classes');
    }
};
