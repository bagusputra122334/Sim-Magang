<?php

namespace App\Repositories;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProfileRepository
{
    public function __construct(protected Profile $model) {}

    public function findById(int $id): ?Profile
    {
        return $this->model->find($id);
    }

    public function findByIdOrFail(int $id): Profile
    {
        return $this->model->findOrFail($id);
    }

    public function findByUserId(int $userId): ?Profile
    {
        return $this->model
            ->where('user_id', $userId)
            ->first();
    }

    public function findByUserIdWithUser(int $userId): ?Profile
    {
        return $this->model
            ->with(['user:id,name,email,email_verified_at'])
            ->where('user_id', $userId)
            ->first();
    }

    public function existsForUser(int $userId): bool
    {
        return $this->model->where('user_id', $userId)->exists();
    }

    public function createForUser(User $user, array $data): Profile
    {
        $data = array_merge($data, ['user_id' => $user->id]);

        return $this->model->create($data);
    }

    public function update(Profile $profile, array $data): bool
    {
        return $profile->update($data);
    }

    public function updateFoto(Profile $profile, string $fotoPath): bool
    {
        $oldFoto = $profile->foto;

        $updated = $profile->update(['foto' => $fotoPath]);

        if ($updated && $oldFoto !== null && trim($oldFoto) !== '') {
            $this->deleteFotoFile($oldFoto);
        }

        return $updated;
    }

    public function deleteFoto(Profile $profile): bool
    {
        if ($profile->foto === null || trim($profile->foto) === '') {
            return true;
        }

        $fotoPath = $profile->foto;

        $updated = $profile->update(['foto' => null]);

        if ($updated) {
            $this->deleteFotoFile($fotoPath);
        }

        return $updated;
    }

    protected function deleteFotoFile(string $relativePath): void
    {
        if (str_starts_with($relativePath, 'http://') || str_starts_with($relativePath, 'https://')) {
            return;
        }

        $disk = \Illuminate\Support\Facades\Storage::disk('public');

        if ($disk->exists($relativePath)) {
            $disk->delete($relativePath);
        }
    }

    public function isOwner(Profile $profile, int $userId): bool
    {
        return (int) $profile->user_id === $userId;
    }
}
