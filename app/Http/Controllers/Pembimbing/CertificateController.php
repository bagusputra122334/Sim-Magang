<?php

namespace App\Http\Controllers\Pembimbing;

use App\Enums\RegistrationStatus;
use App\Http\Controllers\Controller;
use App\Mail\CertificateIssuedMail;
use App\Models\Registration;
use App\Support\AuditLogger;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CertificateController extends Controller
{
    /**
     * Tampilkan daftar anak bimbing untuk manajemen Sertifikat Magang Digital.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        abort_unless($user && $user->isPembimbing(), 403, 'Akses khusus pembimbing lapangan.');

        $search = trim((string) $request->query('search', ''));
        $status = trim((string) $request->query('status', 'all'));
        $perPageRaw = (int) $request->query('per_page', 10);
        $allowedPerPage = [5, 10, 15, 25, 50];
        $perPage = in_array($perPageRaw, $allowedPerPage, true) ? $perPageRaw : 10;

        $today = now()->toDateString();

        // Query anak bimbing hanya dalam divisi pembimbing atau alokasi mentor
        $baseQuery = Registration::query()
            ->where('status', RegistrationStatus::Accepted->value)
            ->with(['user.profile', 'user.division', 'position'])
            ->where(function (Builder $q) use ($user): void {
                if (! empty($user->division_id)) {
                    $q->whereHas('user', function (Builder $uq) use ($user): void {
                        $uq->where('division_id', $user->division_id);
                    });
                }
                if (! empty($user->nip)) {
                    $q->orWhereHas('position', function (Builder $pq) use ($user): void {
                        $pq->where('mentor_nip', $user->nip);
                    });
                }
                if (! empty($user->name)) {
                    $q->orWhereHas('position', function (Builder $pq) use ($user): void {
                        $pq->where('mentor_name', $user->name);
                    });
                }
            });

        // Filter status operasional: all, active, upcoming, completed
        $query = clone $baseQuery;
        if ($status === 'active') {
            $query->where('is_terminated', false)
                ->whereDate('periode_mulai', '<=', $today)
                ->where(function (Builder $q) use ($today): void {
                    $q->whereNull('periode_selesai')
                        ->orWhereDate('periode_selesai', '>=', $today);
                });
        } elseif ($status === 'upcoming') {
            $query->where('is_terminated', false)
                ->whereDate('periode_mulai', '>', $today);
        } elseif ($status === 'completed') {
            $query->where('is_terminated', false)
                ->whereDate('periode_selesai', '<', $today);
        }

        $year = trim((string) $request->query('year', ''));

        // Filter Tahun
        if ($year !== '') {
            $query->where(function (Builder $q) use ($year): void {
                $q->whereYear('periode_mulai', $year)
                    ->orWhereYear('periode_selesai', $year)
                    ->orWhereYear('created_at', $year);
            });
        }

        // Live Search: nama peserta / email / jurusan / instansi
        if ($search !== '') {
            $query->where(function (Builder $q) use ($search): void {
                $q->where('nomor_pendaftaran', 'like', "%{$search}%")
                    ->orWhereHas('user', function (Builder $uq) use ($search): void {
                        $uq->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%")
                            ->orWhereHas('profile', function (Builder $pq) use ($search): void {
                                $pq->where('institusi', 'like', "%{$search}%")
                                    ->orWhere('jurusan', 'like', "%{$search}%");
                            });
                    })
                    ->orWhereHas('position', function (Builder $pq) use ($search): void {
                        $pq->where('nama_posisi', 'like', "%{$search}%");
                    });
            });
        }

        $interns = $query->latest('updated_at')->paginate($perPage)->withQueryString();

        // Ambil daftar tahun unik dari data bimbingan pembimbing
        $dbYears = (clone $baseQuery)
            ->whereNotNull('created_at')
            ->pluck('created_at')
            ->map(fn($dt) => (int) \Illuminate\Support\Carbon::parse($dt)->format('Y'))
            ->unique()
            ->values();

        $currentYear = (int) date('Y');
        $availableYears = $dbYears->merge([$currentYear, $currentYear + 1])
            ->unique()
            ->sortDesc()
            ->values();

        // Statistik ringkas untuk pembimbing
        $allMentees = (clone $baseQuery)->get();
        $stats = [
            'total'          => $allMentees->count(),
            'issued'         => $allMentees->filter(fn ($r) => ! empty($r->certificate_path))->count(),
            'ready_to_issue' => $allMentees->filter(fn ($r) => empty($r->certificate_path) && $r->is_graduation_eligible)->count(),
            'in_progress'    => $allMentees->filter(fn ($r) => empty($r->certificate_path) && ! $r->is_graduation_eligible)->count(),
        ];

        return view('pembimbing.certificates.index', [
            'interns'        => $interns,
            'user'           => $user,
            'search'         => $search,
            'status'         => $status,
            'year'           => $year,
            'perPage'        => $perPage,
            'availableYears' => $availableYears,
            'stats'          => $stats,
        ]);
    }

    /**
     * Ekspor daftar anak bimbing sertifikat magang ke file PDF secara dinamis sesuai filter.
     */
    public function exportPdf(Request $request)
    {
        $user = Auth::user();
        abort_unless($user && $user->isPembimbing(), 403, 'Akses khusus pembimbing lapangan.');

        $search = trim((string) $request->query('search', ''));
        $status = trim((string) $request->query('status', 'all'));
        $year = trim((string) $request->query('year', ''));
        $today = now()->toDateString();

        // Query anak bimbing hanya dalam divisi pembimbing atau alokasi mentor
        $baseQuery = Registration::query()
            ->where('status', RegistrationStatus::Accepted->value)
            ->with(['user.profile', 'user.division', 'position'])
            ->where(function (Builder $q) use ($user): void {
                if (! empty($user->division_id)) {
                    $q->whereHas('user', function (Builder $uq) use ($user): void {
                        $uq->where('division_id', $user->division_id);
                    });
                }
                if (! empty($user->nip)) {
                    $q->orWhereHas('position', function (Builder $pq) use ($user): void {
                        $pq->where('mentor_nip', $user->nip);
                    });
                }
                if (! empty($user->name)) {
                    $q->orWhereHas('position', function (Builder $pq) use ($user): void {
                        $pq->where('mentor_name', $user->name);
                    });
                }
            });

        $query = clone $baseQuery;
        if ($status === 'active') {
            $query->where('is_terminated', false)
                ->whereDate('periode_mulai', '<=', $today)
                ->where(function (Builder $q) use ($today): void {
                    $q->whereNull('periode_selesai')
                        ->orWhereDate('periode_selesai', '>=', $today);
                });
        } elseif ($status === 'upcoming') {
            $query->where('is_terminated', false)
                ->whereDate('periode_mulai', '>', $today);
        } elseif ($status === 'completed') {
            $query->where('is_terminated', false)
                ->whereDate('periode_selesai', '<', $today);
        }

        if ($year !== '') {
            $query->where(function (Builder $q) use ($year): void {
                $q->whereYear('periode_mulai', $year)
                    ->orWhereYear('periode_selesai', $year)
                    ->orWhereYear('created_at', $year);
            });
        }

        if ($search !== '') {
            $query->where(function (Builder $q) use ($search): void {
                $q->where('nomor_pendaftaran', 'like', "%{$search}%")
                    ->orWhereHas('user', function (Builder $uq) use ($search): void {
                        $uq->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%")
                            ->orWhereHas('profile', function (Builder $pq) use ($search): void {
                                $pq->where('institusi', 'like', "%{$search}%")
                                    ->orWhere('jurusan', 'like', "%{$search}%");
                            });
                    })
                    ->orWhereHas('position', function (Builder $pq) use ($search): void {
                        $pq->where('nama_posisi', 'like', "%{$search}%");
                    });
            });
        }

        $interns = $query->latest('updated_at')->get();

        $allMentees = (clone $baseQuery)->get();
        $stats = [
            'total'          => $allMentees->count(),
            'issued'         => $allMentees->filter(fn ($r) => ! empty($r->certificate_path))->count(),
            'ready_to_issue' => $allMentees->filter(fn ($r) => empty($r->certificate_path) && $r->is_graduation_eligible)->count(),
            'in_progress'    => $allMentees->filter(fn ($r) => empty($r->certificate_path) && ! $r->is_graduation_eligible)->count(),
        ];

        $divisionName = $user->division->nama_divisi ?? ($user->division->name ?? 'Aplikasi dan Informatika');
        $printedAt = now()->translatedFormat('d F Y H:i').' WIB';

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pembimbing.certificates.pdf', [
            'interns'      => $interns,
            'stats'        => $stats,
            'user'         => $user,
            'divisionName' => $divisionName,
            'search'       => $search,
            'status'       => $status,
            'year'         => $year,
            'printedAt'    => $printedAt,
        ])->setPaper('a4', 'landscape');

        return $pdf->download('Laporan_Sertifikat_Magang_'.now()->format('Ymd_His').'.pdf');
    }

    /**
     * Upload / Ganti file fisik Sertifikat Kelulusan Magang Digital.
     */
    public function upload(Request $request, $id): RedirectResponse
    {
        $registration = $this->resolveRegistration($id);
        $this->authorizeMentorForRegistration($registration);

        // Guard: Time-Gated Feature (now >= end_date)
        if (! $registration->isGraduationEligible()) {
            $endDateStr = $registration->periode_selesai?->translatedFormat('d F Y') ?? 'akhir periode magang';
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

        $isReplacing = ! empty($registration->certificate_path);

        // Hapus file fisik lama jika ada
        if (! empty($registration->certificate_path) && Storage::disk('public')->exists($registration->certificate_path)) {
            Storage::disk('public')->delete($registration->certificate_path);
        }

        // Simpan file sertifikat ke folder 'certificates/'
        $file = $request->file('certificate');
        $safeName = 'certificate_' . $registration->id . '_' . time() . '.' . $file->getClientOriginalExtension();
        $storedPath = $file->storeAs('certificates', $safeName, 'public');

        $registration->certificate_path = $storedPath;
        $registration->certificate_issued_at = now();
        $registration->save();

        // Notifikasi email otomatis via Resend
        $registration->loadMissing(['user.profile', 'position']);
        if (! empty($registration->user?->email)) {
            try {
                Mail::to($registration->user->email)->send(new CertificateIssuedMail($registration, $isReplacing));
                AuditLogger::emailSent(true, CertificateIssuedMail::class, (string) $registration->user->email, $registration->id);
            } catch (\Throwable $e) {
                AuditLogger::emailSent(false, CertificateIssuedMail::class, (string) ($registration->user->email ?? 'unknown'), $registration->id, $e);
                Log::error('[Pembimbing Certificate] Gagal mengirim email CertificateIssuedMail: ' . $e->getMessage(), [
                    'registration_id' => $registration->id,
                    'user_id'         => $registration->user_id,
                    'is_replacing'    => $isReplacing,
                    'error_class'     => $e::class,
                ]);
            }
        }

        $msg = $isReplacing
            ? 'Sertifikat Kelulusan Magang Digital berhasil diganti dan diperbarui (file lama berhasil ditimpa).'
            : 'Sertifikat Kelulusan Magang Digital berhasil diunggah dan resmi diterbitkan kepada peserta.';

        return back()->with('success', $msg);
    }

    /**
     * Preview sertifikat di tab baru browser.
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
     * Download sertifikat digital peserta magang.
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
     * Hapus file fisik sertifikat dan kosongkan kolom database.
     */
    public function delete($id): RedirectResponse
    {
        $registration = $this->resolveRegistration($id);
        $this->authorizeMentorForRegistration($registration);

        if (! empty($registration->certificate_path) && Storage::disk('public')->exists($registration->certificate_path)) {
            Storage::disk('public')->delete($registration->certificate_path);
        }

        $registration->certificate_path = null;
        $registration->certificate_issued_at = null;
        $registration->save();

        return back()->with('success', 'Sertifikat Kelulusan Magang Digital berhasil dihapus.');
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
            return; // Admin dapat melihat / download jika diperlukan
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
     * Resolve instance Registration dari ID atau model.
     */
    private function resolveRegistration($id): Registration
    {
        if ($id instanceof Registration) {
            return $id;
        }

        return Registration::findOrFail((int) $id);
    }
}
