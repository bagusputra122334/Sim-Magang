<?php

namespace Tests\Feature\Admin;

use App\Enums\PositionStatus;
use App\Enums\RegistrationStatus;
use App\Enums\UserRole;
use App\Models\Opd;
use App\Models\Position;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OpdManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $superAdmin;
    protected User $adminOpd;
    protected Opd $opdDiskominfo;
    protected Opd $opdDinkes;

    protected function setUp(): void
    {
        parent::setUp();

        $this->opdDiskominfo = Opd::factory()->create([
            'name' => 'Dinas Komunikasi dan Informatika',
            'code' => 'diskominfo',
            'slug' => 'diskominfo',
            'is_active' => true,
        ]);

        $this->opdDinkes = Opd::factory()->create([
            'name' => 'Dinas Kesehatan',
            'code' => 'dinkes',
            'slug' => 'dinkes',
            'is_active' => true,
        ]);

        $this->superAdmin = User::factory()->create([
            'name' => 'Super Administrator',
            'email' => 'superadmin@tubankab.go.id',
            'role' => UserRole::SuperAdmin,
            'opd_id' => null,
        ]);

        $this->adminOpd = User::factory()->create([
            'name' => 'Admin Dinkes',
            'email' => 'admin.dinkes@tubankab.go.id',
            'role' => UserRole::Admin,
            'opd_id' => $this->opdDinkes->id,
        ]);
    }

    public function test_super_admin_can_view_opds_index(): void
    {
        $response = $this->actingAs($this->superAdmin)->get(route('admin.opds.index'));

        $response->assertOk();
        $response->assertSee('Manajemen Master OPD');
        $response->assertSee('Dinas Komunikasi dan Informatika');
        $response->assertSee('Dinas Kesehatan');
    }

    public function test_admin_opd_cannot_access_opds_module_and_gets_forbidden(): void
    {
        $response = $this->actingAs($this->adminOpd)->get(route('admin.opds.index'));
        $response->assertForbidden();

        $createResponse = $this->actingAs($this->adminOpd)->get(route('admin.opds.create'));
        $createResponse->assertForbidden();

        $adminMgmtResponse = $this->actingAs($this->adminOpd)->get(route('admin.opd-admins.index'));
        $adminMgmtResponse->assertForbidden();
    }

    public function test_super_admin_can_create_new_opd(): void
    {
        $response = $this->actingAs($this->superAdmin)->post(route('admin.opds.store'), [
            'name'                => 'Dinas Pendidikan Kabupaten Tuban',
            'code'                => 'disdik',
            'slug'                => 'disdik-tuban',
            'address'             => 'Jl. Dr. Wahidin Sudirohusodo No. 100 Tuban',
            'phone'               => '0356-321111',
            'email'               => 'disdik@tubankab.go.id',
            'head_of_office_name' => 'Drs. H. Abdul Somad, M.Pd',
            'head_of_office_nip'  => '197001011995031001',
            'is_active'           => '1',
        ]);

        $response->assertRedirect(route('admin.opds.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('opds', [
            'name' => 'Dinas Pendidikan Kabupaten Tuban',
            'code' => 'disdik',
            'slug' => 'disdik-tuban',
            'is_active' => 1,
        ]);
    }

    public function test_super_admin_can_edit_and_toggle_opd_status(): void
    {
        $response = $this->actingAs($this->superAdmin)->put(route('admin.opds.update', $this->opdDinkes), [
            'name'                => 'Dinas Kesehatan P2KB Tuban',
            'code'                => 'dinkes-p2kb',
            'slug'                => 'dinkes-p2kb',
            'address'             => 'Jl. Brawijaya Tuban',
            'is_active'           => '1',
        ]);

        $response->assertRedirect(route('admin.opds.index'));
        $this->assertDatabaseHas('opds', [
            'id'   => $this->opdDinkes->id,
            'name' => 'Dinas Kesehatan P2KB Tuban',
            'code' => 'dinkes-p2kb',
        ]);

        // Toggle to inactive
        $toggleResponse = $this->actingAs($this->superAdmin)->get(route('admin.opds.toggle-status', $this->opdDinkes));
        $toggleResponse->assertRedirect(route('admin.opds.index'));

        $this->assertFalse((bool) $this->opdDinkes->fresh()->is_active);
    }

    public function test_super_admin_can_manage_opd_admin_accounts(): void
    {
        $response = $this->actingAs($this->superAdmin)->get(route('admin.opd-admins.index'));
        $response->assertOk();
        $response->assertSee('Manajemen Akun Admin OPD');

        // Create new admin assigned to Dinkes
        $createResponse = $this->actingAs($this->superAdmin)->post(route('admin.opd-admins.store'), [
            'name'           => 'Admin Baru Dinkes',
            'email'          => 'admin2.dinkes@tubankab.go.id',
            'password'       => 'password123',
            'nip'            => '198506072010011005',
            'position_title' => 'Pengelola IT',
            'role'           => 'admin',
            'opd_id'         => $this->opdDinkes->id,
        ]);

        $createResponse->assertRedirect(route('admin.opd-admins.index'));
        $this->assertDatabaseHas('users', [
            'email'  => 'admin2.dinkes@tubankab.go.id',
            'opd_id' => $this->opdDinkes->id,
            'role'   => UserRole::Admin->value,
        ]);

        $newAdmin = User::where('email', 'admin2.dinkes@tubankab.go.id')->firstOrFail();

        // Update admin OPD
        $updateResponse = $this->actingAs($this->superAdmin)->put(route('admin.opd-admins.update', $newAdmin), [
            'name'           => 'Admin Baru Dinkes Updated',
            'email'          => 'admin2.dinkes@tubankab.go.id',
            'role'           => 'admin',
            'opd_id'         => $this->opdDiskominfo->id, // Reassign to Diskominfo
            'position_title' => 'Kepala Seksi',
        ]);

        $updateResponse->assertRedirect(route('admin.opd-admins.index'));
        $this->assertEquals($this->opdDiskominfo->id, $newAdmin->fresh()->opd_id);
    }

    public function test_super_admin_cannot_delete_self(): void
    {
        $response = $this->actingAs($this->superAdmin)->delete(route('admin.opd-admins.destroy', $this->superAdmin));

        $response->assertRedirect(route('admin.opd-admins.index'));
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('users', ['id' => $this->superAdmin->id]);
    }

    public function test_dashboard_renders_for_super_admin_with_opd_filters_and_summaries(): void
    {
        // Create position in Dinkes
        Position::factory()->create([
            'opd_id'      => $this->opdDinkes->id,
            'nama_posisi' => 'Staf Rekam Medis',
            'status'      => PositionStatus::Aktif,
        ]);

        // Create position in Diskominfo
        Position::factory()->create([
            'opd_id'      => $this->opdDiskominfo->id,
            'nama_posisi' => 'Web Developer Diskominfo',
            'status'      => PositionStatus::Aktif,
        ]);

        // Super Admin default view (All OPDs)
        $response = $this->actingAs($this->superAdmin)->get(route('admin.dashboard'));
        $response->assertOk();
        $response->assertSee('Filter Wilayah OPD:');
        $response->assertSee('Ringkasan Formasi dan Pendaftar per OPD di Kabupaten Tuban');

        // Super Admin filtered view
        $filteredResponse = $this->actingAs($this->superAdmin)->get(route('admin.dashboard', ['opd_id' => $this->opdDinkes->id]));
        $filteredResponse->assertOk();
        $filteredResponse->assertSee($this->opdDinkes->name);
    }

    public function test_dashboard_renders_for_admin_opd_scoped_to_their_opd(): void
    {
        $response = $this->actingAs($this->adminOpd)->get(route('admin.dashboard'));

        $response->assertOk();
        // Should not see Super Admin overall OPD filter
        $response->assertDontSee('Filter Wilayah OPD:');
        $response->assertSee($this->opdDinkes->name);
    }

    public function test_super_admin_can_export_opds_pdf(): void
    {
        $response = $this->actingAs($this->superAdmin)->get(route('admin.opds.export_pdf'));

        $response->assertOk();
        $response->assertHeader('content-type', 'application/pdf');
    }

    public function test_super_admin_can_export_opds_pdf_with_filters(): void
    {
        $response = $this->actingAs($this->superAdmin)->get(route('admin.opds.export_pdf', [
            'search' => 'Kesehatan',
            'status' => 'active',
        ]));

        $response->assertOk();
        $response->assertHeader('content-type', 'application/pdf');
    }

    public function test_admin_opd_cannot_export_opds_pdf(): void
    {
        $response = $this->actingAs($this->adminOpd)->get(route('admin.opds.export_pdf'));

        $response->assertForbidden();
    }

    public function test_super_admin_does_not_see_posisi_magang_menu_and_is_redirected(): void
    {
        $dashboard = $this->actingAs($this->superAdmin)->get(route('admin.dashboard'));
        $dashboard->assertOk();
        $dashboard->assertDontSee('<span class="nav-text">Posisi Magang</span>', false);

        $response = $this->actingAs($this->superAdmin)->get(route('admin.positions.index'));
        $response->assertRedirect(route('admin.opds.index'));
    }

    public function test_admin_opd_sees_posisi_magang_menu_and_can_access(): void
    {
        $dashboard = $this->actingAs($this->adminOpd)->get(route('admin.dashboard'));
        $dashboard->assertOk();
        $dashboard->assertSee('<span class="nav-text">Posisi Magang</span>', false);

        $response = $this->actingAs($this->adminOpd)->get(route('admin.positions.index'));
        $response->assertOk();
    }
}
