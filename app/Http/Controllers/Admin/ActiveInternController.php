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
        $perPage = $request->integer('per_page', 10);
        if (! in_array($perPage, [5, 10, 15, 25, 50], true)) {
            $perPage = 10;
        }

        $query = Registration::query()
            ->where('status', RegistrationStatus::Accepted->value)
            ->with(['user.profile', 'position']);

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

        $interns = $query->latest('updated_at')->paginate($perPage)->withQueryString();

        $allAccepted = Registration::where('status', RegistrationStatus::Accepted->value)->get();
        $statistics = [
            'total'      => $allAccepted->count(),
            'active'     => $allAccepted->filter(fn ($r) => $r->operational_status === 'active' || $r->operational_status === 'upcoming')->count(),
            'completed'  => $allAccepted->filter(fn ($r) => $r->operational_status === 'completed')->count(),
            'terminated' => $allAccepted->filter(fn ($r) => $r->operational_status === 'terminated')->count(),
        ];

        return view('admin.active-interns.index', [
            'interns'    => $interns,
            'search'     => $search,
            'opStatus'   => $opStatus,
            'perPage'    => $perPage,
            'statistics' => $statistics,
        ]);
    }

    /**
     * Display the specified active intern details.
     */
    public function show(int $id): View
    {
        $intern = Registration::query()
            ->where('status', RegistrationStatus::Accepted->value)
            ->with(['user.profile', 'position'])
            ->findOrFail($id);

        return view('admin.active-interns.show', [
            'intern' => $intern,
        ]);
    }

    /**
     * Toggle active/terminated status for an intern manually.
     */
    public function toggleStatus(Request $request, int $id): RedirectResponse
    {
        $intern = Registration::query()
            ->where('status', RegistrationStatus::Accepted->value)
            ->findOrFail($id);

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
}
