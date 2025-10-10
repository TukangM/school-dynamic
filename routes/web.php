<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('pages.index');
});

// Tambahkan route ini untuk preview navbar
Route::get('/navbar-preview', function () {
    return view('components.navbar');
});

// Dashboard Routes
Route::get('/dashboard', function () {
    if (!Auth::check()) {
        return view('admin.auth');
    }
    return view('admin.index');
})->name('dashboard');

Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
