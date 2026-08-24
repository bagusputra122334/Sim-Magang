<?php

namespace Tests\Feature\Participant;

use App\Enums\JenisKelamin;
use App\Enums\ParticipantType;
use App\Enums\PositionStatus;
use App\Enums\RegistrationStatus;
use App\Enums\UserRole;
use App\Models\Position;
use App\Models\Profile;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ParticipantRegistrationNoQuotaTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    private function createParticipantProfile(User $user, string $nim): Profile
    {
        return Profile::create([
            'user_id'          => $user->id,
            'nama_lengkap'     => $user->name,
            'participant_type' => 'mahasiswa',
            'nis_nim'          => $nim,
            'nim'              => $nim,
            'nik'              => '352301010101' . rand(1000, 9999),
            'tempat_lahir'     => 'Tuban',
            'tanggal_lahir'    => '2001-05-15',
            'jenis_kelamin'    => 'Laki-laki',
            'institusi'        => 'Universitas Brawijaya',
            'jurusan'          => 'Teknik Informatika',
            'tahun_angkatan'   => '2022',
            'semester'         => 6,
            'alamat'           => 'Jl. Veteran Tuban',
            'no_telepon'       => '081234567890',
        ]);
    }

    public function test_multiple_participants_can_register_for_same_position_without_quota_limit(): void
    {
        // Create position with legacy kuota = 1
        $position = Position::create([
            'nama_posisi'   => 'Web Developer SPBE',
            'slug'          => 'web-developer-spbe',
            'deskripsi'     => 'Pengembangan web dan aplikasi pemerintah daerah',
            'kuota'         => 1,
            'status'        => PositionStatus::Aktif,
            'tanggal_buka'  => now()->subDays(5),
            'tanggal_tutup' => now()->addDays(20),
        ]);

        // Create 3 already accepted registrations for this position (exceeding kuota of 1)
        for ($i = 1; $i <= 3; $i++) {
            $userExisting = User::factory()->create(['role' => UserRole::Peserta]);
            $this->createParticipantProfile($userExisting, 'NIM_EXIST_'.$i);

            Registration::create([
                'user_id'            => $userExisting->id,
                'position_id'        => $position->id,
                'nomor_pendaftaran'  => 'REG-EXIST-'.$i,
                'status'             => RegistrationStatus::Accepted,
                'periode_mulai'      => now()->addDays(5)->toDateString(),
                'periode_selesai'    => now()->addDays(35)->toDateString(),
                'cv_path'            => 'cvs/dummy.pdf',
                'surat_pengantar_path' => 'surat/dummy.pdf',
                'tanggal_submit'     => now(),
            ]);
        }

        // Now a new participant attempts to register for the same position
        $newParticipant = User::factory()->create(['role' => UserRole::Peserta]);
        $this->createParticipantProfile($newParticipant, 'NIM_NEW_12345');

        $cv = UploadedFile::fake()->create('cv_budi.pdf', 500, 'application/pdf');
        $surat = UploadedFile::fake()->create('surat_rekom.pdf', 600, 'application/pdf');
        $proposal = UploadedFile::fake()->create('proposal.pdf', 800, 'application/pdf');

        $response = $this->actingAs($newParticipant)->post(route('participant.registrations.store'), [
            'position_id'     => $position->id,
            'periode_mulai'   => now()->addDays(10)->toDateString(),
            'periode_selesai' => now()->addDays(40)->toDateString(),
            'cv'              => $cv,
            'surat_pengantar' => $surat,
            'proposal_magang' => $proposal,
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect();

        $this->assertDatabaseHas('registrations', [
            'user_id'     => $newParticipant->id,
            'position_id' => $position->id,
            'status'      => RegistrationStatus::Submitted->value,
        ]);
    }

    public function test_registration_form_view_does_not_contain_quota_words(): void
    {
        $position = Position::create([
            'nama_posisi'   => 'Desain Grafis & Konten',
            'slug'          => 'desain-grafis',
            'deskripsi'     => 'Desain publikasi pemerintah daerah',
            'kuota'         => 2,
            'status'        => PositionStatus::Aktif,
            'tanggal_buka'  => now()->subDays(2),
            'tanggal_tutup' => now()->addDays(15),
        ]);

        $participant = User::factory()->create(['role' => UserRole::Peserta]);
        $this->createParticipantProfile($participant, 'NIM_VIEW_999');

        $response = $this->actingAs($participant)->get(route('participant.registrations.create'));

        $response->assertStatus(200);
        $response->assertSee('Desain Grafis & Konten');
        $response->assertSee('Terbuka');
        $response->assertDontSee('Sisa:');
        $response->assertDontSee('Kuota:');
        $response->assertDontSee('kuota penuh');
    }
}
