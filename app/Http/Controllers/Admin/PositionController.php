<?php

namespace App\Http\Controllers\Admin;

use App\Enums\PositionStatus;
use App\Exceptions\PositionDeleteRestrictedException;
use App\Http\Requests\Admin\StorePositionRequest;
use App\Http\Requests\Admin\UpdatePositionRequest;
use App\Models\Position;
use App\Services\PositionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PositionController extends AdminController
{
    public function __construct(
        protected PositionService $positionService
    ) {}

    /**
     * Cek apakah user yang login adalah Super Admin.
     */
    protected function isStrictSuperAdmin(): bool
    {
        $user = auth()->user();
        if (!$user) {
            return false;
        }

        $role = $user->role instanceof \App\Enums\UserRole ? $user->role->value : (string) $user->role;
        return $role === 'super_admin';
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View|RedirectResponse
    {
        if ($this->isStrictSuperAdmin()) {
            return redirect()->route('admin.opds.index')
                ->with('info', 'Pengelolaan posisi magang dikelola secara mandiri oleh masing-masing Admin OPD.');
        }

        $filters = request()->only(['search', 'sort', 'direction', 'status']);
        $rawPerPage = request()->input('per_page', '10');
        if ($rawPerPage === 'all' || $rawPerPage === 'semua' || $rawPerPage === '-1') {
            $perPage = 10000;
        } else {
            $perPage = (int) $rawPerPage;
            if (! in_array($perPage, [5, 10, 15, 20, 25, 50, 100, 10000], true)) {
                $perPage = 10;
            }
        }

        $positions = $this->positionService->getPaginatedPositions($filters, $perPage);
        $statistics = $this->positionService->getStatistics();

        return view($this->viewPrefix.'.positions.index', [
            'positions'  => $positions,
            'statistics' => $statistics,
            'filters'    => $filters,
            'statusOptions' => PositionStatus::class,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View|RedirectResponse
    {
        if ($this->isStrictSuperAdmin()) {
            return redirect()->route('admin.opds.index')
                ->with('error', 'Super Admin tidak mengelola posisi magang. Posisi magang dikelola oleh Admin OPD terkait.');
        }

        return view($this->viewPrefix.'.positions.create', [
            'position'      => new Position(),
            'statusOptions' => PositionStatus::cases(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePositionRequest $request): RedirectResponse
    {
        if ($this->isStrictSuperAdmin()) {
            return redirect()->route('admin.opds.index')
                ->with('error', 'Super Admin tidak membuat posisi magang. Posisi magang dikelola oleh Admin OPD terkait.');
        }

        $validated = $request->validated();
        $validated['deskripsi'] = $validated['deskripsi'] ?? '';
        $validated['kualifikasi_siswa'] = $validated['kualifikasi_siswa'] ?? null;
        $validated['kualifikasi_mahasiswa'] = $validated['kualifikasi_mahasiswa'] ?? null;
        $validated['tipe_pendaftar'] = $validated['tipe_pendaftar'] ?? 'all';

        if (empty($validated['kualifikasi'])) {
            $parts = [];
            if (!empty($validated['kualifikasi_mahasiswa'])) {
                $parts[] = "[Mahasiswa]\n" . $validated['kualifikasi_mahasiswa'];
            }
            if (!empty($validated['kualifikasi_siswa'])) {
                $parts[] = "[Siswa SMK]\n" . $validated['kualifikasi_siswa'];
            }
            $validated['kualifikasi'] = implode("\n\n", $parts);
        }

        $position = $this->positionService->createPosition($validated);

        return redirect()
            ->route('admin.positions.index')
            ->with('success', 'Posisi magang "'.$position->nama_posisi.'" berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Position $position): RedirectResponse
    {
        return redirect()->route('admin.positions.edit', $position);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Position $position): View
    {
        return view($this->viewPrefix.'.positions.edit', [
            'position'      => $position,
            'statusOptions' => PositionStatus::cases(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePositionRequest $request, Position $position): RedirectResponse
    {
        $validated = $request->validated();
        $validated['deskripsi'] = $validated['deskripsi'] ?? '';
        $validated['kualifikasi_siswa'] = $validated['kualifikasi_siswa'] ?? null;
        $validated['kualifikasi_mahasiswa'] = $validated['kualifikasi_mahasiswa'] ?? null;
        $validated['tipe_pendaftar'] = $validated['tipe_pendaftar'] ?? 'all';

        if (empty($validated['kualifikasi'])) {
            $parts = [];
            if (!empty($validated['kualifikasi_mahasiswa'])) {
                $parts[] = "[Mahasiswa]\n" . $validated['kualifikasi_mahasiswa'];
            }
            if (!empty($validated['kualifikasi_siswa'])) {
                $parts[] = "[Siswa SMK]\n" . $validated['kualifikasi_siswa'];
            }
            $validated['kualifikasi'] = implode("\n\n", $parts);
        }

        $this->positionService->updatePosition($position, $validated);

        return redirect()
            ->route('admin.positions.index')
            ->with('success', 'Posisi magang "'.$position->nama_posisi.'" berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Position $position): RedirectResponse
    {
        $namaPosisi = $position->nama_posisi;

        try {
            $this->positionService->deletePosition($position);
        } catch (PositionDeleteRestrictedException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()
            ->route('admin.positions.index')
            ->with('success', 'Posisi magang "'.$namaPosisi.'" berhasil dihapus.');
    }

    /**
     * Toggle status aktif / tidak aktif posisi.
     */
    public function toggleStatus(Position $position): RedirectResponse
    {
        $position = $this->positionService->togglePositionStatus($position);
        $label = $position->status->isAktif() ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()
            ->route('admin.positions.index')
            ->with('success', 'Posisi magang "'.$position->nama_posisi.'" berhasil '.$label.'.');
    }
}
