<?php

namespace Tests\Feature\Participant;

use App\Enums\JenisKelamin;
use App\Enums\ParticipantType;
use App\Enums\UserRole;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BrowserRegressionTest extends TestCase
{
    use RefreshDatabase;

    protected function createPeserta(array $attr = []): User
    {
        return User::factory()->create(array_merge([
            'role' => UserRole::Peserta,
        ], $attr));
    }

    /**
     * Scenario 1: MAHASISWA - valid new NIM
     */
    public function test_scenario_1_mahasiswa_valid_new_nim(): void
    {
        $user = $this->createPeserta();

        $response = $this->actingAs($user)->post(route('participant.profile.store'), [
            'participant_type' => 'university',
            'nama_lengkap'     => 'Bagus Dwi Mahasiswa',
            'nik'              => '3523143010060001',
            'tempat_lahir'     => 'Tuban',
            'tanggal_lahir'    => '2002-05-15',
            'jenis_kelamin'    => JenisKelamin::LakiLaki->value,
            'institusi'        => 'Universitas Negeri Surabaya',
            'nis_nim'          => '24050974001',
            'jurusan'          => 'Teknik Informatika',
            'semester'         => 6,
            'tahun_angkatan'   => '2022',
            'no_telepon'       => '081234567890',
            'alamat'           => 'Jl. Veteran No. 10 Tuban',
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('participant.onboarding.success'));

        $profile = Profile::where('user_id', $user->id)->first();
        $this->assertNotNull($profile);
        $this->assertSame('24050974001', $profile->nim);
        $this->assertSame('24050974001', $profile->nis_nim);
        $this->assertTrue($profile->isMahasiswa());
    }

    /**
     * Scenario 2: MAHASISWA - duplicate NIM
     */
    public function test_scenario_2_mahasiswa_duplicate_nim_rejected(): void
    {
        $mhs1 = $this->createPeserta();
        Profile::create([
            'user_id'          => $mhs1->id,
            'participant_type' => ParticipantType::University,
            'nik'              => '3523143010060001',
            'nama_lengkap'     => 'Mahasiswa Pertama',
            'nis_nim'          => '24050974001',
            'nim'              => '24050974001',
            'tempat_lahir'     => 'Tuban',
            'tanggal_lahir'    => '2002-05-15',
            'jenis_kelamin'    => JenisKelamin::LakiLaki,
            'institusi'        => 'UNESA',
            'jurusan'          => 'TI',
            'semester'         => 6,
            'tahun_angkatan'   => '2022',
            'no_telepon'       => '081234567890',
            'alamat'           => 'Jl. Veteran Tuban',
        ]);

        $mhs2 = $this->createPeserta();
        $response = $this->actingAs($mhs2)->post(route('participant.profile.store'), [
            'participant_type' => 'university',
            'nama_lengkap'     => 'Mahasiswa Kedua Duplicate',
            'nik'              => '3523143010060002',
            'tempat_lahir'     => 'Tuban',
            'tanggal_lahir'    => '2002-06-20',
            'jenis_kelamin'    => JenisKelamin::LakiLaki->value,
            'institusi'        => 'UNESA',
            'nis_nim'          => '24050974001', // duplicate
            'jurusan'          => 'TI',
            'semester'         => 6,
            'tahun_angkatan'   => '2022',
            'no_telepon'       => '081234567891',
            'alamat'           => 'Jl. Basuki Rahmat Tuban',
        ]);

        $response->assertSessionHasErrors(['nis_nim', 'nim']);
        $this->assertDatabaseMissing('profiles', ['user_id' => $mhs2->id]);
    }

    /**
     * Scenario 3: MAHASISWA - update own profile
     */
    public function test_scenario_3_mahasiswa_update_own_profile(): void
    {
        $user = $this->createPeserta();
        $profile = Profile::create([
            'user_id'          => $user->id,
            'participant_type' => ParticipantType::University,
            'nik'              => '3523143010060001',
            'nama_lengkap'     => 'Mahasiswa Asli',
            'nis_nim'          => '24050974001',
            'nim'              => '24050974001',
            'tempat_lahir'     => 'Tuban',
            'tanggal_lahir'    => '2002-05-15',
            'jenis_kelamin'    => JenisKelamin::LakiLaki,
            'institusi'        => 'UNESA',
            'jurusan'          => 'TI',
            'semester'         => 6,
            'tahun_angkatan'   => '2022',
            'no_telepon'       => '081234567890',
            'alamat'           => 'Jl. Veteran Tuban',
        ]);

        $response = $this->actingAs($user)->put(route('participant.profile.update'), [
            'participant_type' => 'university',
            'nama_lengkap'     => 'Mahasiswa Updated Name',
            'nik'              => '3523143010060001',
            'tempat_lahir'     => 'Tuban',
            'tanggal_lahir'    => '2002-05-15',
            'jenis_kelamin'    => JenisKelamin::LakiLaki->value,
            'institusi'        => 'UNESA',
            'nis_nim'          => '24050974001', // same NIM
            'jurusan'          => 'TI',
            'semester'         => 7,
            'tahun_angkatan'   => '2022',
            'no_telepon'       => '081234567899',
            'alamat'           => 'Jl. Sunan Kalijaga Tuban',
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('participant.profile.index'));

        $profile->refresh();
        $this->assertSame('Mahasiswa Updated Name', $profile->nama_lengkap);
        $this->assertSame(7, $profile->semester);
        $this->assertSame('24050974001', $profile->nim);
    }

    /**
     * Scenario 4: SISWA / SMK - without NIM
     */
    public function test_scenario_4_siswa_without_nim(): void
    {
        $user = $this->createPeserta();

        $response = $this->actingAs($user)->post(route('participant.profile.store'), [
            'participant_type' => 'student',
            'nama_lengkap'     => 'Siswa SMK Hebat',
            'nik'              => '3523143010060003',
            'tempat_lahir'     => 'Tuban',
            'tanggal_lahir'    => '2007-08-10',
            'jenis_kelamin'    => JenisKelamin::LakiLaki->value,
            'institusi'        => 'SMKN 1 Tuban',
            'jurusan'          => 'Teknik Komputer dan Jaringan',
            'tahun_angkatan'   => '2024',
            'no_telepon'       => '085712345678',
            'alamat'           => 'Jl. Dr. Wahidin Tuban',
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('participant.onboarding.success'));

        $profile = Profile::where('user_id', $user->id)->first();
        $this->assertNotNull($profile);
        $this->assertNull($profile->nim);
        $this->assertNull($profile->nis_nim);
        $this->assertNull($profile->semester);
        $this->assertTrue($profile->isSiswa());
    }

    /**
     * Scenario 5: SISWA / SMK - with NIS/NISN
     */
    public function test_scenario_5_siswa_with_nis(): void
    {
        $user = $this->createPeserta();

        $response = $this->actingAs($user)->post(route('participant.profile.store'), [
            'participant_type' => 'student',
            'nama_lengkap'     => 'Siswa SMK Dengan NIS',
            'nik'              => '3523143010060004',
            'tempat_lahir'     => 'Tuban',
            'tanggal_lahir'    => '2007-08-10',
            'jenis_kelamin'    => JenisKelamin::Perempuan->value,
            'institusi'        => 'SMKN 2 Tuban',
            'nis_nim'          => '220054321',
            'jurusan'          => 'Rekayasa Perangkat Lunak',
            'tahun_angkatan'   => '2024',
            'no_telepon'       => '085712345679',
            'alamat'           => 'Jl. Ronggolawe Tuban',
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('participant.onboarding.success'));

        $profile = Profile::where('user_id', $user->id)->first();
        $this->assertNotNull($profile);
        $this->assertNull($profile->nim);
        $this->assertSame('220054321', $profile->nis_nim);
        $this->assertTrue($profile->isSiswa());
    }

    /**
     * Scenario 6: Corruption Check across rendered views
     */
    public function test_scenario_6_no_corruption_in_rendered_views(): void
    {
        $user = $this->createPeserta();

        $urls = [
            route('participant.profile.create', ['type' => 'university']),
            route('participant.profile.create', ['type' => 'student']),
            route('participant.profile.choose-type'),
            route('participant.onboarding.choose-type'),
        ];

        foreach ($urls as $url) {
            $resp = $this->actingAs($user)->get($url);
            $resp->assertOk();
            $resp->assertDontSee('[object HTMLInputElement]', false);
            $resp->assertDontSee('[object', false);
        }
    }
}
