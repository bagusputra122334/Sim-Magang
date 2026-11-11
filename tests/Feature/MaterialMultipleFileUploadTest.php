<?php

namespace Tests\Feature;

use App\Enums\RegistrationStatus;
use App\Enums\SubmissionType;
use App\Enums\UserRole;
use App\Models\Division;
use App\Models\Material;
use App\Models\ModuleSubmission;
use App\Models\Position;
use App\Models\Profile;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MaterialMultipleFileUploadTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    public function test_pembimbing_can_upload_multiple_files_up_to_100mb()
    {
        $division = Division::factory()->create();
        $pembimbing = User::factory()->create([
            'role' => UserRole::Pembimbing,
            'division_id' => $division->id,
        ]);

        $file1 = UploadedFile::fake()->create('panduan.pdf', 2048, 'application/pdf');
        $file2 = UploadedFile::fake()->create('source_code.rar', 4096, 'application/x-rar-compressed');
        $file3 = UploadedFile::fake()->create('tutorial.mp4', 8192, 'video/mp4');

        $response = $this->actingAs($pembimbing)->post(route('pembimbing.materials.store'), [
            'title' => 'Materi Praktik Lengkap',
            'description' => 'Silakan unduh dokumen panduan, source code arsip, dan video penjelasan.',
            'files' => [$file1, $file2, $file3],
            'is_task' => 1,
            'submission_type' => SubmissionType::All->value,
            'deadline' => Carbon::now()->addDays(7)->format('Y-m-d\TH:i'),
        ]);

        $response->assertRedirect(route('pembimbing.materials.index'));
        $response->assertSessionHas('success');

        $material = Material::where('title', 'Materi Praktik Lengkap')->first();
        $this->assertNotNull($material);
        $this->assertIsArray($material->files);
        $this->assertCount(3, $material->files);
        $this->assertNotEmpty($material->file_materi); // Backward compatibility check

        $allFiles = $material->all_files;
        $this->assertCount(3, $allFiles);
        $this->assertEquals('panduan.pdf', $allFiles[0]['name']);
        $this->assertEquals('source_code.rar', $allFiles[1]['name']);
        $this->assertEquals('tutorial.mp4', $allFiles[2]['name']);

        foreach ($allFiles as $f) {
            Storage::disk('public')->assertExists($f['path']);
        }
    }

    public function test_participant_can_submit_multiple_files_up_to_100mb()
    {
        $division = Division::factory()->create();
        $pembimbing = User::factory()->create([
            'role' => UserRole::Pembimbing,
            'division_id' => $division->id,
        ]);

        $participant = User::factory()->create([
            'role' => UserRole::Peserta,
            'division_id' => $division->id,
            'created_at' => Carbon::parse('2026-10-01 08:00:00'),
        ]);

        Profile::create([
            'user_id' => $participant->id,
            'nama_lengkap' => $participant->name,
            'participant_type' => 'mahasiswa',
            'nis_nim' => '24050974002',
            'nim' => '24050974002',
            'nik' => '3523010101010002',
            'tempat_lahir' => 'Tuban',
            'tanggal_lahir' => '2001-05-15',
            'jenis_kelamin' => 'Laki-laki',
            'institusi' => 'Universitas Tuban',
            'jurusan' => 'Teknik Informatika',
            'tahun_angkatan' => '2022',
            'semester' => 6,
            'alamat' => 'Jl. Veteran Tuban',
            'no_telepon' => '081234567899',
        ]);

        $position = Position::create([
            'nama_posisi' => 'Staff IT',
            'slug' => 'staff-it-' . uniqid(),
            'kuota' => 5,
            'status' => 'aktif',
            'deskripsi' => 'Deskripsi Staff IT',
            'division_id' => $division->id,
        ]);

        Registration::create([
            'nomor_pendaftaran' => 'REG-2026-0002',
            'user_id' => $participant->id,
            'position_id' => $position->id,
            'status' => RegistrationStatus::Accepted,
            'periode_mulai' => Carbon::parse('2026-10-15'),
            'periode_selesai' => Carbon::parse('2026-12-15'),
            'cv_path' => 'cv.pdf',
            'surat_pengantar_path' => 'sp.pdf',
        ]);

        $material = Material::create([
            'pembimbing_id' => $pembimbing->id,
            'division_id' => $division->id,
            'title' => 'Tugas Integrasi Sistem',
            'description' => 'Unggah tugas Anda',
            'is_task' => true,
            'submission_type' => SubmissionType::All,
            'created_at' => Carbon::parse('2026-10-20 10:00:00'),
        ]);

        $subFile1 = UploadedFile::fake()->create('laporan.docx', 3072, 'application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        $subFile2 = UploadedFile::fake()->create('backup.zip', 5120, 'application/zip');

        $response = $this->actingAs($participant)->post(route('participant.materials.submit', $material->id), [
            'files' => [$subFile1, $subFile2],
            'submission_text' => 'Berikut adalah laporan dan file backup tugas saya.',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $submission = ModuleSubmission::where('material_id', $material->id)->where('user_id', $participant->id)->first();
        $this->assertNotNull($submission);
        $this->assertIsArray($submission->files);
        $this->assertCount(2, $submission->files);
        $this->assertNotEmpty($submission->file_path); // Legacy compatibility check

        $allSubmissionFiles = $submission->all_files;
        $this->assertCount(2, $allSubmissionFiles);
        $this->assertEquals('laporan.docx', $allSubmissionFiles[0]['name']);
        $this->assertEquals('backup.zip', $allSubmissionFiles[1]['name']);

        foreach ($allSubmissionFiles as $f) {
            Storage::disk('public')->assertExists($f['path']);
        }
    }

    public function test_backward_compatibility_for_legacy_single_file_records()
    {
        $material = new Material([
            'title' => 'Legacy Material',
            'file_materi' => 'materials/legacy.pdf',
            'files' => null,
        ]);

        $allFiles = $material->all_files;
        $this->assertCount(1, $allFiles);
        $this->assertEquals('legacy.pdf', $allFiles[0]['name']);
        $this->assertEquals('materials/legacy.pdf', $allFiles[0]['path']);

        $submission = new ModuleSubmission([
            'file_path' => 'submissions/legacy_sub.zip',
            'files' => null,
        ]);

        $allSubFiles = $submission->all_files;
        $this->assertCount(1, $allSubFiles);
        $this->assertEquals('legacy_sub.zip', $allSubFiles[0]['name']);
        $this->assertEquals('submissions/legacy_sub.zip', $allSubFiles[0]['path']);
    }
}

