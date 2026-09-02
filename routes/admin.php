<?php

use App\Http\Controllers\Admin\ApplicationReviewController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\PositionController;
use App\Http\Controllers\Admin\ReplyLetterController;
use App\Http\Controllers\Admin\SurveyController;
use App\Models\Position;
use App\Models\Registration;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
| Prefix  : /admin (dari group routes/web.php)
| Name    : admin.*
| Middleware : auth + admin (EnsureIsAdmin)
|--------------------------------------------------------------------------
*/

Route::model('position', Position::class);
Route::model('registration', Registration::class);
Route::model('application', Registration::class);

Route::get('/dashboard', [AdminDashboardController::class, 'index'])
    ->name('dashboard');

/*
|--------------------------------------------------------------------------
| Resource CRUD: Posisi Magang (Position)
|--------------------------------------------------------------------------
*/
Route::resource('positions', PositionController::class)
    ->names([
        'index'   => 'positions.index',
        'create'  => 'positions.create',
        'store'   => 'positions.store',
        'show'    => 'positions.show',
        'edit'    => 'positions.edit',
        'update'  => 'positions.update',
        'destroy' => 'positions.destroy',
    ]);

Route::get('/positions/{position}/toggle-status', [PositionController::class, 'toggleStatus'])
    ->name('positions.toggle-status');

/*
|--------------------------------------------------------------------------
| Placeholder Routes — Pendaftaran (Sudah tidak dipakai — di-replace oleh
|   ApplicationReviewController di bawah ini).
|--------------------------------------------------------------------------
*/
Route::prefix('registrations')
    ->name('registrations.')
    ->group(function (): void {
        Route::get('/', fn () => redirect()->route('admin.applications.index', [], 301))
            ->name('index');
    });

/*
|--------------------------------------------------------------------------
| ADMIN APPLICATION VERIFICATION (ApplicationReviewController) — SPRINT 14
| Entity DB: registrations (TIDAK BOLEH UBAH MODEL / MIGRATION)
| Route Parameter RMB: {application} = Binding explisit ke App\Models\Registration (sudah didefinisikan di TOP file ini)
| Method HTTP untuk submit review: **PUT** sesuai Sprint 14 spec.
|--------------------------------------------------------------------------
*/

Route::prefix('applications')
    ->name('applications.')
    ->group(function (): void {
        Route::get('/', [ApplicationReviewController::class, 'index'])
            ->name('index');

        Route::get('/export', [ApplicationReviewController::class, 'exportExcel'])
            ->name('export');

        Route::get('{application}', [ApplicationReviewController::class, 'show'])
            ->name('show')
            ->missing(static function (): \Illuminate\Http\RedirectResponse {
                return redirect()->route('admin.applications.index')
                    ->with('error', 'Pendaftaran (Application) yang Anda minta tidak ditemukan di Database SIM-MAGANG.');
            });

        Route::get('{application}/review', [ApplicationReviewController::class, 'review'])
            ->name('review');

        Route::put('{application}/review', [ApplicationReviewController::class, 'updateReview'])
            ->name('update-review');

        Route::get('{application}/reply-letter', [ReplyLetterController::class, 'create'])
            ->name('reply-letter');

        Route::post('{application}/reply-letter', [ReplyLetterController::class, 'store'])
            ->name('reply-letter.store');

        Route::get('{application}/reply-letter/download', [ReplyLetterController::class, 'download'])
            ->name('reply-letter.download');
    });

/*
|--------------------------------------------------------------------------
| ADMIN ACTIVE INTERNS (ActiveInternController)
|--------------------------------------------------------------------------
*/
Route::prefix('active-interns')
    ->name('active-interns.')
    ->group(function (): void {
        Route::get('/', [\App\Http\Controllers\Admin\ActiveInternController::class, 'index'])
            ->name('index');

        Route::get('export', [\App\Http\Controllers\Admin\ActiveInternController::class, 'exportPdf'])
            ->name('export');

        Route::get('export_active', [\App\Http\Controllers\Admin\ActiveInternController::class, 'exportPdf'])
            ->name('export_active');

        Route::get('{id}', [\App\Http\Controllers\Admin\ActiveInternController::class, 'show'])
            ->name('show');

        Route::patch('{id}/toggle-status', [\App\Http\Controllers\Admin\ActiveInternController::class, 'toggleStatus'])
            ->name('toggle-status');
    });

/*
|--------------------------------------------------------------------------
| ADMIN SURVEI KEPUASAN (SurveyController)
|--------------------------------------------------------------------------
*/
Route::get('surveys', [SurveyController::class, 'index'])
    ->name('surveys.index');

Route::get('surveys/export', [SurveyController::class, 'exportPdf'])
    ->name('surveys.export');


