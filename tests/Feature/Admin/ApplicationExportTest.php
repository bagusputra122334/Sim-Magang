<?php

namespace Tests\Feature\Admin;

use App\Enums\ParticipantType;
use App\Enums\PositionStatus;
use App\Enums\RegistrationStatus;
use App\Enums\UserRole;
use App\Exports\RegistrationsExport;
use App\Models\Position;
use App\Models\Profile;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Maatwebsite\Excel\Facades\Excel;
use Tests\TestCase;

class ApplicationExportTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $participantMhs;
    protected User $participantSiswa;
    protected Position $position1;
    protected Position $position2;
    protected Registration $reg1;
    protected Registration $reg2;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'name' => 'Admin Diskominfo',
            'role' => UserRole::Admin,
        ]);

        $this->participantMhs = User::factory()->create([
            'name' => 'Bagus Dwi Mahasiswa',
            'role' => UserRole::Peserta,
        ]);

        Profile::create([
            'user_id'          => $this->participantMhs->id,
            'nama_lengkap'     => 'Bagus Dwi Mahasiswa',
            'participant_type' => ParticipantType::University,
            'nis_nim'          => '24050974004',
            'nim'              => '24050974004',
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

        $this->participantSiswa = User::factory()->create([
            'name' => 'Putra Siswa',
            'role' => UserRole::Peserta,
        ]);

        Profile::create([
            'user_id'          => $this->participantSiswa->id,
            'nama_lengkap'     => 'Putra Siswa',
            'participant_type' => ParticipantType::Student,
            'nis_nim'          => '123456789',
            'nim'              => null,
            'nik'              => '3523010101010002',
            'tempat_lahir'     => 'Tuban',
            'tanggal_lahir'    => '2006-05-10',
            'jenis_kelamin'    => 'Laki-laki',
            'institusi'        => 'SMKN 1 Tuban',
            'jurusan'          => 'Rekayasa Perangkat Lunak',
            'tahun_angkatan'   => '2024',
            'alamat'           => 'Tuban',
            'no_telepon'       => '08987654321',
        ]);

        $this->position1 = Position::create([
            'nama_posisi'   => 'Web Developer',
            'slug'          => 'web-developer',
            'deskripsi'     => 'Deskripsi posisi web developer',
            'persyaratan'   => 'PHP, Laravel, MySQL',
            'kuota'         => 3,
            'status'        => PositionStatus::Aktif,
            'tanggal_buka'  => now()->subDays(5)->toDateString(),
            'tanggal_tutup' => now()->addDays(30)->toDateString(),
        ]);

        $this->position2 = Position::create([
            'nama_posisi'   => 'Network Engineer',
            'slug'          => 'network-engineer',
            'deskripsi'     => 'Deskripsi posisi network engineer',
            'persyaratan'   => 'Cisco, Mikrotik',
            'kuota'         => 2,
            'status'        => PositionStatus::Aktif,
            'tanggal_buka'  => now()->subDays(5)->toDateString(),
            'tanggal_tutup' => now()->addDays(30)->toDateString(),
        ]);

        $this->reg1 = Registration::create([
            'nomor_pendaftaran'    => 'MAGANG-2026-0001',
            'user_id'              => $this->participantMhs->id,
            'position_id'          => $this->position1->id,
            'cv_path'              => 'registrations/test/cv1.pdf',
            'surat_pengantar_path' => 'registrations/test/sp1.pdf',
            'proposal_magang_path' => 'registrations/test/pm1.pdf',
            'status'               => RegistrationStatus::Submitted,
            'tanggal_submit'       => now()->subDays(2),
            'periode_mulai'        => now()->addDays(5),
            'periode_selesai'      => now()->addMonths(3),
        ]);

        $this->reg2 = Registration::create([
            'nomor_pendaftaran'    => 'MAGANG-2026-0002',
            'user_id'              => $this->participantSiswa->id,
            'position_id'          => $this->position2->id,
            'cv_path'              => 'registrations/test/cv2.pdf',
            'surat_pengantar_path' => 'registrations/test/sp2.pdf',
            'proposal_magang_path' => 'registrations/test/pm2.pdf',
            'status'               => RegistrationStatus::Accepted,
            'tanggal_submit'       => now()->subDay(),
            'periode_mulai'        => now()->addDays(5),
            'periode_selesai'      => now()->addMonths(3),
        ]);
    }

    public function test_guest_cannot_access_export(): void
    {
        $response = $this->get(route('admin.applications.export'));
        $response->assertRedirect(route('login'));
    }

    public function test_participant_cannot_access_export(): void
    {
        $response = $this->actingAs($this->participantMhs)->get(route('admin.applications.export'));
        $response->assertStatus(403);
    }

    public function test_admin_can_download_excel_export(): void
    {
        Excel::fake();
        Excel::matchByRegex();

        $response = $this->actingAs($this->admin)->get(route('admin.applications.export'));

        $response->assertStatus(200);

        Excel::assertDownloaded('/^Data-Pendaftaran-Magang.*\\.xlsx$/', function (RegistrationsExport $export) {
            $headings = $export->headings();
            return $headings === [
                'No. Pendaftaran',
                'Nama Peserta',
                'Jenis & Instansi',
                'Posisi Magang',
                'Tgl Submit',
                'Status',
            ];
        });
    }

    public function test_export_data_mapping_and_transformations(): void
    {
        $export = new RegistrationsExport([]);
        $headings = $export->headings();

        // 1. Verify exactly 6 headings, no 'Aksi' column
        $this->assertCount(6, $headings);
        $this->assertEquals([
            'No. Pendaftaran',
            'Nama Peserta',
            'Jenis & Instansi',
            'Posisi Magang',
            'Tgl Submit',
            'Status',
        ], $headings);
        $this->assertNotContains('Aksi', $headings);

        // 2. Map row 1 (Mahasiswa)
        $mapped1 = $export->map($this->reg1);
        $this->assertEquals('MAGANG-2026-0001', $mapped1[0]);
        $this->assertEquals('Bagus Dwi Mahasiswa', $mapped1[1]);
        $this->assertEquals('Mahasiswa - Universitas Negeri Surabaya', $mapped1[2]);
        $this->assertEquals('Web Developer', $mapped1[3]);
        $this->assertEquals($this->reg1->tanggal_submit->format('d-m-Y H:i'), $mapped1[4]);
        $this->assertEquals('Submitted', $mapped1[5]);

        // 3. Map row 2 (Siswa)
        $mapped2 = $export->map($this->reg2);
        $this->assertEquals('MAGANG-2026-0002', $mapped2[0]);
        $this->assertEquals('Putra Siswa', $mapped2[1]);
        $this->assertEquals('Siswa - SMKN 1 Tuban', $mapped2[2]);
        $this->assertEquals('Network Engineer', $mapped2[3]);
        $this->assertEquals($this->reg2->tanggal_submit->format('d-m-Y H:i'), $mapped2[4]);
        $this->assertEquals('Accepted', $mapped2[5]);

        // 4. Verify no "[object HTMLInputElement]" in mapped rows
        foreach ([$mapped1, $mapped2] as $row) {
            foreach ($row as $cell) {
                $this->assertStringNotContainsString('[object', (string) $cell);
                $this->assertStringNotContainsString('HTMLInputElement', (string) $cell);
                $this->assertStringNotContainsString('<', (string) $cell); // No HTML
            }
        }
    }

    public function test_export_applies_active_filters(): void
    {
        // Filter by Status Accepted
        $exportAccepted = new RegistrationsExport(['status' => RegistrationStatus::Accepted->value]);
        $resultsAccepted = $exportAccepted->query()->get();

        $this->assertCount(1, $resultsAccepted);
        $this->assertEquals('MAGANG-2026-0002', $resultsAccepted->first()->nomor_pendaftaran);

        // Filter by Search Keyword
        $exportSearch = new RegistrationsExport(['search' => 'Bagus Dwi']);
        $resultsSearch = $exportSearch->query()->get();

        $this->assertCount(1, $resultsSearch);
        $this->assertEquals('MAGANG-2026-0001', $resultsSearch->first()->nomor_pendaftaran);
    }
}
