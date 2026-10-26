<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BookController;

// Route public page
Route::view('/', 'home')->name('home');
Route::view('/about', 'about')->name('about');
Route::view('/publication', 'publications.index')->name('publications.index');
Route::view('/publication/semangat-20-relawan', 'publications.show')->name('publications.show');
Route::resource('buku', BookController::class);
Route::get('/digital-library', [BookController::class, 'indexPublic'])->name('library.index');
Route::view('/join-us', 'join-us')->name('join-us');
Route::view('/join-us/volunteer-event', 'join-us.events')->name('join-us.events');
Route::view('/join-us/volunteer-event/gerobak-angkasa', 'join-us.event-detail')->name('join-us.event-detail');


// Route admin
Route::get('/login', [UserController::class, 'showLoginForm'])->name('login');
Route::post('/login', [UserController::class, 'login']);
Route::post('/logout', [UserController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');   // PLACEHOLDER: ganti saat FE sudah bikin viewnya
    })->name('dashboard');
});

// Route super admin
Route::post('/admin/invite', [UserController::class, 'inviteAdmin'])->middleware('auth');
