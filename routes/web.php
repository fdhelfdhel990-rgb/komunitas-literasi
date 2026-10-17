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

// ROUTE DUMMY BUAT NGETES FITUR AJA, HAPUS SAAT VIEW ASLI UDH DIBUAT 
Route::get('/tes-invite', function (Illuminate\Http\Request $request) {
    // Data dummy seolah dimasukin dari form
    $request->merge([
        'name' => 'Admin Baru Uji Coba',
        'username' => 'adminbaru',
        'email' => 'emailku@gmail.com',
        'password' => 'inipasswordbaru123',
        'privileges' => ['publikasi' => true, 'digital_library' => false]
    ]);
    
    return app(UserController::class)->inviteAdmin($request);
})->middleware('auth');
