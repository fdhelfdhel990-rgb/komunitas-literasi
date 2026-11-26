<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JoinUsController;
use App\Http\Controllers\MediaPartnerController;
use App\Http\Controllers\PublicationController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\UserController;
use App\Models\Publication;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Route public page
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', function () {
    $latestPublications = Publication::where('status', 'published')
        ->latest('published_at')
        ->take(2)
        ->get();

    return view('about', compact('latestPublications'));
})->name('about');

Route::get('/publication', [PublicationController::class, 'index'])->name('publications.index');
Route::get('/publication/{slug}', [PublicationController::class, 'show'])->name('publications.show');

Route::get('/digital-library', [BookController::class, 'indexPublic'])->name('library.index');

Route::get('/join-us', [JoinUsController::class, 'indexPublic'])->name('join-us');
Route::get('/join-us/volunteer-event', [JoinUsController::class, 'indexEvents'])->name('join-us.events');
Route::get('/join-us/volunteer-event/gerobak-angkasa', [JoinUsController::class, 'showEventDetail'])->name('join-us.event-detail');

Route::get('/partnership', [MediaPartnerController::class, 'indexPublic'])->name('partnership');

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

    Route::get('/admin/publications', [PublicationController::class, 'indexAdmin'])->name('admin.publications.index');
    Route::get('/admin/publications/create', [PublicationController::class, 'createAdmin'])->name('admin.publications.create');
    Route::get('/admin/publications/{id}/edit', [PublicationController::class, 'editAdmin'])->name('admin.publications.edit');
    Route::post('/admin/publications', [PublicationController::class, 'store'])->name('admin.publications.store');
    Route::put('/admin/publications/{id}', [PublicationController::class, 'update'])->name('admin.publications.update');
    Route::delete('/admin/publications/{id}', [PublicationController::class, 'destroy'])->name('admin.publications.destroy');

    Route::get('/admin/library', [BookController::class, 'indexAdmin'])->name('admin.library.index');
    Route::get('/admin/library/create', [BookController::class, 'create'])->name('admin.library.create');
    Route::post('/admin/library', [BookController::class, 'store'])->name('admin.library.store');
    Route::get('/admin/library/{book}/edit', [BookController::class, 'edit'])->name('admin.library.edit');
    Route::put('/admin/library/{book}', [BookController::class, 'update'])->name('admin.library.update');
    Route::delete('/admin/library/{book}', [BookController::class, 'destroy'])->name('admin.library.destroy');

    Route::get('/admin/join-us', [JoinUsController::class, 'indexAdmin'])->name('admin.join-us.index');
    Route::get('/admin/join-us/events/create', [JoinUsController::class, 'createEvent'])->name('admin.join-us.events.create');
    Route::get('/admin/join-us/events/{id}/edit', [JoinUsController::class, 'editEvent'])->name('admin.join-us.events.edit');
    Route::post('/admin/join-us', [JoinUsController::class, 'updateAdmin'])->name('admin.join-us.update');
    Route::post('/admin/join-us/events', [JoinUsController::class, 'storeEvent'])->name('admin.join-us.events.store');
    Route::put('/admin/join-us/events/{id}', [JoinUsController::class, 'updateEvent'])->name('admin.join-us.events.update');
    Route::delete('/admin/join-us/events/{id}', [JoinUsController::class, 'destroyEvent'])->name('admin.join-us.events.destroy');

    Route::get('/admin/media-partners', [MediaPartnerController::class, 'indexAdmin'])->name('admin.media-partners.index');
    Route::get('/admin/media-partners/create', [MediaPartnerController::class, 'createAdmin'])->name('admin.media-partners.create');
    Route::get('/admin/media-partners/{id}/edit', [MediaPartnerController::class, 'editAdmin'])->name('admin.media-partners.edit');
    Route::post('/admin/media-partners', [MediaPartnerController::class, 'storeAdmin'])->name('admin.media-partners.store');
    Route::put('/admin/media-partners/{id}', [MediaPartnerController::class, 'updateAdmin'])->name('admin.media-partners.update');
    Route::delete('/admin/media-partners/{id}', [MediaPartnerController::class, 'destroyAdmin'])->name('admin.media-partners.destroy');

    Route::get('/admin/settings', [SettingController::class, 'indexAdmin'])->name('admin.settings.index');
    Route::post('/admin/settings', [SettingController::class, 'update'])->name('admin.settings.update');

    // Route super admin
    Route::post('/admin/invite', [UserController::class, 'inviteAdmin']);

    Route::get('/admin/users', [UserController::class, 'indexAdmin'])->name('admin.users.index');
    Route::get('/admin/users/create', [UserController::class, 'create'])->name('admin.users.create');
    Route::post('/admin/users', [UserController::class, 'store'])->name('admin.users.store');
    Route::get('/admin/users/{username}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/admin/users/{username}', [UserController::class, 'update'])->name('admin.users.update');

    Route::patch('/admin/users/{username}/deactivate', [UserController::class, 'deactivate'])->name('admin.users.deactivate');
    Route::patch('/admin/users/{username}/restore', [UserController::class, 'restore'])->name('admin.users.restore');
    Route::delete('/admin/users/{username}/force-delete', [UserController::class, 'forceDestroy'])->name('admin.users.force-delete');
    Route::post('/admin/users/{username}/transfer-super-admin', [UserController::class, 'transferSuperAdmin'])->name('admin.users.transfer-super-admin');
    
    Route::redirect('/admin', '/admin/dashboard');
    Route::redirect('/dashboard', '/admin/dashboard')->name('dashboard');
    Route::redirect('/workspace/dashboard', '/admin/dashboard');
});
