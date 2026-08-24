<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class GuideController extends Controller
{
    /**
     * Master data panduan resmi SIM-MAGANG Diskominfo SP Kabupaten Tuban.
     *
     * @return array<string, array<string, mixed>>
     */
    public static function getGuides(): array
    {
        return [
            'pendaftaran' => [
                'slug' => 'pendaftaran',
                'title' => 'Panduan Lengkap Pendaftaran & Upload Berkas Magang',
                'badge' => 'Prosedur & Berkas',
                'badge_icon' => 'bi-file-earmark-arrow-up-fill',
                'meta_author' => 'Tim Administrator Diskominfo SP',
                'meta_category' => 'Panduan Pendaftaran',
                'image' => 'traveland/images/blog-guide.png',
                'summary' => 'Persyaratan dokumen (CV, Surat Pengantar, Proposal Magang) dan tata cara pendaftaran daring melalui portal SIM-MAGANG.',
            ],
            'kategori-peserta' => [
                'slug' => 'kategori-peserta',
                'title' => 'Ketentuan Kategori Mahasiswa dan Siswa SMK',
                'badge' => 'Ketentuan Akademik',
                'badge_icon' => 'bi-mortarboard-fill',
                'meta_author' => 'Bidang Kepegawaian & Tata Usaha',
                'meta_category' => 'Ketentuan Peserta',
                'image' => 'traveland/images/blog-category.png',
                'summary' => 'Ketentuan pengisian profil dan persyaratan pendaftaran bagi kategori Mahasiswa Perguruan Tinggi serta Siswa SMK.',
            ],
            'surat-balasan' => [
                'slug' => 'surat-balasan',
                'title' => 'Penerbitan Surat Balasan Resmi Berformat Digital PDF',
                'badge' => 'Verifikasi & Surat',
                'badge_icon' => 'bi-file-earmark-check-fill',
                'meta_author' => 'Tim Verifikator Magang',
                'meta_category' => 'Verifikasi & Surat Balasan',
                'image' => 'traveland/images/blog-letter.png',
                'summary' => 'Tahapan verifikasi pendaftaran dan tata cara mengunduh Surat Balasan resmi instansi berformat digital PDF.',
            ],
        ];
    }

    /**
     * Tampilkan halaman detail panduan magang berdasarkan slug.
     */
    public function show(string $slug): View
    {
        $guides = self::getGuides();

        if (!array_key_exists($slug, $guides)) {
            abort(404, 'Halaman panduan magang yang Anda cari tidak ditemukan.');
        }

        $guide = $guides[$slug];
        $otherGuides = array_filter($guides, fn($k) => $k !== $slug, ARRAY_FILTER_USE_KEY);

        return view('guides.show', compact('guide', 'otherGuides', 'slug'));
    }

    /**
     * Redirect indeks panduan ke seksi #blog di landing page.
     */
    public function index(): RedirectResponse
    {
        return redirect()->to(url('/#blog'));
    }
}
