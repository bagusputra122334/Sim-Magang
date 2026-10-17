<?php

namespace Tests\Feature\Participant;

use App\Enums\RegistrationStatus;
use App\Enums\UserRole;
use App\Models\Division;
use App\Models\Material;
use App\Models\Position;
use App\Models\Profile;
use App\Models\Registration;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class MaterialTaskPeriodFilterTest extends TestCase
{
    use RefreshDatabase;

    private function createParticipantWithRegistration(string $startDate = '2026-10-19', string $endDate = '2026-11-19', ?int $divisionId = null): User
    {
        $user = User::factory()->create([
            'role'        => UserRole::Peserta,
            'division_id' => $divisionId,
            'created_at'  => Carbon::parse('2026-10-01 08:00:00'),
        ]);

        Profile::create([
            'user_id'          => $user->id,
            'nama_lengkap'     => $user->name,
            'participant_type' => 'mahasiswa',
            'nis_nim'          => '24050974001',
            'nim'              => '24050974001',
            'nik'              => '3523010101010001',
            'tempat_lahir'     => 'Tuban',
            'tanggal_lahir'    => '2001-05-15',
            'jenis_kelamin'    => 'Laki-laki',
            'institusi'        => 'Universitas Tuban',
            'jurusan'          => 'Teknik Informatika',
            'tahun_angkatan'   => '2022',
            'semester'         => 6,
            'alamat'           => 'Jl. Pemuda Tuban',
            'no_telepon'       => '081234567890',
        ]);

        $position = Position::create([
            'nama_posisi'  => 'Web Developer',
            'slug'         => 'web-developer-' . uniqid(),
            'kuota'        => 5,
            'status'       => 'aktif',
            'deskripsi'    => 'Deskripsi posisi magang',
            'division_id'  => $divisionId,
        ]);

        Registration::create([
            'nomor_pendaftaran'    => 'REG-2026-0001',
            'user_id'              => $user->id,
            'position_id'          => $position->id,
            'status'               => RegistrationStatus::Accepted,
            'periode_mulai'        => Carbon::parse($startDate),
            'periode_selesai'      => Carbon::parse($endDate),
            'cv_path'              => 'cv.pdf',
            'surat_pengantar_path' => 'sp.pdf',
        ]);

        return $user;
    }

    public function test_participant_only_sees_materials_created_on_or_after_internship_start_date(): void
    {
        $division = Division::create(['nama_divisi' => 'Divisi Aptika']);

        $pembimbing = User::factory()->create([
            'role'        => UserRole::Pembimbing,
            'division_id' => $division->id,
        ]);

        Material::forceCreate([
            'pembimbing_id' => $pembimbing->id,
            'title'         => 'Tugas Lama Sebelum Bergabung',
            'description'   => 'Deskripsi materi lama',
            'is_task'       => true,
            'deadline'      => Carbon::parse('2026-10-05 23:59:00'),
            'created_at'    => Carbon::parse('2026-10-02 09:00:00'),
        ]);

        Material::forceCreate([
            'pembimbing_id' => $pembimbing->id,
            'title'         => 'Tugas Baru Setelah Bergabung',
            'description'   => 'Deskripsi materi baru',
            'is_task'       => true,
            'deadline'      => Carbon::parse('2026-10-25 23:59:00'),
            'created_at'    => Carbon::parse('2026-10-20 09:00:00'),
        ]);

        $participant = $this->createParticipantWithRegistration('2026-10-19', '2026-11-19', $division->id);

        $response = $this->actingAs($participant)->get(route('participant.materials.index'));
        $response->assertOk();
        $response->assertSee('Tugas Baru Setelah Bergabung');
        $response->assertDontSee('Tugas Lama Sebelum Bergabung');
    }

    public function test_participant_dashboard_does_not_mark_old_tasks_as_overdue(): void
    {
        $division = Division::create(['nama_divisi' => 'Divisi IKP']);

        $pembimbing = User::factory()->create([
            'role'        => UserRole::Pembimbing,
            'division_id' => $division->id,
        ]);

        // Old past task that has a past deadline
        Material::forceCreate([
            'pembimbing_id' => $pembimbing->id,
            'title'         => 'Tugas Awal Bulan',
            'description'   => 'Harus selesai 5 okt',
            'is_task'       => true,
            'deadline'      => Carbon::parse('2026-10-05 23:59:00'),
            'created_at'    => Carbon::parse('2026-10-02 09:00:00'),
        ]);

        $participant = $this->createParticipantWithRegistration('2026-10-19', '2026-11-19', $division->id);

        $response = $this->actingAs($participant)->get(route('participant.dashboard'));
        $response->assertOk();
        $response->assertDontSee('Tugas Awal Bulan');
    }

    public function test_intern_lms_dashboard_filters_by_start_date(): void
    {
        $division = Division::create(['nama_divisi' => 'Divisi SP']);

        $pembimbing = User::factory()->create([
            'role'        => UserRole::Pembimbing,
            'division_id' => $division->id,
        ]);

        Task::forceCreate([
            'pembimbing_id'  => $pembimbing->id,
            'title'          => 'Task Jadul Sebelum Masuk',
            'description'    => 'Deskripsi task jadul',
            'allowed_format' => ['pdf'],
            'deadline'       => Carbon::parse('2026-10-05 23:59:00'),
            'created_at'     => Carbon::parse('2026-10-02 09:00:00'),
        ]);

        Task::forceCreate([
            'pembimbing_id'  => $pembimbing->id,
            'title'          => 'Task Baru Setelah Masuk',
            'description'    => 'Deskripsi task baru',
            'allowed_format' => ['pdf'],
            'deadline'       => Carbon::parse('2026-10-25 23:59:00'),
            'created_at'     => Carbon::parse('2026-10-20 09:00:00'),
        ]);

        $participant = $this->createParticipantWithRegistration('2026-10-19', '2026-11-19', $division->id);

        $response = $this->actingAs($participant)->get(route('intern.lms.dashboard'));
        $response->assertOk();
        $response->assertSee('Task Baru Setelah Masuk');
        $response->assertDontSee('Task Jadul Sebelum Masuk');
    }
}
