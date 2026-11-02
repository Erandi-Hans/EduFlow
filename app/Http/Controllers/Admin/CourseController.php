<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('CourseEnrollments', function (Blueprint $table) {
            $table->id('enrollment_id');
            $table->foreignId('student_id')->constrained('students', 'student_id')->cascadeOnDelete();
            $table->foreignId('course_id')->constrained('Courses', 'course_id')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['student_id', 'course_id']);   // a student can join a course only once
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('CourseEnrollments');
    }
};
