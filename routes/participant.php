<?php

use App\Http\Controllers\Participant\DashboardController as ParticipantDashboardController;
use App\Http\Controllers\Participant\OnboardingController;
use App\Http\Controllers\Participant\ProfileController;
use App\Http\Controllers\Participant\RegistrationController;
use App\Http\Controllers\Participant\ReplyLetterController;
use App\Models\Registration;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| PARTICIPANT ROUTES
| Prefix  : /participant (dari group routes/web.php)
| Name    : participant.*
| Middleware : auth + participant (EnsureIsParticipant)
|--------------------------------------------------------------------------
*/

Route::model('registration', Registration::class);
Route::model('application', Registration::class);

Route::get('/dashboard', [ParticipantDashboardController::class, 'index'])
    ->name('dashboard');

/*
|--------------------------------------------------------------------------
| Onboarding Wizard Routes (Pertama kali login)
|--------------------------------------------------------------------------
*/
Route::prefix('onboarding')->name('onboarding.')->group(function (): void {
    Route::get('/welcome', [OnboardingController::class, 'welcome'])->name('welcome');
    Route::get('/choose-type', [OnboardingController::class, 'chooseType'])->name('choose-type');
    Route::get('/success', [OnboardingController::class, 'success'])->name('success');
});

/*
|--------------------------------------------------------------------------
| Profile — Single Resource Profile Peserta (Milik Sendiri, tanpa parameter {profile})
|--------------------------------------------------------------------------
*/
Route::prefix('profile')->name('profile.')->group(function (): void {
    Route::get('/choose-type', [ProfileController::class, 'chooseType'])->name('choose-type');
    Route::get('/', [ProfileController::class, 'index'])->name('index');
    Route::get('/create', [ProfileController::class, 'create'])->name('create');
    Route::post('/', [ProfileController::class, 'store'])->name('store');
    Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
    Route::put('/', [ProfileController::class, 'update'])->name('update');
    Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
    Route::get('/show', [ProfileController::class, 'show'])->name('show');
});

/*
|--------------------------------------------------------------------------
| Registrations — Resource Route Pendaftaran Magang Milik Peserta
|--------------------------------------------------------------------------
*/

Route::resource('registrations', RegistrationController::class)->names([
    'index'   => 'registrations.index',
    'create'  => 'registrations.create',
    'store'   => 'registrations.store',
    'show'    => 'registrations.show',
    'edit'    => 'registrations.edit',
    'update'  => 'registrations.update',
    'destroy' => 'registrations.destroy',
])
    ->missing(function () {
        return redirect()->route('participant.registrations.index')
            ->with('error', 'Data pendaftaran magang yang Anda minta tidak ditemukan.');
    });

Route::prefix('applications')
    ->name('applications.')
    ->group(function (): void {
        Route::get('{application}/reply-letter/download', [ReplyLetterController::class, 'download'])
            ->name('reply-letter.download')
            ->missing(static function (): \Illuminate\Http\RedirectResponse {
                return redirect()->route('participant.registrations.index')
                    ->with('error', 'Pendaftaran magang untuk Surat Balasan tidak ditemukan.');
            });
    });
