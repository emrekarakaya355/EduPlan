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
            $table->boolean('is_locked')->default(false)->after('interval');
            $table->foreignId('locked_by_user_id')->nullable()->constrained('kimlik')->after('is_locked');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dp_schedules', function (Blueprint $table) {
            $table->dropForeign(['locked_by_user_id']);
            $table->dropColumn('locked_by_user_id');
            $table->dropColumn('is_locked');
        });
    }
};
