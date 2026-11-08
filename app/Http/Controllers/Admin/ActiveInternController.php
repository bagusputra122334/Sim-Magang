<?php

namespace App\Http\Controllers\Admin;

use App\Enums\RegistrationStatus;
use App\Models\Registration;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ActiveInternController extends AdminController
{
    /**
     * Display a listing of active/accepted interns.
     */
    public function index(Request $request): View
    {
        $search = $request->string('search')->trim()->toString();
        $opStatus = $request->string('op_status')->trim()->toString();
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
        $roleVal = $user?->role instanceof \App\Enums\UserRole ? $user->role->value : (string) $user?->role;
        $isSuperAdmin = $roleVal === 'super_admin' || ($user && $user->isSuperAdmin() && \App\Models\Opd::exists());
        $opds = $isSuperAdmin ? \App\Models\Opd::active()->orderBy('name')->get() : collect();

        // Super Admin wajib memilih Wilayah OPD (atau opsi 'all' untuk semua OPD) sebelum data ditampilkan
        if ($isSuperAdmin && empty($opdId)) {
            $interns = new \Illuminate\Pagination\LengthAwarePaginator([], 0, $perPage, 1, [
                'path'  => $request->url(),
                'query' => $request->query(),
            ]);
            $statistics = [
                'total'      => 0,
                'active'     => 0,
                'completed'  => 0,
                'terminated' => 0,
            ];
        } else {
            $query = Registration::query()
                ->where('status', RegistrationStatus::Accepted->value)
                ->with(['user.profile', 'position', 'opd']);

            if ($user && $user->isAdminOpd() && ! empty($user->opd_id)) {
                $query->where('opd_id', $user->opd_id);
            } elseif (! empty($opdId) && $opdId !== 'all') {
                $query->where('opd_id', $opdId);
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

            if ($opStatus === 'terminated') {
                $query->where('is_terminated', true);
            } elseif ($opStatus === 'completed') {
                $query->where('is_terminated', false)
                    ->whereDate('periode_selesai', '<', now()->toDateString());
            } elseif ($opStatus === 'active') {
                $today = now()->toDateString();
                $query->where('is_terminated', false)
                    ->where(function ($q) use ($today): void {
                        $q->whereNull('periode_selesai')
                            ->orWhereDate('periode_selesai', '>=', $today);
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

            $allAcceptedQuery = Registration::where('status', RegistrationStatus::Accepted->value);
            if ($user && $user->isAdminOpd() && ! empty($user->opd_id)) {
                $allAcceptedQuery->where('opd_id', $user->opd_id);
            } elseif (! empty($opdId) && $opdId !== 'all') {
                $allAcceptedQuery->where('opd_id', $opdId);
            }
            $allAccepted = $allAcceptedQuery->get();
            $statistics = [
                'total'      => $allAccepted->count(),
                'active'     => $allAccepted->filter(fn ($r) => $r->operational_status === 'active' || $r->operational_status === 'upcoming')->count(),
                'completed'  => $allAccepted->filter(fn ($r) => $r->operational_status === 'completed')->count(),
                'terminated' => $allAccepted->filter(fn ($r) => $r->operational_status === 'terminated')->count(),
            ];
        }

        $dbYears = Registration::where('status', RegistrationStatus::Accepted->value)
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

        return view('admin.active-interns.index', [
            'interns'        => $interns,
            'search'         => $search,
            'opStatus'       => $opStatus,
            'year'           => $year,
            'availableYears' => $availableYears,
            'perPage'        => $perPage,
            'statistics'     => $statistics,
            'isSuperAdmin'   => $isSuperAdmin,
            'opds'           => $opds,
            'selectedOpdId'  => $opdId,
        ]);
    }

    /**
     * Display the specified active intern details.
     */
    public function show(Request $request, int $id): View
    {
        $intern = Registration::query()
            ->where('status', RegistrationStatus::Accepted->value)
            ->with(['user.profile', 'position'])
            ->findOrFail($id);

        $user = $request->user();
        $roleVal = $user?->role instanceof \App\Enums\UserRole ? $user->role->value : (string) $user?->role;
        $isSuperAdmin = $roleVal === 'super_admin' || ($user && $user->isSuperAdmin() && \App\Models\Opd::exists());

        return view('admin.active-interns.show', [
            'intern'       => $intern,
            'isSuperAdmin' => $isSuperAdmin,
        ]);
    }

    /**
     * Toggle active/terminated status for an intern manually.
     */
    public function toggleStatus(Request $request, int $id): RedirectResponse
    {
        $user = $request->user();
        $roleVal = $user?->role instanceof \App\Enums\UserRole ? $user->role->value : (string) $user?->role;
        $isSuperAdmin = $roleVal === 'super_admin' || ($user && $user->isSuperAdmin() && \App\Models\Opd::exists());

        if ($isSuperAdmin) {
            abort(403, 'Akses Ditolak: Super Admin hanya memiliki hak akses pemantauan (View Only). Pemberhentian atau pengaktifan kembali status operasional magang merupakan wewenang mutlak Administrator OPD terkait.');
        }

        $intern = Registration::query()
            ->where('status', RegistrationStatus::Accepted->value)
            ->findOrFail($id);

        $this->authorizeOpdAccess($request, $intern->id);

        $validated = $request->validate([
            'catatan_penonaktifan' => ['nullable', 'string', 'max:1000'],
        ]);

        if ($intern->is_terminated) {
            $intern->update([
                'is_terminated'        => false,
                'catatan_penonaktifan' => null,
                'terminated_at'        => null,
            ]);

            return redirect()->back()->with('success', "Status magang untuk peserta {$intern->user?->name} berhasil diaktifkan kembali.");
        }

        $catatan = trim((string) ($validated['catatan_penonaktifan'] ?? 'Nonaktif manual oleh Admin'));

        $intern->update([
            'is_terminated'        => true,
            'catatan_penonaktifan' => $catatan,
            'terminated_at'        => now(),
        ]);

        $intern->loadMissing(['user', 'position']);
        if ($intern->user) {
            try {
                $intern->user->notify(new \App\Notifications\InternDeactivatedNotification($intern, $catatan));
                \App\Support\AuditLogger::emailSent(true, \App\Notifications\InternDeactivatedNotification::class, (string) $intern->user->email, $intern->id);
            } catch (\Throwable $e) {
                \App\Support\AuditLogger::emailSent(false, \App\Notifications\InternDeactivatedNotification::class, (string) ($intern->user->email ?? 'unknown'), $intern->id, $e);
            }
        }

        return redirect()->back()->with('success', "Status magang untuk peserta {$intern->user?->name} telah dinonaktifkan.");
    }

    /**
     * Export active interns monitoring report to downloadable PDF.
     */
    public function exportPdf(Request $request)
    {
        $user = $request->user();
        $roleVal = $user?->role instanceof \App\Enums\UserRole ? $user->role->value : (string) $user?->role;
        $isSuperAdmin = $roleVal === 'super_admin' || ($user && $user->isSuperAdmin() && \App\Models\Opd::exists());
        $search = $request->string('search')->trim()->toString();
        $opStatus = $request->string('op_status')->trim()->toString();
        $year = $request->input('year');
        $opdId = $request->input('opd_id');

        if ($isSuperAdmin && empty($opdId)) {
            return redirect()->route('admin.active-interns.index')
                ->with('warning', 'Silakan pilih Wilayah OPD terlebih dahulu sebelum mengekspor laporan status magang.');
        }

        $query = Registration::query()
            ->where('status', RegistrationStatus::Accepted->value)
            ->with(['user.profile', 'position', 'opd']);

        if ($request->user() && $request->user()->isAdminOpd() && ! empty($request->user()->opd_id)) {
            $query->where('opd_id', $request->user()->opd_id);
        } elseif (! empty($opdId) && $opdId !== 'all') {
            $query->where('opd_id', $opdId);
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

        if ($opStatus === 'terminated') {
            $query->where('is_terminated', true);
        } elseif ($opStatus === 'completed') {
            $query->where('is_terminated', false)
                ->whereDate('periode_selesai', '<', now()->toDateString());
        } elseif ($opStatus === 'active') {
            $today = now()->toDateString();
            $query->where('is_terminated', false)
                ->where(function ($q) use ($today): void {
                    $q->whereNull('periode_selesai')
                        ->orWhereDate('periode_selesai', '>=', $today);
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

        $allAcceptedQuery = Registration::where('status', RegistrationStatus::Accepted->value);
        if ($request->user() && $request->user()->isAdminOpd() && ! empty($request->user()->opd_id)) {
            $allAcceptedQuery->where('opd_id', $request->user()->opd_id);
        } elseif ($request->filled('opd_id')) {
            $allAcceptedQuery->where('opd_id', $request->input('opd_id'));
        }
        $allAccepted = $allAcceptedQuery->get();
        $statistics = [
            'total'      => $allAccepted->count(),
            'active'     => $allAccepted->filter(fn ($r) => $r->operational_status === 'active' || $r->operational_status === 'upcoming')->count(),
            'completed'  => $allAccepted->filter(fn ($r) => $r->operational_status === 'completed')->count(),
            'terminated' => $allAccepted->filter(fn ($r) => $r->operational_status === 'terminated')->count(),
        ];

        $printedAt = now()->translatedFormat('d F Y H:i').' WIB';
        $user = $request->user();
        $opd = $user && $user->isAdminOpd() ? $user->opd : ($request->filled('opd_id') ? \App\Models\Opd::find($request->input('opd_id')) : null);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.active-interns.pdf', compact('interns', 'statistics', 'search', 'opStatus', 'year', 'printedAt', 'opd'))
            ->setPaper('a4', 'landscape');
        $slug = $opd ? \Illuminate\Support\Str::slug($opd->name) : 'Semua_OPD';
        return $pdf->download('Laporan_Magang_Aktif_'.$slug.'_'.now()->format('Ymd_His').'.pdf');
    }
}
