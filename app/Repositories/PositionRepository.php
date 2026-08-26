<?php

namespace App\Repositories;

use App\Enums\PositionStatus;
use App\Models\Position;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class PositionRepository
{
    protected array $defaultSort = ['column' => 'id', 'direction' => 'desc'];

    protected array $allowedSortColumns = [
        'id',
        'nama_posisi',
        'kuota',
        'status',
        'created_at',
        'updated_at',
    ];

    public function __construct(
        protected Position $model
    ) {}

    /**
     * Ambil semua posisi dengan fitur search, sort, dan pagination.
     *
     * @param  array<string, mixed>  $filters
     */
    public function paginateWithFilters(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        $query = $this->model->newQuery();

        $search = trim((string) ($filters['search'] ?? ''));
        if ($search !== '') {
            $query->where(function ($q) use ($search): void {
                $q->where('nama_posisi', 'LIKE', "%{$search}%")
                    ->orWhere('deskripsi', 'LIKE', "%{$search}%")
                    ->orWhere('kualifikasi', 'LIKE', "%{$search}%")
                    ->orWhere('slug', 'LIKE', "%{$search}%");
            });
        }

        if (isset($filters['status']) && $filters['status'] instanceof PositionStatus) {
            $query->where('status', $filters['status']);
        }

        $sortColumn = (string) ($filters['sort'] ?? $this->defaultSort['column']);
        if (! in_array($sortColumn, $this->allowedSortColumns, true)) {
            $sortColumn = $this->defaultSort['column'];
        }

        $sortDirection = strtolower((string) ($filters['direction'] ?? $this->defaultSort['direction']));
        if (! in_array($sortDirection, ['asc', 'desc'], true)) {
            $sortDirection = $this->defaultSort['direction'];
        }

        $query->orderBy($sortColumn, $sortDirection);

        return $query->paginate($perPage)
            ->appends($filters);
    }

    public function findById(int $id): ?Position
    {
        return $this->model->find($id);
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function create(array $attributes): Position
    {
        return $this->model->create($attributes);
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function update(Position $position, array $attributes): bool
    {
        return $position->update($attributes);
    }

    public function delete(Position $position): ?bool
    {
        return $position->delete();
    }

    public function toggleStatus(Position $position): Position
    {
        $position->status = $position->status === PositionStatus::Aktif
            ? PositionStatus::TidakAktif
            : PositionStatus::Aktif;

        $position->save();

        return $position;
    }

    public function activate(Position $position): Position
    {
        $position->status = PositionStatus::Aktif;
        $position->save();

        return $position;
    }

    public function deactivate(Position $position): Position
    {
        $position->status = PositionStatus::TidakAktif;
        $position->save();

        return $position;
    }

    /**
     * @return Collection<int, Position>
     */
    public function getAllActive(): Collection
    {
        return $this->model
            ->where('status', PositionStatus::Aktif)
            ->orderBy('nama_posisi', 'asc')
            ->get();
    }

    public function countAll(): int
    {
        return $this->model->count();
    }

    public function countActive(): int
    {
        return $this->model->where('status', PositionStatus::Aktif)->count();
    }
}
