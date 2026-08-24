<?php

namespace Tests\Feature\Admin;

use App\Enums\PositionStatus;
use App\Models\Position;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PositionValidationAndNumberingTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'admin',
        ]);
    }

    /**
     * Test 1: Membuat posisi baru dengan nama yang sama persis seperti posisi AKTIF
     * akan menghasilkan validation error (422/session error).
     */
    public function test_cannot_create_position_with_duplicate_name_of_active_position(): void
    {
        Position::create([
            'nama_posisi'   => 'Frontend Engineer',
            'slug'          => 'frontend-engineer',
            'deskripsi'     => 'Deskripsi frontend engineer',
            'kualifikasi'   => 'Kualifikasi',
            'kuota'         => 3,
            'status'        => PositionStatus::Aktif,
            'tanggal_buka'  => now()->toDateString(),
            'tanggal_tutup' => now()->addYear()->toDateString(),
        ]);

        $response = $this->actingAs($this->admin)
            ->post(route('admin.positions.store'), [
                'nama_posisi' => 'Frontend Engineer',
                'slug'        => 'frontend-engineer-baru',
                'status'      => PositionStatus::Aktif->value,
            ]);

        $response->assertSessionHasErrors(['nama_posisi']);
    }

    /**
     * Test 2: Membuat posisi baru dengan nama yang sama seperti posisi yang SUDAH DI-SOFT DELETE
     * HARUS BERHASIL (tidak terblokir oleh data soft-deleted).
     */
    public function test_can_create_position_with_same_name_as_soft_deleted_position(): void
    {
        $deletedPos = Position::create([
            'nama_posisi'   => 'DevOps Specialist',
            'slug'          => 'devops-specialist',
            'deskripsi'     => 'Deskripsi devops lama',
            'kualifikasi'   => 'Kualifikasi',
            'kuota'         => 2,
            'status'        => PositionStatus::Aktif,
            'tanggal_buka'  => now()->toDateString(),
            'tanggal_tutup' => now()->addYear()->toDateString(),
        ]);

        $deletedPos->delete(); // Soft delete

        $this->assertSoftDeleted('positions', ['id' => $deletedPos->id]);

        $response = $this->actingAs($this->admin)
            ->post(route('admin.positions.store'), [
                'nama_posisi' => 'DevOps Specialist',
                'slug'        => 'devops-specialist-baru',
                'deskripsi'   => 'Deskripsi devops baru',
                'status'      => PositionStatus::Aktif->value,
            ]);

        $response->assertRedirect(route('admin.positions.index'));
        $this->assertDatabaseHas('positions', [
            'nama_posisi' => 'DevOps Specialist',
            'slug'        => 'devops-specialist-baru',
            'deleted_at'  => null,
        ]);
    }

    /**
     * Test 3: Update posisi diri sendiri tanpa mengubah nama
     * HARUS BERHASIL (ignore ID sendiri).
     */
    public function test_can_update_own_position_without_changing_name(): void
    {
        $pos = Position::create([
            'nama_posisi'   => 'UI/UX Designer',
            'slug'          => 'ui-ux-designer',
            'deskripsi'     => 'Deskripsi UI UX',
            'kualifikasi'   => 'Figma',
            'kuota'         => 4,
            'status'        => PositionStatus::Aktif,
            'tanggal_buka'  => now()->toDateString(),
            'tanggal_tutup' => now()->addYear()->toDateString(),
        ]);

        $response = $this->actingAs($this->admin)
            ->put(route('admin.positions.update', $pos->id), [
                'nama_posisi' => 'UI/UX Designer', // Sama
                'slug'        => 'ui-ux-designer',
                'deskripsi'   => 'Deskripsi UI UX Terkini',
                'status'      => PositionStatus::Aktif->value,
            ]);

        $response->assertRedirect(route('admin.positions.index'));
        $pos->refresh();
        $this->assertEquals('Deskripsi UI UX Terkini', $pos->deskripsi);
    }

    /**
     * Test 4: Perhitungan nomor urut sekuensial tampilan (halaman pertama & halaman paginasi lanjutan).
     */
    public function test_sequential_number_calculation_on_pagination_pages(): void
    {
        // Buat 15 posisi
        for ($i = 1; $i <= 15; $i++) {
            Position::create([
                'nama_posisi'   => 'Posisi '.$i,
                'slug'          => 'posisi-'.$i,
                'deskripsi'     => 'Deskripsi posisi '.$i,
                'status'        => PositionStatus::Aktif,
                'tanggal_buka'  => now()->toDateString(),
                'tanggal_tutup' => now()->addYear()->toDateString(),
            ]);
        }

        // Halaman 1 (per_page = 10)
        $respPage1 = $this->actingAs($this->admin)
            ->get(route('admin.positions.index', ['per_page' => 10, 'page' => 1]));

        $respPage1->assertStatus(200);
        $paginatorPage1 = $respPage1->viewData('positions');

        $this->assertEquals(1, $paginatorPage1->firstItem());
        $this->assertEquals(10, $paginatorPage1->lastItem());

        // Halaman 2 (per_page = 10, page = 2)
        $respPage2 = $this->actingAs($this->admin)
            ->get(route('admin.positions.index', ['per_page' => 10, 'page' => 2]));

        $respPage2->assertStatus(200);
        $paginatorPage2 = $respPage2->viewData('positions');

        $this->assertEquals(11, $paginatorPage2->firstItem());
        $this->assertEquals(15, $paginatorPage2->lastItem());
    }
}
