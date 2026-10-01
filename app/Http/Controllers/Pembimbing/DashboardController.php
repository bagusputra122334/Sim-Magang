<?php

namespace App\Http\Controllers\Pembimbing;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Material;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Database\Eloquent\Builder;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $divisionName = $user->division->name ?? 'Aplikasi dan Informatika';
        $divisionId = $user->division_id;

        $search = trim((string) $request->query('search', ''));
        $status = trim((string) $request->query('status', 'all'));
        $perPageRaw = trim((string) $request->query('per_page', '10'));
        if (! in_array($status, ['all', 'active', 'inactive'], true)) {
            $status = 'all';
        }
        $allowedPerPage = ['10', '20', '50', 'all'];
        if (! in_array($perPageRaw, $allowedPerPage, true)) {
            $perPageRaw = '10';
        }
        $isAllPerPage = $perPageRaw === 'all';
        $perPage = $isAllPerPage ? 10 : (int) $perPageRaw;

        $today = now()->toDateString();
        $opStatus = $status;
        $year = $request->input('year');

        $query = \App\Models\Registration::query()
            ->where('status', \App\Enums\RegistrationStatus::Accepted->value)
            ->with(['user.profile', 'position'])
            ->whereHas('user', function (Builder $q) use ($user) {
                $q->where('division_id', $user->division_id);
            });

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

        if ($opStatus === 'inactive') {
            $query->where('is_terminated', true)
                ->orWhere(function($q) use ($today) {
                    $q->where('is_terminated', false)
                      ->whereDate('periode_selesai', '<', $today);
                });
        } elseif ($opStatus === 'active') {
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

        if ($isAllPerPage) {
            $allRows = $query->latest('updated_at')->get();
            $totalCount = $allRows->count();
            $interns = new \Illuminate\Pagination\LengthAwarePaginator(
                $allRows,
                $totalCount,
                max(1, $totalCount),
                1,
                [
                    'path' => \Illuminate\Pagination\Paginator::resolveCurrentPath(),
                    'query' => $request->query(),
                ]
            );
        } else {
            $interns = $query->latest('updated_at')->paginate($perPage)->withQueryString();
        }

        $allAccepted = \App\Models\Registration::where('status', \App\Enums\RegistrationStatus::Accepted->value)
            ->whereHas('user', function (Builder $q) use ($user) {
                $q->where('division_id', $user->division_id);
            })->get();
            
        $statsTotalInterns = $allAccepted->count();
        $statsActiveInterns = $allAccepted->filter(fn ($r) => $r->operational_status === 'active' || $r->operational_status === 'upcoming')->count();
        $statsInactiveInterns = $allAccepted->filter(fn ($r) => $r->operational_status === 'completed' || $r->operational_status === 'terminated')->count();

        $totalPenugasan = Material::where('pembimbing_id', $user->id)->count()
            + Task::where('pembimbing_id', $user->id)->count();

        return view('pembimbing.dashboard', compact(
            'interns',
            'totalPenugasan',
            'user',
            'search',
            'status',
            'perPageRaw',
            'isAllPerPage',
            'statsTotalInterns',
            'statsActiveInterns',
            'statsInactiveInterns',
        ));
    }
}
