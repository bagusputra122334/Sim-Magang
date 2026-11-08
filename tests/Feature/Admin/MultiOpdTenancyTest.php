<?php

namespace Tests\Feature\Admin;

use App\Enums\PositionStatus;
use App\Enums\RegistrationStatus;
use App\Enums\UserRole;
use App\Models\Opd;
use App\Models\Position;
use App\Models\Profile;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MultiOpdTenancyTest extends TestCase
{
    use RefreshDatabase;

    protected User $superAdmin;
    protected User $adminOpdA;
    protected User $adminOpdB;
    protected Opd $opdA;
    protected Opd $opdB;
    protected User $peserta;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('local');
        Storage::fake('public');

        $this->superAdmin = User::factory()->create([
            'name'   => 'Super Administrator Tuban',
            'email'  => 'superadmin.tenancy@tubankab.go.id',
            'role'   => UserRole::SuperAdmin,
            'opd_id' => null,
        ]);

        $this->opdA = Opd::factory()->create([
            'name'      => 'Dinas Komunikasi dan Informatika Tuban',
            'code'      => 'DISKOMINFO-TEST',
            'slug'      => 'diskominfo-test',
            'is_active' => true,
        ]);

        $this->opdB = Opd::factory()->create([
            'name'      => 'Badan Perencanaan Pembangunan Daerah',
            'code'      => 'BAPPEDA-TEST',
            'slug'      => 'bappeda-test',
            'is_active' => true,
        ]);

        $this->adminOpdA = User::factory()->create([
            'name'   => 'Admin OPD Kominfo',
            'email'  => 'admin.kominfo@tubankab.go.id',
            'role'   => UserRole::Admin,
            'opd_id' => $this->opdA->id,
        ]);

        $this->adminOpdB = User::factory()->create([
            'name'   => 'Admin OPD Bappeda',
            'email'  => 'admin.bappeda@tubankab.go.id',
            'role'   => UserRole::Admin,
            'opd_id' => $this->opdB->id,
        ]);

        $this->peserta = User::factory()->create([
            'name'  => 'Calon Peserta Magang',
            'email' => 'peserta.tenancy@gmail.com',
            'role'  => UserRole::Peserta,
        ]);

        Profile::create([
            'user_id'          => $this->peserta->id,
            'nama_lengkap'     => 'Calon Peserta Magang',
            'participant_type' => 'mahasiswa',
            'nis_nim'          => '12345678',
            'nim'              => '12345678',
            'nik'              => '3523010101010001',
            'tempat_lahir'     => 'Tuban',
            'tanggal_lahir'    => '2000-01-01',
            'jenis_kelamin'    => 'Laki-laki',
            'institusi'        => 'Universitas Airlangga',
            'jurusan'          => 'Sistem Informasi',
            'tahun_angkatan'   => '2022',
            'alamat'           => 'Tuban',
            'no_telepon'       => '081234567890',
        ]);
    }

    /**
     * Uji 1: Super Admin dapat membuat master data OPD baru dan membuat akun Admin OPD.
     */
    public function test_super_admin_can_create_new_opd_and_admin_opd_account(): void
    {
        // 1. Super Admin membuat OPD baru
        $opdData = [
            'name'                => 'Dinas Perhubungan Kabupaten Tuban',
            'code'                => 'DISHUB-TUBAN',
            'slug'                => 'dishub-tuban',
            'address'             => 'Jl. Dr. Wahidin Sudirohusodo No. 45 Tuban',
            'phone'               => '0356-321456',
            'email'               => 'dishub@tubankab.go.id',
            'head_of_office_name' => 'Drs. H. Bambang M.Si',
            'head_of_office_nip'  => '197505102000031002',
            'is_active'           => 1,
        ];

        $responseOpd = $this->actingAs($this->superAdmin)
            ->post(route('admin.opds.store'), $opdData);

        $responseOpd->assertRedirect(route('admin.opds.index'));
        $responseOpd->assertSessionHas('success');

        $this->assertDatabaseHas('opds', [
            'code' => 'DISHUB-TUBAN',
            'name' => 'Dinas Perhubungan Kabupaten Tuban',
        ]);

        $createdOpd = Opd::where('code', 'DISHUB-TUBAN')->firstOrFail();

        // Verifikasi akun Admin OPD otomatis dibuat dengan nama sesuai Nama OPD dan email sesuai Email Resmi OPD
        $autoAdmin = User::where('opd_id', $createdOpd->id)->where('email', 'dishub@tubankab.go.id')->firstOrFail();
        $this->assertEquals('Dinas Perhubungan Kabupaten Tuban', $autoAdmin->name);
        $this->assertEquals(UserRole::Admin, $autoAdmin->role);
        $this->assertTrue($autoAdmin->hasDefaultPassword());

        // Verifikasi saat Admin OPD login, terdapat peringatan keamanan ganti password default
        $this->actingAs($autoAdmin);
        $dashboardResponse = $this->get(route('admin.dashboard'));
        $dashboardResponse->assertOk();
        $dashboardResponse->assertSee('Peringatan Keamanan');
        $dashboardResponse->assertSee('password default');

        // 2. Super Admin juga dapat menambahkan akun Admin OPD tambahan secara manual
        $adminData = [
            'name'           => 'Admin Dishub Tuban',
            'email'          => 'admin.dishub@tubankab.go.id',
            'password'       => 'PasswordDishub123#',
            'nip'            => '198801012010011005',
            'position_title' => 'Staf Tata Usaha Dishub',
            'role'           => 'admin',
            'opd_id'         => $createdOpd->id,
        ];

        $responseAdmin = $this->actingAs($this->superAdmin)
            ->post(route('admin.opd-admins.store'), $adminData);

        $responseAdmin->assertRedirect(route('admin.opd-admins.index'));
        $responseAdmin->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'email'  => 'admin.dishub@tubankab.go.id',
            'opd_id' => $createdOpd->id,
            'role'   => UserRole::Admin->value,
        ]);
    }

    /**
     * Uji 2: Admin OPD A hanya bisa melihat posisi dan pendaftar di OPD A,
     * serta tidak bisa melihat atau memverifikasi pendaftar di OPD B.
     */
    public function test_admin_opd_a_can_only_see_positions_and_applications_in_own_opd_and_cannot_view_or_verify_opd_b(): void
    {
        // Setup Posisi OPD A & OPD B
        $posA = Position::factory()->create([
            'nama_posisi' => 'Frontend Developer Kominfo',
            'opd_id'      => $this->opdA->id,
            'status'      => PositionStatus::Aktif,
        ]);

        $posB = Position::factory()->create([
            'nama_posisi' => 'Perencana Kebijakan Bappeda',
            'opd_id'      => $this->opdB->id,
            'status'      => PositionStatus::Aktif,
        ]);

        // Setup Pendaftar OPD A & OPD B
        $userA = User::factory()->create(['role' => UserRole::Peserta]);
        Profile::create([
            'user_id'          => $userA->id,
            'nama_lengkap'     => 'Peserta A',
            'participant_type' => 'mahasiswa',
            'nis_nim'          => '11111111',
            'nim'              => '11111111',
            'nik'              => '3523010101010002',
            'tempat_lahir'     => 'Tuban',
            'tanggal_lahir'    => '2001-01-01',
            'jenis_kelamin'    => 'Laki-laki',
            'institusi'        => 'Universitas A',
            'jurusan'          => 'Teknik',
            'tahun_angkatan'   => '2022',
            'alamat'           => 'Tuban',
            'no_telepon'       => '081234567891',
        ]);

        $regA = Registration::create([
            'user_id'              => $userA->id,
            'position_id'          => $posA->id,
            'opd_id'               => $this->opdA->id,
            'nomor_pendaftaran'    => 'MAGANG-2026-A001',
            'status'               => RegistrationStatus::Submitted,
            'cv_path'              => 'documents/cv/a.pdf',
            'surat_pengantar_path' => 'documents/sp/a.pdf',
        ]);

        $userB = User::factory()->create(['role' => UserRole::Peserta]);
        Profile::create([
            'user_id'          => $userB->id,
            'nama_lengkap'     => 'Peserta B',
            'participant_type' => 'mahasiswa',
            'nis_nim'          => '22222222',
            'nim'              => '22222222',
            'nik'              => '3523010101010003',
            'tempat_lahir'     => 'Tuban',
            'tanggal_lahir'    => '2001-02-02',
            'jenis_kelamin'    => 'Perempuan',
            'institusi'        => 'Universitas B',
            'jurusan'          => 'Ekonomi',
            'tahun_angkatan'   => '2022',
            'alamat'           => 'Tuban',
            'no_telepon'       => '081234567892',
        ]);

        $regB = Registration::create([
            'user_id'              => $userB->id,
            'position_id'          => $posB->id,
            'opd_id'               => $this->opdB->id,
            'nomor_pendaftaran'    => 'MAGANG-2026-B001',
            'status'               => RegistrationStatus::Submitted,
            'cv_path'              => 'documents/cv/b.pdf',
            'surat_pengantar_path' => 'documents/sp/b.pdf',
        ]);

        // Login sebagai Admin OPD A
        $this->actingAs($this->adminOpdA);

        // a. Hanya melihat posisi di OPD A pada daftar posisi
        $posResponse = $this->get(route('admin.positions.index'));
        $posResponse->assertOk();
        $posResponse->assertSee('Frontend Developer Kominfo');
        $posResponse->assertDontSee('Perencana Kebijakan Bappeda');

        // b. Hanya melihat pendaftar di OPD A pada daftar pendaftar
        $appResponse = $this->get(route('admin.applications.index'));
        $appResponse->assertOk();
        $appResponse->assertSee('MAGANG-2026-A001');
        $appResponse->assertDontSee('MAGANG-2026-B001');

        // c. Tidak bisa melihat detail pendaftar di OPD B (403 Forbidden)
        $detailB = $this->get(route('admin.applications.show', $regB->id));
        $detailB->assertForbidden();

        // d. Tidak bisa membuka form review pendaftar di OPD B (403 Forbidden)
        $reviewPageB = $this->get(route('admin.applications.review', $regB->id));
        $reviewPageB->assertForbidden();

        // e. Tidak bisa memverifikasi pendaftar di OPD B (403 Forbidden)
        $verifyB = $this->put(route('admin.applications.update-review', $regB->id), [
            'status'        => 'accepted',
            'catatan_admin' => 'Verifikasi cross-OPD tidak sah',
        ]);
        $verifyB->assertForbidden();

        // Pastikan status di database tetap Submitted (tidak berubah)
        $this->assertEquals(RegistrationStatus::Submitted->value, $regB->fresh()->status->value);

        // f. Admin OPD tidak memiliki hak akses ke Survei Kepuasan, Pengaturan, dan Konten Landing (403 Forbidden)
        $this->get(route('admin.surveys.index'))->assertForbidden();
        $this->get(route('admin.settings.index'))->assertForbidden();
        $this->get(route('admin.landing-contents.index'))->assertForbidden();

        // g. Sidebar Admin OPD tidak menampilkan menu Survei Kepuasan dan Pengaturan
        $sidebarResp = $this->get(route('admin.dashboard'));
        $sidebarResp->assertOk();
        $sidebarResp->assertDontSee('Survei Kepuasan');
        $sidebarResp->assertDontSee('Pengaturan Global');
    }

    /**
     * Uji 3: Proteksi Cross-OPD IDOR: Admin OPD A mendapat respons 403 Forbidden
     * saat mencoba mengunduh berkas privat pendaftar milik OPD B.
     */
    public function test_cross_opd_idor_protection_admin_opd_a_gets_403_forbidden_when_downloading_opd_b_files(): void
    {
        $posB = Position::factory()->create([
            'nama_posisi' => 'Staff IT Bappeda',
            'opd_id'      => $this->opdB->id,
            'status'      => PositionStatus::Aktif,
        ]);

        $cvPath = 'documents/cv/cv_peserta_b.pdf';
        $spPath = 'documents/sp/sp_peserta_b.pdf';
        $propPath = 'documents/proposal/proposal_peserta_b.pdf';
        $balasanPath = 'documents/surat_balasan/balasan_peserta_b.pdf';

        Storage::disk('local')->put($cvPath, '%PDF-1.4 dummy cv content');
        Storage::disk('local')->put($spPath, '%PDF-1.4 dummy sp content');
        Storage::disk('local')->put($propPath, '%PDF-1.4 dummy prop content');
        Storage::disk('local')->put($balasanPath, '%PDF-1.4 dummy balasan content');

        $userB = User::factory()->create(['role' => UserRole::Peserta]);
        Profile::create([
            'user_id'          => $userB->id,
            'nama_lengkap'     => 'Peserta B Private',
            'participant_type' => 'mahasiswa',
            'nis_nim'          => '33333333',
            'nim'              => '33333333',
            'nik'              => '3523010101010004',
            'tempat_lahir'     => 'Tuban',
            'tanggal_lahir'    => '2001-03-03',
            'jenis_kelamin'    => 'Laki-laki',
            'institusi'        => 'Universitas B',
            'jurusan'          => 'IT',
            'tahun_angkatan'   => '2022',
            'alamat'           => 'Tuban',
            'no_telepon'       => '081234567893',
        ]);

        $regB = Registration::create([
            'user_id'              => $userB->id,
            'position_id'          => $posB->id,
            'opd_id'               => $this->opdB->id,
            'nomor_pendaftaran'    => 'MAGANG-2026-B002',
            'status'               => RegistrationStatus::Accepted,
            'cv_path'              => $cvPath,
            'surat_pengantar_path' => $spPath,
            'proposal_magang_path' => $propPath,
            'surat_balasan_path'   => $balasanPath,
        ]);

        // Login sebagai Admin OPD A (Mencoba IDOR ke berkas pendaftar OPD B)
        $this->actingAs($this->adminOpdA);

        // a. Download CV pendaftar OPD B -> 403 Forbidden
        $this->get(route('documents.download', ['registration' => $regB->id, 'type' => 'cv']))
            ->assertForbidden();

        // b. Download Surat Pengantar pendaftar OPD B -> 403 Forbidden
        $this->get(route('documents.download', ['registration' => $regB->id, 'type' => 'surat_pengantar']))
            ->assertForbidden();

        // c. Download Proposal pendaftar OPD B -> 403 Forbidden
        $this->get(route('documents.download', ['registration' => $regB->id, 'type' => 'proposal_magang']))
            ->assertForbidden();

        // d. Download Surat Balasan pendaftar OPD B via route documents -> 403 Forbidden
        $this->get(route('documents.download', ['registration' => $regB->id, 'type' => 'surat_balasan']))
            ->assertForbidden();

        // e. Download Surat Balasan pendaftar OPD B via admin route -> 403 Forbidden
        $this->get(route('admin.applications.reply-letter.download', $regB->id))
            ->assertForbidden();

        // f. Verifikasi Super Admin DIIZINKAN mengakses dokumen OPD mana pun untuk audit pusat
        $this->actingAs($this->superAdmin);
        $this->get(route('documents.download', ['registration' => $regB->id, 'type' => 'cv']))
            ->assertSuccessful();
    }

    /**
     * Uji 4: Peserta dapat memfilter posisi magang berdasarkan OPD.
     */
    public function test_participants_can_filter_internship_positions_by_opd(): void
    {
        $posA = Position::factory()->create([
            'nama_posisi'  => 'Web Programmer Diskominfo',
            'opd_id'       => $this->opdA->id,
            'status'       => PositionStatus::Aktif,
        ]);

        $posB = Position::factory()->create([
            'nama_posisi'  => 'Analis Data Spasial Bappeda',
            'opd_id'       => $this->opdB->id,
            'status'       => PositionStatus::Aktif,
        ]);

        $this->actingAs($this->peserta);

        // 1. Filter via Global Search / Omni-Search publik
        $searchResponse = $this->get(route('global.search', ['opd_id' => $this->opdA->id]));
        $searchResponse->assertOk();
        $searchResponse->assertJsonFragment(['name' => 'Web Programmer Diskominfo']);
        $searchResponse->assertJsonMissing(['name' => 'Analis Data Spasial Bappeda']);

        // 2. Filter via halaman publik / landing search
        $landingResponse = $this->get('/?opd_id=' . $this->opdA->id);
        $landingResponse->assertOk();
        $landingResponse->assertSee('Web Programmer Diskominfo');
        $landingResponse->assertDontSee('Analis Data Spasial Bappeda');

        // 3. Filter pada form pendaftaran peserta
        $formResponse = $this->get(route('participant.registrations.create', ['opd_id' => $this->opdA->id]));
        $formResponse->assertOk();
        $formResponse->assertSee('Web Programmer Diskominfo');
    }

    /**
     * Uji 5: Super Admin memiliki hak monitoring pendaftaran lintas OPD (Read-Only)
     * dan wewenang keputusan verifikasi tetap berada pada masing-masing Admin OPD.
     */
    public function test_super_admin_has_read_only_monitoring_and_opd_filter(): void
    {
        $posA = Position::factory()->create([
            'nama_posisi' => 'Pranata Komputer Diskominfo',
            'opd_id'      => $this->opdA->id,
            'status'      => PositionStatus::Aktif,
        ]);

        $regA = Registration::create([
            'nomor_pendaftaran'    => 'MAGANG-2026-TEST-A',
            'user_id'              => $this->peserta->id,
            'position_id'          => $posA->id,
            'opd_id'               => $this->opdA->id,
            'status'               => RegistrationStatus::Submitted,
            'tanggal_submit'       => now(),
            'cv_path'              => 'dokumen/cv.pdf',
            'surat_pengantar_path' => 'dokumen/surat.pdf',
        ]);

        // 1. Super Admin mengakses index pendaftaran -> Melihat judul Monitoring, filter Wilayah OPD default '-- Pilih Wilayah OPD --', dan data belum tampil sebelum OPD dipilih
        $this->actingAs($this->superAdmin);
        $indexResp = $this->get(route('admin.applications.index'));
        $indexResp->assertOk();
        $indexResp->assertSee('Monitoring Pendaftaran Magang Seluruh Tuban');
        $indexResp->assertSee('Wilayah OPD');
        $indexResp->assertSee('-- Pilih Wilayah OPD --');
        $indexResp->assertSee('Pilih Wilayah OPD Terlebih Dahulu');
        $indexResp->assertDontSee('MAGANG-2026-TEST-A');

        // 2. Super Admin memilih opsi 'all' (Semua Wilayah OPD) -> Data agregat muncul
        $allResp = $this->get(route('admin.applications.index', ['opd_id' => 'all']));
        $allResp->assertOk();
        $allResp->assertSee('MAGANG-2026-TEST-A');
        $allResp->assertSee($this->peserta->name);

        // 3. Super Admin memfilter berdasarkan OPD spesifik -> Data pendaftaran OPD tersebut muncul
        $filteredResp = $this->get(route('admin.applications.index', ['opd_id' => $this->opdA->id]));
        $filteredResp->assertOk();
        $filteredResp->assertSee('MAGANG-2026-TEST-A');
        $filteredResp->assertSee($this->peserta->name);

        // 4. Super Admin mencoba ekspor PDF tanpa memilih Wilayah OPD -> Dialihkan dengan pesan peringatan
        $exportPdfResp = $this->get(route('admin.applications.export_pdf'));
        $exportPdfResp->assertRedirect(route('admin.applications.index'));
        $exportPdfResp->assertSessionHas('warning');

        // 3. Super Admin melihat detail pendaftaran -> Berada dalam Mode Monitoring (Read-Only)
        $detailResp = $this->get(route('admin.applications.show', $regA->id));
        $detailResp->assertOk();
        $detailResp->assertSee('Mode Monitoring Super Admin');

        // Memastikan status tidak otomatis beralih ke UnderReview saat Super Admin memantau
        $regA->refresh();
        $this->assertEquals(RegistrationStatus::Submitted, $regA->status);

        // 4. Super Admin mencoba mengakses halaman form review verifikasi -> Ditolak / dialihkan
        $reviewResp = $this->get(route('admin.applications.review', $regA->id));
        $reviewResp->assertRedirect(route('admin.applications.show', $regA->id));

        // 5. Super Admin mencoba mengirim update keputusan verifikasi -> Ditolak / dialihkan
        $putResp = $this->put(route('admin.applications.update-review', $regA->id), [
            'status'        => 'accepted',
            'catatan_admin' => 'Percobaan persetujuan oleh Super Admin',
        ]);
        $putResp->assertRedirect(route('admin.applications.show', $regA->id));

        // 6. Admin OPD yang bersangkutan dapat melakukan review secara sah
        $this->actingAs($this->adminOpdA);
        $opdReviewResp = $this->get(route('admin.applications.review', $regA->id));
        $opdReviewResp->assertOk();

        // 7. Dashboard Super Admin: Langsung menampilkan Ringkasan OPD tanpa Pendaftaran Magang Terbaru
        $this->actingAs($this->superAdmin);
        $dashboardSuperAdmin = $this->get(route('admin.dashboard'));
        $dashboardSuperAdmin->assertOk();
        $dashboardSuperAdmin->assertSee('Ringkasan Formasi dan Pendaftar per OPD di Kabupaten Tuban');
        $dashboardSuperAdmin->assertDontSee('Pendaftaran Magang Terbaru');

        // Saat Super Admin memfilter spesifik OPD, tampilkan pendaftaran magang terbaru milik OPD tersebut
        $dashboardFiltered = $this->get(route('admin.dashboard', ['opd_id' => $this->opdA->id]));
        $dashboardFiltered->assertOk();
        $dashboardFiltered->assertSee('Pendaftaran Magang Terbaru');

        // Admin OPD selalu melihat Pendaftaran Magang Terbaru OPD miliknya
        $this->actingAs($this->adminOpdA);
        $dashboardOpd = $this->get(route('admin.dashboard'));
        $dashboardOpd->assertOk();
        $dashboardOpd->assertSee('Pendaftaran Magang Terbaru');

        // 8. Super Admin memiliki hak penuh mengakses Survei Kepuasan, Pengaturan, dan Konten Landing
        $this->actingAs($this->superAdmin);
        $this->get(route('admin.surveys.index'))->assertOk();
        $this->get(route('admin.settings.index'))->assertOk();
        $this->get(route('admin.landing-contents.index'))->assertOk();
        $superAdminDashboard = $this->get(route('admin.dashboard'));
        $superAdminDashboard->assertSee('Survei Kepuasan');
        $superAdminDashboard->assertSee('Pengaturan Global');
    }
}
