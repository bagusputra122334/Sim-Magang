<?php

namespace App\Repositories;

use App\Enums\RegistrationStatus;
use App\Models\Position;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class RegistrationRepository
{
    public function __construct(protected Registration $model) {}

    public function getById(int $id): ?Registration
    {
        return $this->model->find($id);
    }

    public function getByIdOrFail(int $id): Registration
    {
        return $this->model->findOrFail($id);
    }

    public function getByIdWithRelations(int $id): ?Registration
    {
        return $this->model
            ->with(['user:id,name,email', 'position:id,nama_posisi,slug,kuota,status,tanggal_buka,tanggal_tutup'])
            ->find($id);
    }

    public function getByIdWithRelationsOrFail(int $id): Registration
    {
        return $this->model
            ->with(['user:id,name,email', 'position:id,nama_posisi,slug,kuota,status,tanggal_buka,tanggal_tutup'])
            ->findOrFail($id);
    }

    public function getByIdWithFullRelationsOrFail(int $id): Registration
    {
        return $this->model
            ->with([
                'user' => static function (\Illuminate\Database\Eloquent\Relations\BelongsTo $q): void {
                    $q->select(['id', 'name', 'email', 'created_at'])
                        ->with(['profile']);
                },
                'position:id,nama_posisi,slug,kuota,status,deskripsi,tanggal_buka,tanggal_tutup',
            ])
            ->findOrFail($id);
    }

    /** @return LengthAwarePaginator<Registration> */
    public function riwayatByUserIdPaginated(int $userId, int $perPage = 10): LengthAwarePaginator
    {
        return $this->model
            ->with(['position:id,nama_posisi,slug,kuota,status'])
            ->where('user_id', $userId)
            ->latest('tanggal_submit')
            ->latest('id')
            ->paginate($perPage);
    }

    /** @return Collection<int, Registration> */
    public function riwayatByUserIdAll(int $userId): Collection
    {
        return $this->model
            ->with(['position:id,nama_posisi,slug,kuota,status'])
            ->where('user_id', $userId)
            ->latest('tanggal_submit')
            ->get();
    }

    public function isOwner(Registration $registration, int $userId): bool
    {
        return (int) $registration->user_id === $userId;
    }

    public function ensureOwner(Registration $registration, int $userId): void
    {
        if (! $this->isOwner($registration, $userId)) {
            throw new \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException(
                'Anda tidak memiliki izin untuk mengakses data pendaftaran orang lain.'
            );
        }
    }

    public function punyaPendaftaranAktif(int $userId): bool
    {
        $today = now()->toDateString();

        return $this->model
            ->where('user_id', $userId)
            ->where(function ($query) use ($today): void {
                $query->whereIn('status', [
                    RegistrationStatus::Submitted->value,
                    RegistrationStatus::UnderReview->value,
                ])
                ->orWhere(function ($q) use ($today): void {
                    $q->where('status', RegistrationStatus::Accepted->value)
                        ->where('is_terminated', false)
                        ->where(function ($sq) use ($today): void {
                            $sq->whereNull('periode_selesai')
                                ->orWhereDate('periode_selesai', '>=', $today);
                        });
                });
            })
            ->exists();
    }

    public function punyaPendaftaranAktifUntukPosisi(int $userId, int $positionId, ?int $ignoreRegistrationId = null): bool
    {
        $today = now()->toDateString();

        $query = $this->model
            ->where('user_id', $userId)
            ->where('position_id', $positionId)
            ->where(function ($q) use ($today): void {
                $q->whereIn('status', [
                    RegistrationStatus::Submitted->value,
                    RegistrationStatus::UnderReview->value,
                ])
                ->orWhere(function ($sq) use ($today): void {
                    $sq->where('status', RegistrationStatus::Accepted->value)
                        ->where('is_terminated', false)
                        ->where(function ($ssq) use ($today): void {
                            $ssq->whereNull('periode_selesai')
                                ->orWhereDate('periode_selesai', '>=', $today);
                        });
                });
            });

        if ($ignoreRegistrationId !== null) {
            $query->where('id', '!=', $ignoreRegistrationId);
        }

        return $query->exists();
    }

    public function create(array $data): Registration
    {
        return $this->model->create($data);
    }

    public function update(Registration $registration, array $data): bool
    {
        return $registration->update($data);
    }

    public function delete(Registration $registration): ?bool
    {
        $this->hapusFileLampiran($registration);

        return $registration->delete();
    }

    public function hapusFileLampiran(Registration $registration): void
    {
        $disk = \Illuminate\Support\Facades\Storage::disk('public');

        foreach (['cv_path', 'surat_pengantar_path', 'proposal_magang_path'] as $field) {
            $path = $registration->{$field};
            if ($path === null || trim($path) === '' || str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
                continue;
            }
            if ($disk->exists($path)) {
                $disk->delete($path);
            }
        }
    }

    /** @return Collection<int, Position> */
    public function getPilihanPosisiYangAktif(): Collection
    {
        return Position::query()
            ->where('status', \App\Enums\PositionStatus::Aktif->value)
            ->where(function ($q) {
                $q->whereNull('tanggal_buka')
                    ->orWhere('tanggal_buka', '<=', now()->toDateString());
            })
            ->where(function ($q) {
                $q->whereNull('tanggal_tutup')
                    ->orWhere('tanggal_tutup', '>=', now()->toDateString());
            })
            ->orderBy('nama_posisi')
            ->get(['id', 'nama_posisi', 'slug', 'kuota', 'deskripsi', 'status', 'tanggal_buka', 'tanggal_tutup']);
    }

    public function countByPositionAndStatusAktif(int $positionId): int
    {
        return $this->model
            ->where('position_id', $positionId)
            ->whereIn('status', [
                RegistrationStatus::Submitted->value,
                RegistrationStatus::UnderReview->value,
                RegistrationStatus::Accepted->value,
            ])
            ->count();
    }

    /**
    /**
     * @param  array<string, mixed>  $filters
     * @return Builder<Registration>
     */
    public function adminSearchQuery(array $filters = []): Builder
    {
        $query = $this->model
            ->with([
                'user:id,name,email',
                'user.profile',
                'position:id,nama_posisi,slug,kuota,status',
            ]);

        $searchKeyword = isset($filters['search']) && is_string($filters['search'])
            ? trim($filters['search'])
            : null;

        if ($searchKeyword !== '' && $searchKeyword !== null) {
            $keywordWildcard = "%{$searchKeyword}%";
            $query->where(function ($q) use ($keywordWildcard, $searchKeyword): void {
                $q->where('nomor_pendaftaran', 'like', $keywordWildcard)
                    ->orWhere('catatan_admin', 'like', $keywordWildcard)
                    ->orWhereHas('user', function ($subQ) use ($keywordWildcard): void {
                        $subQ->where('name', 'like', $keywordWildcard)
                            ->orWhere('email', 'like', $keywordWildcard)
                            ->orWhereHas('profile', function ($profQ) use ($keywordWildcard): void {
                                $profQ->where('nama_lengkap', 'like', $keywordWildcard)
                                    ->orWhere('institusi', 'like', $keywordWildcard)
                                    ->orWhere('nis_nim', 'like', $keywordWildcard)
                                    ->orWhere('nim', 'like', $keywordWildcard);
                            });
                    })
                    ->orWhereHas('position', function ($subQ) use ($keywordWildcard): void {
                        $subQ->where('nama_posisi', 'like', $keywordWildcard)
                            ->orWhere('slug', 'like', $keywordWildcard);
                    });
            });
        }

        $statusFilter = isset($filters['status']) && is_string($filters['status']) ? trim($filters['status']) : null;
        if ($statusFilter !== '' && $statusFilter !== null) {
            $validStatusList = array_map(
                static fn (\BackedEnum $e): string => $e->value,
                RegistrationStatus::cases()
            );
            if (in_array($statusFilter, $validStatusList, true)) {
                $query->where('status', $statusFilter);
            }
        }

        $positionFilter = isset($filters['position_id'])
            ? (is_numeric($filters['position_id']) ? (int) $filters['position_id'] : null)
            : null;
        if ($positionFilter !== null && $positionFilter > 0) {
            $query->where('position_id', $positionFilter);
        }

        // Additional date filters if provided
        if (! empty($filters['tanggal_mulai']) && is_string($filters['tanggal_mulai'])) {
            try {
                $tStart = \Illuminate\Support\Carbon::createFromFormat('Y-m-d', trim($filters['tanggal_mulai']));
                if ($tStart instanceof \Illuminate\Support\Carbon) {
                    $query->whereDate('tanggal_submit', '>=', $tStart->toDateString());
                }
            } catch (\Throwable) {}
        }
        if (! empty($filters['tanggal_selesai']) && is_string($filters['tanggal_selesai'])) {
            try {
                $tEnd = \Illuminate\Support\Carbon::createFromFormat('Y-m-d', trim($filters['tanggal_selesai']));
                if ($tEnd instanceof \Illuminate\Support\Carbon) {
                    $query->whereDate('tanggal_submit', '<=', $tEnd->toDateString());
                }
            } catch (\Throwable) {}
        }

        return $query
            ->latest('tanggal_submit')
            ->latest('id');
    }

    /**
     * @param  array<string, mixed>  $filters
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator<Registration>
     */
    public function adminSearchPaginated(array $filters = [], int $perPage = 10): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return $this->adminSearchQuery($filters)
            ->paginate($perPage)
            ->withQueryString();
    }

    /** @return Collection<int, Position> */
    public function getAllPilihanPosisiUntukFilterAdmin(): Collection
    {
        return Position::query()
            ->orderBy('nama_posisi')
            ->get(['id', 'nama_posisi', 'slug', 'status']);
    }
}
