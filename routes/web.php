<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\PublicationController;
use App\Http\Controllers\HomeController;

// Route public page
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::view('/about', 'about')->name('about');

Route::get('/publication', [PublicationController::class, 'index'])->name('publications.index');
Route::get('/publication/{slug}', [PublicationController::class, 'show'])->name('publications.show');

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
