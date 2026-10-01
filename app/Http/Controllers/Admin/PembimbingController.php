<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\Division;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class PembimbingController extends Controller
{
    public function index()
    {
        // Auto-repair: Ensure Admin role in DB is strictly 'admin'
        User::where('id', 1)
            ->orWhere('email', 'like', '%diskominfo%')
            ->orWhere('email', 'like', '%admin%')
            ->update(['role' => 'admin']);

        // Fetch ONLY genuine Pembimbing accounts, strictly excluding Admin
        $pembimbings = User::where(function($query) {
                $query->where('role', 'pembimbing')
                      ->orWhere('role', \App\Enums\UserRole::Pembimbing ?? null);
            })
            ->where('id', '!=', 1)
            ->where('email', 'NOT LIKE', '%diskominfo%')
            ->where('email', 'NOT LIKE', '%admin%')
            ->whereNotIn('role', ['admin', \App\Enums\UserRole::Admin ?? 'admin'])
            ->with('division')
            ->latest()
            ->paginate(10);
            
        return view('admin.pembimbing.index', compact('pembimbings'));
    }

    public function create()
    {
        $positions = \App\Models\Position::all();
        foreach ($positions as $position) {
            \App\Models\Division::firstOrCreate(['nama_divisi' => $position->nama_posisi]);
        }
        $divisions = \App\Models\Division::orderBy('nama_divisi')->get();
        return view('admin.pembimbing.create', compact('divisions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'nip' => 'required|string|max:255|unique:users,nip',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
            'division_id' => 'required|exists:divisions,id',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['role'] = 'pembimbing';

        User::forceCreate($validated);

        return redirect()->route('admin.pembimbing.index')->with('success', 'Akun Pembimbing berhasil ditambahkan.');
    }

    public function edit(User $pembimbing)
    {
        if (! $pembimbing->isPembimbing()) {
            abort(404);
        }
        $positions = \App\Models\Position::all();
        foreach ($positions as $position) {
            \App\Models\Division::firstOrCreate(['nama_divisi' => $position->nama_posisi]);
        }
        $divisions = \App\Models\Division::orderBy('nama_divisi')->get();

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

    public function destroy(User $pembimbing)
    {
        if (! $pembimbing->isPembimbing()) {
            abort(404);
        }

        $pembimbing->delete();

        return redirect()->route('admin.pembimbing.index')->with('success', 'Akun Pembimbing berhasil dihapus.');
    }
}
