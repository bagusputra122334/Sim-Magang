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
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $filters = request()->only(['search', 'sort', 'direction', 'status']);
        $perPage = request()->integer('per_page', 10);
        if ($perPage < 1 || $perPage > 100) {
            $perPage = 10;
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
    public function create(): View
    {
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
        $validated = $request->validated();
        $validated['deskripsi'] = $validated['deskripsi'] ?? '';
        $validated['kualifikasi'] = $validated['kualifikasi'] ?? '';

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
        $validated['kualifikasi'] = $validated['kualifikasi'] ?? '';

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
