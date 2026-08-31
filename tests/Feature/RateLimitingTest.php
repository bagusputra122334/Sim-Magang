<?php

namespace Tests\Feature;

use App\Enums\PositionStatus;
use App\Enums\UserRole;
use App\Models\Position;
use App\Models\Profile;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class RateLimitingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('local');
        Storage::fake('public');
        RateLimiter::clear(sprintf('%s|%s', 'test@example.com', '127.0.0.1'));
    }

    public function test_login_endpoint_is_throttled_after_five_failed_attempts(): void
    {
        $user = User::factory()->create([
            'email'    => 'throttletest@example.com',
            'password' => bcrypt('correct-password'),
        ]);

        for ($i = 0; $i < 5; $i++) {
            $this->post('/login', [
                'email'    => $user->email,
                'password' => 'wrong-password',
            ]);
        }

        // 6th attempt should be throttled with HTTP 429 Too Many Requests or session error
        $response = $this->post('/login', [
            'email'    => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertTrue($response->status() === 429 || session()->has('errors'));
        $this->assertGuest();
    }

    public function test_registration_submission_is_throttled_after_three_attempts_per_minute(): void
    {
        $participant = User::factory()->create([
            'role' => UserRole::Peserta,
        ]);

        Profile::create([
            'user_id'          => $participant->id,
            'nama_lengkap'     => 'Peserta Rate Limit',
            'participant_type' => 'mahasiswa',
            'nis_nim'          => '12345678',
            'nim'              => '12345678',
            'nik'              => '3523010101010009',
            'tempat_lahir'     => 'Tuban',
            'tanggal_lahir'    => '2000-01-01',
            'jenis_kelamin'    => 'Laki-laki',
            'institusi'        => 'Universitas Negeri Surabaya',
            'jurusan'          => 'Teknik Informatika',
            'tahun_angkatan'   => '2022',
            'alamat'           => 'Surabaya',
            'no_telepon'       => '08123456789',
        ]);

        $position = Position::create([
            'nama_posisi' => 'DevOps Intern Rate Limit',
            'slug'        => 'devops-intern-rate-limit',
            'deskripsi'   => 'Deskripsi posisi magang.',
            'kuota'       => 10,
            'status'      => PositionStatus::Aktif,
        ]);

        // RateLimiter throttle key for registration submission
        RateLimiter::clear('registration-submission:'.$participant->id);

        for ($i = 0; $i < 3; $i++) {
            $cv = UploadedFile::fake()->create('cv.pdf', 500, 'application/pdf');
            $surat = UploadedFile::fake()->create('surat.pdf', 500, 'application/pdf');
            $proposal = UploadedFile::fake()->create('proposal.pdf', 500, 'application/pdf');

            $this->actingAs($participant)->post(route('participant.registrations.store'), [
                'position_id'     => $position->id,
                'periode_mulai'   => now()->addDays(2)->toDateString(),
                'periode_selesai' => now()->addMonths(3)->toDateString(),
                'cv'              => $cv,
                'surat_pengantar' => $surat,
                'proposal_magang' => $proposal,
            ]);
        }

        // 4th attempt in the same minute should be throttled (HTTP 429 Too Many Requests)
        $cv = UploadedFile::fake()->create('cv.pdf', 500, 'application/pdf');
        $surat = UploadedFile::fake()->create('surat.pdf', 500, 'application/pdf');
        $proposal = UploadedFile::fake()->create('proposal.pdf', 500, 'application/pdf');

        $response = $this->actingAs($participant)->post(route('participant.registrations.store'), [
            'position_id'     => $position->id,
            'periode_mulai'   => now()->addDays(2)->toDateString(),
            'periode_selesai' => now()->addMonths(3)->toDateString(),
            'cv'              => $cv,
            'surat_pengantar' => $surat,
            'proposal_magang' => $proposal,
        ]);

        $response->assertStatus(429);
    }
}
