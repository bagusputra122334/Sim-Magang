<?php

namespace App\Http\Controllers\Admin;

use App\Enums\RegistrationStatus;
use App\Enums\UserRole;
use App\Mail\CertificateIssuedMail;
use App\Models\Opd;
use App\Models\Registration;
use App\Support\AuditLogger;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CertificateController extends AdminController
{
    /**
     * Tampilkan daftar peserta magang untuk manajemen sertifikat magang digital terpusat Admin OPD.
     */
    public function index(Request $request): View
    {
        $search = $request->string('search')->trim()->toString();
        $status = $request->string('status', 'all')->trim()->toString();
        $year = $request->input('year');
        $opdId = $request->input('opd_id');

        $rawPerPage = $request->input('per_page', '10');
        if ($rawPerPage === 'all' || $rawPerPage === 'semua' || $rawPerPage === '-1') {
            $perPage = 10000;
        } else {
            $perPage = (int) $rawPerPage;
            if (! in_array($perPage, [5, 10, 15, 20, 25, 50, 100, 10000], true)) {
                $perPage = 10;
            }
        }

        $user = $request->user();
        $roleVal = $user?->role instanceof UserRole ? $user->role->value : (string) $user?->role;
        $isSuperAdmin = $roleVal === 'super_admin' || ($user && $user->isSuperAdmin() && Opd::exists());
        $opds = $isSuperAdmin ? Opd::active()->orderBy('name')->get() : collect();

        // Super Admin wajib memilih Wilayah OPD (atau opsi 'all') sebelum data ditampilkan
        if ($isSuperAdmin && empty($opdId)) {
            $interns = new LengthAwarePaginator([], 0, $perPage, 1, [
                'path'  => $request->url(),
                'query' => $request->query(),
            ]);

            $stats = [
                'total'          => 0,
                'issued'         => 0,
                'ready_to_issue' => 0,
                'in_progress'    => 0,
            ];
        } else {
            $baseQuery = Registration::query()
                ->where('status', RegistrationStatus::Accepted->value)
                ->with(['user.profile', 'position', 'opd']);

            if ($user && $user->isAdminOpd() && ! empty($user->opd_id)) {
                $baseQuery->where(function ($q) use ($user): void {
                    $q->where('opd_id', $user->opd_id)
                        ->orWhereHas('position', fn ($pq) => $pq->where('opd_id', $user->opd_id))
                        ->orWhereHas('user', fn ($uq) => $uq->where('opd_id', $user->opd_id));
                });
            } elseif (! empty($opdId) && $opdId !== 'all') {
                $baseQuery->where(function ($q) use ($opdId): void {
                    $q->where('opd_id', $opdId)
                        ->orWhereHas('position', fn ($pq) => $pq->where('opd_id', $opdId))
                        ->orWhereHas('user', fn ($uq) => $uq->where('opd_id', $opdId));
                });
            }

            $query = clone $baseQuery;

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

            $today = now()->toDateString();

            if ($status === 'issued') {
                $query->whereNotNull('certificate_path');
            } elseif ($status === 'ready_to_issue') {
                $query->whereNull('certificate_path')
                    ->where('is_terminated', false)
                    ->whereDate('periode_selesai', '<=', $today);
            } elseif ($status === 'in_progress') {
                $query->whereNull('certificate_path')
                    ->where('is_terminated', false)
                    ->where(function ($q) use ($today): void {
                        $q->whereNull('periode_selesai')
                            ->orWhereDate('periode_selesai', '>', $today);
                    });
            }

            if (! empty($year)) {
                $query->where(function ($q) use ($year): void {
                    $q->whereYear('periode_mulai', $year)
                        ->orWhereYear('periode_selesai', $year)
                        ->orWhereYear('created_at', $year);
                });
            }

            $interns = $query->latest('updated_at')->paginate($perPage)->withQueryString();

            $allMentees = (clone $baseQuery)->get();
            $stats = [
                'total'          => $allMentees->count(),
                'issued'         => $allMentees->filter(fn ($r) => ! empty($r->certificate_path))->count(),
                'ready_to_issue' => $allMentees->filter(fn ($r) => empty($r->certificate_path) && $r->is_graduation_eligible)->count(),
                'in_progress'    => $allMentees->filter(fn ($r) => empty($r->certificate_path) && ! $r->is_graduation_eligible)->count(),
            ];
        }

        $dbYears = Registration::where('status', RegistrationStatus::Accepted->value)
            ->whereNotNull('created_at')
            ->pluck('created_at')
            ->map(fn($dt) => (int) Carbon::parse($dt)->format('Y'))
            ->unique()
            ->values();

        $currentYear = (int) date('Y');
        $availableYears = $dbYears->merge([$currentYear, $currentYear + 1])
            ->unique()
            ->sortDesc()
            ->values();

        return view('admin.certificates.index', [
            'interns'        => $interns,
            'stats'          => $stats,
            'search'         => $search,
            'status'         => $status,
            'year'           => $year,
            'selectedOpdId'  => $opdId,
            'opds'           => $opds,
            'isSuperAdmin'   => $isSuperAdmin,
            'perPage'        => $perPage,
            'availableYears' => $availableYears,
        ]);
    }

    /**
     * Upload / Ganti file fisik Sertifikat Kelulusan Magang Digital oleh Admin OPD.
     */
    public function upload(Request $request, int|Registration $registration): RedirectResponse
    {
        $user = $request->user();
        if ($user && $user->isSuperAdmin()) {
            abort(403, 'Akses Ditolak: Super Admin hanya memiliki hak akses pemantauan (View Only) pada sertifikat magang. Penerbitan dan pengelolaan file sertifikat merupakan wewenang mutlak Administrator OPD.');
        }

        $reg = $registration instanceof Registration ? $registration : Registration::findOrFail($registration);
        $this->authorizeOpdAccess($request, $reg->id);

        // Guard: Peserta wajib telah mencapai akhir masa magang
        if (! $reg->isGraduationEligible()) {
            $endDateStr = $reg->periode_selesai?->translatedFormat('d F Y') ?? 'akhir periode magang';
            return back()->with('error', "Sertifikat belum dapat diunggah. Peserta belum mencapai hari terakhir pelaksanaan magang ({$endDateStr}).");
        }

        $request->validate([
            'certificate' => ['required', 'file', 'mimes:pdf', 'max:5120'], // Max 5MB
        ], [
            'certificate.required' => 'File sertifikat wajib dipilih sebelum mengunggah.',
            'certificate.file'     => 'File yang diunggah tidak valid.',
            'certificate.mimes'    => 'File sertifikat harus berformat PDF.',
            'certificate.max'      => 'Ukuran file sertifikat kelulusan tidak boleh lebih dari 5 MB.',
        ]);

        $isReplacing = ! empty($reg->certificate_path);

        // Hapus file fisik lama jika ada
        if (! empty($reg->certificate_path) && Storage::disk('public')->exists($reg->certificate_path)) {
            Storage::disk('public')->delete($reg->certificate_path);
        }

        // Simpan file sertifikat ke folder 'certificates/'
        $file = $request->file('certificate');
        $safeName = 'certificate_' . $reg->id . '_' . time() . '.' . $file->getClientOriginalExtension();
        $storedPath = $file->storeAs('certificates', $safeName, 'public');

        $reg->update([
            'certificate_path'      => $storedPath,
            'certificate_issued_at' => now(),
        ]);

        // Kirim email notifikasi ke peserta
        if (! empty($reg->user?->email)) {
            try {
                Mail::to((string) $reg->user->email)->send(new CertificateIssuedMail($reg, $isReplacing));
                AuditLogger::emailSent(true, CertificateIssuedMail::class, (string) $reg->user->email, $reg->id);
            } catch (\Throwable $e) {
                Log::warning("Gagal mengirim email sertifikat ke {$reg->user->email}: " . $e->getMessage());
                AuditLogger::emailSent(false, CertificateIssuedMail::class, (string) $reg->user->email, $reg->id, $e);
            }
        }

        $actionVerb = $isReplacing ? 'diperbarui' : 'diterbitkan dan dikirimkan';
        return back()->with('success', "Sertifikat kelulusan magang untuk peserta {$reg->user?->name} berhasil {$actionVerb}.");
    }

    /**
     * Hapus file sertifikat kelulusan.
     */
    public function delete(Request $request, int|Registration $registration): RedirectResponse
    {
        $user = $request->user();
        if ($user && $user->isSuperAdmin()) {
            abort(403, 'Akses Ditolak: Super Admin hanya memiliki hak akses pemantauan (View Only) pada sertifikat magang. Penghapusan file sertifikat merupakan wewenang mutlak Administrator OPD.');
        }

        $reg = $registration instanceof Registration ? $registration : Registration::findOrFail($registration);
        $this->authorizeOpdAccess($request, $reg->id);

        if (! empty($reg->certificate_path) && Storage::disk('public')->exists($reg->certificate_path)) {
            Storage::disk('public')->delete($reg->certificate_path);
        }

        $reg->update([
            'certificate_path'      => null,
            'certificate_issued_at' => null,
        ]);

        return back()->with('success', "Sertifikat magang untuk peserta {$reg->user?->name} berhasil dihapus.");
    }

    /**
     * Unduh file sertifikat kelulusan.
     */
    public function download(Request $request, int|Registration $registration): BinaryFileResponse|RedirectResponse
    {
        $reg = $registration instanceof Registration ? $registration : Registration::findOrFail($registration);
        $this->authorizeOpdAccess($request, $reg->id);

        if (empty($reg->certificate_path) || ! Storage::disk('public')->exists($reg->certificate_path)) {
            return back()->with('error', 'File sertifikat belum diunggah atau tidak ditemukan di penyimpanan server.');
        }

        $filePath = Storage::disk('public')->path($reg->certificate_path);
        $fileName = 'Sertifikat_Magang_' . str_replace(' ', '_', $reg->user?->name ?? 'Peserta') . '_' . $reg->nomor_pendaftaran . '.pdf';

        return response()->download($filePath, $fileName);
    }

    /**
     * Pratinjau (inline view) file sertifikat kelulusan.
     */
    public function view(Request $request, int|Registration $registration): StreamedResponse|RedirectResponse
    {
        $reg = $registration instanceof Registration ? $registration : Registration::findOrFail($registration);
        $this->authorizeOpdAccess($request, $reg->id);

        if (empty($reg->certificate_path) || ! Storage::disk('public')->exists($reg->certificate_path)) {
            return back()->with('error', 'File sertifikat belum diunggah atau tidak ditemukan di server.');
        }

        $stream = Storage::disk('public')->readStream($reg->certificate_path);

        return response()->stream(function () use ($stream): void {
            fpassthru($stream);
        }, 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'inline; filename="Sertifikat_' . $reg->nomor_pendaftaran . '.pdf"',
        ]);
    }

    /**
     * Ekspor laporan sertifikat magang ke file PDF secara dinamis.
     */
    public function exportPdf(Request $request)
    {
        $search = $request->string('search')->trim()->toString();
        $status = $request->string('status', 'all')->trim()->toString();
        $year = $request->input('year');
        $opdId = $request->input('opd_id');

        $user = $request->user();
        $roleVal = $user?->role instanceof UserRole ? $user->role->value : (string) $user?->role;
        $isSuperAdmin = $roleVal === 'super_admin' || ($user && $user->isSuperAdmin() && Opd::exists());

        if ($isSuperAdmin && empty($opdId)) {
            return redirect()->route('admin.certificates.index')
                ->with('warning', 'Silakan pilih Wilayah OPD terlebih dahulu sebelum mengekspor laporan sertifikat magang.');
        }

        $query = Registration::query()
            ->where('status', RegistrationStatus::Accepted->value)
            ->with(['user.profile', 'position', 'opd']);

        if ($user && $user->isAdminOpd() && ! empty($user->opd_id)) {
            $query->where(function ($q) use ($user): void {
                $q->where('opd_id', $user->opd_id)
                    ->orWhereHas('position', fn ($pq) => $pq->where('opd_id', $user->opd_id))
                    ->orWhereHas('user', fn ($uq) => $uq->where('opd_id', $user->opd_id));
            });
        } elseif (! empty($opdId) && $opdId !== 'all') {
            $query->where(function ($q) use ($opdId): void {
                $q->where('opd_id', $opdId)
                    ->orWhereHas('position', fn ($pq) => $pq->where('opd_id', $opdId))
                    ->orWhereHas('user', fn ($uq) => $uq->where('opd_id', $opdId));
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

        $today = now()->toDateString();

        if ($status === 'issued') {
            $query->whereNotNull('certificate_path');
        } elseif ($status === 'ready_to_issue') {
            $query->whereNull('certificate_path')
                ->where('is_terminated', false)
                ->whereDate('periode_selesai', '<=', $today);
        } elseif ($status === 'in_progress') {
            $query->whereNull('certificate_path')
                ->where('is_terminated', false)
                ->where(function ($q) use ($today): void {
                    $q->whereNull('periode_selesai')
                        ->orWhereDate('periode_selesai', '>', $today);
                });
        }

        if (! empty($year)) {
            $query->where(function ($q) use ($year): void {
                $q->whereYear('periode_mulai', $year)
                    ->orWhereYear('periode_selesai', $year)
                    ->orWhereYear('created_at', $year);
            });
        }

        $interns = $query->latest('updated_at')->get();

        $stats = [
            'total'          => $interns->count(),
            'issued'         => $interns->filter(fn ($r) => ! empty($r->certificate_path))->count(),
            'ready_to_issue' => $interns->filter(fn ($r) => empty($r->certificate_path) && $r->is_graduation_eligible)->count(),
            'in_progress'    => $interns->filter(fn ($r) => empty($r->certificate_path) && ! $r->is_graduation_eligible)->count(),
        ];

        $targetOpd = $user && $user->isAdminOpd() ? $user->opd : (! empty($opdId) && $opdId !== 'all' ? Opd::find($opdId) : null);
        $printedAt = now()->translatedFormat('d F Y H:i').' WIB';

        $pdf = Pdf::loadView('admin.certificates.pdf', [
            'interns'   => $interns,
            'stats'     => $stats,
            'user'      => $user,
            'opd'       => $targetOpd,
            'search'    => $search,
            'status'    => $status,
            'year'      => $year,
            'printedAt' => $printedAt,
        ])->setPaper('a4', 'landscape');

        return $pdf->download('Laporan_Sertifikat_Magang_Tuban_'.now()->format('Ymd_His').'.pdf');
    }
}
