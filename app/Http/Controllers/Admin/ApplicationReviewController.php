<?php

namespace App\Http\Controllers\Admin;

use App\Enums\RegistrationStatus;
use App\Http\Requests\Admin\UpdateApplicationReviewRequest;
use App\Mail\ApplicationAcceptedMail;
use App\Notifications\ApplicationStatusUpdatedNotification;
use App\Mail\ApplicationReviewedMail;
use App\Mail\ApplicationRejectedMail;
use App\Models\Registration;
use App\Models\User;
use App\Repositories\RegistrationRepository;
use App\Services\RegistrationService;
use App\Support\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class ApplicationReviewController extends AdminController
{
    public function __construct(
        protected RegistrationService $registrationService,
        protected RegistrationRepository $registrationRepository
    ) {}

    /**
     * Index — List seluruh pendaftaran magang.
     * Fitur: Search nama / no.pendaftaran / email, Filter Status, Filter Posisi, Pagination.
     *
     * GET /admin/applications
     */
    public function index(Request $request): View
    {
        $rawPerPage = $request->input('per_page', '10');
        if ($rawPerPage === 'all' || $rawPerPage === 'semua' || $rawPerPage === '-1') {
            $perPage = 10000;
        } else {
            $perPage = (int) $rawPerPage;
            if (! in_array($perPage, [5, 10, 15, 20, 25, 50, 100, 10000], true)) {
                $perPage = 10;
            }
        }

        $filters = [
            'search'      => $request->string('search')->toString(),
            'status'      => $request->string('status')->toString(),
            'position_id' => $request->integer('position_id'),
            'year'        => $request->input('year'),
            'opd_id'      => $request->input('opd_id'),
        ];

        $user             = auth()->user();
        $roleVal          = $user?->role instanceof \App\Enums\UserRole ? $user->role->value : (string) $user?->role;
        $isSuperAdmin     = $roleVal === 'super_admin' || ($user && $user->isSuperAdmin() && \App\Models\Opd::exists());
        $opds             = $isSuperAdmin ? \App\Models\Opd::active()->orderBy('name')->get() : collect();

        // Super Admin wajib memilih Wilayah OPD (atau opsi 'all' untuk semua OPD) sebelum data ditampilkan
        if ($isSuperAdmin && empty($filters['opd_id'])) {
            $daftarPendaftaran = new \Illuminate\Pagination\LengthAwarePaginator([], 0, $perPage, 1, [
                'path'  => $request->url(),
                'query' => $request->query(),
            ]);

            $summary = [
                'total'     => 0,
                'submitted' => 0,
                'review'    => 0,
                'accepted'  => 0,
                'rejected'  => 0,
            ];
        } else {
            $daftarPendaftaran = $this->registrationService->adminSearchList($filters, $perPage);

            $baseSummaryQuery = Registration::query();
            if ($isSuperAdmin && ! empty($filters['opd_id']) && $filters['opd_id'] !== 'all') {
                $baseSummaryQuery->where('opd_id', (int) $filters['opd_id']);
            } elseif ($user && $user->isAdminOpd() && ! empty($user->opd_id)) {
                $baseSummaryQuery->where('opd_id', (int) $user->opd_id);
            }

            $summary = [
                'total'     => (clone $baseSummaryQuery)->count(),
                'submitted' => (clone $baseSummaryQuery)->whereStatus(RegistrationStatus::Submitted)->count(),
                'review'    => (clone $baseSummaryQuery)->whereStatus(RegistrationStatus::UnderReview)->count(),
                'accepted'  => (clone $baseSummaryQuery)->whereStatus(RegistrationStatus::Accepted)->count(),
                'rejected'  => (clone $baseSummaryQuery)->whereStatus(RegistrationStatus::Rejected)->count(),
            ];
        }

        $pilihanPosisi    = $this->registrationRepository->getAllPilihanPosisiUntukFilterAdmin();

        $dbYears = Registration::whereNotNull('created_at')
            ->pluck('created_at')
            ->map(fn($dt) => (int) \Illuminate\Support\Carbon::parse($dt)->format('Y'))
            ->unique()
            ->values();

        $currentYear = (int) date('Y');
        $availableYears = $dbYears->merge([$currentYear, $currentYear + 1])
            ->unique()
            ->sortDesc()
            ->values();

        return view('admin.applications.index', [
            'applications'    => $daftarPendaftaran,
            'filters'         => $filters,
            'perPage'         => $perPage,
            'pilihanPosisi'   => $pilihanPosisi,
            'availableYears'  => $availableYears,
            'summary'         => $summary,
            'isSuperAdmin'    => $isSuperAdmin,
            'opds'            => $opds,
        ]);
    }

    /**
     * Export Excel — Unduh data pendaftaran magang (.xlsx) sesuai filter aktif.
     *
     * GET /admin/applications/export
     */
    public function exportExcel(Request $request): \Symfony\Component\HttpFoundation\BinaryFileResponse|\Illuminate\Http\RedirectResponse
    {
        try {
            $filters = [
                'search'      => $request->string('search')->toString(),
                'status'      => $request->string('status')->toString(),
                'position_id' => $request->integer('position_id'),
            ];

            $timestamp = now()->format('Ymd_His');
            $statusLabel = ! empty($filters['status'])
                ? '_'.ucwords(str_replace('_', '', $filters['status']))
                : '';
            $posLabel = ! empty($filters['position_id'])
                ? '_Posisi-'.(int) $filters['position_id']
                : '';

            $filename = sprintf(
                'Data-Pendaftaran-Magang-Diskominfo-Tuban%s%s_%s.xlsx',
                $statusLabel,
                $posLabel,
                $timestamp
            );

            $export = new \App\Exports\RegistrationsExport($filters);

            \App\Support\AuditLogger::exportExcel($filters);

            return \Maatwebsite\Excel\Facades\Excel::download(
                $export,
                $filename,
                \Maatwebsite\Excel\Excel::XLSX,
                [
                    'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                ]
            );
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('[EXPORT_EXCEL] Gagal export excel: '.$e->getMessage(), [
                'exception' => $e,
            ]);

            return back()->with('error', 'Gagal mengekspor data ke Excel: '.$e->getMessage());
        }
    }

    /**
     * Export PDF — Unduh data pendaftaran magang (.pdf) dengan Kop Surat Resmi.
     *
     * GET /admin/applications/export-pdf
     */
    public function exportPdf(Request $request)
    {
        $user = $request->user();
        $roleVal = $user?->role instanceof \App\Enums\UserRole ? $user->role->value : (string) $user?->role;
        $isSuperAdmin = $roleVal === 'super_admin' || ($user && $user->isSuperAdmin() && \App\Models\Opd::exists());
        $status = $request->string('status')->trim()->toString();
        $search = $request->string('search')->trim()->toString();
        $positionId = $request->input('position_id');
        $year = $request->input('year');
        $opdId = $request->input('opd_id');

        if ($isSuperAdmin && empty($opdId)) {
            return redirect()->route('admin.applications.index')
                ->with('warning', 'Silakan pilih Wilayah OPD terlebih dahulu sebelum mengekspor laporan pendaftaran magang.');
        }

        $query = \App\Models\Registration::with(['user.profile', 'position', 'opd']);

        if ($user && $user->isAdminOpd() && ! empty($user->opd_id)) {
            $query->where('opd_id', $user->opd_id);
        } elseif (! empty($opdId) && $opdId !== 'all') {
            $query->where('opd_id', (int) $opdId);
        }

        if ($status !== '') {
            $query->where('status', $status);
        }

        if ($positionId) {
            $query->where('position_id', $positionId);
        }

        if ($request->filled('opd_id') && is_numeric($request->input('opd_id'))) {
            $query->where('opd_id', (int) $request->input('opd_id'));
        }

        if (! empty($year)) {
            $yearVal = (int) $year;
            $query->where(function ($q) use ($yearVal): void {
                $q->whereYear('tanggal_submit', $yearVal)
                    ->orWhereYear('created_at', $yearVal)
                    ->orWhereYear('periode_mulai', $yearVal);
            });
        }

        if ($search !== '') {
            $query->where(function ($q) use ($search): void {
                $q->where('nomor_pendaftaran', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($uq) use ($search): void {
                        $uq->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%")
                            ->orWhereHas('profile', function ($pq) use ($search): void {
                                $pq->where('institusi', 'like', "%{$search}%")
                                    ->orWhere('jurusan', 'like', "%{$search}%");
                            });
                    })
                    ->orWhereHas('position', function ($pq) use ($search): void {
                        $pq->where('nama_posisi', 'like', "%{$search}%");
                    });
            });
        }

        $applications = $query->latest()->get();

        $user = $request->user();
        $opd = $user && $user->isAdminOpd() ? $user->opd : ($request->filled('opd_id') ? \App\Models\Opd::find($request->input('opd_id')) : null);

        // FORCE PDF ENGINE AND DOWNLOAD with Landscape orientation
        $pdf = PDF::loadView('admin.applications.pdf', compact('applications', 'opd'))->setPaper('a4', 'landscape');
        $slug = $opd ? \Illuminate\Support\Str::slug($opd->name) : 'Semua_OPD';
        return $pdf->download('Laporan_Verifikasi_Pendaftaran_'.$slug.'_'.now()->format('Ymd_His').'.pdf');
    }

    /**
     * Show — Detail pendaftaran untuk Admin.
     * AUTO: Business Rule 1. Status Submitted -> UnderReview ketika pertama kali dibuka.
     *
     * GET /admin/applications/{application}
     */
    public function show(Request $request, int|Registration $application): View|RedirectResponse
    {
        try {
            $regId = $application instanceof Registration ? $application->id : (int) $application;
            $this->authorizeOpdAccess($request, $regId);
            $reg = $this->registrationService->getAdminDetailWithRelationsOrFail($regId);

            $statusAwal = $reg->status->value;
            $berubahJadiUnderReview = false;

            $roleVal = $request->user()?->role instanceof \App\Enums\UserRole ? $request->user()->role->value : (string) $request->user()?->role;
            $isStrictSuperAdmin = $roleVal === 'super_admin';

            // Super Admin hanya memantau secara read-only; perubahan ke UnderReview dilakukan saat Admin OPD membuka berkas
            if (! $isStrictSuperAdmin) {
                $reg = $this->registrationService->markUnderReviewOnFirstView($reg);
                $berubahJadiUnderReview = $statusAwal === RegistrationStatus::Submitted->value
                    && $reg->status->isUnderReview();
            }

            $cvUrl   = $this->registrationService->getUrlDokumen($reg->cv_path);
            $spUrl   = $this->registrationService->getUrlDokumen($reg->surat_pengantar_path);
            $pmUrl   = $this->registrationService->getUrlDokumen($reg->proposal_magang_path);
            $profil  = $reg->user?->profile ?? null;

            if ($berubahJadiUnderReview && ! empty($reg->user?->email)) {
                try {
                    Mail::to((string) $reg->user->email)->send(new ApplicationReviewedMail($reg));
                    AuditLogger::emailSent(true, ApplicationReviewedMail::class, (string) $reg->user->email, $reg->id);
                } catch (\Throwable $e) {
                    AuditLogger::emailSent(false, ApplicationReviewedMail::class, (string) $reg->user->email, $reg->id, $e);
                    Log::error('[SPRINT19] Gagal kirim ApplicationReviewedMail (Auto UnderReview) untuk Registration #'.$reg->id.' NP: '.$reg->nomor_pendaftaran, [
                        'error_message' => $e->getMessage(),
                        'error_class'   => $e::class,
                        'registration_id' => $reg->id,
                        'user_id'       => $reg->user_id,
                    ]);
                }
            }

            if ($berubahJadiUnderReview) {
                AuditLogger::write(AuditLogger::ACT_VERIFY_UNDER_REVIEW_AUTO, [
                    'registration_id'   => $reg->id,
                    'nomor_pendaftaran' => $reg->nomor_pendaftaran,
                    'before_status'     => RegistrationStatus::Submitted->value,
                    'after_status'      => RegistrationStatus::UnderReview->value,
                ]);
            }

            return view('admin.applications.show', [
                'application'           => $reg,
                'reg'                   => $reg,
                'cvUrl'                 => $cvUrl,
                'suratPengantarUrl'     => $spUrl,
                'proposalMagangUrl'     => $pmUrl,
                'profilPeserta'         => $profil,
                'berubahJadiUnderReview'=> $berubahJadiUnderReview,
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            unset($e);

            return redirect()->route('admin.applications.index')
                ->with('error', 'Data pendaftaran (application) yang Anda minta tidak ditemukan.');
        }
    }

    /**
     * Review — Tampilkan form ubah status + catatan admin.
     *
     * GET /admin/applications/{application}/review
     */
    public function review(Request $request, int|Registration $application): View|RedirectResponse
    {
        try {
            $regId = $application instanceof Registration ? $application->id : (int) $application;
            $this->authorizeOpdAccess($request, $regId);
            $reg = $this->registrationService->getAdminDetailWithRelationsOrFail($regId);

            $roleVal = auth()->user()?->role instanceof \App\Enums\UserRole ? auth()->user()->role->value : (string) auth()->user()?->role;
            if ($roleVal === 'super_admin') {
                return redirect()->route('admin.applications.show', $reg->id)
                    ->with('info', 'Mode Monitoring Super Admin: Keputusan verifikasi (Terima/Tolak) dilakukan oleh Admin OPD yang bersangkutan.');
            }

            if ($reg->isAccepted() || $reg->isRejected()) {
                return redirect()->route('admin.applications.show', $reg->id)
                    ->with(
                        'warning',
                        'Pendaftaran nomor '.$reg->nomor_pendaftaran.' sudah diverifikasi FINAL dengan status '.
                        $reg->status->label().'. Keputusan Accepted/Rejected tidak dapat diubah kembali melalui form review.'
                    );
            }

            $cvUrl = ! empty($reg->cv_path) ? (str_starts_with($reg->cv_path, 'http') ? $reg->cv_path : route('documents.view', ['registration' => $reg->id, 'type' => 'cv'])) : null;
            $spUrl = ! empty($reg->surat_pengantar_path) ? (str_starts_with($reg->surat_pengantar_path, 'http') ? $reg->surat_pengantar_path : route('documents.view', ['registration' => $reg->id, 'type' => 'surat_pengantar'])) : null;
            $pmUrl = ! empty($reg->proposal_magang_path) ? (str_starts_with($reg->proposal_magang_path, 'http') ? $reg->proposal_magang_path : route('documents.view', ['registration' => $reg->id, 'type' => 'proposal_magang'])) : null;

            return view('admin.applications.review', [
                'application'       => $reg,
                'reg'               => $reg,
                'cvUrl'             => $cvUrl,
                'suratPengantarUrl' => $spUrl,
                'proposalMagangUrl' => $pmUrl,
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            unset($e);

            return redirect()->route('admin.applications.index')
                ->with('error', 'Pendaftaran (application) yang akan direview tidak ditemukan.');
        }
    }

    /**
     * Update Review — Proses submit keputusan Accepted / Rejected via PUT method.
     *
     * PUT /admin/applications/{application}/review
     */
    public function updateReview(UpdateApplicationReviewRequest $request, int|Registration $application): RedirectResponse
    {
        try {
            $regId = $application instanceof Registration ? $application->id : (int) $application;
            $this->authorizeOpdAccess($request, $regId);
            $reg = $this->registrationService->getAdminDetailWithRelationsOrFail($regId);

            $roleVal = auth()->user()?->role instanceof \App\Enums\UserRole ? auth()->user()->role->value : (string) auth()->user()?->role;
            if ($roleVal === 'super_admin') {
                return redirect()->route('admin.applications.show', $reg->id)
                    ->with('error', 'Super Admin tidak memiliki wewenang mengubah keputusan verifikasi OPD.');
            }

            /** @var User $admin */
            $admin = $request->user();

            $validated = $request->validated();
            $this->registrationService->processAdminReview($reg, $validated, $admin);

            $statusEnum = RegistrationStatus::from($validated['status']);

            $reg->loadMissing(['user', 'position']);

            if (! empty($reg->user?->email)) {
                try {
                    if ($statusEnum === RegistrationStatus::Accepted || $statusEnum === RegistrationStatus::Rejected) {
                        $reg->user->notify(new ApplicationStatusUpdatedNotification($reg, (string) ($validated['catatan_admin'] ?? '')));
                        AuditLogger::emailSent(true, ApplicationStatusUpdatedNotification::class, (string) $reg->user->email, $reg->id);
                    }
                } catch (\Throwable $e) {
                    AuditLogger::emailSent(false, ApplicationStatusUpdatedNotification::class, (string) ($reg->user->email ?? 'unknown'), $reg->id, $e);
                    Log::error('[NOTIFICATION] Gagal dispatch ApplicationStatusUpdatedNotification ke user #'.$reg->user_id, [
                        'error_message' => $e->getMessage(),
                        'error_class'   => $e::class,
                        'registration_id' => $reg->id,
                        'user_id'       => $reg->user_id,
                        'status_target' => $statusEnum->value,
                    ]);
                }
            }

            AuditLogger::write(
                activity: $statusEnum === RegistrationStatus::Accepted
                    ? AuditLogger::ACT_VERIFY_ACCEPTED
                    : AuditLogger::ACT_VERIFY_REJECTED,
                detail: [
                    'registration_id'   => $reg->id,
                    'nomor_pendaftaran' => $reg->nomor_pendaftaran,
                    'admin_id'          => $admin->id ?? null,
                    'admin_name'        => $admin?->name ?? '-',
                    'status_final'      => $statusEnum->value,
                    'catatan_admin_len' => strlen((string) ($validated['catatan_admin'] ?? '')),
                    'catatan_admin_excerpt' => mb_substr((string) ($validated['catatan_admin'] ?? ''), 0, 80),
                ],
            );

            if ($statusEnum === RegistrationStatus::Accepted) {
                return redirect()->route('admin.applications.show', $reg->id)
                    ->with(
                        'success',
                        '✅ VERIFIKASI BERHASIL. Pendaftaran <b>'.$reg->nomor_pendaftaran.'</b> resmi status <b>ACCEPTED (Diterima)</b>. Status diterima disimpan dan data peserta tidak dapat diedit oleh peserta.'
                    );
            }

            return redirect()->route('admin.applications.show', $reg->id)
                ->with(
                    'success',
                    '❌ VERIFIKASI BERHASIL. Pendaftaran <b>'.$reg->nomor_pendaftaran.'</b> resmi status <b>REJECTED (Ditolak)</b>. Catatan admin berhasil disimpan.'
                );
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            unset($e);

            return redirect()->route('admin.applications.index')
                ->with('error', 'Pendaftaran (application) yang diverifikasi tidak ditemukan.');
        } catch (\DomainException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }
    }
}
