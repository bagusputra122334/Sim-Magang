<?php

namespace App\Http\Controllers\Admin;

use App\Enums\RegistrationStatus;
use App\Enums\UserRole;
use App\Models\Position;
use App\Models\Registration;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends AdminController
{
    public function index(): View
    {
        $stats = $this->getDashboardStatistics();

        $recentApplications = Registration::query()
            ->latest('tanggal_submit')
            ->latest('id')
            ->with(['user:id,name,email', 'position:id,nama_posisi'])
            ->limit(10)
            ->get();

        return view($this->viewPrefix.'.dashboard', compact('stats', 'recentApplications'));
    }

    /**
     * Kumpulkan seluruh metrik dashboard dalam satu method agar Controller ramping.
     * Hanya query aggregate count() yang efisien — hindari N+1.
     *
     * @return array{
     *     total_positions:int,
     *     total_positions_aktif:int,
     *     total_peserta:int,
     *     total_peserta_verified:int,
     *     total_registrations:int,
     *     status_submitted:int,
     *     status_under_review:int,
     *     status_accepted:int,
     *     status_rejected:int,
     *     total_surat_balasan:int,
     *     percent_accepted:float,
     *     verifikasi_pending:int
     * }
     */
    protected function getDashboardStatistics(): array
    {
        $totalPositions     = (int) Position::query()->count();
        $totalPositionsAktif = (int) Position::query()
            ->where('status', \App\Enums\PositionStatus::Aktif)
            ->count();

        $totalPeserta       = (int) User::query()->where('role', UserRole::Peserta)->count();
        $totalPesertaVerified = (int) User::query()
            ->where('role', UserRole::Peserta)
            ->whereNotNull('email_verified_at')
            ->count();

        $totalRegistrations = (int) Registration::query()->count();

        $statusSubmitted    = (int) Registration::query()
            ->where('status', RegistrationStatus::Submitted)->count();
        $statusUnderReview  = (int) Registration::query()
            ->where('status', RegistrationStatus::UnderReview)->count();
        $statusAccepted     = (int) Registration::query()
            ->where('status', RegistrationStatus::Accepted)->count();
        $statusRejected     = (int) Registration::query()
            ->where('status', RegistrationStatus::Rejected)->count();

        $totalSuratBalasan = (int) Registration::query()
            ->where('status', RegistrationStatus::Accepted)
            ->whereNotNull('surat_balasan_path')
            ->count();

        $percentAccepted = $totalRegistrations > 0
            ? (float) round(($statusAccepted / $totalRegistrations) * 100, 1)
            : 0.0;

        $verifikasiPending = $statusSubmitted + $statusUnderReview;

        return [
            'total_positions'         => $totalPositions,
            'total_positions_aktif'   => $totalPositionsAktif,
            'total_peserta'           => $totalPeserta,
            'total_peserta_verified'  => $totalPesertaVerified,
            'total_registrations'     => $totalRegistrations,
            'status_submitted'        => $statusSubmitted,
            'status_under_review'     => $statusUnderReview,
            'status_accepted'         => $statusAccepted,
            'status_rejected'         => $statusRejected,
            'total_surat_balasan'     => $totalSuratBalasan,
            'percent_accepted'        => $percentAccepted,
            'verifikasi_pending'      => $verifikasiPending,
        ];
    }
}
