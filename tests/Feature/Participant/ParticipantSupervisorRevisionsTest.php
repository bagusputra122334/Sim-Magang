<?php

namespace Tests\Feature\Participant;

use App\Enums\PositionStatus;
use App\Enums\RegistrationStatus;
use App\Enums\UserRole;
use App\Models\Position;
use App\Models\Profile;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ParticipantSupervisorRevisionsTest extends TestCase
{
    use RefreshDatabase;

    private User $participant;
    private Position $position;

    protected function setUp(): void
    {
        parent::setUp();

        $this->participant = User::factory()->create([
            'name' => 'Peserta Supervisor Test',
            'email' => 'peserta.supervisor@example.com',
            'role' => UserRole::Peserta,
        ]);

        Profile::create([
            'user_id'          => $this->participant->id,
            'nama_lengkap'     => 'Peserta Supervisor Test',
            'participant_type' => 'mahasiswa',
            'nis_nim'          => '123456789',
            'nim'              => '123456789',
            'nik'              => '3523010101010009',
            'tempat_lahir'     => 'Tuban',
            'tanggal_lahir'    => '2002-01-01',
            'jenis_kelamin'    => 'Laki-laki',
            'institusi'        => 'Universitas Tuban',
            'jurusan'          => 'Teknik Informatika',
            'tahun_angkatan'   => '2022',
            'semester'         => 6,
            'alamat'           => 'Jl. Panglima Sudirman Tuban',
            'no_telepon'       => '081234567899',
        ]);

        $this->position = Position::create([
            'nama_posisi'   => 'Programmer SPBE',
            'slug'          => 'programmer-spbe',
            'deskripsi'     => 'Deskripsi posisi test',
            'kuota'         => 5,
            'status'        => PositionStatus::Aktif,
        ]);
    }

    /**
     * Test participant sidebar menu item is renamed from 'Pendaftaran Magang' to 'Riwayat Magang'.
     */
    public function test_sidebar_contains_riwayat_magang_label(): void
    {
        $response = $this->actingAs($this->participant)
            ->get(route('participant.registrations.index'));

        $response->assertOk();
        $response->assertSee('Riwayat Magang');
        $response->assertDontSee('>Pendaftaran Magang<');
    }

    /**
     * Test history table status badge displays 'Dinonaktifkan' for terminated active internship.
     */
    public function test_history_table_displays_dinonaktifkan_badge_when_terminated(): void
    {
        $reg = Registration::create([
            'nomor_pendaftaran'    => 'MAGANG-2026-9901',
            'user_id'              => $this->participant->id,
            'position_id'          => $this->position->id,
            'cv_path'              => 'dokumen/cv.pdf',
            'surat_pengantar_path' => 'dokumen/surat.pdf',
            'status'               => RegistrationStatus::Accepted,
            'tanggal_submit'       => now()->subDays(15),
            'periode_mulai'        => now()->subDays(10),
            'periode_selesai'      => now()->addDays(20),
            'is_terminated'        => true,
            'catatan_penonaktifan' => 'Magang dihentikan lebih awal oleh admin',
            'terminated_at'        => now()->subDays(2),
        ]);

        $response = $this->actingAs($this->participant)
            ->get(route('participant.registrations.index'));

        $response->assertOk();
        $response->assertSee('Dinonaktifkan');
        $response->assertSee('bg-danger-subtle text-danger-emphasis border-danger-subtle', false);
        $response->assertSee('data-is-terminated="1"', false);
    }

    /**
     * Test history table status badge displays 'Diterima' for active non-terminated internship.
     */
    public function test_history_table_displays_diterima_badge_when_not_terminated(): void
    {
        Registration::create([
            'nomor_pendaftaran'    => 'MAGANG-2026-9902',
            'user_id'              => $this->participant->id,
            'position_id'          => $this->position->id,
            'cv_path'              => 'dokumen/cv.pdf',
            'surat_pengantar_path' => 'dokumen/surat.pdf',
            'status'               => RegistrationStatus::Accepted,
            'tanggal_submit'       => now()->subDays(5),
            'periode_mulai'        => now()->subDays(2),
            'periode_selesai'      => now()->addDays(30),
            'is_terminated'        => false,
        ]);

        $response = $this->actingAs($this->participant)
            ->get(route('participant.registrations.index'));

        $response->assertOk();
        $response->assertSee('Diterima');
        $response->assertSee('bg-success-subtle text-success-emphasis border-success-subtle', false);
        $response->assertSee('data-is-terminated="0"', false);
    }
}
