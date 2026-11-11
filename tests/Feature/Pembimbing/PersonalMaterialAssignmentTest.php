<?php

namespace Tests\Feature\Pembimbing;

use App\Enums\RegistrationStatus;
use App\Enums\SubmissionType;
use App\Enums\UserRole;
use App\Models\Division;
use App\Models\Material;
use App\Models\Position;
use App\Models\Profile;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class PersonalMaterialAssignmentTest extends TestCase
{
    use RefreshDatabase;

    private User $pembimbing;
    private Division $division;
    private User $internA;
    private User $internB;
    private User $finishedIntern;

    protected function setUp(): void
    {
        parent::setUp();

        $this->division = Division::factory()->create(['nama_divisi' => 'Teknologi Informasi']);

        $this->pembimbing = User::factory()->create([
            'role' => UserRole::Pembimbing,
            'division_id' => $this->division->id,
            'name' => 'Mentor IT',
        ]);

        $position = Position::factory()->create([
            'nama_posisi' => 'Web Programmer',
        ]);

        // Intern A: Aktif (2 bulan ke depan)
        $this->internA = User::factory()->create([
            'role' => UserRole::Peserta,
            'division_id' => $this->division->id,
            'name' => 'Bagus Dwi Cahya Putra',
        ]);
        Profile::create([
            'user_id' => $this->internA->id,
            'nis_nim' => '24050974001',
            'tempat_lahir' => 'Tuban',
            'tanggal_lahir' => '2002-01-01',
            'jenis_kelamin' => 'Laki-laki',
            'alamat' => 'Tuban',
            'no_telepon' => '081234567891',
            'institusi' => 'Universitas Negeri Surabaya',
            'jurusan' => 'Sistem Informasi',
            'tahun_angkatan' => '2024',
        ]);
        Registration::create([
            'nomor_pendaftaran' => 'REG-2026-0001',
            'user_id' => $this->internA->id,
            'position_id' => $position->id,
            'status' => RegistrationStatus::Accepted,
            'is_terminated' => false,
            'periode_mulai' => Carbon::today()->subMonth()->toDateString(),
            'periode_selesai' => Carbon::today()->addMonths(2)->toDateString(),
            'cv_path' => 'cv.pdf',
            'surat_pengantar_path' => 'sp.pdf',
        ]);

        // Intern B: Aktif (2 bulan ke depan, teman satu divisi)
        $this->internB = User::factory()->create([
            'role' => UserRole::Peserta,
            'division_id' => $this->division->id,
            'name' => 'Siti Nurhaliza',
        ]);
        Profile::create([
            'user_id' => $this->internB->id,
            'nis_nim' => '24050974002',
            'tempat_lahir' => 'Tuban',
            'tanggal_lahir' => '2002-02-02',
            'jenis_kelamin' => 'Perempuan',
            'alamat' => 'Tuban',
            'no_telepon' => '081234567892',
            'institusi' => 'Politeknik Negeri Malang',
            'jurusan' => 'Teknik Komputer',
            'tahun_angkatan' => '2024',
        ]);
        Registration::create([
            'nomor_pendaftaran' => 'REG-2026-0002',
            'user_id' => $this->internB->id,
            'position_id' => $position->id,
            'status' => RegistrationStatus::Accepted,
            'is_terminated' => false,
            'periode_mulai' => Carbon::today()->subMonth()->toDateString(),
            'periode_selesai' => Carbon::today()->addMonths(2)->toDateString(),
            'cv_path' => 'cv.pdf',
            'surat_pengantar_path' => 'sp.pdf',
        ]);

        // Finished Intern: Masa magang sudah berakhir minggu lalu
        $this->finishedIntern = User::factory()->create([
            'role' => UserRole::Peserta,
            'division_id' => $this->division->id,
            'name' => 'Alumni Magang',
        ]);
        Profile::create([
            'user_id' => $this->finishedIntern->id,
            'nis_nim' => '24050974003',
            'tempat_lahir' => 'Tuban',
            'tanggal_lahir' => '2005-03-03',
            'jenis_kelamin' => 'Laki-laki',
            'alamat' => 'Tuban',
            'no_telepon' => '081234567893',
            'institusi' => 'SMK Negeri 1 Tuban',
            'jurusan' => 'RPL',
            'tahun_angkatan' => '2024',
        ]);
        Registration::create([
            'nomor_pendaftaran' => 'REG-2026-0003',
            'user_id' => $this->finishedIntern->id,
            'position_id' => $position->id,
            'status' => RegistrationStatus::Accepted,
            'is_terminated' => false,
            'periode_mulai' => Carbon::today()->subMonths(4)->toDateString(),
            'periode_selesai' => Carbon::today()->subWeek()->toDateString(),
            'cv_path' => 'cv.pdf',
            'surat_pengantar_path' => 'sp.pdf',
        ]);
    }

    public function test_pembimbing_can_view_materials_tabs_and_active_interns()
    {
        $response = $this->actingAs($this->pembimbing)->get(route('pembimbing.materials.index', ['tab' => 'mandiri']));

        $response->assertStatus(200);
        $response->assertSee('Bagus Dwi Cahya Putra');
        $response->assertSee('Siti Nurhaliza');
        // Peserta yang masa magangnya sudah berakhir harus otomatis hilang dari daftar
        $response->assertDontSee('Alumni Magang');
    }

    public function test_pembimbing_can_create_targeted_personal_material_for_specific_intern()
    {
        $response = $this->actingAs($this->pembimbing)->post(route('pembimbing.materials.store'), [
            'target_type' => 'custom',
            'assigned_users' => [$this->internA->id],
            'title' => 'Tugas Spesifik Backend API Redis',
            'description' => 'Konfigurasikan caching Redis pada endpoint dashboard.',
            'is_task' => 1,
            'submission_type' => SubmissionType::All->value,
            'deadline' => Carbon::tomorrow()->format('Y-m-d\TH:i'),
        ]);

        $response->assertRedirect(route('pembimbing.materials.personal', $this->internA->id));

        $material = Material::where('title', 'Tugas Spesifik Backend API Redis')->first();
        $this->assertNotNull($material);
        $this->assertEquals('custom', $material->target_type);
        $this->assertTrue($material->assignedUsers->contains('id', $this->internA->id));
        $this->assertFalse($material->assignedUsers->contains('id', $this->internB->id));
    }

    public function test_targeted_material_is_visible_only_to_assigned_intern_and_forbidden_to_others()
    {
        $material = Material::create([
            'pembimbing_id' => $this->pembimbing->id,
            'target_type' => 'custom',
            'title' => 'Tugas Rahasia Khusus Bagus',
            'description' => 'Perbaiki vulnerabilitas modul auth.',
            'is_task' => true,
        ]);
        $material->assignedUsers()->attach($this->internA->id);

        // Intern A (Penerima Tugas): Dapat melihat di list dan detail
        $resA = $this->actingAs($this->internA)->get(route('participant.materials.index'));
        $resA->assertStatus(200);
        $resA->assertSee('Tugas Rahasia Khusus Bagus');
        $resA->assertSee('Tugas Mandiri Khusus Anda');

        $resADetail = $this->actingAs($this->internA)->get(route('participant.materials.show', $material->id));
        $resADetail->assertStatus(200);
        $resADetail->assertSee('Tugas Rahasia Khusus Bagus');

        // Intern B (Bukan Penerima): TIDAK BOLEH melihat di list dan dilarang akses (403)
        $resB = $this->actingAs($this->internB)->get(route('participant.materials.index'));
        $resB->assertStatus(200);
        $resB->assertDontSee('Tugas Rahasia Khusus Bagus');

        $resBDetail = $this->actingAs($this->internB)->get(route('participant.materials.show', $material->id));
        $resBDetail->assertStatus(403);

        $resBSubmit = $this->actingAs($this->internB)->post(route('participant.materials.submit', $material->id), [
            'submission_link' => 'https://github.com/evil/repo',
        ]);
        $resBSubmit->assertStatus(403);
    }

    public function test_pembimbing_can_view_personal_dossier_of_intern()
    {
        $material = Material::create([
            'pembimbing_id' => $this->pembimbing->id,
            'target_type' => 'custom',
            'title' => 'Tugas Mandiri Portfolio',
            'description' => 'Susun portofolio teknis.',
            'is_task' => true,
        ]);
        $material->assignedUsers()->attach($this->internA->id);

        $response = $this->actingAs($this->pembimbing)->get(route('pembimbing.materials.personal', $this->internA->id));

        $response->assertStatus(200);
        $response->assertSee('Bagus Dwi Cahya Putra');
        $response->assertSee('Universitas Negeri Surabaya');
        $response->assertSee('Tugas Mandiri Portfolio');
        $response->assertSee('Belum Mengumpulkan');
    }

    public function test_pembimbing_from_another_division_cannot_access_intern_dossier()
    {
        $otherDivision = Division::factory()->create(['nama_divisi' => 'Sekretariat']);
        $otherPembimbing = User::factory()->create([
            'role' => UserRole::Pembimbing,
            'division_id' => $otherDivision->id,
        ]);

        $response = $this->actingAs($otherPembimbing)->get(route('pembimbing.materials.personal', $this->internA->id));
        $response->assertStatus(403);
    }

    public function test_create_view_with_specific_user_locks_to_intern_and_hides_picker()
    {
        $response = $this->actingAs($this->pembimbing)->get(route('pembimbing.materials.create', [
            'target' => 'custom',
            'user_id' => $this->internA->id,
        ]));

        $response->assertStatus(200);
        $response->assertSee('Penugasan Mandiri Terarah (Perorangan)');
        $response->assertSee('Bagus Dwi Cahya Putra');
        $response->assertSee('name="specific_user_id" value="' . $this->internA->id . '"', false);
        // Tidak boleh memunculkan radio pilihan target maupun checkbox daftar anak lain
        $response->assertDontSee('Pilih Anak Magang yang Ditugaskan');
        $response->assertDontSee('id="labelTargetAll"', false);
        $response->assertDontSee('Siti Nurhaliza');
    }

    public function test_create_view_without_user_id_displays_radio_and_intern_picker()
    {
        $response = $this->actingAs($this->pembimbing)->get(route('pembimbing.materials.create', [
            'target' => 'custom',
        ]));

        $response->assertStatus(200);
        $response->assertSee('Target Penerima Penugasan');
        $response->assertSee('Seluruh Peserta Divisi (Bersama)');
        $response->assertSee('Pilih Anak Magang yang Ditugaskan');
        $response->assertSee('Bagus Dwi Cahya Putra');
        $response->assertSee('Siti Nurhaliza');
        $response->assertDontSee('Penugasan Mandiri Terarah (Perorangan)');
    }
}
