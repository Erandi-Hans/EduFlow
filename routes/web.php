<?php

use App\Http\Controllers\Admin\CourseController as AdminCourseController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Student\AssignmentController as StudentAssignmentController;
use App\Http\Controllers\Student\CourseController as StudentCourseController;
use App\Http\Controllers\Student\SubmissionController as StudentubmissionController;
use App\Http\Controllers\Teacher\AssignmentController as TeacherAssignmentController;
use App\Http\Controllers\Teacher\CourseController as TeacherCourseController;
use App\Http\Controllers\Teacher\StudentController as TeacherStudentController;
use App\Http\Controllers\Teacher\SubmissionController as TeacherSubmissionController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => redirect()->route('dashboard'));

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    // ---------- ADMIN ----------
    Route::prefix('admin')->name('admin.')->middleware('role:Admin')->group(function () {
        Route::resource('users', AdminUserController::class)->except('show');

        Route::resource('Course', AdminCourseController::class);
        Route::post('Course/{course}/enroll', [AdminCourseController::class, 'enroll'])->name('Course.enroll');
        Route::delete('Course/{course}/Student/{student}', [AdminCourseController::class, 'unenroll'])->name('Course.unenroll');
    });

    // ---------- TEACHER ----------
    Route::prefix('teacher')->name('teacher.')->middleware('role:Teacher')->group(function () {
        Route::get('Course', [TeacherCourseController::class, 'index'])->name('Course.index');
        Route::get('Course/{course}', [TeacherCourseController::class, 'show'])->name('Course.show');
        Route::get('Student', [TeacherStudentController::class, 'index'])->name('Student.index');

        Route::resource('assignments', TeacherAssignmentController::class)->except('show');
        Route::get('assignments/{assignment}/submissions', [TeacherSubmissionController::class, 'index'])->name('submissions.index');
        Route::patch('submissions/{submission}', [TeacherSubmissionController::class, 'grade'])->name('submissions.grade');
    });

    // ---------- STUDENT ----------
    Route::prefix('student')->name('student.')->middleware('role:Student')->group(function () {
        Route::get('Course', [StudentCourseController::class, 'index'])->name('Course.index');
        Route::get('assignments', [StudentAssignmentController::class, 'index'])->name('assignments.index');

        Route::post('assignments/{assignment}/submit', [StudentubmissionController::class, 'store'])->name('submissions.store');
        Route::post('submissions/{submission}', [StudentubmissionController::class, 'update'])->name('submissions.update');
        Route::delete('submissions/{submission}', [StudentubmissionController::class, 'destroy'])->name('submissions.destroy');
    });
});
