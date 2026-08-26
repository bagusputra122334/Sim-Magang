<?php

namespace Tests\Feature\Admin;

use App\Enums\PositionStatus;
use App\Enums\RegistrationStatus;
use App\Models\Position;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ActiveInternModuleTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected User $participant;

    protected Position $position;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->participant = User::factory()->create(['role' => 'peserta']);

        $this->position = Position::create([
            'nama_posisi'   => 'Sistem Informasi SPBE',
            'slug'          => 'sistem-informasi-spbe',
            'deskripsi'     => 'Pengembangan aplikasi Pemkab',
            'status'        => PositionStatus::Aktif,
            'mentor_name'   => 'Drs. Eko Prasetyo, M.Kom',
            'mentor_nip'    => '19820315 200801 1 004',
        ]);
    }

    /**
     * Test admin can access active interns list.
     */
    public function test_admin_can_access_active_interns_index(): void
    {
        Registration::create([
            'nomor_pendaftaran'    => 'MAGANG-2026-9001',
            'user_id'              => $this->participant->id,
            'position_id'          => $this->position->id,
            'cv_path'              => 'cv/dummy.pdf',
            'surat_pengantar_path' => 'surat/dummy.pdf',
            'status'               => RegistrationStatus::Accepted,
            'periode_mulai'        => now()->subDays(5)->toDateString(),
            'periode_selesai'      => now()->addMonth()->toDateString(),
        ]);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.active-interns.index'));

        $response->assertStatus(200);
        $response->assertSee('Monitoring Magang Aktif');
        $response->assertSee($this->participant->name);
    }

    /**
     * Test admin can search active interns by institution column without SQL error.
     */
    public function test_admin_can_search_active_interns_by_institution_and_keyword(): void
    {
        \App\Models\Profile::create([
            'user_id'          => $this->participant->id,
            'participant_type' => 'mahasiswa',
            'institusi'        => 'Universitas PGRI Ronggolawe Tuban',
            'jurusan'          => 'Teknik Informatika',
            'nis_nim'          => '2026999',
            'no_telepon'       => '08123456789',
            'tempat_lahir'     => 'Tuban',
            'tanggal_lahir'    => '2002-01-01',
            'jenis_kelamin'    => 'Laki-laki',
            'tahun_angkatan'   => '2022',
            'alamat'           => 'Tuban',
        ]);

        Registration::create([
            'nomor_pendaftaran'    => 'MAGANG-2026-9005',
            'user_id'              => $this->participant->id,
            'position_id'          => $this->position->id,
            'cv_path'              => 'cv/dummy.pdf',
            'surat_pengantar_path' => 'surat/dummy.pdf',
            'status'               => RegistrationStatus::Accepted,
            'periode_mulai'        => now()->subDays(5)->toDateString(),
            'periode_selesai'      => now()->addMonth()->toDateString(),
        ]);

        $searchResponse = $this->actingAs($this->admin)
            ->get(route('admin.active-interns.index', ['search' => 'Ronggolawe']));

        $searchResponse->assertStatus(200);
        $searchResponse->assertSee($this->participant->name);
        $searchResponse->assertSee('Ronggolawe');
    }

    /**
     * Test active intern show detail view.
     */
    public function test_admin_can_view_active_intern_detail(): void
    {
        $reg = Registration::create([
            'nomor_pendaftaran'    => 'MAGANG-2026-9002',
            'user_id'              => $this->participant->id,
            'position_id'          => $this->position->id,
            'cv_path'              => 'cv/dummy.pdf',
            'surat_pengantar_path' => 'surat/dummy.pdf',
            'status'               => RegistrationStatus::Accepted,
            'periode_mulai'        => now()->subDays(5)->toDateString(),
            'periode_selesai'      => now()->addMonth()->toDateString(),
        ]);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.active-interns.show', $reg->id));

        $response->assertStatus(200);
        $response->assertSee('Detail Peserta Magang');
        $response->assertSee($this->participant->name);
        $response->assertSee('Drs. Eko Prasetyo, M.Kom');
    }

    /**
     * Test admin can deactivate and reactivate an active intern.
     */
    public function test_admin_can_toggle_deactivate_intern(): void
    {
        $reg = Registration::create([
            'nomor_pendaftaran'    => 'MAGANG-2026-9003',
            'user_id'              => $this->participant->id,
            'position_id'          => $this->position->id,
            'cv_path'              => 'cv/dummy.pdf',
            'surat_pengantar_path' => 'surat/dummy.pdf',
            'status'               => RegistrationStatus::Accepted,
            'periode_mulai'        => now()->subDays(5)->toDateString(),
            'periode_selesai'      => now()->addMonth()->toDateString(),
            'is_terminated'        => false,
        ]);

        // Deactivate
        $deactivateResponse = $this->actingAs($this->admin)
            ->patch(route('admin.active-interns.toggle-status', $reg->id), [
                'catatan_penonaktifan' => 'Mengundurkan diri karena skripsi',
            ]);

        $deactivateResponse->assertRedirect();
        $this->assertDatabaseHas('registrations', [
            'id'                   => $reg->id,
            'is_terminated'        => true,
            'catatan_penonaktifan' => 'Mengundurkan diri karena skripsi',
        ]);

        // Reactivate
        $reactivateResponse = $this->actingAs($this->admin)
            ->patch(route('admin.active-interns.toggle-status', $reg->id));

        $reactivateResponse->assertRedirect();
        $this->assertDatabaseHas('registrations', [
            'id'            => $reg->id,
            'is_terminated' => false,
        ]);
    }
}
