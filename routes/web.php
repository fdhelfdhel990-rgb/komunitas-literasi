<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'home')->name('home');
Route::view('/about', 'about')->name('about');
Route::view('/publication', 'publications.index')->name('publications.index');
Route::view('/publication/semangat-20-relawan', 'publications.show')->name('publications.show');
Route::view('/digital-library', 'library.index')->name('library.index');
Route::view('/join-us', 'join-us')->name('join-us');
Route::view('/join-us/volunteer-event', 'join-us.events')->name('join-us.events');
Route::view('/join-us/volunteer-event/gerobak-angkasa', 'join-us.event-detail')->name('join-us.event-detail');

// Guest Authentication Routes (Login)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Authenticated Admin Routes
Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    Route::get('/admin/publications', function () {
        return view('admin.publications.index');
    })->name('admin.publications.index');

    Route::get('/admin/library', function () {
        return view('admin.library.index');
    })->name('admin.library.index');

    Route::get('/admin/join-us', function () {
        return view('admin.join-us.index');
    })->name('admin.join-us.index');

    Route::get('/admin/media-partners', function () {
        return view('admin.media-partners.index');
    })->name('admin.media-partners.index');

    Route::get('/admin/settings', function () {
        return view('admin.settings.index');
    })->name('admin.settings.index');

    Route::get('/admin/users', function () {
        return view('admin.users.index');
    })->name('admin.users.index');

    Route::redirect('/admin', '/admin/dashboard');
    Route::redirect('/workspace/dashboard', '/admin/dashboard');
});
