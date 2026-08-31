<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DocumentDownloadController extends Controller
{
    /**
     * Download a document by Registration model and document type.
     */
    public function download(Request $request, Registration $registration, string $type): BinaryFileResponse|StreamedResponse
    {
        $this->authorizeDocumentAccess($request, $registration, $type);

        $path = $this->resolveDocumentPath($registration, $type);

        return $this->serveFile($request, $path, $type, $registration);
    }

    /**
     * View a document inline in browser (preview).
     */
    public function view(Request $request, Registration $registration, string $type): BinaryFileResponse|StreamedResponse
    {
        $this->authorizeDocumentAccess($request, $registration, $type);

        $path = $this->resolveDocumentPath($registration, $type);

        $request->query->set('disposition', 'inline');

        return $this->serveFile($request, $path, $type, $registration);
    }

    /**
     * Download by storage path string.
     */
    public function downloadByPath(Request $request, string $path): BinaryFileResponse|StreamedResponse
    {
        $user = $request->user();
        if ($user === null) {
            abort(401, 'Unauthenticated.');
        }

        $registration = Registration::where('cv_path', $path)
            ->orWhere('surat_pengantar_path', $path)
            ->orWhere('proposal_magang_path', $path)
            ->orWhere('surat_balasan_path', $path)
            ->first();

        if ($registration !== null) {
            $type = match ($path) {
                $registration->cv_path => 'cv',
                $registration->surat_pengantar_path => 'surat_pengantar',
                $registration->proposal_magang_path => 'proposal_magang',
                $registration->surat_balasan_path => 'surat_balasan',
                default => 'document',
            };

            $this->authorizeDocumentAccess($request, $registration, $type);
        } else {
            if (! $user->isAdmin()) {
                abort(403, 'Anda tidak memiliki izin untuk mengakses dokumen ini.');
            }
        }

        return $this->serveFile($request, $path, 'document', $registration);
    }

    protected function authorizeDocumentAccess(Request $request, Registration $registration, string $type): void
    {
        $user = $request->user();
        if ($user === null) {
            abort(401, 'Unauthenticated.');
        }

        if ($user->isAdmin()) {
            return;
        }

        if ($user->isPeserta() && (int) $registration->user_id === (int) $user->id) {
            if ($type === 'surat_balasan' && ! $registration->isAccepted()) {
                abort(403, 'Surat Balasan hanya tersedia untuk pendaftaran dengan status Accepted.');
            }

            return;
        }

        abort(403, 'Anda tidak memiliki izin untuk mengakses dokumen ini.');
    }

    protected function resolveDocumentPath(Registration $registration, string $type): string
    {
        $path = match ($type) {
            'cv'              => $registration->cv_path,
            'surat_pengantar' => $registration->surat_pengantar_path,
            'proposal_magang' => $registration->proposal_magang_path,
            'surat_balasan'   => $registration->surat_balasan_path,
            default           => null,
        };

        if (empty($path) || ! is_string($path)) {
            abort(404, 'Dokumen yang diminta belum diunggah.');
        }

        return $path;
    }

    protected function serveFile(Request $request, string $path, string $type = 'document', ?Registration $registration = null): BinaryFileResponse|StreamedResponse
    {
        $diskName = 'local';
        if (! Storage::disk('local')->exists($path)) {
            if (Storage::disk('public')->exists($path)) {
                $diskName = 'public';
            } else {
                abort(404, 'File dokumen tidak ditemukan di storage server.');
            }
        }

        $disk = Storage::disk($diskName);
        $downloadName = sprintf(
            '%s-%s.pdf',
            strtoupper(str_replace('_', '-', $type)),
            $registration?->nomor_pendaftaran ?? 'DOC'
        );

        if ($request->query('disposition') === 'inline' || $request->boolean('inline')) {
            return $disk->response($path, $downloadName, [
                'Content-Type'  => 'application/pdf',
                'Cache-Control' => 'private, no-cache, no-store, must-revalidate',
            ]);
        }

        return $disk->download($path, $downloadName, [
            'Cache-Control' => 'private, no-cache, no-store, must-revalidate',
        ]);
    }
}
