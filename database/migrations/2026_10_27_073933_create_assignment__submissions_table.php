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
        Schema::create('assignment__submissions', function (Blueprint $table) {
            $table->bigIncrements('submission_id');

            // Foreign Keys
            $table->unsignedBigInteger('assignment_id');
            $table->foreign('assignment_id')->references('assignment_id')->on('assignments')->onDelete('cascade');

            $table->unsignedBigInteger('student_id');
            $table->foreign('student_id')->references('student_id')->on('students')->onDelete('cascade');

            $table->string('submission_file_path')->nullable();
            $table->timestamp('submitted_at')->useCurrent();
            $table->decimal('marks', 5, 2)->nullable();
            $table->string('status')->default('Pending'); // e.g. 'Pending', 'Graded'

            // System Audit Fields
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->boolean('is_deleted')->default(false);

            // Audit Keys Reference
            $table->foreign('created_by')->references('user_id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('user_id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignment__submissions');
    }
};
