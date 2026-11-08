<?php

namespace App\Http\Controllers\Admin;

use App\Enums\RegistrationStatus;
use App\Enums\UserRole;
use App\Models\Opd;
use App\Models\Position;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends AdminController
{
    public function index(Request $request): View
    {
        $user = auth()->user();
        $isSuperAdmin = $user && $user->isSuperAdmin();

        $selectedOpdId = null;
        $selectedOpd = null;
        $opds = collect();
        $opdSummaries = collect();

        if ($isSuperAdmin) {
            $opds = Opd::active()->orderBy('name')->get();
            if ($request->filled('opd_id') && is_numeric($request->input('opd_id'))) {
                $selectedOpdId = (int) $request->input('opd_id');
                $selectedOpd = $opds->firstWhere('id', $selectedOpdId) ?? Opd::find($selectedOpdId);
            }

            // Ringkasan per OPD khusus Super Admin
            $opdSummaries = Opd::query()
                ->where('is_active', true)
                ->withCount([
                    'positions',
                    'positions as active_positions_count' => fn ($q) => $q->where('status', \App\Enums\PositionStatus::Aktif),
                    'registrations',
                    'registrations as accepted_count' => fn ($q) => $q->where('status', RegistrationStatus::Accepted),
                    'registrations as pending_count' => fn ($q) => $q->whereIn('status', [RegistrationStatus::Submitted, RegistrationStatus::UnderReview]),
                ])
                ->orderBy('name')
                ->get();
        }

        $stats = $this->getDashboardStatistics($selectedOpdId);

        $recentQuery = Registration::query()
            ->latest('tanggal_submit')
            ->latest('id')
            ->with(['user:id,name,email', 'position:id,nama_posisi,opd_id', 'opd:id,name,code']);

        if ($selectedOpdId) {
            $recentQuery->where('opd_id', $selectedOpdId);
        }

        $recentApplications = $recentQuery->limit(10)->get();

        return view($this->viewPrefix.'.dashboard', compact(
            'stats',
            'recentApplications',
            'isSuperAdmin',
            'opds',
            'selectedOpdId',
            'selectedOpd',
            'opdSummaries'
        ));
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
    protected function getDashboardStatistics(?int $opdId = null): array
    {
        $user = auth()->user();

        $posQuery = Position::query();
        $regQuery = Registration::query();

        if ($opdId) {
            $posQuery->where('opd_id', $opdId);
            $regQuery->where('opd_id', $opdId);
        }

        $totalPositions     = (int) (clone $posQuery)->count();
        $totalPositionsAktif = (int) (clone $posQuery)
            ->where('status', \App\Enums\PositionStatus::Aktif)
            ->count();

        $userQuery = User::query()->where('role', UserRole::Peserta);
        if ($opdId) {
            $userQuery->whereHas('registrations', fn ($q) => $q->where('opd_id', $opdId));
        } elseif ($user && $user->isAdminOpd()) {
            $userQuery->whereHas('registrations', fn ($q) => $q->where('opd_id', $user->opd_id));
        }

        $totalPeserta       = (int) (clone $userQuery)->count();
        $totalPesertaVerified = (int) (clone $userQuery)
            ->whereNotNull('email_verified_at')
            ->count();

        $totalRegistrations = (int) (clone $regQuery)->count();

        $statusSubmitted    = (int) (clone $regQuery)
            ->where('status', RegistrationStatus::Submitted)->count();
        $statusUnderReview  = (int) (clone $regQuery)
            ->where('status', RegistrationStatus::UnderReview)->count();
        $statusAccepted     = (int) (clone $regQuery)
            ->where('status', RegistrationStatus::Accepted)->count();
        $statusRejected     = (int) (clone $regQuery)
            ->where('status', RegistrationStatus::Rejected)->count();

        $totalSuratBalasan = (int) (clone $regQuery)
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
