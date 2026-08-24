<?php

namespace App\Http\Controllers\Participant;

use App\Http\Requests\Participant\StoreRegistrationRequest;
use App\Http\Requests\Participant\UpdateRegistrationRequest;
use App\Mail\ApplicationSubmittedMail;
use App\Models\Position;
use App\Models\Registration;
use App\Services\RegistrationService;
use App\Support\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class RegistrationController extends ParticipantController
{
    public function __construct(protected RegistrationService $registrationService) {}

    public function index(Request $request): View
    {
        $user = $request->user();
        $perPage = (int) $request->input('per_page', 10);
        if ($perPage < 5) {
            $perPage = 5;
        }
        if ($perPage > 50) {
            $perPage = 50;
        }

        $registrations = $this->registrationService->getRiwayatPendaftaran($user->id, $perPage);
        $punyaProfile = $user->hasProfile();
        $bisaDaftarBaru = true;
        try {
            $this->registrationService->ensureUserBisaMendaftar($user);
        } catch (\DomainException $e) {
            $bisaDaftarBaru = false;
        }

        return view('participant.registrations.index', compact(
            'registrations',
            'punyaProfile',
            'bisaDaftarBaru'
        ));
    }

    public function create(Request $request): View|RedirectResponse
    {
        $user = $request->user();

        try {
            $this->registrationService->ensureUserBisaMendaftar($user);
        } catch (\DomainException $e) {
            return redirect()->route('participant.registrations.index')
                ->with('warning', $e->getMessage());
        }

        $positions = $this->registrationService->getPilihanPosisiAktif();

        if ($positions->isEmpty()) {
            return redirect()->route('participant.registrations.index')
                ->with('warning', 'Maaf, saat ini belum ada posisi magang yang sedang dibuka. Silakan pantau kembali nanti.');
        }

        return view('participant.registrations.create', compact('positions'));
    }

    public function store(StoreRegistrationRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $user = $request->user();

        $position = Position::findOrFail($validated['position_id']);

        try {
            $this->registrationService->ensureUserBisaMendaftar($user, $position);

            $registration = $this->registrationService->createRegistration($user, $position, $validated);
        } catch (\DomainException $e) {
            return back()->withInput()
                ->with('warning', $e->getMessage());
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            abort(403, $e->getMessage());
        } catch (\Throwable $e) {
            return back()->withInput()
                ->with('error', 'Pendaftaran gagal disimpan. Silakan coba beberapa saat lagi. Kesalahan: '.$e->getMessage());
        }

        try {
            Mail::to((string) $user->email)->send(new ApplicationSubmittedMail($registration));
            AuditLogger::emailSent(true, ApplicationSubmittedMail::class, (string) $user->email, $registration->id);
        } catch (\Throwable $e) {
            AuditLogger::emailSent(false, ApplicationSubmittedMail::class, (string) $user->email, $registration->id, $e);
            Log::error('[SPRINT19] Gagal kirim ApplicationSubmittedMail ke peserta #'.$user->id.' <'.$user->email.'>. Registration #'.$registration->id.' NP: '.$registration->nomor_pendaftaran, [
                'error_message' => $e->getMessage(),
                'error_class'   => $e::class,
                'registration_id' => $registration->id,
                'user_id'       => $user->id,
            ]);
        }

        AuditLogger::write(AuditLogger::ACT_REGISTRATION_SUBMIT, [
            'registration_id'   => $registration->id,
            'nomor_pendaftaran' => $registration->nomor_pendaftaran,
            'position_id'       => $registration->position_id,
            'nama_posisi'       => $position->nama_posisi,
        ]);

        return redirect()->route('participant.registrations.show', $registration->id)
            ->with('success',
                'Pendaftaran magang berhasil diajukan! Nomor pendaftaran Anda: '.
                '<strong class="fw-bold">'.$registration->nomor_pendaftaran.'</strong>. '.
                'Silakan pantau status pendaftaran secara berkala melalui halaman Riwayat Pendaftaran.'
            );
    }

    public function show(Request $request, Registration $registration): View
    {
        $reg = $this->registrationService->getDetailPendaftaranOrFail($registration->id, $request->user()->id);
        $dokumenUrl = [
            'cv'              => $this->registrationService->getUrlDokumen($reg->cv_path),
            'surat_pengantar' => $this->registrationService->getUrlDokumen($reg->surat_pengantar_path),
            'proposal_magang' => $this->registrationService->getUrlDokumen($reg->proposal_magang_path),
            'surat_balasan'   => $this->registrationService->getUrlDokumen($reg->surat_balasan_path),
        ];

        return view('participant.registrations.show', compact('reg', 'dokumenUrl'));
    }

    public function edit(Request $request, Registration $registration): View|RedirectResponse
    {
        try {
            $reg = $this->registrationService->getDetailPendaftaranUntukEditOrFail($registration->id, $request->user()->id);
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            return redirect()->route('participant.registrations.index')
                ->with('error', $e->getMessage());
        }

        $positions = $this->registrationService->getPilihanPosisiAktif();
        $currentPosition = $reg->position;
        if (! $positions->contains('id', $currentPosition->id)) {
            $positions->push($currentPosition);
            $positions = $positions->sortBy('nama_posisi')->values();
        }

        return view('participant.registrations.edit', compact('reg', 'positions'));
    }

    public function update(UpdateRegistrationRequest $request, Registration $registration): RedirectResponse
    {
        $validated = $request->validated();
        $userId = $request->user()->id;
        $position = Position::findOrFail($validated['position_id']);

        try {
            $reg = $this->registrationService->updateRegistration($registration, $userId, $position, $validated);
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            abort(403, $e->getMessage());
        } catch (\DomainException $e) {
            return back()->withInput()
                ->with('warning', $e->getMessage());
        } catch (\Throwable $e) {
            return back()->withInput()
                ->with('error', 'Gagal memperbarui pendaftaran. Silakan coba beberapa saat lagi. Kesalahan: '.$e->getMessage());
        }

        return redirect()->route('participant.registrations.show', $reg->id)
            ->with('success', 'Pendaftaran magang nomor <strong>'.$reg->nomor_pendaftaran.'</strong> berhasil diperbarui!');
    }

    public function destroy(Request $request, Registration $registration): RedirectResponse
    {
        try {
            $this->registrationService->hapusPendaftaran($registration, $request->user()->id);
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            return back()->with('error', $e->getMessage());
        } catch (\Throwable $e) {
            return back()->with('error', 'Gagal menghapus pendaftaran: '.$e->getMessage());
        }

        return redirect()->route('participant.registrations.index')
            ->with('info', 'Pendaftaran dengan nomor <strong>'.$registration->nomor_pendaftaran.'</strong> telah dihapus.');
    }
}
