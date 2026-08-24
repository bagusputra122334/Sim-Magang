<?php

namespace Tests\Feature\Participant;

use App\Enums\JenisKelamin;
use App\Enums\ParticipantType;
use App\Enums\UserRole;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ParticipantProfileFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function createParticipantUser(array $attributes = []): User
    {
        return User::factory()->create(array_merge([
            'role' => UserRole::Peserta,
        ], $attributes));
    }

    /**
     * Test A: Mahasiswa profile creation succeeds with a unique NIM.
     */
    public function test_mahasiswa_profile_creation_succeeds_with_unique_nim(): void
    {
        $user = $this->createParticipantUser();

        $payload = [
            'participant_type' => 'university',
            'nik'              => '3523143010060001',
            'nama_lengkap'     => 'Bagus Dwi Mahasiswa',
            'nis_nim'          => '24050974001',
            'tempat_lahir'     => 'Tuban',
            'tanggal_lahir'    => '2002-05-15',
            'jenis_kelamin'    => JenisKelamin::LakiLaki->value,
            'alamat'           => 'Jl. Veteran No. 10 Kabupaten Tuban',
            'no_telepon'       => '081234567890',
            'institusi'        => 'Universitas Negeri Surabaya',
            'jurusan'          => 'Teknik Informatika',
            'semester'         => 6,
            'tahun_angkatan'   => '2022',
        ];

        $response = $this->actingAs($user)
            ->post(route('participant.profile.store'), $payload);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('participant.onboarding.success'));

        $this->assertDatabaseHas('profiles', [
            'user_id'          => $user->id,
            'participant_type' => 'university',
            'nik'              => '3523143010060001',
            'nis_nim'          => '24050974001',
            'nim'              => '24050974001',
            'institusi'        => 'Universitas Negeri Surabaya',
            'jurusan'          => 'Teknik Informatika',
            'semester'         => 6,
        ]);
    }

    /**
     * Test B: Mahasiswa profile creation fails when another MAHASISWA already uses the same NIM.
     */
    public function test_mahasiswa_profile_creation_fails_when_another_mahasiswa_uses_same_nim(): void
    {
        $user1 = $this->createParticipantUser();
        Profile::create([
            'user_id'          => $user1->id,
            'participant_type' => ParticipantType::University,
            'nik'              => '3523143010060001',
            'nama_lengkap'     => 'Mahasiswa Pertama',
            'nis_nim'          => '24050974001',
            'nim'              => '24050974001',
            'tempat_lahir'     => 'Tuban',
            'tanggal_lahir'    => '2002-05-15',
            'jenis_kelamin'    => JenisKelamin::LakiLaki,
            'alamat'           => 'Jl. Veteran No. 10 Tuban',
            'no_telepon'       => '081234567890',
            'institusi'        => 'Universitas Negeri Surabaya',
            'jurusan'          => 'Teknik Informatika',
            'semester'         => 6,
            'tahun_angkatan'   => '2022',
        ]);

        $user2 = $this->createParticipantUser();

        $payload = [
            'participant_type' => 'university',
            'nik'              => '3523143010060002',
            'nama_lengkap'     => 'Mahasiswa Kedua',
            'nis_nim'          => '24050974001', // duplicate NIM
            'tempat_lahir'     => 'Tuban',
            'tanggal_lahir'    => '2002-06-20',
            'jenis_kelamin'    => JenisKelamin::LakiLaki->value,
            'alamat'           => 'Jl. Basuki Rahmat No. 12 Tuban',
            'no_telepon'       => '081234567891',
            'institusi'        => 'Universitas Brawijaya',
            'jurusan'          => 'Sistem Informasi',
            'semester'         => 4,
            'tahun_angkatan'   => '2023',
        ];

        $response = $this->actingAs($user2)
            ->post(route('participant.profile.store'), $payload);

        $response->assertSessionHasErrors(['nis_nim', 'nim']);
        $this->assertDatabaseMissing('profiles', [
            'user_id' => $user2->id,
        ]);
    }

    /**
     * Test C: Mahasiswa can update their own profile without being rejected because their own NIM already exists.
     */
    public function test_mahasiswa_can_update_own_profile_without_duplicate_error(): void
    {
        $user = $this->createParticipantUser();
        $profile = Profile::create([
            'user_id'          => $user->id,
            'participant_type' => ParticipantType::University,
            'nik'              => '3523143010060001',
            'nama_lengkap'     => 'Bagus Dwi Mahasiswa',
            'nis_nim'          => '24050974001',
            'nim'              => '24050974001',
            'tempat_lahir'     => 'Tuban',
            'tanggal_lahir'    => '2002-05-15',
            'jenis_kelamin'    => JenisKelamin::LakiLaki,
            'alamat'           => 'Jl. Veteran No. 10 Tuban',
            'no_telepon'       => '081234567890',
            'institusi'        => 'Universitas Negeri Surabaya',
            'jurusan'          => 'Teknik Informatika',
            'semester'         => 6,
            'tahun_angkatan'   => '2022',
        ]);

        $updatePayload = [
            'participant_type' => 'university',
            'nik'              => '3523143010060001',
            'nama_lengkap'     => 'Bagus Dwi Updated',
            'nis_nim'          => '24050974001', // keeping same NIM
            'tempat_lahir'     => 'Tuban',
            'tanggal_lahir'    => '2002-05-15',
            'jenis_kelamin'    => JenisKelamin::LakiLaki->value,
            'alamat'           => 'Jl. Sunan Kalijaga No. 99 Tuban',
            'no_telepon'       => '081234567899',
            'institusi'        => 'Universitas Negeri Surabaya',
            'jurusan'          => 'Teknik Informatika',
            'semester'         => 7,
            'tahun_angkatan'   => '2022',
        ];

        $response = $this->actingAs($user)
            ->put(route('participant.profile.update'), $updatePayload);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('participant.profile.index'));

        $this->assertDatabaseHas('profiles', [
            'id'           => $profile->id,
            'nama_lengkap' => 'Bagus Dwi Updated',
            'semester'     => 7,
            'alamat'       => 'Jl. Sunan Kalijaga No. 99 Tuban',
        ]);
    }

    /**
     * Test D: Siswa profile creation succeeds without an NIM.
     */
    public function test_siswa_profile_creation_succeeds_without_nim(): void
    {
        $user = $this->createParticipantUser();

        $payload = [
            'participant_type' => 'student',
            'nik'              => '3523143010060003',
            'nama_lengkap'     => 'Siswa SMK Hebat',
            'tempat_lahir'     => 'Tuban',
            'tanggal_lahir'    => '2007-08-10',
            'jenis_kelamin'    => JenisKelamin::LakiLaki->value,
            'alamat'           => 'Jl. Dr. Wahidin Sudirohusodo Tuban',
            'no_telepon'       => '085712345678',
            'institusi'        => 'SMKN 1 Tuban',
            'jurusan'          => 'Teknik Komputer dan Jaringan',
            'tahun_angkatan'   => '2024',
        ];

        $response = $this->actingAs($user)
            ->post(route('participant.profile.store'), $payload);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('participant.onboarding.success'));

        $this->assertDatabaseHas('profiles', [
            'user_id'          => $user->id,
            'participant_type' => 'student',
            'nik'              => '3523143010060003',
            'nis_nim'          => null,
            'nim'              => null,
            'institusi'        => 'SMKN 1 Tuban',
            'jurusan'          => 'Teknik Komputer dan Jaringan',
            'semester'         => null,
        ]);
    }

    /**
     * Test E: Siswa profile creation does NOT trigger NIM uniqueness validation.
     */
    public function test_siswa_profile_creation_does_not_trigger_nim_uniqueness_validation(): void
    {
        // Existing Mahasiswa with NIM '24050974001'
        $mhs = $this->createParticipantUser();
        Profile::create([
            'user_id'          => $mhs->id,
            'participant_type' => ParticipantType::University,
            'nik'              => '3523143010060010',
            'nama_lengkap'     => 'Mahasiswa Senior',
            'nis_nim'          => '24050974001',
            'nim'              => '24050974001',
            'tempat_lahir'     => 'Tuban',
            'tanggal_lahir'    => '2001-01-01',
            'jenis_kelamin'    => JenisKelamin::LakiLaki,
            'alamat'           => 'Jl. Pemuda No. 1 Tuban',
            'no_telepon'       => '081234567890',
            'institusi'        => 'UNESA',
            'jurusan'          => 'TI',
            'semester'         => 8,
            'tahun_angkatan'   => '2021',
        ]);

        // First Siswa without NIM
        $siswa1 = $this->createParticipantUser();
        $this->actingAs($siswa1)->post(route('participant.profile.store'), [
            'participant_type' => 'student',
            'nik'              => '3523143010060011',
            'nama_lengkap'     => 'Siswa Pertama',
            'tempat_lahir'     => 'Tuban',
            'tanggal_lahir'    => '2007-01-01',
            'jenis_kelamin'    => JenisKelamin::Perempuan->value,
            'alamat'           => 'Jl. Ronggolawe Tuban',
            'no_telepon'       => '081234567891',
            'institusi'        => 'SMKN 2 Tuban',
            'jurusan'          => 'RPL',
            'tahun_angkatan'   => '2024',
        ])->assertSessionHasNoErrors();

        // Second Siswa without NIM (should succeed even though another profile has nim=null)
        $siswa2 = $this->createParticipantUser();
        $response = $this->actingAs($siswa2)->post(route('participant.profile.store'), [
            'participant_type' => 'student',
            'nik'              => '3523143010060012',
            'nama_lengkap'     => 'Siswa Kedua',
            'tempat_lahir'     => 'Tuban',
            'tanggal_lahir'    => '2007-02-02',
            'jenis_kelamin'    => JenisKelamin::LakiLaki->value,
            'alamat'           => 'Jl. Panglima Sudirman Tuban',
            'no_telepon'       => '081234567892',
            'institusi'        => 'SMK TKM Tuban',
            'jurusan'          => 'TKJ',
            'tahun_angkatan'   => '2024',
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('participant.onboarding.success'));

        $this->assertDatabaseHas('profiles', [
            'user_id' => $siswa2->id,
            'nim'     => null,
            'nis_nim' => null,
        ]);
    }

    /**
     * Test F: Siswa profile can have NULL NIM in database.
     */
    public function test_siswa_profile_can_have_null_nim(): void
    {
        $user = $this->createParticipantUser();

        $this->actingAs($user)->post(route('participant.profile.store'), [
            'participant_type' => 'student',
            'nik'              => '3523143010060020',
            'nama_lengkap'     => 'Siswa Profil Null NIM',
            'nis_nim'          => null,
            'tempat_lahir'     => 'Tuban',
            'tanggal_lahir'    => '2007-03-03',
            'jenis_kelamin'    => JenisKelamin::LakiLaki->value,
            'alamat'           => 'Jl. Hayam Wuruk No. 5 Tuban',
            'no_telepon'       => '085812345678',
            'institusi'        => 'SMKN 1 Tuban',
            'jurusan'          => 'Multimedia',
            'tahun_angkatan'   => '2024',
        ])->assertSessionHasNoErrors();

        $profile = Profile::where('user_id', $user->id)->first();
        $this->assertNotNull($profile);
        $this->assertNull($profile->nim);
        $this->assertNull($profile->semester);
        $this->assertTrue($profile->isSiswa());
        $this->assertSame('Siswa / SMK', $profile->participantTypeLabel());
        $this->assertSame('-', $profile->numberValue());
    }

    /**
     * Test G: Siswa requires school information (institusi and jurusan).
     */
    public function test_siswa_requires_school_information(): void
    {
        $user = $this->createParticipantUser();

        $payload = [
            'participant_type' => 'student',
            'nik'              => '3523143010060030',
            'nama_lengkap'     => 'Siswa Tanpa Sekolah',
            'tempat_lahir'     => 'Tuban',
            'tanggal_lahir'    => '2007-04-04',
            'jenis_kelamin'    => JenisKelamin::LakiLaki->value,
            'alamat'           => 'Jl. Diponegoro No. 10 Tuban',
            'no_telepon'       => '085812345679',
            'institusi'        => '', // missing school
            'jurusan'          => '', // missing major
            'tahun_angkatan'   => '2024',
        ];

        $response = $this->actingAs($user)
            ->post(route('participant.profile.store'), $payload);

        $response->assertSessionHasErrors(['institusi', 'jurusan']);
        $this->assertDatabaseMissing('profiles', [
            'user_id' => $user->id,
        ]);
    }

    /**
     * Test H: Invalid participant type cannot bypass validation.
     */
    public function test_invalid_participant_type_cannot_bypass_validation(): void
    {
        $user = $this->createParticipantUser();

        $payload = [
            'participant_type' => 'unknown_hacker_category',
            'nik'              => '3523143010060040',
            'nama_lengkap'     => 'Penyerang Sistem',
            'tempat_lahir'     => 'Tuban',
            'tanggal_lahir'    => '2000-01-01',
            'jenis_kelamin'    => JenisKelamin::LakiLaki->value,
            'alamat'           => 'Jl. Pahlawan No. 1 Tuban',
            'no_telepon'       => '081234567800',
            'institusi'        => 'Institusi Anonim',
            'jurusan'          => 'Informatika',
            'tahun_angkatan'   => '2024',
        ];

        $response = $this->actingAs($user)
            ->post(route('participant.profile.store'), $payload);

        $response->assertSessionHasErrors('participant_type');
    }

    /**
     * Test I: Existing participant records remain compatible with enum and helpers.
     */
    public function test_existing_participant_records_remain_compatible(): void
    {
        $mhsUser = $this->createParticipantUser();
        $mhsProfile = Profile::create([
            'user_id'          => $mhsUser->id,
            'participant_type' => 'mahasiswa', // legacy string value
            'nik'              => '3523143010060050',
            'nama_lengkap'     => 'Legacy Mahasiswa',
            'nis_nim'          => '123456789123',
            'nim'              => '123456789123',
            'tempat_lahir'     => 'Bawean',
            'tanggal_lahir'    => '2001-08-11',
            'jenis_kelamin'    => JenisKelamin::LakiLaki,
            'alamat'           => 'Jl. Bawean Indah Tuban',
            'no_telepon'       => '081234567801',
            'institusi'        => 'SMKN 11 Bawean',
            'jurusan'          => 'Teknik Komputer',
            'semester'         => 6,
            'tahun_angkatan'   => '2021',
        ]);

        $this->assertTrue($mhsProfile->isMahasiswa());
        $this->assertFalse($mhsProfile->isSiswa());
        $this->assertSame('Mahasiswa', $mhsProfile->participantTypeLabel());
        $this->assertSame('Universitas / Perguruan Tinggi', $mhsProfile->institutionLabel());
        $this->assertSame('NIM', $mhsProfile->numberLabel());
        $this->assertSame('123456789123', $mhsProfile->numberValue());
    }
}
