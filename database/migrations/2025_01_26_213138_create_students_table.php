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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table -> string('name');
            $table -> string('surname');
            $table -> string('album_number')->unique();
            $table -> string('email')->unique();
            $table -> string('password');
            $table -> integer('year');
            $table -> string('academic_year');
            $table -> string('comments')->nullable();
            $table->foreignId('course_id')->constrained('courses')->onDelete('cascade');
            $table->boolean('must_change_password')->default(true);
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropForeign(['course_id']); // Удаляет внешний ключ
            $table->dropColumn('course_id'); // Удаляет поле course_id
        });
    }
};
