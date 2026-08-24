<?php

namespace Tests\Feature\Participant;

use App\Enums\PositionStatus;
use App\Enums\RegistrationStatus;
use App\Models\Position;
use App\Models\Profile;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ReapplicationTest extends TestCase
{
    use RefreshDatabase;

    protected User $participant;

    protected Position $position1;

    protected Position $position2;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');

        $this->participant = User::factory()->create(['role' => 'peserta']);

        Profile::create([
            'user_id'          => $this->participant->id,
            'participant_type' => 'mahasiswa',
            'institusi'        => 'Universitas Negeri Tuban',
            'instansi'         => 'Universitas Negeri Tuban',
            'jurusan'          => 'Teknik Informatika',
            'nis_nim'          => '20261001',
            'no_hp'            => '081234567890',
            'no_telepon'       => '081234567890',
            'tempat_lahir'     => 'Tuban',
            'tanggal_lahir'    => '2002-05-15',
            'jenis_kelamin'    => 'Laki-laki',
            'tahun_angkatan'   => '2022',
            'alamat'           => 'Jl. Veteran No. 10 Tuban',
        ]);

        $this->participant->unsetRelation('profile');

        $this->position1 = Position::create([
            'nama_posisi'   => 'Web Backend Developer',
            'slug'          => 'web-backend-developer',
            'deskripsi'     => 'Deskripsi backend',
            'kuota'         => 5,
            'status'        => PositionStatus::Aktif->value,
            'tanggal_buka'  => '2026-01-01',
            'tanggal_tutup' => '2026-12-31',
        ]);

        $this->position2 = Position::create([
            'nama_posisi'   => 'UI/UX Mobile Designer',
            'slug'          => 'ui-ux-mobile-designer',
            'deskripsi'     => 'Deskripsi designer',
            'kuota'         => 5,
            'status'        => PositionStatus::Aktif->value,
            'tanggal_buka'  => '2026-01-01',
            'tanggal_tutup' => '2026-12-31',
        ]);
    }

    /**
     * Test deactivated participant can submit new application while old record remains intact.
     */
    public function test_deactivated_participant_can_submit_new_application(): void
    {
        // 1. Initial application (Accepted & Terminated by Admin)
        $oldReg = Registration::create([
            'nomor_pendaftaran'    => 'MAGANG-2026-0001',
            'user_id'              => $this->participant->id,
            'position_id'          => $this->position1->id,
            'cv_path'              => 'cv/old.pdf',
            'surat_pengantar_path' => 'surat/old.pdf',
            'status'               => RegistrationStatus::Accepted,
            'periode_mulai'        => now()->subMonth()->toDateString(),
            'periode_selesai'      => now()->addMonths(2)->toDateString(),
            'is_terminated'        => true,
            'catatan_penonaktifan' => 'Mengundurkan diri karena skripsi',
            'terminated_at'        => now(),
        ]);

        // 2. Deactivated participant accesses registration create page
        $createResponse = $this->actingAs($this->participant)
            ->get(route('participant.registrations.create'));

        $createResponse->assertStatus(200);

        // 3. Participant submits a new application for position 2
        $storeResponse = $this->actingAs($this->participant)
            ->post(route('participant.registrations.store'), [
                'position_id'     => $this->position2->id,
                'periode_mulai'   => now()->addDays(5)->format('Y-m-d'),
                'periode_selesai' => now()->addMonths(3)->format('Y-m-d'),
                'cv'              => UploadedFile::fake()->create('cv_baru.pdf', 100, 'application/pdf'),
                'surat_pengantar' => UploadedFile::fake()->create('surat_baru.pdf', 100, 'application/pdf'),
                'proposal_magang' => UploadedFile::fake()->create('proposal_baru.pdf', 100, 'application/pdf'),
            ]);

        $storeResponse->assertRedirect();

        // 4. Verify Database Integrity: 2 distinct registration rows exist
        $this->assertDatabaseCount('registrations', 2);

        // Old application history remains untouched
        $this->assertDatabaseHas('registrations', [
            'id'                   => $oldReg->id,
            'nomor_pendaftaran'    => 'MAGANG-2026-0001',
            'position_id'          => $this->position1->id,
            'is_terminated'        => true,
            'catatan_penonaktifan' => 'Mengundurkan diri karena skripsi',
        ]);

        // New application created with new sequential registration number
        $this->assertDatabaseHas('registrations', [
            'user_id'       => $this->participant->id,
            'position_id'   => $this->position2->id,
            'status'        => RegistrationStatus::Submitted->value,
            'is_terminated' => false,
        ]);
    }

    /**
     * Test active (non-terminated) ongoing intern is blocked from submitting duplicate application.
     */
    public function test_active_ongoing_intern_cannot_submit_duplicate_application(): void
    {
        // Ongoing active application
        Registration::create([
            'nomor_pendaftaran'    => 'MAGANG-2026-0005',
            'user_id'              => $this->participant->id,
            'position_id'          => $this->position1->id,
            'cv_path'              => 'cv/active.pdf',
            'surat_pengantar_path' => 'surat/active.pdf',
            'status'               => RegistrationStatus::Accepted,
            'periode_mulai'        => now()->subDays(5)->toDateString(),
            'periode_selesai'      => now()->addMonth()->toDateString(),
            'is_terminated'        => false,
        ]);

        // Attempting to access create page redirects back to index
        $createResponse = $this->actingAs($this->participant)
            ->get(route('participant.registrations.create'));

        $createResponse->assertRedirect(route('participant.registrations.index'));

        // Database count remains 1
        $this->assertDatabaseCount('registrations', 1);
    }
}
