<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('Courses', function (Blueprint $table) {
            $table->id('course_id');
            $table->string('course_name', 120);
            $table->string('course_code', 30)->unique();
            $table->text('description')->nullable();
            $table->foreignId('teacher_id')->nullable()
                ->constrained('teachers', 'teacher_id')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Courses');
    }
};
