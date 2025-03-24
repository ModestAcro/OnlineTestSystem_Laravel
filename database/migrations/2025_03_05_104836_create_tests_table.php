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
        Schema::create('tests', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->dateTime('start_time')->nullable();
            $table->dateTime('end_time')->nullable();
            $table->integer('duration');
            $table->integer('number_of_trials');  // Если это количество попыток, лучше использовать integer
            $table->foreignId('group_id')->constrained()->onDelete('cascade');  // Добавил constrained() и onDelete('cascade')
            $table->foreignId('teacher_id')->constrained()->onDelete('cascade');  // Добавил constrained() и onDelete('cascade')
            $table->foreignId('subject_id')->constrained()->onDelete('cascade');  // Добавил constrained() и onDelete('cascade')
            $table->foreignId('course_id')->constrained()->onDelete('cascade');  // Добавил constrained() и onDelete('cascade')
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tests');
    }
};
