<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('login');
});

// Root URL ge bandaga login page thoralu
Route::get('/', [AuthController::class, 'showLogin']);

// Login routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

// Dashboard route (Login aada udane illi redirect aguthe)
Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');
