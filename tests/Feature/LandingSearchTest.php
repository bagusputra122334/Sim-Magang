<?php

namespace Tests\Feature;

use App\Enums\PositionStatus;
use App\Models\Position;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LandingSearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_landing_page_search_filters_positions_by_name(): void
    {
        Position::create([
            'nama_posisi' => 'Laravel Developer SPBE',
            'slug' => 'laravel-developer-spbe',
            'deskripsi' => 'Pengembangan backend laravel',
            'status' => PositionStatus::Aktif,
        ]);

        Position::create([
            'nama_posisi' => 'Desainer Grafis Humas',
            'slug' => 'desainer-grafis-humas',
            'deskripsi' => 'Desain konten media sosial',
            'status' => PositionStatus::Aktif,
        ]);

        $response = $this->get('/?search=Laravel#positions');

        $response->assertOk();
        $response->assertSee('Laravel Developer SPBE');
        $response->assertDontSee('Desainer Grafis Humas');
    }

    public function test_landing_page_renders_interactive_qualification_placeholder_and_buttons(): void
    {
        $opd = \App\Models\Opd::factory()->create(['name' => 'Dinas Uji Coba']);

        $position = Position::create([
            'opd_id' => $opd->id,
            'nama_posisi' => 'Fullstack Web Engineer',
            'slug' => 'fullstack-web-engineer',
            'deskripsi' => 'Pengembangan portal terpadu',
            'tipe_pendaftar' => 'all',
            'kualifikasi_siswa' => 'Siswa SMK RPL semester akhir',
            'kualifikasi_mahasiswa' => 'Mahasiswa Teknik Informatika S1',
            'status' => PositionStatus::Open,
        ]);

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('btn-qual-siswa-' . $position->id);
        $response->assertSee('btn-qual-mhs-' . $position->id);
        $response->assertSee('qual-placeholder-' . $position->id);
        $response->assertSee('Silakan Pilih Jenjang di Atas');
    }
}
