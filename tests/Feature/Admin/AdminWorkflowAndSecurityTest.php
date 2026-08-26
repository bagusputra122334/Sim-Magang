<?php

namespace Tests\Feature\Admin;

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

class AdminWorkflowAndSecurityTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    private function createAdmin(): User
    {
        return User::factory()->create([
            'name'  => 'Admin Diskominfo',
            'email' => 'admin@tubankab.go.id',
            'role'  => UserRole::Admin,
        ]);
    }

    private function createPeserta(string $name = 'Peserta Test', string $email = 'peserta@test.com'): User
    {
        $user = User::factory()->create([
            'name'  => $name,
            'email' => $email,
            'role'  => UserRole::Peserta,
        ]);

        Profile::create([
            'user_id'          => $user->id,
            'nama_lengkap'     => $name,
            'participant_type' => ParticipantType::University,
            'nis_nim'          => '2405' . rand(100000, 999999),
            'nim'              => '2405' . rand(100000, 999999),
            'nik'              => '35230101' . rand(10000000, 99999999),
            'tempat_lahir'     => 'Tuban',
            'tanggal_lahir'    => '2001-05-15',
            'jenis_kelamin'    => JenisKelamin::LakiLaki,
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
            'slug'          => 'web-developer-spbe-' . rand(1000, 9999),
            'deskripsi'     => 'Deskripsi posisi magang',
            'kuota'         => 5,
            'status'        => PositionStatus::Aktif,
        ]);
    }

    /**
     * Test 1: Admin Dashboard Access & Statistics
     */
    public function test_admin_dashboard_renders_and_shows_statistics(): void
    {
        $admin = $this->createAdmin();
        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertOk();
        $response->assertSee('Admin Diskominfo');
        $response->assertSee('Dashboard Admin');
    }

    /**
     * Test 2: Admin Application Review Flow (Submitted -> UnderReview on view -> Accepted)
     */
    public function test_admin_application_review_flow(): void
    {
        $admin = $this->createAdmin();
        $peserta = $this->createPeserta();
        $position = $this->createPosition();

        $reg = Registration::create([
            'nomor_pendaftaran'    => 'MAGANG-2026-0001',
            'user_id'              => $peserta->id,
            'position_id'          => $position->id,
            'cv_path'              => 'dokumen/cv.pdf',
            'surat_pengantar_path' => 'dokumen/surat.pdf',
            'proposal_magang_path' => 'dokumen/proposal.pdf',
            'status'               => RegistrationStatus::Submitted,
            'tanggal_submit'       => now(),
            'periode_mulai'        => now()->addDays(10),
            'periode_selesai'      => now()->addDays(40),
        ]);

        // Show application details as Admin
        $response = $this->actingAs($admin)->get(route('admin.applications.show', $reg->id));
        $response->assertOk();

        // Check if viewing marked it UnderReview
        $reg->refresh();
        $this->assertEquals(RegistrationStatus::UnderReview, $reg->status);

        // Review form view
        $reviewView = $this->actingAs($admin)->get(route('admin.applications.review', $reg->id));
        $reviewView->assertOk();

        // Accept application
        $updateResp = $this->actingAs($admin)->put(route('admin.applications.update-review', $reg->id), [
            'status'        => 'accepted',
            'catatan_admin' => 'Selamat, Anda diterima magang di Diskominfo SP.',
        ]);

        $updateResp->assertRedirect(route('admin.applications.show', $reg->id));
        $reg->refresh();
        $this->assertEquals(RegistrationStatus::Accepted, $reg->status);
    }

    /**
     * Test 3: Admin Reply Letter Upload & Replacement
     */
    public function test_admin_reply_letter_upload_and_replacement(): void
    {
        $admin = $this->createAdmin();
        $peserta = $this->createPeserta();
        $position = $this->createPosition();

        $reg = Registration::create([
            'nomor_pendaftaran'    => 'MAGANG-2026-0002',
            'user_id'              => $peserta->id,
            'position_id'          => $position->id,
            'cv_path'              => 'dokumen/cv.pdf',
            'surat_pengantar_path' => 'dokumen/surat.pdf',
            'proposal_magang_path' => 'dokumen/proposal.pdf',
            'status'               => RegistrationStatus::Accepted,
            'tanggal_submit'       => now(),
            'periode_mulai'        => now()->addDays(10),
            'periode_selesai'      => now()->addDays(40),
        ]);

        $pdfFile = UploadedFile::fake()->create('surat_balasan_01.pdf', 500, 'application/pdf');

        $uploadResp = $this->actingAs($admin)->post(route('admin.applications.reply-letter.store', $reg->id), [
            'surat_balasan' => $pdfFile,
        ]);

        $uploadResp->assertRedirect(route('admin.applications.reply-letter', $reg->id));
        $reg->refresh();
        $this->assertNotEmpty($reg->surat_balasan_path);
        Storage::disk('public')->assertExists($reg->surat_balasan_path);

        // Download as admin
        $dlResp = $this->actingAs($admin)->get(route('admin.applications.reply-letter.download', $reg->id));
        $dlResp->assertOk();

        // Download as owner participant
        $pDlResp = $this->actingAs($peserta)->get(route('participant.applications.reply-letter.download', $reg->id));
        $pDlResp->assertOk();
    }

    /**
     * Test 4: Security & Authorization Isolations
     */
    public function test_cross_role_and_cross_user_security(): void
    {
        $admin = $this->createAdmin();
        $pesertaA = $this->createPeserta('Peserta A', 'pesertaA@test.com');
        $pesertaB = $this->createPeserta('Peserta B', 'pesertaB@test.com');
        $position = $this->createPosition();

        $regA = Registration::create([
            'nomor_pendaftaran'    => 'MAGANG-2026-0003',
            'user_id'              => $pesertaA->id,
            'position_id'          => $position->id,
            'cv_path'              => 'dokumen/cv_a.pdf',
            'surat_pengantar_path' => 'dokumen/surat_a.pdf',
            'proposal_magang_path' => 'dokumen/proposal_a.pdf',
            'status'               => RegistrationStatus::Submitted,
            'tanggal_submit'       => now(),
        ]);

        // 1. Guest cannot access admin or participant dashboard
        $this->get(route('admin.dashboard'))->assertRedirect(route('login'));
        $this->get(route('participant.dashboard'))->assertRedirect(route('login'));

        // 2. Participant cannot access Admin routes (403)
        $this->actingAs($pesertaA)->get(route('admin.dashboard'))->assertForbidden();
        $this->actingAs($pesertaA)->get(route('admin.applications.index'))->assertForbidden();
        $this->actingAs($pesertaA)->get(route('admin.applications.export'))->assertForbidden();
        $this->actingAs($pesertaA)->get(route('admin.positions.index'))->assertForbidden();

        // 3. Admin cannot access Participant routes (403)
        $this->actingAs($admin)->get(route('participant.dashboard'))->assertForbidden();
        $this->actingAs($admin)->get(route('participant.registrations.index'))->assertForbidden();

        // 4. Participant B cannot view, edit, or delete Participant A's registration
        $this->actingAs($pesertaB)->get(route('participant.registrations.show', $regA->id))
            ->assertForbidden();

        $this->actingAs($pesertaB)->get(route('participant.registrations.edit', $regA->id))
            ->assertRedirect(route('participant.registrations.index'));

        $this->actingAs($pesertaB)->delete(route('participant.registrations.destroy', $regA->id))
            ->assertRedirect();
    }

    /**
     * Test 5: Position Management & Delete Restriction
     */
    public function test_position_crud_and_delete_protection(): void
    {
        $admin = $this->createAdmin();

        // Create position
        $createResp = $this->actingAs($admin)->post(route('admin.positions.store'), [
            'nama_posisi'   => 'Cyber Security Specialist',
            'slug'          => 'cyber-security-specialist',
            'deskripsi'     => 'Keamanan Informasi Pemerintah',
            'kualifikasi'   => 'Memahami ISO 27001 dan Vulnerability Assessment',
            'kuota'         => 2,
            'status'        => PositionStatus::Aktif->value,
        ]);

        $createResp->assertRedirect(route('admin.positions.index'));
        $pos = Position::where('slug', 'cyber-security-specialist')->first();
        $this->assertNotNull($pos);

        // Toggle status
        $toggleResp = $this->actingAs($admin)->get(route('admin.positions.toggle-status', $pos->id));
        $toggleResp->assertRedirect(route('admin.positions.index'));
        $pos->refresh();
        $this->assertEquals(PositionStatus::TidakAktif, $pos->status);

        // Delete unused position
        $delResp = $this->actingAs($admin)->delete(route('admin.positions.destroy', $pos->id));
        $delResp->assertRedirect(route('admin.positions.index'));
        $this->assertSoftDeleted('positions', ['id' => $pos->id]);
    }

    public function test_admin_can_update_position_without_date_fields(): void
    {
        $admin = $this->createAdmin();
        $pos = Position::create([
            'nama_posisi'   => 'Data Analyst Test',
            'slug'          => 'data-analyst-test',
            'deskripsi'     => 'Deskripsi Data Analyst Test',
            'kualifikasi'   => 'Kualifikasi Test',
            'kuota'         => 5,
            'status'        => PositionStatus::Aktif,
        ]);

        $originalUpdatedAt = $pos->updated_at;

        // Sleep 1 second to ensure updated_at changes
        sleep(1);

        $updateResp = $this->actingAs($admin)->put(route('admin.positions.update', $pos->id), [
            'nama_posisi' => 'Data Analyst Senior',
            'slug'        => 'data-analyst-senior',
            'deskripsi'   => 'Deskripsi baru data analyst senior',
            'kualifikasi' => 'Kualifikasi baru',
            'status'      => PositionStatus::Aktif->value,
        ]);

        $updateResp->assertRedirect(route('admin.positions.index'));
        $pos->refresh();

        $this->assertEquals('Data Analyst Senior', $pos->nama_posisi);
        $this->assertEquals('data-analyst-senior', $pos->slug);
        $this->assertNotEquals($originalUpdatedAt->toDateTimeString(), $pos->updated_at->toDateTimeString());
    }
}
