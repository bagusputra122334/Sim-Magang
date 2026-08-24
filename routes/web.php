<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes — Hanya Landing Page & Global Redirect
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

Route::post('/contact', [App\Http\Controllers\ContactController::class, 'send'])->name('contact.send');
Route::get('/panduan', [App\Http\Controllers\GuideController::class, 'index'])->name('guides.index');
Route::get('/panduan/{slug}', [App\Http\Controllers\GuideController::class, 'show'])->name('guides.show');

/*
|--------------------------------------------------------------------------
| Auth Breeze Routes — login, register, logout, reset password, verify
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';

/*
|--------------------------------------------------------------------------
| Redirect Generik /dashboard → ke dashboard per-role
| (Supaya route('dashboard') bawaan Breeze & redirect intended fallback aman)
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function () {
    $user = auth()->user();

    if ($user?->isAdmin()) {
        return redirect()->route('admin.dashboard');
    }

    if ($user?->isPeserta()) {
        return redirect()->route('participant.dashboard');
    }

    return redirect('/');
})->middleware('auth')->name('dashboard');

/*
|--------------------------------------------------------------------------
| Profile Breeze Routes (shared kedua role, untuk update password/profile Breeze)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/global-search', [App\Http\Controllers\GlobalSearchController::class, 'search'])->name('global.search');
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [App\Http\Controllers\ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES GROUP
| Prefix  : /admin
| Name    : admin.*
| Middleware : auth + admin (EnsureIsAdmin)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'admin'])
    ->group(base_path('routes/admin.php'));

/*
|--------------------------------------------------------------------------
| PARTICIPANT ROUTES GROUP
| Prefix  : /participant
| Name    : participant.*
| Middleware : auth + participant (EnsureIsParticipant)
|--------------------------------------------------------------------------
*/
Route::prefix('participant')
    ->name('participant.')
    ->middleware(['auth', 'participant'])
    ->group(base_path('routes/participant.php'));

