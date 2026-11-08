<?php

namespace App\Http\Controllers\Pembimbing;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CertificateController extends Controller
{
    /**
     * Wewenang pengelolaan dan penerbitan sertifikat magang kini dipusatkan kepada Administrator OPD.
     * Pembimbing tidak memiliki hak akses untuk menerbitkan, mengubah, mengunggah, atau menghapus sertifikat.
     */
    public function index(Request $request)
    {
        abort(403, 'Akses Ditolak: Wewenang penerbitan dan pengelolaan sertifikat magang telah dipusatkan kepada Administrator OPD.');
    }

    /**
     * Ekspor daftar sertifikat magang (pembimbing dinonaktifkan).
     */
    public function exportPdf(Request $request)
    {
        abort(403, 'Akses Ditolak: Wewenang penerbitan dan pengelolaan sertifikat magang telah dipusatkan kepada Administrator OPD.');
    }

    /**
     * Upload / Ganti file fisik Sertifikat (pembimbing dinonaktifkan).
     */
    public function upload(Request $request, $id): RedirectResponse
    {
        abort(403, 'Akses Ditolak: Wewenang penerbitan dan pengelolaan sertifikat magang telah dipusatkan kepada Administrator OPD.');
    }

    /**
     * Hapus file fisik sertifikat (pembimbing dinonaktifkan).
     */
    public function delete($id): RedirectResponse
    {
        abort(403, 'Akses Ditolak: Wewenang penerbitan dan pengelolaan sertifikat magang telah dipusatkan kepada Administrator OPD.');
    }

    /**
     * Preview sertifikat di tab baru browser (read-only jika diizinkan).
     */
    public function view($id): BinaryFileResponse|RedirectResponse
    {
        $registration = $this->resolveRegistration($id);
        $this->authorizeMentorForRegistration($registration);

        if (empty($registration->certificate_path) || ! Storage::disk('public')->exists($registration->certificate_path)) {
            return back()->with('error', 'File sertifikat belum tersedia di sistem penyimpanan.');
        }

        return response()->file(Storage::disk('public')->path($registration->certificate_path), [
            'Content-Type' => 'application/pdf',
        ]);
    }

    /**
     * Download sertifikat digital peserta magang (read-only jika diizinkan).
     */
    public function download($id): StreamedResponse|RedirectResponse
    {
        $registration = $this->resolveRegistration($id);
        $this->authorizeMentorForRegistration($registration);

        if (empty($registration->certificate_path) || ! Storage::disk('public')->exists($registration->certificate_path)) {
            return back()->with('error', 'File sertifikat belum tersedia di sistem penyimpanan.');
        }

        $cleanNomor = str_replace(['/', '\\', ' '], '_', $registration->nomor_pendaftaran);
        $downloadFileName = 'Sertifikat_Magang_' . $cleanNomor . '.pdf';

        return Storage::disk('public')->download($registration->certificate_path, $downloadFileName);
    }

    /**
     * Validasi otorisasi pembimbing terhadap peserta magang.
     */
    private function authorizeMentorForRegistration(Registration $registration): void
    {
        $user = Auth::user();
        if (! $user) {
            abort(401);
        }

        if ($user->isAdmin()) {
            return;
        }

        $internUser = $registration->user;
        $isAuthorized = false;

        if (! empty($user->division_id) && $internUser && $internUser->division_id === $user->division_id) {
            $isAuthorized = true;
        } elseif (! empty($user->nip) && $registration->position && $registration->position->mentor_nip === $user->nip) {
            $isAuthorized = true;
        } elseif (! empty($user->name) && $registration->position && $registration->position->mentor_name === $user->name) {
            $isAuthorized = true;
        }

        abort_unless($isAuthorized, 403, 'Anda tidak memiliki hak akses untuk mengelola sertifikat peserta magang di luar divisi bimbingan Anda.');
    }

    /**
     * Helper resolve registration
     */
    private function resolveRegistration($id): Registration
    {
        if ($id instanceof Registration) {
            return $id;
        }

        return Registration::with(['user.profile', 'position', 'user.division'])->findOrFail((int) $id);
    }
}
