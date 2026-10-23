<?php

namespace App\Http\Controllers\Admin;

use App\Mail\CertificateIssuedMail;
use App\Models\Application;
use App\Models\Registration;
use App\Support\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ApplicationController extends AdminController
{
    /**
     * Upload & terbitkan Sertifikat Kelulusan Magang Digital.
     *
     * @param Request $request
     * @param int|Registration|Application $id
     * @return RedirectResponse
     */
    public function uploadCertificate(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'certificate' => ['required', 'file', 'mimes:pdf', 'max:5120'], // Max 5MB (5120 KB)
        ], [
            'certificate.required' => 'File sertifikat wajib dipilih sebelum mengunggah.',
            'certificate.file'     => 'File yang diunggah tidak valid.',
            'certificate.mimes'    => 'File sertifikat harus berformat PDF.',
            'certificate.max'      => 'Ukuran file sertifikat kelulusan tidak boleh lebih dari 5 MB.',
        ]);

        $appId = $id instanceof Registration || $id instanceof Application ? $id->id : (int) $id;
        $application = Registration::find($appId) ?? Application::find($appId);

        if (! $application) {
            return back()->with('error', 'Data pendaftaran magang tidak ditemukan.');
        }

        $isReplacing = ! empty($application->certificate_path);

        // Hapus file fisik sertifikat lama jika sebelumnya sudah ada
        if (! empty($application->certificate_path) && Storage::disk('public')->exists($application->certificate_path)) {
            Storage::disk('public')->delete($application->certificate_path);
        }

        // Simpan file sertifikat ke disk publik dalam folder 'certificates/'
        $file = $request->file('certificate');
        $safeName = 'certificate_' . $application->id . '_' . time() . '.' . $file->getClientOriginalExtension();
        $storedPath = $file->storeAs('certificates', $safeName, 'public');

        // Update database record
        $application->certificate_path = $storedPath;
        $application->certificate_issued_at = now();
        $application->save();

        // Kirim email notifikasi ke peserta magang via Resend
        $application->loadMissing(['user.profile', 'position']);
        if (! empty($application->user?->email)) {
            try {
                Mail::to($application->user->email)->send(new CertificateIssuedMail($application, $isReplacing));
                AuditLogger::emailSent(true, CertificateIssuedMail::class, (string) $application->user->email, $application->id);
            } catch (\Throwable $e) {
                AuditLogger::emailSent(false, CertificateIssuedMail::class, (string) ($application->user->email ?? 'unknown'), $application->id, $e);
                Log::error('[Certificate] Gagal mengirim email CertificateIssuedMail: ' . $e->getMessage(), [
                    'application_id' => $application->id,
                    'user_id'        => $application->user_id,
                    'is_replacing'   => $isReplacing,
                    'error_class'    => $e::class,
                ]);
            }
        }

        $successMessage = $isReplacing
            ? 'Sertifikat Kelulusan Magang Digital berhasil diperbarui (file lama berhasil ditimpa).'
            : 'Sertifikat Kelulusan Magang Digital berhasil diunggah dan resmi diterbitkan.';

        return back()->with('success', $successMessage);
    }

    /**
     * Hapus file fisik sertifikat kelulusan dan kosongkan kolom database.
     *
     * @param int|Registration|Application $id
     * @return RedirectResponse
     */
    public function deleteCertificate($id): RedirectResponse
    {
        $appId = $id instanceof Registration || $id instanceof Application ? $id->id : (int) $id;
        $application = Registration::find($appId) ?? Application::find($appId);

        if (! $application) {
            return back()->with('error', 'Data pendaftaran magang tidak ditemukan.');
        }

        // Hapus file fisik sertifikat dari disk publik
        if (! empty($application->certificate_path) && Storage::disk('public')->exists($application->certificate_path)) {
            Storage::disk('public')->delete($application->certificate_path);
        }

        // Kosongkan kolom certificate_path dan certificate_issued_at di database
        $application->certificate_path = null;
        $application->certificate_issued_at = null;
        $application->save();

        return back()->with('success', 'Sertifikat Kelulusan Magang Digital berhasil dihapus.');
    }

    /**
     * Unduh file sertifikat kelulusan (Admin / Pembimbing).
     *
     * @param int|Registration|Application $id
     * @return StreamedResponse|RedirectResponse
     */
    public function downloadCertificate($id)
    {
        $appId = $id instanceof Registration || $id instanceof Application ? $id->id : (int) $id;
        $application = Registration::find($appId) ?? Application::findOrFail($appId);

        if (empty($application->certificate_path) || ! Storage::disk('public')->exists($application->certificate_path)) {
            return back()->with('error', 'File sertifikat belum tersedia di sistem penyimpanan.');
        }

        $cleanNomor = str_replace(['/', '\\', ' '], '_', $application->nomor_pendaftaran);
        $downloadFileName = 'Sertifikat_Magang_' . $cleanNomor . '.pdf';

        return Storage::disk('public')->download($application->certificate_path, $downloadFileName);
    }

    /**
     * Preview file sertifikat kelulusan di browser tab baru.
     *
     * @param int|Registration|Application $id
     * @return BinaryFileResponse|RedirectResponse
     */
    public function viewCertificate($id)
    {
        $appId = $id instanceof Registration || $id instanceof Application ? $id->id : (int) $id;
        $application = Registration::find($appId) ?? Application::findOrFail($appId);

        if (empty($application->certificate_path) || ! Storage::disk('public')->exists($application->certificate_path)) {
            return back()->with('error', 'File sertifikat belum tersedia di sistem penyimpanan.');
        }

        return response()->file(Storage::disk('public')->path($application->certificate_path), [
            'Content-Type' => 'application/pdf',
        ]);
    }
}
