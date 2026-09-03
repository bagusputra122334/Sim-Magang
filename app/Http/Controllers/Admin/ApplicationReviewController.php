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
        $perPage = $request->integer('per_page', 5);
        if (! in_array($perPage, [5, 10, 15, 25, 50], true)) {
            $perPage = 5;
        }

        $filters = [
            'search'      => $request->string('search')->toString(),
            'status'      => $request->string('status')->toString(),
            'position_id' => $request->integer('position_id'),
        ];

        $daftarPendaftaran = $this->registrationService->adminSearchList($filters, $perPage);
        $pilihanPosisi    = $this->registrationRepository->getAllPilihanPosisiUntukFilterAdmin();

        $summary = [
            'total'     => $daftarPendaftaran->total(),
            'submitted' => Registration::whereStatus(RegistrationStatus::Submitted)->count(),
            'review'    => Registration::whereStatus(RegistrationStatus::UnderReview)->count(),
            'accepted'  => Registration::whereStatus(RegistrationStatus::Accepted)->count(),
            'rejected'  => Registration::whereStatus(RegistrationStatus::Rejected)->count(),
        ];

        return view('admin.applications.index', [
            'applications'    => $daftarPendaftaran,
            'filters'         => $filters,
            'perPage'         => $perPage,
            'pilihanPosisi'   => $pilihanPosisi,
            'summary'         => $summary,
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
    public function exportPdf()
    {
        $applications = \App\Models\Registration::with(['user.profile', 'position'])->latest()->get();

        // FORCE PDF ENGINE AND DOWNLOAD with Landscape orientation
        $pdf = PDF::loadView('admin.applications.pdf', compact('applications'))->setPaper('a4', 'landscape');
        return $pdf->download('Laporan_Verifikasi_Pendaftaran_Tuban.pdf');
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
            $reg = $this->registrationService->getAdminDetailWithRelationsOrFail($regId);

            $statusAwal = $reg->status->value;
            $reg = $this->registrationService->markUnderReviewOnFirstView($reg);
            $berubahJadiUnderReview = $statusAwal === RegistrationStatus::Submitted->value
                && $reg->status->isUnderReview();

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
            $reg = $this->registrationService->getAdminDetailWithRelationsOrFail($regId);

            if ($reg->isAccepted() || $reg->isRejected()) {
                return redirect()->route('admin.applications.show', $reg->id)
                    ->with(
                        'warning',
                        'Pendaftaran nomor '.$reg->nomor_pendaftaran.' sudah diverifikasi FINAL dengan status '.
                        $reg->status->label().'. Keputusan Accepted/Rejected tidak dapat diubah kembali melalui form review.'
                    );
            }

            return view('admin.applications.review', [
                'application' => $reg,
                'reg'         => $reg,
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
            $reg = $this->registrationService->getAdminDetailWithRelationsOrFail($regId);

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
