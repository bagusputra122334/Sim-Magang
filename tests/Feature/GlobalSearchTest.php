<?php

namespace Tests\Feature;

use App\Enums\PositionStatus;
use App\Enums\RegistrationStatus;
use App\Enums\UserRole;
use App\Models\Position;
use App\Models\Profile;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GlobalSearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_user_cannot_access_global_search(): void
    {
        $response = $this->get('/global-search?q=test');
        $response->assertRedirect(route('login'));
    }

    public function test_empty_query_returns_empty_results_safely(): void
    {
        $admin = User::factory()->create(['role' => UserRole::Admin]);

        $response = $this->actingAs($admin)->getJson('/global-search?q=');
        $response->assertStatus(200)
            ->assertJson([
                'count'   => 0,
                'results' => [],
            ]);
    }

    public function test_admin_can_search_by_applicant_name(): void
    {
        $admin = User::factory()->create(['role' => UserRole::Admin]);

        $participant = User::factory()->create(['name' => 'Bagus Dwi Junior', 'role' => UserRole::Peserta]);
        $position = Position::create([
            'nama_posisi'   => 'Web Developer SPBE',
            'slug'          => 'web-developer-spbe',
            'deskripsi'     => 'Pengembangan aplikasi pemerintah',
            'kuota'         => 3,
            'status'        => PositionStatus::Aktif,
        ]);

        $registration = Registration::create([
            'nomor_pendaftaran'    => 'MAGANG-2026-0007',
            'user_id'              => $participant->id,
            'position_id'          => $position->id,
            'status'               => RegistrationStatus::Accepted,
            'cv_path'              => 'cv/test.pdf',
            'surat_pengantar_path' => 'surat/test.pdf',
            'tanggal_submit'       => now(),
        ]);

        $response = $this->actingAs($admin)->getJson('/global-search?q=Bagus');

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'type'         => 'registration',
            'code'         => 'MAGANG-2026-0007',
            'status'       => 'accepted',
            'status_label' => 'Accepted',
        ]);
    }

    public function test_admin_can_search_by_registration_code(): void
    {
        $admin = User::factory()->create(['role' => UserRole::Admin]);
        $participant = User::factory()->create(['role' => UserRole::Peserta]);
        $position = Position::create([
            'nama_posisi'   => 'Network Engineer',
            'slug'          => 'network-engineer',
            'deskripsi'     => 'Jaringan Diskominfo',
            'kuota'         => 2,
            'status'        => PositionStatus::Aktif,
        ]);

        Registration::create([
            'nomor_pendaftaran'    => 'MAGANG-2026-0099',
            'user_id'              => $participant->id,
            'position_id'          => $position->id,
            'status'               => RegistrationStatus::Submitted,
            'cv_path'              => 'cv/test.pdf',
            'surat_pengantar_path' => 'surat/test.pdf',
            'tanggal_submit'       => now(),
        ]);

        $response = $this->actingAs($admin)->getJson('/global-search?q=0099');

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'code' => 'MAGANG-2026-0099',
        ]);
    }

    public function test_admin_can_search_by_position_and_status_alias(): void
    {
        $admin = User::factory()->create(['role' => UserRole::Admin]);
        $participant = User::factory()->create(['role' => UserRole::Peserta]);
        $position = Position::create([
            'nama_posisi'   => 'Persandian & Keamanan Informasi',
            'slug'          => 'persandian-keamanan',
            'deskripsi'     => 'Bidang Persandian',
            'kuota'         => 2,
            'status'        => PositionStatus::Aktif,
        ]);

        Registration::create([
            'nomor_pendaftaran'    => 'MAGANG-2026-0012',
            'user_id'              => $participant->id,
            'position_id'          => $position->id,
            'status'               => RegistrationStatus::UnderReview,
            'cv_path'              => 'cv/test.pdf',
            'surat_pengantar_path' => 'surat/test.pdf',
            'tanggal_submit'       => now(),
        ]);

        // Search by position keyword "Persandian"
        $posSearch = $this->actingAs($admin)->getJson('/global-search?q=Persandian');
        $posSearch->assertStatus(200);
        $posSearch->assertJsonFragment(['code' => 'MAGANG-2026-0012']);

        // Search by Indonesian status alias "diverifikasi"
        $statusSearch = $this->actingAs($admin)->getJson('/global-search?q=diverifikasi');
        $statusSearch->assertStatus(200);
        $statusSearch->assertJsonFragment(['code' => 'MAGANG-2026-0012']);
    }

    public function test_participant_cannot_see_other_participants_registrations(): void
    {
        $participantA = User::factory()->create(['name' => 'Peserta A', 'role' => UserRole::Peserta]);
        $participantB = User::factory()->create(['name' => 'Peserta B Rahasia', 'role' => UserRole::Peserta]);

        $position = Position::create([
            'nama_posisi'   => 'Desain Grafis SPBE',
            'slug'          => 'desain-grafis-spbe',
            'deskripsi'     => 'Desain Publikasi',
            'kuota'         => 2,
            'status'        => PositionStatus::Aktif,
        ]);

        // Registration belonging to participant B
        Registration::create([
            'nomor_pendaftaran'    => 'MAGANG-2026-7777',
            'user_id'              => $participantB->id,
            'position_id'          => $position->id,
            'status'               => RegistrationStatus::Accepted,
            'cv_path'              => 'cv/test.pdf',
            'surat_pengantar_path' => 'surat/test.pdf',
            'tanggal_submit'       => now(),
        ]);

        // Participant A searches for Participant B's registration code
        $response = $this->actingAs($participantA)->getJson('/global-search?q=7777');
        $response->assertStatus(200);
        $response->assertJsonMissing(['code' => 'MAGANG-2026-7777']);
    }
}
