<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\Division;
use App\Models\Opd;
use App\Models\Registration;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PembimbingController extends Controller
{
    public function index(Request $request)
    {
        // Auto-repair: Ensure Admin role in DB is strictly 'admin' or 'super_admin'
        User::where(function($q) {
                $q->where('id', 1)
                  ->orWhere('email', 'like', '%diskominfo%')
                  ->orWhere('email', 'like', '%admin%');
            })
            ->whereNotIn('role', ['admin', 'super_admin'])
            ->update(['role' => 'admin']);

        $user = $request->user();
        $roleVal = $user?->role instanceof UserRole ? $user->role->value : (string) $user?->role;
        $isSuperAdmin = $roleVal === 'super_admin' || ($user && $user->isSuperAdmin() && Opd::exists());
        $opdId = $request->input('opd_id');

        $rawPerPage = $request->input('per_page', $request->input('perPage', '10'));
        if ($rawPerPage === 'all' || $rawPerPage === 'semua' || $rawPerPage === '-1') {
            $perPage = 10000;
        } else {
            $perPage = (int) $rawPerPage;
            if (! in_array($perPage, [5, 10, 15, 20, 25, 50, 100, 10000], true)) {
                $perPage = 10;
            }
        }

        $opds = $isSuperAdmin ? Opd::active()->orderBy('name')->get() : collect();

        // Super Admin wajib memilih Wilayah OPD (atau opsi 'all' untuk agregat seluruh OPD) sebelum data ditampilkan
        if ($isSuperAdmin && empty($opdId)) {
            $pembimbings = new LengthAwarePaginator([], 0, $perPage, 1, [
                'path'  => $request->url(),
                'query' => $request->query(),
            ]);

            $stats = [
                'total'           => 0,
                'total_opd'       => 0,
                'total_divisions' => 0,
                'total_interns'   => 0,
            ];

            $divisions = collect();
        } else {
            // Fetch ONLY genuine Pembimbing accounts, strictly excluding Admin
            $query = User::where(function($query) {
                    $query->where('role', 'pembimbing')
                          ->orWhere('role', UserRole::Pembimbing ?? null);
                })
                ->where('id', '!=', 1)
                ->where('email', 'NOT LIKE', '%diskominfo%')
                ->where('email', 'NOT LIKE', '%admin%')
                ->whereNotIn('role', ['admin', 'super_admin', UserRole::Admin ?? 'admin', UserRole::SuperAdmin ?? 'super_admin'])
                ->with(['division', 'opd', 'materials', 'tasks']);

            // Multi-tenant: Isolasi daftar pembimbing untuk Admin OPD atau filter OPD untuk Super Admin
            if (! $isSuperAdmin && $user && !empty($user->opd_id)) {
                $query->where('opd_id', $user->opd_id);
            } elseif ($isSuperAdmin && !empty($opdId) && $opdId !== 'all') {
                $query->where('opd_id', $opdId);
            }

            if ($request->filled('division_id') && $request->division_id !== 'all') {
                $query->where('division_id', $request->division_id);
            }

            if ($request->filled('search')) {
                $search = $request->input('search');
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('nip', 'like', "%{$search}%");
                });
            }

            // Statistik Dashboard Top Metric Cards
            $statsBase = User::where(function($query) {
                    $query->where('role', 'pembimbing')
                          ->orWhere('role', UserRole::Pembimbing ?? null);
                })
                ->where('id', '!=', 1)
                ->where('email', 'NOT LIKE', '%diskominfo%')
                ->where('email', 'NOT LIKE', '%admin%')
                ->whereNotIn('role', ['admin', 'super_admin', UserRole::Admin ?? 'admin', UserRole::SuperAdmin ?? 'super_admin']);

            if (! $isSuperAdmin && $user && !empty($user->opd_id)) {
                $statsBase->where('opd_id', $user->opd_id);
            } elseif ($isSuperAdmin && !empty($opdId) && $opdId !== 'all') {
                $statsBase->where('opd_id', $opdId);
            }

            $allNips = (clone $statsBase)->whereNotNull('nip')->pluck('nip')->filter()->all();
            $allNames = (clone $statsBase)->pluck('name')->filter()->all();

            $stats = [
                'total'           => (clone $statsBase)->count(),
                'total_opd'       => (clone $statsBase)->whereNotNull('opd_id')->distinct('opd_id')->count('opd_id'),
                'total_divisions' => (clone $statsBase)->whereNotNull('division_id')->distinct('division_id')->count('division_id'),
                'total_interns'   => Registration::whereIn('status', ['accepted'])
                    ->whereHas('position', function($q) use ($allNips, $allNames) {
                        $q->whereIn('mentor_nip', $allNips)->orWhereIn('mentor_name', $allNames);
                    })->count(),
            ];

            $pembimbings = $query->latest()->paginate($perPage)->withQueryString();

            $divisionsQuery = Division::orderBy('nama_divisi');
            if (! $isSuperAdmin && $user && !empty($user->opd_id)) {
                $divisionsQuery->where('opd_id', $user->opd_id);
            } elseif ($isSuperAdmin && !empty($opdId) && $opdId !== 'all') {
                $divisionsQuery->where('opd_id', $opdId);
            }
            $divisions = $divisionsQuery->get();
        }

        $selectedOpdId = $opdId;

        return view('admin.pembimbing.index', compact('pembimbings', 'perPage', 'isSuperAdmin', 'stats', 'opds', 'divisions', 'selectedOpdId'));
    }

    public function exportPdf(Request $request)
    {
        $user = $request->user();
        $roleVal = $user?->role instanceof UserRole ? $user->role->value : (string) $user?->role;
        $isSuperAdmin = $roleVal === 'super_admin' || ($user && $user->isSuperAdmin() && Opd::exists());
        $opdId = $request->input('opd_id');

        if ($isSuperAdmin && empty($opdId)) {
            return redirect()->route('admin.pembimbing.index')
                ->with('warning', 'Silakan pilih Wilayah OPD terlebih dahulu sebelum mengekspor laporan pembimbing magang.');
        }

        $query = User::where(function($query) {
                $query->where('role', 'pembimbing')
                      ->orWhere('role', UserRole::Pembimbing ?? null);
            })
            ->where('id', '!=', 1)
            ->where('email', 'NOT LIKE', '%diskominfo%')
            ->where('email', 'NOT LIKE', '%admin%')
            ->whereNotIn('role', ['admin', 'super_admin', UserRole::Admin ?? 'admin', UserRole::SuperAdmin ?? 'super_admin'])
            ->with(['division', 'opd']);

        if (! $isSuperAdmin && $user && !empty($user->opd_id)) {
            $query->where('opd_id', $user->opd_id);
        } elseif ($isSuperAdmin && !empty($opdId) && $opdId !== 'all') {
            $query->where('opd_id', $opdId);
        }

        if ($request->filled('division_id') && $request->division_id !== 'all') {
            $query->where('division_id', $request->division_id);
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('nip', 'like', "%{$search}%");
            });
        }

        $pembimbings = $query->latest()->get();
        $selectedOpd = !empty($opdId) && $opdId !== 'all' ? Opd::find($opdId) : ($user && !empty($user->opd_id) ? $user->opd : null);
        $printedAt = now()->translatedFormat('d F Y, H:i') . ' WIB';

        $pdf = Pdf::loadView('admin.pembimbing.pdf', compact('pembimbings', 'isSuperAdmin', 'selectedOpd', 'printedAt'))
            ->setPaper('a4', 'portrait');

        $slug = $selectedOpd ? Str::slug($selectedOpd->name) : 'Semua_OPD';
        return $pdf->download('Daftar_Pembimbing_Magang_'.$slug.'_'.now()->format('Ymd_His').'.pdf');
    }

    public function create(Request $request)
    {
        if ($request->user() && $request->user()->isSuperAdmin()) {
            abort(403, 'Aksi ini hanya dapat dilakukan oleh Administrator OPD.');
        }

        $positions = \App\Models\Position::all();
        foreach ($positions as $position) {
            Division::firstOrCreate(['nama_divisi' => $position->nama_posisi]);
        }
        $divisions = Division::orderBy('nama_divisi')->get();
        return view('admin.pembimbing.create', compact('divisions'));
    }

    public function store(Request $request)
    {
        if ($request->user() && $request->user()->isSuperAdmin()) {
            abort(403, 'Aksi ini hanya dapat dilakukan oleh Administrator OPD.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'nip' => 'required|string|max:255|unique:users,nip',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
            'division_id' => 'required|exists:divisions,id',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['role'] = 'pembimbing';

        // Multi-tenant: Ketika Admin OPD membuat pembimbing, opd_id otomatis mengikuti opd_id Admin OPD
        $currentUser = $request->user();
        if ($currentUser && !empty($currentUser->opd_id)) {
            $validated['opd_id'] = $currentUser->opd_id;
        } elseif (!empty($validated['division_id'])) {
            $div = Division::withoutGlobalScopes()->find($validated['division_id']);
            if ($div && !empty($div->opd_id)) {
                $validated['opd_id'] = $div->opd_id;
            }
        }

        User::forceCreate($validated);

        return redirect()->route('admin.pembimbing.index')->with('success', 'Akun Pembimbing berhasil ditambahkan.');
    }

    public function edit(Request $request, User $pembimbing)
    {
        if ($request->user() && $request->user()->isSuperAdmin()) {
            abort(403, 'Aksi ini hanya dapat dilakukan oleh Administrator OPD.');
        }

        if (! $pembimbing->isPembimbing()) {
            abort(404);
        }
        $positions = \App\Models\Position::all();
        foreach ($positions as $position) {
            Division::firstOrCreate(['nama_divisi' => $position->nama_posisi]);
        }
        $divisions = Division::orderBy('nama_divisi')->get();

        $materialsCount = $pembimbing->materials()->count();
        $tasksCount = method_exists($pembimbing, 'tasks') ? $pembimbing->tasks()->count() : 0;
        $submissionsCount = 0;
        if ($materialsCount > 0) {
            $submissionsCount = \App\Models\ModuleSubmission::whereHas('material', function ($q) use ($pembimbing) {
                $q->where('pembimbing_id', $pembimbing->id);
            })->count();
        }

        return view('admin.pembimbing.edit', compact('pembimbing', 'divisions', 'materialsCount', 'tasksCount', 'submissionsCount'));
    }

    public function update(Request $request, User $pembimbing)
    {
        if ($request->user() && $request->user()->isSuperAdmin()) {
            abort(403, 'Aksi ini hanya dapat dilakukan oleh Administrator OPD.');
        }

        if (! $pembimbing->isPembimbing()) {
            abort(404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'nip' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($pembimbing->id)],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($pembimbing->id)],
            'password' => 'nullable|string|min:8',
            'division_id' => 'required|exists:divisions,id',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $pembimbing->update($validated);

        return redirect()->route('admin.pembimbing.index')->with('success', 'Data Pembimbing berhasil diperbarui.');
    }

    public function destroy(Request $request, User $pembimbing)
    {
        if ($request->user() && $request->user()->isSuperAdmin()) {
            abort(403, 'Aksi ini hanya dapat dilakukan oleh Administrator OPD.');
        }

        if (! $pembimbing->isPembimbing()) {
            abort(404);
        }

        $pembimbing->delete();

        return redirect()->route('admin.pembimbing.index')->with('success', 'Akun Pembimbing berhasil dihapus.');
    }
}
