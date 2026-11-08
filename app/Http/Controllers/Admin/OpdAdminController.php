<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\Opd;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class OpdAdminController extends Controller
{
    /**
     * Pastikan hanya Super Admin yang dapat mengakses manajemen akun Admin OPD.
     */
    protected function authorizeSuperAdmin(): void
    {
        $user = auth()->user();
        if (!$user || !$user->isSuperAdmin()) {
            abort(403, 'Akses Ditolak: Hanya Super Admin yang memiliki wewenang mengelola Akun Admin OPD.');
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

        $query = User::query()
            ->where('role', UserRole::Admin->value)
            ->with('opd');

        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('nip', 'like', "%{$search}%")
                  ->orWhere('position_title', 'like', "%{$search}%");
            });
        }

        if ($request->filled('opd_id')) {
            $query->where('opd_id', $request->input('opd_id'));
        }

        $admins = $query->orderBy('name')->paginate($perPage)->withQueryString();
        $opds = Opd::active()->orderBy('name')->get();

        return view('admin.opd-admins.index', compact('admins', 'opds', 'perPage'));
    }

    public function create(): View
    {
        $this->authorizeSuperAdmin();

        $opds = Opd::active()->orderBy('name')->get();

        return view('admin.opd-admins.create', compact('opds'));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorizeSuperAdmin();

        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'email'          => 'required|string|email|max:255|unique:users,email',
            'password'       => 'required|string|min:8',
            'nip'            => 'nullable|string|max:50|unique:users,nip',
            'position_title' => 'nullable|string|max:100',
            'role'           => 'required|in:admin,super_admin',
            'opd_id'         => 'nullable|required_if:role,admin|exists:opds,id',
        ]);

        $role = $validated['role'] === 'super_admin' ? UserRole::SuperAdmin : UserRole::Admin;
        $opdId = $validated['role'] === 'super_admin' ? null : $validated['opd_id'];

        User::forceCreate([
            'name'              => $validated['name'],
            'email'             => $validated['email'],
            'password'          => Hash::make($validated['password']),
            'nip'               => $validated['nip'] ?? null,
            'position_title'    => $validated['position_title'] ?? null,
            'role'              => $role,
            'opd_id'            => $opdId,
            'email_verified_at' => now(),
        ]);

        return redirect()->route('admin.opd-admins.index')->with('success', 'Akun Administrator OPD berhasil dibuat.');
    }

    public function edit(User $opdAdmin): View
    {
        $this->authorizeSuperAdmin();

        if (!$opdAdmin->isAdmin()) {
            abort(404, 'User yang dipilih bukan Administrator.');
        }

        $opds = Opd::active()->orderBy('name')->get();

        return view('admin.opd-admins.edit', compact('opdAdmin', 'opds'));
    }

    public function update(Request $request, User $opdAdmin): RedirectResponse
    {
        $this->authorizeSuperAdmin();

        if (!$opdAdmin->isAdmin()) {
            abort(404);
        }

        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'email'          => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($opdAdmin->id)],
            'password'       => 'nullable|string|min:8',
            'nip'            => ['nullable', 'string', 'max:50', Rule::unique('users')->ignore($opdAdmin->id)],
            'position_title' => 'nullable|string|max:100',
            'role'           => 'required|in:admin,super_admin',
            'opd_id'         => 'nullable|required_if:role,admin|exists:opds,id',
        ]);

        $updateData = [
            'name'           => $validated['name'],
            'email'          => $validated['email'],
            'nip'            => $validated['nip'] ?? null,
            'position_title' => $validated['position_title'] ?? null,
            'role'           => $validated['role'] === 'super_admin' ? UserRole::SuperAdmin : UserRole::Admin,
            'opd_id'         => $validated['role'] === 'super_admin' ? null : $validated['opd_id'],
        ];

        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $opdAdmin->update($updateData);

        return redirect()->route('admin.opd-admins.index')->with('success', 'Data Akun Administrator berhasil diperbarui.');
    }

    public function destroy(Request $request, User $opdAdmin): RedirectResponse
    {
        $this->authorizeSuperAdmin();

        if ($request->user()->id === $opdAdmin->id) {
            return redirect()->route('admin.opd-admins.index')
                ->with('error', 'Anda tidak dapat menghapus akun Anda sendiri saat sedang login.');
        }

        if (!$opdAdmin->isAdmin()) {
            abort(404);
        }

        $opdAdmin->delete();

        return redirect()->route('admin.opd-admins.index')->with('success', 'Akun Administrator berhasil dihapus.');
    }
}
