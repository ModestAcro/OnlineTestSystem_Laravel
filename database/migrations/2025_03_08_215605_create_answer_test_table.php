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
        Schema::create('answer_test', function (Blueprint $table) {
            $table->id();
            $table->foreignId('result_test_id')->constrained('result_test'); // Внешний ключ на таблицу проб
            $table->foreignId('test_id')->constrained('tests'); // Внешний ключ на таблицу тестов
            $table->foreignId('student_id')->constrained('students'); // Внешний ключ на таблицу студентов
            $table->foreignId('question_id')->constrained('questions'); // Внешний ключ на таблицу вопросов
            $table->foreignId('answer_id')->constrained('answers'); // Внешний ключ на таблицу ответов
            $table->boolean('correct'); // Правильность ответа
            $table->decimal('points', 5, 2); // Баллы за ответ
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('answer_test');
    }
};
