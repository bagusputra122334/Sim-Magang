<?php

namespace App\Services;

use App\Models\Profile;
use App\Models\User;
use App\Repositories\ProfileRepository;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ProfileService
{
    public function __construct(protected ProfileRepository $profileRepository) {}

    public function getProfileByUser(int $userId): ?Profile
    {
        return $this->profileRepository->findByUserIdWithUser($userId);
    }

    public function getProfileByUserOrFail(int $userId): Profile
    {
        $profile = $this->profileRepository->findByUserIdWithUser($userId);

        if ($profile === null) {
            throw new \Illuminate\Database\Eloquent\ModelNotFoundException('Profil peserta tidak ditemukan.');
        }

        return $profile;
    }

    public function getProfileByIdOrFail(int $profileId): Profile
    {
        return $this->profileRepository->findByIdOrFail($profileId);
    }

    public function userHasProfile(int $userId): bool
    {
        return $this->profileRepository->existsForUser($userId);
    }

    public function ensureOwner(Profile $profile, int $userId): void
    {
        if (! $this->profileRepository->isOwner($profile, $userId)) {
            throw new \Illuminate\Auth\Access\AuthorizationException(
                'Anda tidak diizinkan mengakses atau mengubah profil peserta lain.'
            );
        }
    }

    public function createProfile(User $user, array $validatedData): Profile
    {
        $fotoFile = $validatedData['foto'] ?? null;

        $fotoPath = null;
        if ($fotoFile instanceof UploadedFile) {
            $fotoPath = $this->uploadFoto($user, $fotoFile);
        }

        $profileData = $validatedData;
        unset($profileData['foto']);

        if ($fotoPath !== null) {
            $profileData['foto'] = $fotoPath;
        }

        return $this->profileRepository->createForUser($user, $profileData);
    }

    public function updateProfile(Profile $profile, int $userId, array $validatedData): Profile
    {
        $this->ensureOwner($profile, $userId);

        $fotoFile = $validatedData['foto'] ?? null;

        $updateData = $validatedData;
        unset($updateData['foto']);

        if ($fotoFile instanceof UploadedFile) {
            $fotoPath = $this->uploadFoto($profile->user ?? User::find($userId), $fotoFile);
            if ($fotoPath !== null) {
                $updateData['foto'] = $fotoPath;
            }
        }

        $this->profileRepository->update($profile, $updateData);

        return $profile->fresh();
    }

    public function updateFotoOnly(Profile $profile, int $userId, UploadedFile $foto): Profile
    {
        $this->ensureOwner($profile, $userId);

        $fotoPath = $this->uploadFoto($profile->user ?? User::find($userId), $foto);

        if ($fotoPath !== null) {
            $this->profileRepository->updateFoto($profile, $fotoPath);
        }

        return $profile->fresh();
    }

    public function deleteFoto(Profile $profile, int $userId): bool
    {
        $this->ensureOwner($profile, $userId);

        return $this->profileRepository->deleteFoto($profile);
    }

    protected function uploadFoto(?User $user, UploadedFile $foto): ?string
    {
        $userId = $user?->id ?? 'guest';

        $ext = strtolower($foto->getClientOriginalExtension());
        if (! in_array($ext, ['jpg', 'jpeg', 'png'], true)) {
            $ext = 'png';
        }

        $timestamp = now()->format('YmdHis');
        $random = bin2hex(random_bytes(4));
        $fileName = sprintf('profile_%d_%s_%s.%s', $userId, $timestamp, $random, $ext);

        $disk = Storage::disk('public');

        $path = $foto->storeAs('profiles', $fileName, 'public');

        return $path ?: null;
    }
}
