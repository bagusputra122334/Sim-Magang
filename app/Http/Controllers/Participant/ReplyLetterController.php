<?php

namespace App\Http\Controllers\Participant;

use App\Models\Registration;
use App\Services\RegistrationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReplyLetterController extends ParticipantController
{
    public function __construct(
        protected RegistrationService $registrationService
    ) {}

    /**
     * GET /participant/applications/{application}/reply-letter/download
     *
     * Peserta mengunduh Surat Balasan PDF miliknya sendiri.
     * Security:
     * - IDOR protection via ensureOwner() di RegistrationService
     * - Status WAJIB Accepted
     * - File WAJIB ada di Storage
     *
     * @return RedirectResponse|StreamedResponse
     */
    public function download(Request $request, Registration $application): RedirectResponse|StreamedResponse
    {
        try {
            $userId = $request->user()->id;

            $dl = $this->registrationService->getSuratBalasanForParticipantDownload($application, $userId);

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
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            return redirect()->route('participant.registrations.index')
                ->with('error', $e->getMessage());
        } catch (\DomainException $e) {
            return redirect()->route('participant.registrations.show', $application->id)
                ->with('error', $e->getMessage());
        }
    }
}
