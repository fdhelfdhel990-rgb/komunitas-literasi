<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BookController;

// Route public page
Route::view('/', 'home')->name('home');
Route::view('/about', 'about')->name('about');


// Route admin
Route::get('/login', [UserController::class, 'showLoginForm'])->name('login');
Route::post('/login', [UserController::class, 'login']);
Route::post('/logout', [UserController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');   // PLACEHOLDER: ganti saat FE sudah bikin viewnya
    })->name('dashboard');
});
