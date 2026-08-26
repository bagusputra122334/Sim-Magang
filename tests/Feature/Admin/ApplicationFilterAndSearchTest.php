<?php

namespace Tests\Feature\Admin;

use App\Enums\PositionStatus;
use App\Enums\RegistrationStatus;
use App\Models\Position;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApplicationFilterAndSearchTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'admin',
        ]);
    }

    /**
     * Test filtering applications by status (Submitted, UnderReview, Accepted, Rejected).
     */
    public function test_can_filter_applications_by_status(): void
    {
        $pos = Position::create([
            'nama_posisi'   => 'Backend Developer',
            'slug'          => 'backend-developer',
            'deskripsi'     => 'Deskripsi Backend',
            'status'        => PositionStatus::Aktif,
        ]);

        $userAccepted = User::factory()->create(['name' => 'Budi Santoso']);
        $regAccepted = Registration::create([
            'nomor_pendaftaran'    => 'MAGANG-2026-0001',
            'user_id'              => $userAccepted->id,
            'position_id'          => $pos->id,
            'status'               => RegistrationStatus::Accepted,
            'cv_path'              => 'registrations/cv/test1.pdf',
            'surat_pengantar_path' => 'registrations/surat/test1.pdf',
            'tanggal_submit'       => now(),
        ]);

        $userRejected = User::factory()->create(['name' => 'Siti Aminah']);
        $regRejected = Registration::create([
            'nomor_pendaftaran'    => 'MAGANG-2026-0002',
            'user_id'              => $userRejected->id,
            'position_id'          => $pos->id,
            'status'               => RegistrationStatus::Rejected,
            'cv_path'              => 'registrations/cv/test2.pdf',
            'surat_pengantar_path' => 'registrations/surat/test2.pdf',
            'tanggal_submit'       => now(),
        ]);

        // Filter status = accepted
        $response = $this->actingAs($this->admin)
            ->get(route('admin.applications.index', ['status' => 'accepted']));

        $response->assertStatus(200);
        $response->assertSee('Budi Santoso');
        $response->assertDontSee('Siti Aminah');
    }

    /**
     * Test filtering applications by position_id.
     */
    public function test_can_filter_applications_by_position(): void
    {
        $posA = Position::create([
            'nama_posisi'   => 'Web Developer',
            'slug'          => 'web-developer',
            'deskripsi'     => 'Deskripsi Web',
            'status'        => PositionStatus::Aktif,
        ]);

        $posB = Position::create([
            'nama_posisi'   => 'Network Engineer',
            'slug'          => 'network-engineer',
            'deskripsi'     => 'Deskripsi Network',
            'status'        => PositionStatus::Aktif,
        ]);

        $userA = User::factory()->create(['name' => 'Peserta A']);
        Registration::create([
            'nomor_pendaftaran'    => 'MAGANG-2026-0101',
            'user_id'              => $userA->id,
            'position_id'          => $posA->id,
            'status'               => RegistrationStatus::Submitted,
            'cv_path'              => 'registrations/cv/testA.pdf',
            'surat_pengantar_path' => 'registrations/surat/testA.pdf',
            'tanggal_submit'       => now(),
        ]);

        $userB = User::factory()->create(['name' => 'Peserta B']);
        Registration::create([
            'nomor_pendaftaran'    => 'MAGANG-2026-0102',
            'user_id'              => $userB->id,
            'position_id'          => $posB->id,
            'status'               => RegistrationStatus::Submitted,
            'cv_path'              => 'registrations/cv/testB.pdf',
            'surat_pengantar_path' => 'registrations/surat/testB.pdf',
            'tanggal_submit'       => now(),
        ]);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.applications.index', ['position_id' => $posA->id]));

        $response->assertStatus(200);
        $response->assertSee('Peserta A');
        $response->assertDontSee('Peserta B');
    }

    /**
     * Test position dropdown labels correctly display active without (Nonaktif) tag.
     */
    public function test_position_dropdown_labels_correctly_display_active_status(): void
    {
        $posAktif = Position::create([
            'nama_posisi'   => 'Sistem Informasi SPBE',
            'slug'          => 'sistem-informasi-spbe',
            'deskripsi'     => 'Deskripsi SPBE',
            'status'        => PositionStatus::Aktif,
        ]);

        $posNonaktif = Position::create([
            'nama_posisi'   => 'Posisi Arsip Lama',
            'slug'          => 'posisi-arsip-lama',
            'deskripsi'     => 'Deskripsi Arsip',
            'status'        => PositionStatus::TidakAktif,
        ]);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.applications.index'));

        $response->assertStatus(200);
        $response->assertSee('Sistem Informasi SPBE');
        $response->assertSee('Posisi Arsip Lama (Nonaktif)');
        $response->assertDontSee('Sistem Informasi SPBE (Nonaktif)');
    }
}
