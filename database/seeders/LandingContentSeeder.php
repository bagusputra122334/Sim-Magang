<?php

namespace Database\Seeders;

use App\Models\LandingContent;
use Illuminate\Database\Seeder;

class LandingContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            // ==========================================
            // ABOUT SECTION ITEMS
            // ==========================================
            [
                'section'     => 'about',
                'title'       => '100% Digital & Paperless',
                'description' => 'Seluruh berkas pendaftaran, seleksi, hingga verifikasi magang dilakukan secara digital tanpa membutuhkan berkas fisik.',
                'icon'        => 'bi-laptop',
                'order'       => 1,
                'is_active'   => true,
            ],
            [
                'section'     => 'about',
                'title'       => 'Transparan & Terintegrasi',
                'description' => 'Status permohonan magang dapat dipantau secara langsung dan real-time melalui dashboard akun peserta.',
                'icon'        => 'bi-shield-check',
                'order'       => 2,
                'is_active'   => true,
            ],
            [
                'section'     => 'about',
                'title'       => 'Pembimbingan ASN & Praktisi',
                'description' => 'Peserta magang dibimbing langsung oleh Aparatur Sipil Negara dan tim teknis profesional di bidangnya.',
                'icon'        => 'bi-people',
                'order'       => 3,
                'is_active'   => true,
            ],

            // ==========================================
            // ADVANTAGE SECTION ITEMS
            // ==========================================
            [
                'section'     => 'advantage',
                'title'       => 'Pengalaman Kerja Real & Portofolio',
                'description' => 'Terlibat langsung dalam proyek layanan publik digital, jaringan pemerintahan, serta pengelolaan komunikasi daerah.',
                'icon'        => 'bi-briefcase',
                'order'       => 1,
                'is_active'   => true,
            ],
            [
                'section'     => 'advantage',
                'title'       => 'Sertifikat Magang Resmi',
                'description' => 'Mendapatkan sertifikat resmi dari Diskominfo SP Kabupaten Tuban sebagai penunjang karir dan akademik.',
                'icon'        => 'bi-award',
                'order'       => 2,
                'is_active'   => true,
            ],
            [
                'section'     => 'advantage',
                'title'       => 'Lingkungan Kerja Profesional',
                'description' => 'Fasilitas kerja yang memadai dengan suasana kolaboratif, suportif, dan adaptif terhadap teknologi terbaru.',
                'icon'        => 'bi-building',
                'order'       => 3,
                'is_active'   => true,
            ],
            [
                'section'     => 'advantage',
                'title'       => 'Fleksibilitas Program',
                'description' => 'Terbuka bagi Siswa SMK/SMA dan Mahasiswa Perguruan Tinggi dari berbagai rumpun ilmu.',
                'icon'        => 'bi-calendar-check',
                'order'       => 4,
                'is_active'   => true,
            ],

            // ==========================================
            // WORKFLOW SECTION ITEMS
            // ==========================================
            [
                'section'     => 'workflow',
                'title'       => 'Registrasi Akun',
                'description' => 'Buat akun peserta magang di SIMAGANG dengan melengkapi email dan identitas dasar.',
                'icon'        => 'bi-person-plus',
                'order'       => 1,
                'is_active'   => true,
            ],
            [
                'section'     => 'workflow',
                'title'       => 'Pengajuan & Unggah Berkas',
                'description' => 'Pilih posisi magang yang diminati dan unggah dokumen persyaratan seperti Surat Pengantar dan Proposal.',
                'icon'        => 'bi-file-earmark-arrow-up',
                'order'       => 2,
                'is_active'   => true,
            ],
            [
                'section'     => 'workflow',
                'title'       => 'Verifikasi & Seleksi',
                'description' => 'Tim verifikator mengevaluasi kelengkapan berkas dan ketersediaan kuota pembimbing.',
                'icon'        => 'bi-clipboard-check',
                'order'       => 3,
                'is_active'   => true,
            ],
            [
                'section'     => 'workflow',
                'title'       => 'Penerimaan & Pelaksanaan',
                'description' => 'Unduh surat balasan resmi dari portal SIMAGANG dan mulai pelaksanaan magang sesuai jadwal.',
                'icon'        => 'bi-check-circle',
                'order'       => 4,
                'is_active'   => true,
            ],

            // ==========================================
            // FAQ SECTION ITEMS
            // ==========================================
            [
                'section'     => 'faq',
                'title'       => 'Siapa saja yang dapat mendaftar magang di Diskominfo SP Tuban?',
                'description' => 'Program magang ini terbuka untuk Siswa SMK/SMA dan Mahasiswa Perguruan Tinggi yang membutuhkan Praktik Kerja Lapangan (PKL) atau Magang Akademik.',
                'icon'        => 'bi-question-circle',
                'order'       => 1,
                'is_active'   => true,
            ],
            [
                'section'     => 'faq',
                'title'       => 'Apakah pendaftaran magang dipungut biaya?',
                'description' => 'Tidak ada biaya sama sekali. Seluruh proses pendaftaran dan pelaksanaan magang di SIMAGANG adalah 100% GRATIS.',
                'icon'        => 'bi-cash-stack',
                'order'       => 2,
                'is_active'   => true,
            ],
            [
                'section'     => 'faq',
                'title'       => 'Berapa lama durasi pelaksanaan magang?',
                'description' => 'Durasi magang menyesuaikan ketentuan instansi pendidikan pemohon, umumnya berkisar antara 1 hingga 6 bulan.',
                'icon'        => 'bi-clock',
                'order'       => 3,
                'is_active'   => true,
            ],
            [
                'section'     => 'faq',
                'title'       => 'Bagaimana cara mengetahui status penerimaan magang?',
                'description' => 'Peserta dapat memantau perubahan status secara real-time melalui dashboard akun SIMAGANG dan email notifikasi.',
                'icon'        => 'bi-info-circle',
                'order'       => 4,
                'is_active'   => true,
            ],
        ];

        foreach ($items as $item) {
            LandingContent::updateOrCreate(
                [
                    'section' => $item['section'],
                    'title'   => $item['title'],
                ],
                [
                    'description' => $item['description'],
                    'icon'        => $item['icon'],
                    'order'       => $item['order'],
                    'is_active'   => $item['is_active'],
                ]
            );
        }
    }
}
