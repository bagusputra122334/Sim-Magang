<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\Opd;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class OpdController extends Controller
{
    /**
     * Pastikan hanya Super Admin yang dapat mengakses manajemen OPD.
     */
    protected function authorizeSuperAdmin(): void
    {
        $user = auth()->user();
        if (!$user || !$user->isSuperAdmin()) {
            abort(403, 'Akses Ditolak: Hanya Super Admin yang memiliki wewenang mengelola Master Data OPD.');
        }
    }

    public function index(Request $request): View
    {
        $this->authorizeSuperAdmin();

        $rawPerPage = $request->input('perPage', $request->input('per_page', 10));
        if ($rawPerPage === 'all' || $rawPerPage === 'semua' || $rawPerPage === '-1') {
            $perPage = 10000;
        } else {
            $perPage = (int) $rawPerPage;
            if (!in_array($perPage, [5, 10, 15, 20, 25, 50, 100, 10000], true)) {
                $perPage = 10;
            }
        }

        $query = Opd::query()
            ->withCount([
                'positions',
                'divisions',
                'registrations',
                'registrations as active_interns_count' => function ($q) {
                    $q->where('status', \App\Enums\RegistrationStatus::Accepted->value)
                      ->where('is_terminated', false);
                },
                'users as admins_count' => function ($q) {
                    $q->whereIn('role', ['admin', 'super_admin']);
                },
            ]);

        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('head_of_office_name', 'like', "%{$search}%")
                  ->orWhere('head_of_office_nip', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $status = $request->input('status');
            if ($status === 'active') {
                $query->where('is_active', true);
            } elseif ($status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        $opds = $query->orderBy('name')->paginate($perPage)->withQueryString();

        $statistics = [
            'total_opd'       => Opd::count(),
            'active_opd'      => Opd::where('is_active', true)->count(),
            'total_positions' => \App\Models\Position::count(),
            'active_interns'  => \App\Models\Registration::where('status', \App\Enums\RegistrationStatus::Accepted->value)->where('is_terminated', false)->count(),
        ];

        return view('admin.opds.index', compact('opds', 'perPage', 'statistics'));
    }

    /**
     * Export daftar Master OPD ke PDF sesuai filter pencarian & status yang aktif.
     */
    public function exportPdf(Request $request)
    {
        $this->authorizeSuperAdmin();

        $query = Opd::query()
            ->withCount([
                'positions',
                'divisions',
                'registrations',
                'registrations as active_interns_count' => function ($q) {
                    $q->where('status', \App\Enums\RegistrationStatus::Accepted->value)
                      ->where('is_terminated', false);
                },
                'users as admins_count' => function ($q) {
                    $q->whereIn('role', ['admin', 'super_admin']);
                },
            ]);

        $search = null;
        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('head_of_office_name', 'like', "%{$search}%")
                  ->orWhere('head_of_office_nip', 'like', "%{$search}%");
            });
        }

        $status = null;
        if ($request->filled('status')) {
            $status = $request->input('status');
            if ($status === 'active') {
                $query->where('is_active', true);
            } elseif ($status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        $opds = $query->orderBy('name')->get();

        $statistics = [
            'total_opd'       => $opds->count(),
            'active_opd'      => $opds->where('is_active', true)->count(),
            'total_positions' => $opds->sum('positions_count'),
            'active_interns'  => $opds->sum('active_interns_count'),
        ];

        $printedAt = now()->translatedFormat('d F Y, H:i') . ' WIB';

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.opds.pdf', compact('opds', 'statistics', 'search', 'status', 'printedAt'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('Laporan_Master_OPD_Tuban_'.now()->format('Ymd_His').'.pdf');
    }

    public function create(): View
    {
        $this->authorizeSuperAdmin();

        return view('admin.opds.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorizeSuperAdmin();

        $validated = $request->validate([
            'name'                => 'required|string|max:255',
            'code'                => 'required|string|max:50|unique:opds,code',
            'slug'                => 'nullable|string|max:100|unique:opds,slug',
            'address'             => 'nullable|string|max:500',
            'phone'               => 'nullable|string|max:50',
            'email'               => 'nullable|email|max:255',
            'head_of_office_name' => 'nullable|string|max:255',
            'head_of_office_nip'  => 'nullable|string|max:50',
            'is_active'           => 'nullable|boolean',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['code'] ?? $validated['name']);
        }
        $validated['is_active'] = $request->boolean('is_active', true);

        $opd = Opd::create($validated);

        // Generate Akun Admin OPD Otomatis:
        // Nama akun admin sesuai Nama OPD, dan email login sesuai Email Resmi OPD
        $adminName = $opd->name;

        if (!empty($opd->email) && !User::where('email', $opd->email)->exists()) {
            $adminEmail = $opd->email;
        } else {
            $cleanCode = Str::slug($opd->code, '');
            if (empty($cleanCode)) {
                $cleanCode = 'opd' . $opd->id;
            }
            $baseEmail = !empty($opd->email) ? $opd->email : "admin.{$cleanCode}@tubankab.go.id";
            $adminEmail = $baseEmail;
            $counter = 1;
            while (User::where('email', $adminEmail)->exists()) {
                $parts = explode('@', $baseEmail);
                $adminEmail = $parts[0] . $counter . '@' . ($parts[1] ?? 'tubankab.go.id');
                $counter++;
            }
        }

        $adminUser = User::forceCreate([
            'name'              => $adminName,
            'email'             => $adminEmail,
            'password'          => Hash::make('password'),
            'nip'               => $opd->head_of_office_nip,
            'position_title'    => 'Administrator ' . $opd->name,
            'role'              => UserRole::Admin,
            'opd_id'            => $opd->id,
            'email_verified_at' => now(),
        ]);

        return redirect()->route('admin.opds.index')
            ->with('success', "Master Data OPD \"{$opd->name}\" berhasil ditambahkan dan Akun Admin OPD ({$adminUser->email}) otomatis dibuat dengan password default: password.");
    }

    public function edit(Opd $opd): View
    {
        $this->authorizeSuperAdmin();

        return view('admin.opds.edit', compact('opd'));
    }

    public function update(Request $request, Opd $opd): RedirectResponse
    {
        $this->authorizeSuperAdmin();

        $validated = $request->validate([
            'name'                => 'required|string|max:255',
            'code'                => ['required', 'string', 'max:50', Rule::unique('opds')->ignore($opd->id)],
            'slug'                => ['nullable', 'string', 'max:100', Rule::unique('opds')->ignore($opd->id)],
            'address'             => 'nullable|string|max:500',
            'phone'               => 'nullable|string|max:50',
            'email'               => 'nullable|email|max:255',
            'head_of_office_name' => 'nullable|string|max:255',
            'head_of_office_nip'  => 'nullable|string|max:50',
            'is_active'           => 'nullable|boolean',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['code'] ?? $validated['name']);
        }
        $validated['is_active'] = $request->boolean('is_active', true);

        $opd->update($validated);

        return redirect()->route('admin.opds.index')->with('success', 'Data OPD berhasil diperbarui.');
    }

    public function toggleStatus(Opd $opd): RedirectResponse
    {
        $this->authorizeSuperAdmin();

        $opd->update(['is_active' => !$opd->is_active]);

        $statusLabel = $opd->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->route('admin.opds.index')
            ->with('success', "Status OPD \"{$opd->name}\" berhasil {$statusLabel}.");
    }
}
