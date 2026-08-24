<?php

namespace App\Http\Controllers\Admin;

use App\Enums\RegistrationStatus;
use App\Http\Requests\Admin\UpdateRegistrationReviewRequest;
use App\Models\Registration;
use App\Models\User;
use App\Services\RegistrationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RegistrationReviewController extends AdminController
{
    public function __construct(protected RegistrationService $registrationService) {}

    /**
     * Index — List seluruh pendaftaran dengan search, filter status, pagination.
     *
     * GET /admin/applications
     */
    public function index(Request $request): View
    {
        $perPage = $request->integer('per_page', 10);
        if (! in_array($perPage, [5, 10, 25, 50], true)) {
            $perPage = 10;
        }

        $filters = [
            'search' => $request->string('search')->toString(),
            'status' => $request->string('status')->toString(),
        ];

        $registrations = $this->registrationService->adminSearchList($filters, $perPage);

        $stats = [
            'total'      => $registrations->total(),
            'submitted'  => \App\Models\Registration::where('status', RegistrationStatus::Submitted)->count(),
            'reviewing'  => \App\Models\Registration::where('status', RegistrationStatus::UnderReview)->count(),
            'accepted'   => \App\Models\Registration::where('status', RegistrationStatus::Accepted)->count(),
            'rejected'   => \App\Models\Registration::where('status', RegistrationStatus::Rejected)->count(),
        ];

        return view('admin.registrations.index', [
            'registrations'       => $registrations,
            'filters'             => $filters,
            'stats'               => $stats,
            'currentPerPage'      => $perPage,
            'currentFilterStatus' => $filters['status'],
        ]);
    }

    /**
     * Show — Detail pendaftaran untuk admin review.
     * Auto ubah status Submitted → Under Review (business rule).
     *
     * GET /admin/applications/{registration}
     */
    public function show(Registration $registration): View|RedirectResponse
    {
        try {
            $fullReg = $this->registrationService->getAdminDetailWithRelationsOrFail($registration->id);

            $wasSubmitted = $fullReg->isSubmitted();
            $reg = $this->registrationService->markUnderReviewOnFirstView($fullReg);

            $cvUrl            = $this->registrationService->getUrlDokumen($reg->cv_path);
            $suratPengantarUrl = $this->registrationService->getUrlDokumen($reg->surat_pengantar_path);
            $proposalMagangUrl = $this->registrationService->getUrlDokumen($reg->proposal_magang_path);

            $profil = $reg->user?->profile ?? null;
            $fotoProfilUrl = $profil && ! empty($profil->foto)
                ? $this->registrationService->getUrlDokumen($profil->foto)
                : null;

            return view('admin.registrations.show', [
                'reg'                => $reg,
                'cvUrl'              => $cvUrl,
                'suratPengantarUrl'  => $suratPengantarUrl,
                'proposalMagangUrl'  => $proposalMagangUrl,
                'profilPeserta'      => $profil,
                'fotoProfilUrl'      => $fotoProfilUrl,
                'justReviewed'       => $wasSubmitted && $reg->isUnderReview(),
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            unset($e);

            return redirect()->route('admin.registrations-review.index')
                ->with('error', 'Data pendaftaran magang yang Anda minta tidak ditemukan.');
        }
    }

    /**
     * Review Form — Halaman keputusan verifikasi.
     *
     * GET /admin/applications/{registration}/review
     */
    public function review(Registration $registration): View|RedirectResponse
    {
        try {
            $reg = $this->registrationService->getAdminDetailWithRelationsOrFail($registration->id);

            if ($reg->isAccepted() || $reg->isRejected()) {
                return redirect()->route('admin.registrations-review.show', $reg->id)
                    ->with(
                        'warning',
                        'Pendaftaran nomor '.$reg->nomor_pendaftaran.' sudah diverifikasi FINAL (status '.
                        $reg->status->label().'). Keputusan tidak dapat diubah melalui halaman review.'
                    );
            }

            $cvUrl            = $this->registrationService->getUrlDokumen($reg->cv_path);
            $suratPengantarUrl = $this->registrationService->getUrlDokumen($reg->surat_pengantar_path);
            $proposalMagangUrl = $this->registrationService->getUrlDokumen($reg->proposal_magang_path);
            $profil           = $reg->user?->profile ?? null;

            return view('admin.registrations.review', [
                'reg'                => $reg,
                'cvUrl'              => $cvUrl,
                'suratPengantarUrl'  => $suratPengantarUrl,
                'proposalMagangUrl'  => $proposalMagangUrl,
                'profilPeserta'      => $profil,
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            unset($e);

            return redirect()->route('admin.registrations-review.index')
                ->with('error', 'Pendaftaran yang akan direview tidak ditemukan.');
        }
    }

    /**
     * Do Review — proses keputusan Accepted / Rejected.
     *
     * POST /admin/applications/{registration}/review
     */
    public function doReview(UpdateRegistrationReviewRequest $request, Registration $registration): RedirectResponse
    {
        /** @var User $admin */
        $admin = $request->user();

        try {
            $reg = $this->registrationService->getAdminDetailWithRelationsOrFail($registration->id);

            $validated = $request->validated();
            $this->registrationService->processAdminReview($reg, $validated, $admin);

            $statusEnum = RegistrationStatus::from($validated['status']);
            if ($statusEnum === RegistrationStatus::Accepted) {
                return redirect()->route('admin.registrations-review.show', $reg->id)
                    ->with(
                        'success',
                        '🎉 Verifikasi BERHASIL! Pendaftaran nomor '.$reg->nomor_pendaftaran.' resmi status <b>Accepted</b> (Diterima).'
                    );
            }

            return redirect()->route('admin.registrations-review.show', $reg->id)
                ->with(
                    'success',
                    '✅ Verifikasi BERHASIL! Pendaftaran nomor '.$reg->nomor_pendaftaran.' resmi status <b>Rejected</b> (Ditolak). Catatan penolakan berhasil disimpan dan dapat dilihat oleh peserta.'
                );
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            unset($e);

            return redirect()->route('admin.registrations-review.index')
                ->with('error', 'Pendaftaran yang akan diverifikasi tidak ditemukan (sudah dihapus).');
        } catch (\DomainException $e) {
            return redirect()->route('admin.registrations-review.show', $registration->id)
                ->with('error', $e->getMessage());
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->route('admin.registrations-review.review', $registration->id)
                ->withErrors($e->errors())
                ->withInput();
        }
    }
}
