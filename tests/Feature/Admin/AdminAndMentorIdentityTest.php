<?php

namespace Tests\Feature\Admin;

use App\Enums\PositionStatus;
use App\Models\Position;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAndMentorIdentityTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role'           => 'admin',
            'nip'            => '19800101 200501 1 001',
            'position_title' => 'Kepala Bidang Aplikasi Informatika',
        ]);
    }

    /**
     * Test admin can update own NIP and position title via profile update.
     */
    public function test_admin_can_update_nip_and_position_title(): void
    {
        $response = $this->actingAs($this->admin)
            ->patch(route('profile.update'), [
                'name'           => 'Administrator Utama',
                'email'          => $this->admin->email,
                'nip'            => ' 19850722 201001 2 012 ',
                'position_title' => ' Kepala Bidang SPBE ',
            ]);

        $response->assertRedirect(route('profile.edit'));
        $this->assertDatabaseHas('users', [
            'id'             => $this->admin->id,
            'nip'            => '19850722 201001 2 012',
            'position_title' => 'Kepala Bidang SPBE',
        ]);
    }

    /**
     * Test admin can create a new position with mentor_name and mentor_nip.
     */
    public function test_admin_can_create_position_with_mentor_identity(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('admin.positions.store'), [
                'nama_posisi'   => 'DevOps Engineer SPBE',
                'slug'          => 'devops-engineer-spbe',
                'deskripsi'     => 'Mengelola server & cloud SPBE Diskominfo',
                'kuota'         => 3,
                'status'        => PositionStatus::Aktif->value,
                'mentor_name'   => ' Drs. Eko Prasetyo, M.Kom ',
                'mentor_nip'    => ' 19820315 200801 1 004 ',
            ]);

        $response->assertRedirect(route('admin.positions.index'));
        $this->assertDatabaseHas('positions', [
            'nama_posisi' => 'DevOps Engineer SPBE',
            'mentor_name' => 'Drs. Eko Prasetyo, M.Kom',
            'mentor_nip'  => '19820315 200801 1 004',
        ]);
    }

    /**
     * Test admin can update an existing position's mentor identity.
     */
    public function test_admin_can_update_position_mentor_identity(): void
    {
        $position = Position::create([
            'nama_posisi'   => 'UI/UX Designer',
            'slug'          => 'ui-ux-designer',
            'deskripsi'     => 'Desain antarmuka aplikasi',
            'status'        => PositionStatus::Aktif,
            'mentor_name'   => 'Nama Lama',
            'mentor_nip'    => '11111111',
        ]);

        $response = $this->actingAs($this->admin)
            ->put(route('admin.positions.update', $position), [
                'nama_posisi'   => 'UI/UX Designer',
                'slug'          => 'ui-ux-designer',
                'deskripsi'     => 'Desain antarmuka aplikasi',
                'status'        => PositionStatus::Aktif->value,
                'mentor_name'   => 'Siti Rahmawati, S.ST, M.T.',
                'mentor_nip'    => '19850722 201001 2 012',
            ]);

        $response->assertRedirect(route('admin.positions.index'));
        $this->assertDatabaseHas('positions', [
            'id'          => $position->id,
            'mentor_name' => 'Siti Rahmawati, S.ST, M.T.',
            'mentor_nip'  => '19850722 201001 2 012',
        ]);
    }
}
