<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\StoreReplyLetterRequest;
use App\Mail\ReplyLetterAvailableMail;
use App\Models\Registration;
use App\Services\RegistrationService;
use App\Support\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReplyLetterController extends AdminController
{
    public function __construct(
        protected RegistrationService $registrationService
    ) {}

    /**
     * GET /admin/applications/{application}/reply-letter
     * Menampilkan halaman upload surat balasan beserta info file (jika sudah ada).
     */
    public function create(Request $request, int|Registration $application): View|RedirectResponse
    {
        try {
            $regId = $application instanceof Registration ? $application->id : (int) $application;
            $reg = $this->registrationService->getAdminDetailWithRelationsOrFail($regId);

            $fileInfo = $this->registrationService->getSuratBalasanFileInfo($reg);
            $canUpload = $reg->isAccepted();

            return view('admin.applications.reply-letter', [
                'application' => $reg,
                'reg'         => $reg,
                'fileInfo'    => $fileInfo,
                'canUpload'   => $canUpload,
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            unset($e);

            return redirect()->route('admin.applications.index')
                ->with('error', 'Pendaftaran (application) untuk Surat Balasan tidak ditemukan.');
        }
    }

    /**
     * POST /admin/applications/{application}/reply-letter
     * Proses upload / replace Surat Balasan PDF.
     */
    public function store(StoreReplyLetterRequest $request, int|Registration $application): RedirectResponse
    {
        try {
            $regId = $application instanceof Registration ? $application->id : (int) $application;
            $reg = $this->registrationService->getAdminDetailWithRelationsOrFail($regId);

            $file = $request->file('surat_balasan');
            if (! $file) {
                return back()->withInput()->withErrors([
                    'surat_balasan' => 'File Surat Balasan tidak ditemukan pada request. Coba upload ulang.',
                ]);
            }

            $oldInfo = $this->registrationService->getSuratBalasanFileInfo($reg);
            $oldPath = $oldInfo['exists'] ? ($reg->surat_balasan_path ?? null) : null;

            $reg = $this->registrationService->uploadSuratBalasan($reg, $file);

            $newInfo = $this->registrationService->getSuratBalasanFileInfo($reg);
            $isReplace = $oldInfo['exists'] && ! empty($oldPath) && $oldPath !== $reg->surat_balasan_path;

            if (! empty($reg->user?->email)) {
                try {
                    Mail::to((string) $reg->user->email)->send(new ReplyLetterAvailableMail($reg));
                    AuditLogger::emailSent(true, ReplyLetterAvailableMail::class, (string) $reg->user->email, $reg->id);
                } catch (\Throwable $e) {
                    AuditLogger::emailSent(false, ReplyLetterAvailableMail::class, (string) ($reg->user->email ?? 'unknown'), $reg->id, $e);
                    Log::error('[SPRINT19] Gagal kirim ReplyLetterAvailableMail ('.($isReplace ? 'REPLACE' : 'BARU').') untuk Registration #'.$reg->id.' NP: '.$reg->nomor_pendaftaran, [
                        'error_message'   => $e->getMessage(),
                        'error_class'     => $e::class,
                        'registration_id' => $reg->id,
                        'user_id'         => $reg->user_id,
                        'is_replace'      => $isReplace,
                    ]);
                }
            }

            AuditLogger::write(AuditLogger::ACT_REPLY_LETTER_UPLOAD, [
                'registration_id'      => $reg->id,
                'nomor_pendaftaran'    => $reg->nomor_pendaftaran,
                'is_replace'           => $isReplace,
                'new_surat_basename'   => $newInfo['basename'] ?? null,
                'new_surat_size_bytes' => $newInfo['size_bytes'] ?? null,
                'old_path_deleted'     => $isReplace && $oldPath !== null,
            ]);

            if ($isReplace) {
                return redirect()->route('admin.applications.reply-letter', $reg->id)
                    ->with(
                        'success',
                        '✅ <b>REPLACE Surat Balasan BERHASIL.</b><br>'.
                        'File lama otomatis dihapus. Surat Balasan baru tersimpan: <code>'.$newInfo['basename'].'</code> ('.$newInfo['human_size'].').'
                    );
            }

            return redirect()->route('admin.applications.reply-letter', $reg->id)
                ->with(
                    'success',
                    '✅ Upload <b>Surat Balasan BERHASIL</b> untuk Pendaftaran '.$reg->nomor_pendaftaran.'.<br>'.
                    'File: <code>'.$newInfo['basename'].'</code> ('.$newInfo['human_size'].', Upload: '.$newInfo['last_modified'].').'
                );
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            unset($e);

            return redirect()->route('admin.applications.index')
                ->with('error', 'Pendaftaran (application) tidak ditemukan.');
        } catch (\DomainException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', 'Error upload Surat Balasan: '.$e->getMessage());
        }
    }

    /**
     * GET — Admin Preview / Download Surat Balasan (via tab baru / direct download).
     * Route: admin.applications.reply-letter.download
     */
    public function download(Request $request, int|Registration $application): RedirectResponse|StreamedResponse
    {
        try {
            $regId = $application instanceof Registration ? $application->id : (int) $application;
            $reg = $this->registrationService->getAdminDetailWithRelationsOrFail($regId);

            $dl = $this->registrationService->getSuratBalasanForAdminDownload($reg);

            return response()->streamDownload(function () use ($dl): void {
                $stream = fopen($dl['disk']->path($dl['path']), 'rb');
                if (! $stream) {
                    throw new \RuntimeException('Gagal membuka file Surat Balasan untuk stream download.');
                }
                while (! feof($stream)) {
                    echo fread($stream, 8192);
                    if (ob_get_level() > 0) {
                        ob_flush();
                    }
                    flush();
                }
                fclose($stream);
            }, $dl['filename_download'], [
                'Content-Type'        => 'application/pdf',
                'Cache-Control'       => 'public, max-age=0',
                'Content-Disposition' => 'attachment; filename="'.$dl['filename_download'].'"',
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            unset($e);

            return redirect()->route('admin.applications.index')
                ->with('error', 'Pendaftaran tidak ditemukan.');
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
