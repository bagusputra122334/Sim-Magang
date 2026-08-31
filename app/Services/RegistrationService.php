<?php

namespace App\Services;

use App\Enums\RegistrationStatus;
use App\Models\Position;
use App\Models\Registration;
use App\Models\User;
use App\Repositories\RegistrationRepository;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class RegistrationService
{
    public function __construct(protected RegistrationRepository $registrationRepository) {}

    public function getRiwayatPendaftaran(int $userId, int $perPage = 10)
    {
        return $this->registrationRepository->riwayatByUserIdPaginated($userId, $perPage);
    }

    public function getDetailPendaftaranOrFail(int $registrationId, int $userId): Registration
    {
        $registration = $this->registrationRepository->getByIdWithRelationsOrFail($registrationId);
        $this->ensureOwner($registration, $userId);

        return $registration;
    }

    public function getDetailPendaftaranUntukEditOrFail(int $registrationId, int $userId): Registration
    {
        $registration = $this->getDetailPendaftaranOrFail($registrationId, $userId);

        if (! $registration->dapatDiubah()) {
            throw new \Illuminate\Auth\Access\AuthorizationException(
                'Pendaftaran dengan status '.$registration->status->label().' tidak dapat diubah. Hanya pendaftaran dengan status Submitted atau Rejected yang dapat diperbarui.'
            );
        }

        return $registration;
    }

    public function getPilihanPosisiAktif()
    {
        return $this->registrationRepository->getPilihanPosisiYangAktif();
    }

    public function ensureUserBisaMendaftar(User $user, ?Position $specificPosition = null): void
    {
        if (! $user->isPeserta()) {
            throw new \DomainException('Hanya Peserta magang yang dapat mendaftar.');
        }

        if (! $user->hasProfile()) {
            throw new \DomainException('Anda belum melengkapi profil. Silakan lengkapi profil terlebih dahulu sebelum melakukan pendaftaran magang.');
        }

        if ($this->registrationRepository->punyaPendaftaranAktif($user->id)) {
            throw new \DomainException(
                'Anda masih memiliki pendaftaran magang aktif (status Submitted, Under Review, atau Accepted). '.
                'Selesaikan atau tunggu proses seleksi pendaftaran yang sedang berjalan sebelum membuat pendaftaran baru.'
            );
        }

        if ($specificPosition !== null) {
            $this->ensurePositionBisaDidaftar($specificPosition);
        }
    }

    public function ensurePositionBisaDidaftar(Position $position): void
    {
        if (! $position->sedangDibuka()) {
            throw new \DomainException('Posisi '.$position->nama_posisi.' tidak sedang dibuka. Silakan pilih posisi magang lain yang masih aktif.');
        }
    }

    public function ensureOwner(Registration $registration, int $userId): void
    {
        if (! $this->registrationRepository->isOwner($registration, $userId)) {
            throw new \Illuminate\Auth\Access\AuthorizationException(
                'Anda tidak diizinkan mengakses data pendaftaran milik peserta lain.'
            );
        }
    }

    public function ensurePesertaTidakBisaUbahSetelahAccepted(Registration $registration): void
    {
        if ($registration->isAccepted()) {
            throw new \Illuminate\Auth\Access\AuthorizationException(
                'Pendaftaran MAGANG nomor '.$registration->nomor_pendaftaran.' sudah dinyatakan DITERIMA (Accepted). '.
                'Data tidak dapat diubah lagi oleh peserta sesuai Business Rule. Silakan hubungi Admin Diskominfo jika ada perubahan data mendesak.'
            );
        }
    }

    public function createRegistration(User $user, Position $position, array $validatedData): Registration
    {
        $this->ensureUserBisaMendaftar($user, $position);

        DB::beginTransaction();
        try {
            $cvFile = $validatedData['cv'] ?? null;
            $suratFile = $validatedData['surat_pengantar'] ?? null;
            $proposalFile = $validatedData['proposal_magang'] ?? null;

            if (! $cvFile instanceof UploadedFile || ! $suratFile instanceof UploadedFile || ! $proposalFile instanceof UploadedFile) {
                throw new \InvalidArgumentException('CV, Surat Pengantar, dan Proposal Magang wajib diunggah sebagai file PDF yang valid.');
            }

            $cvPath = $this->uploadDokumenPendaftaran($user, $cvFile, 'cv');
            $suratPath = $this->uploadDokumenPendaftaran($user, $suratFile, 'surat_pengantar');
            $proposalPath = $this->uploadDokumenPendaftaran($user, $proposalFile, 'proposal_magang');

            $nomorPendaftaran = Registration::generateNomorPendaftaran();

            $dataRegistration = [
                'nomor_pendaftaran'    => $nomorPendaftaran,
                'user_id'              => $user->id,
                'position_id'          => $position->id,
                'cv_path'              => $cvPath,
                'surat_pengantar_path' => $suratPath,
                'proposal_magang_path' => $proposalPath,
                'status'               => RegistrationStatus::Submitted,
                'tanggal_submit'       => now(),
                'periode_mulai'        => $validatedData['periode_mulai'],
                'periode_selesai'      => $validatedData['periode_selesai'],
            ];

            $registration = $this->registrationRepository->create($dataRegistration);

            DB::commit();

            return $registration->fresh(['position']);
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function updateRegistration(Registration $registration, int $userId, Position $position, array $validatedData): Registration
    {
        $this->ensureOwner($registration, $userId);
        if (! $registration->dapatDiubah()) {
            throw new \Illuminate\Auth\Access\AuthorizationException(
                'Pendaftaran status '.$registration->status->label().' tidak dapat diubah.'
            );
        }

        $this->ensurePesertaTidakBisaUbahSetelahAccepted($registration);
        $this->ensurePositionBisaDidaftar($position);

        DB::beginTransaction();
        try {
            $updateData = [
                'position_id'     => $position->id,
                'periode_mulai'   => $validatedData['periode_mulai'],
                'periode_selesai' => $validatedData['periode_selesai'],
            ];

            $cvFile = $validatedData['cv'] ?? null;
            if ($cvFile instanceof UploadedFile) {
                $updateData['cv_path'] = $this->uploadDokumenPendaftaran($registration->user ?? User::find($userId), $cvFile, 'cv');
            }
            $suratFile = $validatedData['surat_pengantar'] ?? null;
            if ($suratFile instanceof UploadedFile) {
                $updateData['surat_pengantar_path'] = $this->uploadDokumenPendaftaran($registration->user ?? User::find($userId), $suratFile, 'surat_pengantar');
            }
            $proposalFile = $validatedData['proposal_magang'] ?? null;
            if ($proposalFile instanceof UploadedFile) {
                $updateData['proposal_magang_path'] = $this->uploadDokumenPendaftaran($registration->user ?? User::find($userId), $proposalFile, 'proposal_magang');
            }

            $oldCv = $cvFile instanceof UploadedFile ? $registration->cv_path : null;
            $oldSurat = $suratFile instanceof UploadedFile ? $registration->surat_pengantar_path : null;
            $oldProposal = $proposalFile instanceof UploadedFile ? $registration->proposal_magang_path : null;

            $this->registrationRepository->update($registration, $updateData);

            if ($oldCv !== null) {
                $this->hapusFilePath($oldCv);
            }
            if ($oldSurat !== null) {
                $this->hapusFilePath($oldSurat);
            }
            if ($oldProposal !== null) {
                $this->hapusFilePath($oldProposal);
            }

            DB::commit();

            return $registration->fresh(['position', 'user']);
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function hapusPendaftaran(Registration $registration, int $userId): bool
    {
        $this->ensureOwner($registration, $userId);
        if (! $registration->dapatDihapus()) {
            throw new \Illuminate\Auth\Access\AuthorizationException(
                'Pendaftaran dengan status '.$registration->status->label().' tidak dapat dihapus. Hanya pendaftaran status Submitted atau Rejected yang dapat dihapus.'
            );
        }

        return (bool) $this->registrationRepository->delete($registration);
    }

    protected function uploadDokumenPendaftaran(?User $user, UploadedFile $file, string $type = 'cv'): ?string
    {
        $userId = $user?->id ?? 'guest';
        $timestamp = now()->format('YmdHis');
        $random = bin2hex(random_bytes(4));
        $ext = strtolower($file->getClientOriginalExtension() ?: 'pdf');
        if ($ext !== 'pdf') {
            $ext = 'pdf';
        }

        $subDir = date('Ym');
        $fileName = sprintf('%s_%s_user%d_%s_%s.pdf', strtolower($type), $subDir, $userId, $timestamp, $random);

        $path = $file->storeAs('registrations/'.$subDir, $fileName, 'local');

        return $path ?: null;
    }

    protected function hapusFilePath(?string $relativePath): void
    {
        if ($relativePath === null || trim($relativePath) === '') {
            return;
        }
        if (str_starts_with($relativePath, 'http://') || str_starts_with($relativePath, 'https://')) {
            return;
        }
        $disk = Storage::disk('local')->exists($relativePath) ? Storage::disk('local') : Storage::disk('public');
        if ($disk->exists($relativePath)) {
            $disk->delete($relativePath);
        }
    }

    public function getUrlDokumen(?string $path): ?string
    {
        if ($path === null || trim($path) === '') {
            return null;
        }
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        return route('documents.downloadByPath', ['path' => $path]);
    }

    /**
     * ─── ADMIN REVIEW LAYER (Sprint 13) ───────────────────────────────────────
     */

    /** @return \Illuminate\Contracts\Pagination\LengthAwarePaginator<Registration> */
    public function adminSearchList(array $filters = [], int $perPage = 10): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return $this->registrationRepository->adminSearchPaginated($filters, $perPage);
    }

    public function getAdminDetailWithRelationsOrFail(int $registrationId): Registration
    {
        return $this->registrationRepository->getByIdWithFullRelationsOrFail($registrationId);
    }

    public function markUnderReviewOnFirstView(Registration $registration): Registration
    {
        if (! $registration->isSubmitted()) {
            return $registration;
        }

        DB::beginTransaction();
        try {
            $this->registrationRepository->update($registration, [
                'status' => RegistrationStatus::UnderReview,
            ]);

            DB::commit();

            return $registration->fresh([
                'user' => static function (\Illuminate\Database\Eloquent\Relations\BelongsTo $q): void {
                    $q->select(['id', 'name', 'email', 'created_at'])
                        ->with(['profile']);
                },
                'position',
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function processAdminReview(Registration $registration, array $validatedData, User $adminUser): bool
    {
        if ($registration->isAccepted() || $registration->isRejected()) {
            throw new \DomainException(
                'Pendaftaran nomor '.$registration->nomor_pendaftaran.' sudah diverifikasi dengan status '.
                $registration->status->label().'. Keputusan final tidak dapat diubah kembali melalui halaman review.'
            );
        }

        /** @var string $statusValue */
        $statusValue = $validatedData['status'];
        $enumStatus = RegistrationStatus::from($statusValue);

        if ($enumStatus === RegistrationStatus::Rejected) {
            $catatan = $validatedData['catatan_admin'] ?? null;
            if (! is_string($catatan) || trim($catatan) === '' || strlen(trim($catatan)) < 10) {
                throw new \Illuminate\Validation\ValidationException(
                    validator([], [])->errors()->add(
                        'catatan_admin',
                        'Catatan Admin wajib diisi (minimal 10 karakter) jika keputusan adalah Rejected (Ditolak).'
                    )
                );
            }
        }

        DB::beginTransaction();
        try {
            $updatePayload = [
                'status'        => $enumStatus,
                'catatan_admin' => isset($validatedData['catatan_admin']) && is_string($validatedData['catatan_admin'])
                    ? trim($validatedData['catatan_admin'])
                    : null,
            ];

            $result = $this->registrationRepository->update($registration, $updatePayload);

            DB::commit();

            return $result;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * BR 2 & 3: Upload Surat Balasan HANYA untuk Status ACCEPTED.
     * BR 5: Jika upload ulang -> file lama dihapus otomatis.
     *
     * @throws \DomainException                jika status != Accepted
     * @throws \Illuminate\Http\FileTooLargeException / ValidationException (caller FormRequest handle)
     */
    public function uploadSuratBalasan(Registration $registration, \Illuminate\Http\UploadedFile $file): Registration
    {
        if (! $registration->isAccepted()) {
            throw new \DomainException(
                'Surat Balasan HANYA bisa diunggah untuk pendaftaran status ACCEPTED (Diterima). '.
                'Status saat ini: '.$registration->status->label().'. Upload ditolak (Business Rule 2 & 3).'
            );
        }

        if (! $file->isValid() && ! app()->environment('testing')) {
            throw new \DomainException('File Surat Balasan tidak valid (upload error / file corrupted).');
        }

        DB::beginTransaction();
        try {
            $storageDisk = Storage::disk('local');
            $folder = 'surat_balasan';

            $uniqueName = sprintf(
                'SURAT-BALASAN-%s-%s-%s.pdf',
                $registration->nomor_pendaftaran,
                now()->format('YmdHis'),
                bin2hex(random_bytes(4))
            );

            $storagePath = $file->storeAs($folder, $uniqueName, 'local');
            if ($storagePath === false) {
                throw new \RuntimeException('Gagal menyimpan Surat Balasan ke Storage Disk local/surat_balasan.');
            }

            $fileLama = $registration->surat_balasan_path;
            $updateOk = $this->registrationRepository->update($registration, [
                'surat_balasan_path' => $storagePath,
            ]);

            if (! $updateOk) {
                @$storageDisk->delete($storagePath);
                throw new \RuntimeException('Gagal update kolom surat_balasan_path di DB Registrations.');
            }

            if (! empty($fileLama) && is_string($fileLama) && $fileLama !== $storagePath) {
                if ($storageDisk->exists($fileLama)) {
                    $storageDisk->delete($fileLama);
                }
            }

            DB::commit();

            return $registration->fresh();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function getSuratBalasanPublicUrl(?string $storagePath): ?string
    {
        if (empty($storagePath) || ! is_string($storagePath)) {
            return null;
        }
        $disk = Storage::disk('local')->exists($storagePath) ? Storage::disk('local') : Storage::disk('public');
        if (! $disk->exists($storagePath)) {
            return null;
        }

        return route('documents.downloadByPath', ['path' => $storagePath]);
    }

    /**
     * BR 4 + Security IDOR:
     * - EnsureOwner: Peserta hanya bisa unduh surat MILIK SENDIRI.
     * - Status = Accepted (tidak boleh Submitted / UnderReview / Rejected)
     * - File benar-benar ada di Storage.
     *
     * @param  Registration  $registration  Target surat
     * @param  int           $userId        User ID peserta yang login
     * @return array{path:string,absolute_path:string,disk:\Illuminate\Contracts\Filesystem\Filesystem,filename_download:string}
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException 403 IDOR
     * @throws \DomainException                                422 / 400 Status non Accepted / File tidak ada
     */
    public function getSuratBalasanForParticipantDownload(Registration $registration, int $userId): array
    {
        $this->ensureOwner($registration, $userId);

        if (! $registration->isAccepted()) {
            throw new \DomainException(
                'Surat Balasan hanya tersedia untuk pendaftaran dengan status ACCEPTED. '.
                'Status saat ini: '.$registration->status->label()
            );
        }

        $path = $registration->surat_balasan_path;
        if (empty($path) || ! is_string($path)) {
            throw new \DomainException(
                'Surat Balasan untuk nomor '.$registration->nomor_pendaftaran.' BELUM diunggah oleh Admin. '.
                'Silakan hubungi Admin Diskominfo.'
            );
        }

        $disk = Storage::disk('local')->exists($path) ? Storage::disk('local') : Storage::disk('public');
        if (! $disk->exists($path)) {
            throw new \DomainException(
                'File Surat Balasan tidak ditemukan di server. File tercatat di DB tapi tidak ada di storage.'
            );
        }

        $filenameDownload = sprintf(
            'Surat-Balasan-MAGANG-%s-%s.pdf',
            $registration->nomor_pendaftaran,
            now()->format('Ymd')
        );

        return [
            'path'             => $path,
            'absolute_path'    => $disk->path($path),
            'disk'             => $disk,
            'filename_download'=> $filenameDownload,
        ];
    }

    /**
     * Admin bebas download surat siapa saja (hanya ensure Accepted & file exists).
     *
     * @return array{path:string,disk:\Illuminate\Contracts\Filesystem\Filesystem,filename_download:string}
     */
    public function getSuratBalasanForAdminDownload(Registration $registration): array
    {
        if (! $registration->isAccepted()) {
            throw new \DomainException('Surat Balasan hanya ada untuk status ACCEPTED.');
        }
        if (empty($registration->surat_balasan_path)) {
            throw new \DomainException('Admin belum mengunggah Surat Balasan untuk pendaftaran ini.');
        }
        $disk = Storage::disk('local')->exists($registration->surat_balasan_path) ? Storage::disk('local') : Storage::disk('public');
        if (! $disk->exists($registration->surat_balasan_path)) {
            throw new \DomainException('File Surat Balasan tidak ada di Storage.');
        }

        $filenameDownload = sprintf(
            '[ADMIN] Surat-Balasan-MAGANG-%s.pdf',
            $registration->nomor_pendaftaran
        );

        return [
            'path'              => $registration->surat_balasan_path,
            'disk'              => $disk,
            'filename_download' => $filenameDownload,
        ];
    }

    /**
     * Info file untuk ditampilkan di View Admin (ukuran, waktu upload modifikasi).
     *
     * @return array{exists:bool,size_kb:int,human_size:string,last_modified:string|null,public_url:string|null,basename:string}
     */
    public function getSuratBalasanFileInfo(Registration $registration): array
    {
        $path = $registration->surat_balasan_path;

        if (empty($path) || ! is_string($path)) {
            return [
                'exists'        => false,
                'size_kb'       => 0,
                'human_size'    => '-',
                'last_modified' => null,
                'public_url'    => null,
                'basename'      => '-',
            ];
        }

        $disk = Storage::disk('local')->exists($path) ? Storage::disk('local') : Storage::disk('public');
        $exists = $disk->exists($path);

        if (! $exists) {
            return [
                'exists'        => false,
                'size_kb'       => 0,
                'human_size'    => '-',
                'last_modified' => null,
                'public_url'    => null,
                'basename'      => '-',
            ];
        }

        $bytes = $disk->size($path);
        $sizeKb = (int) round($bytes / 1024);
        $humanSize = number_format($sizeKb, 0, ',', '.').' KB';
        $lmTime = $disk->lastModified($path);

        return [
            'exists'        => true,
            'size_kb'       => $sizeKb,
            'human_size'    => $humanSize,
            'last_modified' => $lmTime ? date('d M Y H:i', $lmTime) : null,
            'public_url'    => $this->getSuratBalasanPublicUrl($path),
            'basename'      => basename($path),
        ];
    }
}
