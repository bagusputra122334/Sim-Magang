<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GuideTest extends TestCase
{
    use RefreshDatabase;
    public function test_landing_page_renders_clickable_guide_cards(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee(route('guides.show', 'pendaftaran'));
        $response->assertSee(route('guides.show', 'kategori-peserta'));
        $response->assertSee(route('guides.show', 'surat-balasan'));
        $response->assertSee('Panduan Lengkap Pendaftaran');
        $response->assertSee('Ketentuan Kategori Mahasiswa dan Siswa SMK');
        $response->assertSee('Penerbitan Surat Balasan Resmi Berformat Digital PDF');
    }

    public function test_topbar_links_to_official_government_portal(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('https://tubankab.go.id/');
        $response->assertSee('target="_blank"', false);
        $response->assertSee('rel="noopener noreferrer"', false);
        $response->assertSee('Pemerintah Kabupaten Tuban');
    }

    public function test_landing_page_navbar_has_portal_button(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Portal Pemerintah Kabupaten Tuban');
        $response->assertSee('https://tubankab.go.id/');
        $response->assertSee('target="_blank"', false);
        $response->assertSee('rel="noopener noreferrer"', false);

        // Extract header_area content to verify no login/register buttons in navbar
        $content = $response->getContent();
        $headerStart = strpos($content, '<section class="header_area">');
        $headerEnd = strpos($content, '<div id="home"', $headerStart);
        $headerHtml = substr($content, $headerStart, $headerEnd - $headerStart);

        $this->assertStringContainsString('Portal Pemerintah Kabupaten Tuban', $headerHtml);
        $this->assertStringContainsString('https://tubankab.go.id/', $headerHtml);
        $this->assertStringNotContainsString('route(\'login\')', $headerHtml);
        $this->assertStringNotContainsString('login', $headerHtml);
        $this->assertStringNotContainsString('register', $headerHtml);
    }

    public function test_landing_page_hero_section_contains_complete_elements_and_fills_viewport(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);

        $content = $response->getContent();
        $heroStart = strpos($content, 'id="home" class="hero_wrapper"');
        $heroEnd = strpos($content, '</section>', $heroStart);
        $heroHtml = substr($content, $heroStart, $heroEnd - $heroStart);

        $this->assertStringContainsString('Portal Resmi Pendaftaran Magang', $heroHtml);
        $this->assertStringContainsString('Membangun Talenta Digital untuk', $heroHtml);
        $this->assertStringContainsString('Daftar Magang Sekarang', $heroHtml);
        $this->assertStringContainsString('Lihat Formasi', $heroHtml);
        $this->assertStringNotContainsString('Pendaftaran Digital 24/7', $heroHtml);
        $this->assertStringNotContainsString('Bimbingan Mentor Praktisi ASN', $heroHtml);
        $this->assertStringNotContainsString('Surat Balasan Resmi PDF', $heroHtml);
    }

    public function test_guide_index_redirects_to_blog_anchor(): void
    {
        $response = $this->get(route('guides.index'));

        $response->assertRedirect('/#blog');
    }

    public function test_registration_guide_detail_page_loads_successfully(): void
    {
        $response = $this->get(route('guides.show', 'pendaftaran'));

        $response->assertStatus(200);
        $response->assertSee('Panduan Lengkap Pendaftaran');
        $response->assertSee('Curriculum Vitae (CV) Terbaru');
        $response->assertSee('Surat Pengantar Institusi Pendidikan');
        $response->assertSee('Proposal Magang');
    }

    public function test_category_guide_detail_page_loads_successfully(): void
    {
        $response = $this->get(route('guides.show', 'kategori-peserta'));

        $response->assertStatus(200);
        $response->assertSee('Ketentuan Kategori Mahasiswa dan Siswa SMK');
        $response->assertSee('Kategori Mahasiswa');
        $response->assertSee('Kategori Siswa SMK');
    }

    public function test_reply_letter_guide_detail_page_loads_successfully(): void
    {
        $response = $this->get(route('guides.show', 'surat-balasan'));

        $response->assertStatus(200);
        $response->assertSee('Penerbitan Surat Balasan Resmi Berformat Digital PDF');
        $response->assertSee('Submitted (Diajukan)');
        $response->assertSee('Accepted (Diterima)');
        $response->assertSee('Unduh Surat Balasan (PDF)');
    }

    public function test_invalid_guide_slug_returns_404(): void
    {
        $response = $this->get(route('guides.show', 'panduan-tidak-ada'));

        $response->assertStatus(404);
    }
}
