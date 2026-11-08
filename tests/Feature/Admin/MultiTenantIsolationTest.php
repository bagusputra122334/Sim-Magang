<?php

namespace Tests\Feature\Admin;

use App\Enums\PositionStatus;
use App\Enums\RegistrationStatus;
use App\Enums\UserRole;
use App\Models\Division;
use App\Models\Opd;
use App\Models\Position;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MultiTenantIsolationTest extends TestCase
{
    use RefreshDatabase;

    public function test_role_hierarchy_methods_work_as_expected(): void
    {
        $opd = Opd::factory()->create();

        // 1. Super Admin with explicit super_admin role
        $superAdmin = User::factory()->create([
            'role'   => UserRole::SuperAdmin,
            'opd_id' => $opd->id,
        ]);
        $this->assertTrue($superAdmin->isAdmin());
        $this->assertTrue($superAdmin->isSuperAdmin());
        $this->assertFalse($superAdmin->isAdminOpd());

        // 2. Super Admin with admin role and null opd_id (Pusat)
        $rootAdmin = User::factory()->create([
            'role'   => UserRole::Admin,
            'opd_id' => null,
        ]);
        $this->assertTrue($rootAdmin->isAdmin());
        $this->assertTrue($rootAdmin->isSuperAdmin());
        $this->assertFalse($rootAdmin->isAdminOpd());

        // 3. Admin OPD (role admin with specific opd_id)
        $adminOpd = User::factory()->create([
            'role'   => UserRole::Admin,
            'opd_id' => $opd->id,
        ]);
        $this->assertTrue($adminOpd->isAdmin());
        $this->assertFalse($adminOpd->isSuperAdmin());
        $this->assertTrue($adminOpd->isAdminOpd());

        // 4. Regular participant & supervisor
        $peserta = User::factory()->create(['role' => UserRole::Peserta]);
        $this->assertFalse($peserta->isAdmin());
        $this->assertFalse($peserta->isSuperAdmin());
        $this->assertFalse($peserta->isAdminOpd());

        $pembimbing = User::factory()->create([
            'role'   => UserRole::Pembimbing,
            'opd_id' => $opd->id,
        ]);
        $this->assertFalse($pembimbing->isAdmin());
        $this->assertFalse($pembimbing->isSuperAdmin());
        $this->assertFalse($pembimbing->isAdminOpd());
    }

    public function test_admin_opd_only_sees_positions_and_divisions_of_their_opd(): void
    {
        $opd1 = Opd::create([
            'name' => 'Dinas Kesehatan Kabupaten Tuban',
            'code' => 'dinkes',
            'slug' => 'dinkes',
        ]);
        $opd2 = Opd::create([
            'name' => 'Dinas Pendidikan Kabupaten Tuban',
            'code' => 'disdik',
            'slug' => 'disdik',
        ]);

        $admin1 = User::factory()->create([
            'role'   => UserRole::Admin,
            'opd_id' => $opd1->id,
        ]);
        $admin2 = User::factory()->create([
            'role'   => UserRole::Admin,
            'opd_id' => $opd2->id,
        ]);
        $superAdmin = User::factory()->create([
            'role'   => UserRole::SuperAdmin,
            'opd_id' => null,
        ]);

        $pos1 = Position::create([
            'nama_posisi' => 'Dokter Umum Magang',
            'slug'        => 'dokter-umum-magang',
            'deskripsi'   => 'Deskripsi 1',
            'kuota'       => 2,
            'status'      => PositionStatus::Aktif,
            'opd_id'      => $opd1->id,
        ]);

        $pos2 = Position::create([
            'nama_posisi' => 'Guru IT Magang',
            'slug'        => 'guru-it-magang',
            'deskripsi'   => 'Deskripsi 2',
            'kuota'       => 2,
            'status'      => PositionStatus::Aktif,
            'opd_id'      => $opd2->id,
        ]);

        $div1 = Division::create(['nama_divisi' => 'Bidang Yankes', 'opd_id' => $opd1->id]);
        $div2 = Division::create(['nama_divisi' => 'Bidang Kurikulum', 'opd_id' => $opd2->id]);

        // When Admin OPD 1 is logged in
        $this->actingAs($admin1);
        $this->assertCount(1, Position::all());
        $this->assertTrue(Position::all()->contains($pos1));
        $this->assertFalse(Position::all()->contains($pos2));
        $this->assertCount(1, Division::all());
        $this->assertTrue(Division::all()->contains($div1));

        // When Admin OPD 2 is logged in
        $this->actingAs($admin2);
        $this->assertCount(1, Position::all());
        $this->assertTrue(Position::all()->contains($pos2));
        $this->assertFalse(Position::all()->contains($pos1));
        $this->assertCount(1, Division::all());
        $this->assertTrue(Division::all()->contains($div2));

        // When Super Admin is logged in: sees all
        $this->actingAs($superAdmin);
        $this->assertCount(2, Position::all());
        $this->assertCount(2, Division::all());
    }

    public function test_admin_opd_auto_fills_opd_id_on_creating_position_and_division(): void
    {
        $opd = Opd::factory()->create();
        $admin = User::factory()->create([
            'role'   => UserRole::Admin,
            'opd_id' => $opd->id,
        ]);

        $this->actingAs($admin);

        $pos = Position::create([
            'nama_posisi' => 'Staff Apoteker Magang',
            'slug'        => 'staff-apoteker-magang',
            'deskripsi'   => 'Deskripsi farmasi',
            'kuota'       => 3,
            'status'      => PositionStatus::Aktif,
        ]);

        $div = Division::create([
            'nama_divisi' => 'Bidang Farmasi',
        ]);

        $this->assertEquals($opd->id, $pos->opd_id);
        $this->assertEquals($opd->id, $div->opd_id);
    }

    public function test_admin_opd_only_sees_registrations_for_their_opd(): void
    {
        $opd1 = Opd::create(['name' => 'OPD 1', 'code' => 'opd-1', 'slug' => 'opd-1']);
        $opd2 = Opd::create(['name' => 'OPD 2', 'code' => 'opd-2', 'slug' => 'opd-2']);

        $admin1 = User::factory()->create(['role' => UserRole::Admin, 'opd_id' => $opd1->id]);
        $admin2 = User::factory()->create(['role' => UserRole::Admin, 'opd_id' => $opd2->id]);

        $pos1 = Position::create([
            'nama_posisi' => 'Posisi 1',
            'slug'        => 'pos-1',
            'deskripsi'   => 'D1',
            'kuota'       => 2,
            'status'      => PositionStatus::Aktif,
            'opd_id'      => $opd1->id,
        ]);

        $pos2 = Position::create([
            'nama_posisi' => 'Posisi 2',
            'slug'        => 'pos-2',
            'deskripsi'   => 'D2',
            'kuota'       => 2,
            'status'      => PositionStatus::Aktif,
            'opd_id'      => $opd2->id,
        ]);

        $userA = User::factory()->create();
        $userB = User::factory()->create();

        $reg1 = Registration::create([
            'nomor_pendaftaran'    => 'MAGANG-2026-0001',
            'user_id'              => $userA->id,
            'position_id'          => $pos1->id,
            'cv_path'              => 'cv/1.pdf',
            'surat_pengantar_path' => 'sp/1.pdf',
            'status'               => RegistrationStatus::Submitted,
        ]);

        $reg2 = Registration::create([
            'nomor_pendaftaran'    => 'MAGANG-2026-0002',
            'user_id'              => $userB->id,
            'position_id'          => $pos2->id,
            'cv_path'              => 'cv/2.pdf',
            'surat_pengantar_path' => 'sp/2.pdf',
            'status'               => RegistrationStatus::Submitted,
        ]);

        $this->actingAs($admin1);
        $this->assertCount(1, Registration::all());
        $this->assertTrue(Registration::all()->contains($reg1));

        $this->actingAs($admin2);
        $this->assertCount(1, Registration::all());
        $this->assertTrue(Registration::all()->contains($reg2));
    }

    public function test_pembimbing_store_assigns_opd_id_of_authenticated_admin_opd(): void
    {
        $opd = Opd::factory()->create();
        $admin = User::factory()->create([
            'role'   => UserRole::Admin,
            'opd_id' => $opd->id,
        ]);

        $this->actingAs($admin);

        $division = Division::create(['nama_divisi' => 'Seksi Jaringan']);

        $response = $this->post(route('admin.pembimbing.store'), [
            'name'        => 'Pembimbing Dinkes',
            'nip'         => '198701012011011005',
            'email'       => 'pembimbing.dinkes@tubankab.go.id',
            'password'    => 'Password123!',
            'division_id' => $division->id,
        ]);

        $response->assertRedirect(route('admin.pembimbing.index'));

        $this->assertDatabaseHas('users', [
            'email'  => 'pembimbing.dinkes@tubankab.go.id',
            'nip'    => '198701012011011005',
            'opd_id' => $opd->id,
            'role'   => 'pembimbing',
        ]);
    }
}
