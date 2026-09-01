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
}
