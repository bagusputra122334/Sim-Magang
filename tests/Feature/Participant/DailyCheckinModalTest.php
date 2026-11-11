<?php

namespace Tests\Feature\Participant;

use App\Enums\RegistrationStatus;
use App\Enums\UserRole;
use App\Models\Division;
use App\Models\Position;
use App\Models\Profile;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class DailyCheckinModalTest extends TestCase
{
    use RefreshDatabase;

    private User $participant;

    protected function setUp(): void
    {
        parent::setUp();

        $division = Division::factory()->create(['nama_divisi' => 'Aplikasi & Informatika']);
        $position = Position::factory()->create(['nama_posisi' => 'Developer']);

        $this->participant = User::factory()->create([
            'role' => UserRole::Peserta,
            'division_id' => $division->id,
        ]);

        Profile::create([
            'user_id' => $this->participant->id,
            'nis_nim' => '24050974099',
            'tempat_lahir' => 'Tuban',
            'tanggal_lahir' => '2003-01-01',
            'jenis_kelamin' => 'Laki-laki',
            'alamat' => 'Tuban',
            'no_telepon' => '081234567890',
            'institusi' => 'Universitas Negeri Surabaya',
            'jurusan' => 'Teknik Informatika',
            'tahun_angkatan' => '2024',
        ]);

        Registration::create([
            'nomor_pendaftaran' => 'REG-2026-0999',
            'user_id' => $this->participant->id,
            'position_id' => $position->id,
            'status' => RegistrationStatus::Accepted,
            'is_terminated' => false,
            'periode_mulai' => Carbon::today()->subWeek()->toDateString(),
            'periode_selesai' => Carbon::today()->addMonth()->toDateString(),
            'cv_path' => 'cv.pdf',
            'surat_pengantar_path' => 'sp.pdf',
        ]);
    }

    public function test_daily_checkins_page_includes_simang_modal_close_and_promise_handling(): void
    {
        $response = $this->actingAs($this->participant)->get(route('participant.daily-checkins.index'));

        $response->assertStatus(200);
        // Pastikan modal menutup setelah fetch AJAX selesai agar tombol Memproses... tidak stuck
        $response->assertSee('return performActualSubmit(activity);', false);
        $response->assertSee('window.SimangModal.close();', false);
        $response->assertSee("header.style.setProperty('background-color', accentColor, 'important');", false);
    }

    public function test_participant_can_submit_daily_checkin_via_ajax(): void
    {
        $response = $this->actingAs($this->participant)->postJson(route('participant.daily-checkins.store'), [
            'date' => Carbon::today()->toDateString(),
            'activity' => 'Membuat endpoint API serta memperbaiki bug pada modal konfirmasi absensi.',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Check-in harian berhasil disimpan.',
            ]);
    }
}

