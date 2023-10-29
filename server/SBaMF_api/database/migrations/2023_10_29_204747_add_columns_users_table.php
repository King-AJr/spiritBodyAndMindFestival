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
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone');
            $table->enum('age', ['Below 21', '21-30', '31-40', '41-50', '51-60', 'above 60']);
            $table->boolean('exercise_history');
            $table->enum('fitness_level', ['Amateur', 'intermediate', 'pro'])->nullable(true);
            $table->enum('form_of_workout', ['cardio', 'HIIT', 'strength training', 'strength weight training', 'dance', 'pilates', 'flexibility workouts']);
            $table->longText('fitness_goal');
            $table->integer('goal_timeline');
            $table->string('city');
            $table->longText('info_source');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('phone');
            $table->dropColumn('age');
            $table->dropColumn('exercise_history');
            $table->dropColumn('fitness_level');
            $table->dropColumn('form_of_workout');
            $table->dropColumn('fitness_goal');
            $table->dropColumn('goal_timeline');
            $table->dropColumn('city');
            $table->dropColumn('info_source');
        });
    }

};
