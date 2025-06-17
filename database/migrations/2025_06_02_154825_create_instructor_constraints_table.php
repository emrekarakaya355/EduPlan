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
        Schema::create('dp_instructor_constraints', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instructor_id')->constrained('kimlik')->onDelete('cascade');
            $table->tinyInteger('day_of_week')->nullable();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->text('note')->nullable();

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('kimlik')
                ->onDelete('set null');

            $table->foreignId('updated_by')
                ->nullable()
                ->constrained('kimlik')
                ->onDelete('set null');

            $table->index(['instructor_id', 'day_of_week']);
            $table->index(['day_of_week', 'start_time', 'end_time']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dp_instructor_constraints');
    }
};
