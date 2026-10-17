<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;

Route::view('/', 'home')->name('home');
Route::view('/about', 'about')->name('about');
Route::get('/digital-library', [BookController::class, 'index']);