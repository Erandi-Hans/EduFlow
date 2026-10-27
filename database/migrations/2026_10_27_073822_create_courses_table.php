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
        Schema::create('courses', function (Blueprint $table) {
            $table->bigIncrements('course_id');
            $table->string('course_name');
            $table->string('course_code')->unique(); // e.g. CS101
            $table->text('description')->nullable();

            // Many-to-One FK -> Teacher Table
            $table->unsignedBigInteger('teacher_id')->nullable();
            $table->foreign('teacher_id')->references('teacher_id')->on('teachers')->onDelete('set null');

            // System Audit Fields (created_by connects with Admin/User)
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->boolean('is_deleted')->default(false);

            // FK to Admin/User table
            $table->foreign('created_by')->references('user_id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('user_id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
