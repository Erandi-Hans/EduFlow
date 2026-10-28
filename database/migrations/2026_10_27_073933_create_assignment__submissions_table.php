<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('AssignmentSubmissions', function (Blueprint $table) {
            $table->id('submission_id');
            $table->foreignId('assignment_id')->constrained('assignments', 'assignment_id')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('Students', 'student_id')->cascadeOnDelete();
            $table->string('submission_file_path');
            $table->decimal('marks', 5, 2)->nullable();
            $table->string('status', 20)->default('submitted'); // submitted | graded
            $table->timestamps();
            $table->unique(['assignment_id', 'student_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('AssignmentSubmissions');
    }
};
