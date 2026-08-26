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
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ParticipantRegistrationActionUiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    private function createPesertaWithProfile(string $name = 'Peserta Test', string $email = 'peserta@example.com'): User
    {
        $user = User::factory()->create([
            'name'  => $name,
            'email' => $email,
            'role'  => UserRole::Peserta,
        ]);

        Profile::create([
            'user_id'          => $user->id,
            'nama_lengkap'     => $name,
            'participant_type' => 'mahasiswa',
            'nis_nim'          => '24050974001',
            'nim'              => '24050974001',
            'nik'              => '3523010101010001',
            'tempat_lahir'     => 'Tuban',
            'tanggal_lahir'    => '2001-05-15',
            'jenis_kelamin'    => 'Laki-laki',
            'institusi'        => 'Universitas Negeri Surabaya',
            'jurusan'          => 'Teknik Informatika',
            'tahun_angkatan'   => '2022',
            'semester'         => 6,
            'alamat'           => 'Jl. Veteran Tuban',
            'no_telepon'       => '081234567890',
        ]);

        return $user;
    }

    private function createPosition(string $name = 'Web Developer SPBE'): Position
    {
        return Position::create([
            'nama_posisi'   => $name,
            'slug'          => 'web-developer-spbe',
            'deskripsi'     => 'Deskripsi posisi magang',
            'kuota'         => 5,
            'status'        => PositionStatus::Aktif,
        ]);
    }

    public function test_registration_table_has_action_column_and_floating_panel(): void
    {
        $user = $this->createPesertaWithProfile();
        $position = $this->createPosition();

        $reg = Registration::create([
            'nomor_pendaftaran'    => 'MAGANG-2026-0001',
            'user_id'              => $user->id,
            'position_id'          => $position->id,
            'cv_path'              => 'dokumen/cv.pdf',
            'surat_pengantar_path' => 'dokumen/surat.pdf',
            'status'               => RegistrationStatus::Submitted,
            'tanggal_submit'       => now(),
            'periode_mulai'        => now()->addDays(10),
            'periode_selesai'      => now()->addDays(40),
        ]);

        $response = $this->actingAs($user)->get(route('participant.registrations.index'));

        $response->assertOk();

        // 1. Table header MUST contain "Aksi" column header for quick access
        $response->assertSee('<th class="px-4 py-3 fw-semibold text-end" style="width: 130px;">Aksi</th>', false);

        // 2. Dedicated floating action section MUST exist below table
        $response->assertSee('id="actionSectionCard"', false);
        $response->assertSee('Pendaftaran Terpilih:', false);
        $response->assertSee(route('participant.registrations.show', $reg->id), false);
        $response->assertSee(route('participant.registrations.edit', $reg->id), false);
        $response->assertSee(route('participant.registrations.destroy', $reg->id), false);
    }

    public function test_single_registration_automatically_targets_that_registration(): void
    {
        $user = $this->createPesertaWithProfile();
        $position = $this->createPosition('Network Engineer');

        $reg = Registration::create([
            'nomor_pendaftaran'    => 'MAGANG-2026-0099',
            'user_id'              => $user->id,
            'position_id'          => $position->id,
            'cv_path'              => 'dokumen/cv.pdf',
            'surat_pengantar_path' => 'dokumen/surat.pdf',
            'status'               => RegistrationStatus::Submitted,
            'tanggal_submit'       => now(),
            'periode_mulai'        => now()->addDays(10),
            'periode_selesai'      => now()->addDays(40),
        ]);

        $response = $this->actingAs($user)->get(route('participant.registrations.index'));

        $response->assertOk();
        $response->assertSee('MAGANG-2026-0099');
        $response->assertSee('Network Engineer');
        $response->assertSee(route('participant.registrations.show', $reg->id));
        // Dropdown selector is eliminated
        $response->assertDontSee('id="registrationSelectDropdown"', false);
    }

    public function test_multiple_registrations_renders_radio_selection(): void
    {
        $user = $this->createPesertaWithProfile();
        $pos1 = $this->createPosition('Web Developer SPBE');
        $pos2 = Position::create([
            'nama_posisi'   => 'Data Analyst',
            'slug'          => 'data-analyst',
            'deskripsi'     => 'Analisis Data',
            'kuota'         => 3,
            'status'        => PositionStatus::Aktif,
        ]);

        $reg1 = Registration::create([
            'nomor_pendaftaran'    => 'MAGANG-2026-0001',
            'user_id'              => $user->id,
            'position_id'          => $pos1->id,
            'cv_path'              => 'dokumen/cv1.pdf',
            'surat_pengantar_path' => 'dokumen/surat1.pdf',
            'status'               => RegistrationStatus::Rejected,
            'tanggal_submit'       => now()->subDays(30),
        ]);

        $reg2 = Registration::create([
            'nomor_pendaftaran'    => 'MAGANG-2026-0002',
            'user_id'              => $user->id,
            'position_id'          => $pos2->id,
            'cv_path'              => 'dokumen/cv2.pdf',
            'surat_pengantar_path' => 'dokumen/surat2.pdf',
            'status'               => RegistrationStatus::Submitted,
            'tanggal_submit'       => now(),
        ]);

        $response = $this->actingAs($user)->get(route('participant.registrations.index'));

        $response->assertOk();
        $response->assertDontSee('id="registrationSelectDropdown"', false);
        $response->assertSee('MAGANG-2026-0001');
        $response->assertSee('MAGANG-2026-0002');
        $response->assertSee('class="form-check-input registration-radio cursor-pointer"', false);
    }

    public function test_empty_registrations_shows_empty_state(): void
    {
        $user = $this->createPesertaWithProfile();

        $response = $this->actingAs($user)->get(route('participant.registrations.index'));

        $response->assertOk();
        $response->assertSee('Belum Ada Riwayat Pendaftaran');
        $response->assertDontSee('id="actionSectionCard"', false);
    }

    public function test_under_review_status_disables_edit_and_delete_buttons(): void
    {
        $user = $this->createPesertaWithProfile();
        $position = $this->createPosition();

        $reg = Registration::create([
            'nomor_pendaftaran'    => 'MAGANG-2026-0050',
            'user_id'              => $user->id,
            'position_id'          => $position->id,
            'cv_path'              => 'dokumen/cv.pdf',
            'surat_pengantar_path' => 'dokumen/surat.pdf',
            'status'               => RegistrationStatus::UnderReview,
            'tanggal_submit'       => now(),
        ]);

        $response = $this->actingAs($user)->get(route('participant.registrations.index'));

        $response->assertOk();
        $response->assertSee('id="actionSectionCard"', false);
        // Ubah button should have disabled class and aria-disabled
        $response->assertSee('disabled', false);
    }

    public function test_accepted_status_with_reply_letter_renders_surat_balasan_button(): void
    {
        $user = $this->createPesertaWithProfile();
        $position = $this->createPosition();

        Storage::disk('public')->put('surat_balasan/surat_007.pdf', 'PDF-CONTENT');

        $reg = Registration::create([
            'nomor_pendaftaran'    => 'MAGANG-2026-0007',
            'user_id'              => $user->id,
            'position_id'          => $position->id,
            'cv_path'              => 'dokumen/cv.pdf',
            'surat_pengantar_path' => 'dokumen/surat.pdf',
            'surat_balasan_path'   => 'surat_balasan/surat_007.pdf',
            'status'               => RegistrationStatus::Accepted,
            'tanggal_submit'       => now(),
        ]);

        $response = $this->actingAs($user)->get(route('participant.registrations.index'));

        $response->assertOk();
        $response->assertSee('Surat Balasan');
        $response->assertSee(route('participant.applications.reply-letter.download', $reg->id));
    }
}

