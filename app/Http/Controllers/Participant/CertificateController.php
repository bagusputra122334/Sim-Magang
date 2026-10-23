<?php

namespace App\Http\Controllers\Participant;

use App\Models\Registration;
use App\Models\User;
use App\Enums\RegistrationStatus;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CertificateController extends ParticipantController
{
    /**
     * Halaman khusus Sertifikat Magang Digital Peserta.
     */
    public function index(): View
    {
        /** @var User $user */
        $user = Auth::user();

        $user = User::query()
            ->with([
                'division',
                'registrations' => function ($query): void {
                    $query->latest('tanggal_submit')
                        ->latest('id')
                        ->with(['position']);
                },
            ])
            ->findOrFail($user->id);

        $acceptedRegistration = $user->registrations
            ->first(fn ($r) => is_object($r->status) && method_exists($r->status, 'isAccepted') && $r->status->isAccepted())
            ?? $user->registrations->first(fn ($r) => is_string($r->status) && strtolower($r->status) === 'accepted');

        $latestRegistration = $user->registrations->first();
        $reg = $acceptedRegistration ?? $latestRegistration;

        $certExists = !empty($reg?->certificate_path) && Storage::disk('public')->exists($reg->certificate_path);
        $isGraduationReached = $reg ? $reg->isGraduationEligible() : false;
        $isTerminated = (bool) ($reg?->is_terminated || $reg?->operational_status === 'terminated');

        // Mentor info
        $division = $user->division;
        $mentor = User::where('role', 'pembimbing')
            ->where('division_id', $user->division_id)
            ->first();

        return view($this->viewPrefix . '.certificates.index', compact(
            'user',
            'reg',
            'certExists',
            'isGraduationReached',
            'isTerminated',
            'division',
            'mentor'
        ));
    }

    /**
     * Unduh file sertifikat kelulusan magang milik peserta yang sedang login.
     */
    public function download(int|Registration $application = null): StreamedResponse|RedirectResponse
    {
        $user = Auth::user();

        if ($application) {
            $appId = $application instanceof Registration ? $application->id : (int) $application;
            $reg = Registration::where('id', $appId)
                ->where('user_id', $user->id)
                ->first();
        } else {
            $reg = Registration::where('user_id', $user->id)
                ->whereNotNull('certificate_path')
                ->latest()
                ->first();
        }

        if (! $reg || empty($reg->certificate_path) || ! Storage::disk('public')->exists($reg->certificate_path)) {
            return back()->with('error', 'Sertifikat kelulusan magang belum tersedia atau belum diterbitkan.');
        }

        $cleanNomor = str_replace(['/', '\\', ' '], '_', $reg->nomor_pendaftaran);
        $fileName = 'Sertifikat_Kelulusan_' . $cleanNomor . '.pdf';

        return Storage::disk('public')->download($reg->certificate_path, $fileName);
    }

    /**
     * Preview sertifikat kelulusan magang di browser tab baru milik peserta yang sedang login.
     */
    public function view(int|Registration $application = null): BinaryFileResponse|RedirectResponse
    {
        $user = Auth::user();

        if ($application) {
            $appId = $application instanceof Registration ? $application->id : (int) $application;
            $reg = Registration::where('id', $appId)
                ->where('user_id', $user->id)
                ->first();
        } else {
            $reg = Registration::where('user_id', $user->id)
                ->whereNotNull('certificate_path')
                ->latest()
                ->first();
        }

        if (! $reg || empty($reg->certificate_path) || ! Storage::disk('public')->exists($reg->certificate_path)) {
            abort(404, 'Sertifikat kelulusan magang belum tersedia.');
        }

        return response()->file(Storage::disk('public')->path($reg->certificate_path), [
            'Content-Type' => 'application/pdf',
        ]);
    }
}
