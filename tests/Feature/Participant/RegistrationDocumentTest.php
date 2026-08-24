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
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class RegistrationDocumentTest extends TestCase
{
    use RefreshDatabase;

    protected User $participant;
    protected User $admin;
    protected Position $position;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');

        $this->participant = User::factory()->create([
            'role' => UserRole::Peserta,
        ]);

        Profile::create([
            'user_id'          => $this->participant->id,
            'nama_lengkap'     => 'Peserta Test',
            'participant_type' => 'mahasiswa',
            'nis_nim'          => '12345678',
            'nim'              => '12345678',
            'nik'              => '3523010101010001',
            'tempat_lahir'     => 'Tuban',
            'tanggal_lahir'    => '2000-01-01',
            'jenis_kelamin'    => 'Laki-laki',
            'institusi'        => 'Universitas Negeri Surabaya',
            'jurusan'          => 'Teknik Informatika',
            'tahun_angkatan'   => '2022',
            'alamat'           => 'Surabaya',
            'no_telepon'       => '08123456789',
        ]);

        $this->admin = User::factory()->create([
            'role' => UserRole::Admin,
        ]);

        $this->position = Position::create([
            'nama_posisi'   => 'Web Developer Intern',
            'slug'          => 'web-developer-intern',
            'deskripsi'     => 'Deskripsi posisi magang web developer.',
            'persyaratan'   => 'PHP, Laravel, MySQL',
            'kuota'         => 5,
            'status'        => PositionStatus::Aktif,
            'tanggal_buka'  => now()->subDays(5)->toDateString(),
            'tanggal_tutup' => now()->addDays(30)->toDateString(),
        ]);
    }

    public function test_participant_can_submit_registration_with_cv_surat_pengantar_and_proposal_magang(): void
    {
        $cv = UploadedFile::fake()->create('my_cv.pdf', 500, 'application/pdf');
        $surat = UploadedFile::fake()->create('surat_pengantar.pdf', 1000, 'application/pdf');
        $proposal = UploadedFile::fake()->create('proposal_magang.pdf', 2000, 'application/pdf');

        $response = $this->actingAs($this->participant)->post(route('participant.registrations.store'), [
            'position_id'     => $this->position->id,
            'periode_mulai'   => now()->addDays(2)->toDateString(),
            'periode_selesai' => now()->addMonths(3)->toDateString(),
            'cv'              => $cv,
            'surat_pengantar' => $surat,
            'proposal_magang' => $proposal,
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect();

        $this->assertDatabaseHas('registrations', [
            'user_id'     => $this->participant->id,
            'position_id' => $this->position->id,
            'status'      => RegistrationStatus::Submitted->value,
        ]);

        $registration = Registration::where('user_id', $this->participant->id)->first();
        $this->assertNotNull($registration);
        $this->assertNotNull($registration->cv_path);
        $this->assertNotNull($registration->surat_pengantar_path);
        $this->assertNotNull($registration->proposal_magang_path);

        Storage::disk('public')->assertExists($registration->cv_path);
        Storage::disk('public')->assertExists($registration->surat_pengantar_path);
        Storage::disk('public')->assertExists($registration->proposal_magang_path);
    }

    public function test_proposal_magang_is_required_for_new_registration(): void
    {
        $cv = UploadedFile::fake()->create('my_cv.pdf', 500, 'application/pdf');
        $surat = UploadedFile::fake()->create('surat_pengantar.pdf', 1000, 'application/pdf');

        $response = $this->actingAs($this->participant)->post(route('participant.registrations.store'), [
            'position_id'     => $this->position->id,
            'periode_mulai'   => now()->addDays(2)->toDateString(),
            'periode_selesai' => now()->addMonths(3)->toDateString(),
            'cv'              => $cv,
            'surat_pengantar' => $surat,
            // proposal_magang omitted
        ]);

        $response->assertSessionHasErrors('proposal_magang');
        $this->assertDatabaseCount('registrations', 0);
    }

    public function test_proposal_magang_rejects_non_pdf_files(): void
    {
        $cv = UploadedFile::fake()->create('my_cv.pdf', 500, 'application/pdf');
        $surat = UploadedFile::fake()->create('surat_pengantar.pdf', 1000, 'application/pdf');
        $invalidProposal = UploadedFile::fake()->create('proposal.docx', 1000, 'application/vnd.openxmlformats-officedocument.wordprocessingml.document');

        $response = $this->actingAs($this->participant)->post(route('participant.registrations.store'), [
            'position_id'     => $this->position->id,
            'periode_mulai'   => now()->addDays(2)->toDateString(),
            'periode_selesai' => now()->addMonths(3)->toDateString(),
            'cv'              => $cv,
            'surat_pengantar' => $surat,
            'proposal_magang' => $invalidProposal,
        ]);

        $response->assertSessionHasErrors('proposal_magang');
    }

    public function test_proposal_magang_rejects_files_larger_than_5mb(): void
    {
        $cv = UploadedFile::fake()->create('my_cv.pdf', 500, 'application/pdf');
        $surat = UploadedFile::fake()->create('surat_pengantar.pdf', 1000, 'application/pdf');
        $oversizedProposal = UploadedFile::fake()->create('big_proposal.pdf', 6000, 'application/pdf'); // > 5120 KB

        $response = $this->actingAs($this->participant)->post(route('participant.registrations.store'), [
            'position_id'     => $this->position->id,
            'periode_mulai'   => now()->addDays(2)->toDateString(),
            'periode_selesai' => now()->addMonths(3)->toDateString(),
            'cv'              => $cv,
            'surat_pengantar' => $surat,
            'proposal_magang' => $oversizedProposal,
        ]);

        $response->assertSessionHasErrors('proposal_magang');
    }

    public function test_participant_can_replace_proposal_magang_on_update(): void
    {
        $cv = UploadedFile::fake()->create('my_cv.pdf', 500, 'application/pdf');
        $surat = UploadedFile::fake()->create('surat_pengantar.pdf', 1000, 'application/pdf');
        $proposal = UploadedFile::fake()->create('proposal_magang.pdf', 2000, 'application/pdf');

        $this->actingAs($this->participant)->post(route('participant.registrations.store'), [
            'position_id'     => $this->position->id,
            'periode_mulai'   => now()->addDays(2)->toDateString(),
            'periode_selesai' => now()->addMonths(3)->toDateString(),
            'cv'              => $cv,
            'surat_pengantar' => $surat,
            'proposal_magang' => $proposal,
        ]);

        $registration = Registration::where('user_id', $this->participant->id)->first();
        $oldProposalPath = $registration->proposal_magang_path;

        $newProposal = UploadedFile::fake()->create('proposal_v2.pdf', 2500, 'application/pdf');

        $response = $this->actingAs($this->participant)->put(route('participant.registrations.update', $registration->id), [
            'position_id'     => $this->position->id,
            'periode_mulai'   => now()->addDays(2)->toDateString(),
            'periode_selesai' => now()->addMonths(3)->toDateString(),
            'proposal_magang' => $newProposal,
        ]);

        $response->assertSessionHasNoErrors();
        $registration->refresh();

        $this->assertNotEquals($oldProposalPath, $registration->proposal_magang_path);
        Storage::disk('public')->assertMissing($oldProposalPath);
        Storage::disk('public')->assertExists($registration->proposal_magang_path);
    }

    public function test_admin_can_view_application_with_proposal_magang(): void
    {
        $cv = UploadedFile::fake()->create('my_cv.pdf', 500, 'application/pdf');
        $surat = UploadedFile::fake()->create('surat_pengantar.pdf', 1000, 'application/pdf');
        $proposal = UploadedFile::fake()->create('proposal_magang.pdf', 2000, 'application/pdf');

        $this->actingAs($this->participant)->post(route('participant.registrations.store'), [
            'position_id'     => $this->position->id,
            'periode_mulai'   => now()->addDays(2)->toDateString(),
            'periode_selesai' => now()->addMonths(3)->toDateString(),
            'cv'              => $cv,
            'surat_pengantar' => $surat,
            'proposal_magang' => $proposal,
        ]);

        $registration = Registration::where('user_id', $this->participant->id)->first();

        $response = $this->actingAs($this->admin)->get(route('admin.applications.show', $registration->id));
        $response->assertStatus(200);
        $response->assertSee('Proposal Magang');
        $response->assertSee(basename($registration->proposal_magang_path));
    }

    public function test_legacy_applications_without_proposal_magang_render_gracefully(): void
    {
        // Simulate an existing application created before Proposal Magang feature
        $registration = Registration::create([
            'nomor_pendaftaran'    => 'MAGANG-2026-9999',
            'user_id'              => $this->participant->id,
            'position_id'          => $this->position->id,
            'cv_path'              => 'registrations/legacy/cv.pdf',
            'surat_pengantar_path' => 'registrations/legacy/surat.pdf',
            'proposal_magang_path' => null, // Legacy record
            'status'               => RegistrationStatus::Submitted,
            'tanggal_submit'       => now(),
            'periode_mulai'        => now()->addDays(2),
            'periode_selesai'      => now()->addMonths(3),
        ]);

        // 1. Participant Dashboard
        $dashboardResponse = $this->actingAs($this->participant)->get(route('participant.dashboard'));
        $dashboardResponse->assertStatus(200);

        // 2. Participant Show Page
        $showResponse = $this->actingAs($this->participant)->get(route('participant.registrations.show', $registration->id));
        $showResponse->assertStatus(200);
        $showResponse->assertSee('Proposal belum diunggah.');

        // 3. Admin Show Page
        $adminShowResponse = $this->actingAs($this->admin)->get(route('admin.applications.show', $registration->id));
        $adminShowResponse->assertStatus(200);
        $adminShowResponse->assertSee('Proposal magang belum di-upload peserta.');
    }
}
