<?php

namespace App\Services;

use App\Enums\PositionStatus;
use App\Exceptions\PositionDeleteRestrictedException;
use App\Models\Position;
use App\Repositories\PositionRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\QueryException;

class PositionService
{
    public function __construct(
        protected PositionRepository $positions
    ) {}

    /**
     * @param  array<string, mixed>  $filters
     */
    public function getPaginatedPositions(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        if (isset($filters['status']) && is_string($filters['status']) && $filters['status'] !== '') {
            try {
                $filters['status'] = PositionStatus::from($filters['status']);
            } catch (\ValueError) {
                unset($filters['status']);
            }
        } else {
            unset($filters['status']);
        }

        return $this->positions->paginateWithFilters($filters, $perPage);
    }

    public function getPositionById(int $id): ?Position
    {
        return $this->positions->findById($id);
    }

    /**
     * @param  array<string, mixed>  $validatedData
     */
    public function createPosition(array $validatedData): Position
    {
        return $this->positions->create($validatedData);
    }

    /**
     * @param  array<string, mixed>  $validatedData
     */
    public function updatePosition(Position $position, array $validatedData): bool
    {
        return $this->positions->update($position, $validatedData);
    }

    /**
     * Soft delete posisi. Jika posisi sudah punya relasi pendaftaran dan ada FK restrict,
     * lemparkan custom exception untuk ditangani controller dengan flash message.
     */
    public function deletePosition(Position $position): bool
    {
        try {
            $deleted = $this->positions->delete($position);

            return $deleted === true;
        } catch (QueryException $e) {
            $errorCode = $e->errorInfo[1] ?? 0;

            if (in_array($errorCode, [1451, 1217, 1452], true)) {
                throw new PositionDeleteRestrictedException(
                    'Posisi "'.$position->nama_posisi.'" tidak dapat dihapus karena masih memiliki data pendaftaran. Nonaktifkan saja statusnya agar tidak muncul di halaman pendaftaran peserta.'
                );
            }

            throw $e;
        }
    }

    public function togglePositionStatus(Position $position): Position
    {
        return $this->positions->toggleStatus($position);
    }

    public function activatePosition(Position $position): Position
    {
        return $this->positions->activate($position);
    }

    public function deactivatePosition(Position $position): Position
    {
        return $this->positions->deactivate($position);
    }

    /**
     * @return array<string, int>
     */
    public function getStatistics(): array
    {
        return [
            'total'     => $this->positions->countAll(),
            'aktif'     => $this->positions->countActive(),
            'nonaktif'  => $this->positions->countAll() - $this->positions->countActive(),
        ];
    }
}
