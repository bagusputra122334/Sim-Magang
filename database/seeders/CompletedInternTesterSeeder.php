<?php

namespace Database\Seeders;

use App\Enums\JenisKelamin;
use App\Enums\ParticipantType;
use App\Enums\PositionStatus;
use App\Enums\RegistrationStatus;
use App\Enums\UserRole;
use App\Models\Division;
use App\Models\Position;
use App\Models\Profile;
use App\Models\Registration;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class CompletedInternTesterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. DIVISI: Aplikasi dan Informatika
        $division = Division::firstOrCreate(
            ['nama_divisi' => 'Aplikasi dan Informatika']
        );

        // 2. POSISI: Terkait Aplikasi dan Informatika
        $position = Position::where('nama_posisi', 'Aplikasi dan Informatika')->first();
        if (! $position) {
            $position = Position::create([
                'nama_posisi'  => 'Aplikasi dan Informatika',
                'slug'         => 'aplikasi-dan-informatika',
                'deskripsi'    => 'Pengembangan aplikasi pemerintahan dan tata kelola informatika DISKOMINFO SP Tuban.',
                'kualifikasi'  => 'Mahasiswa/Siswa jurusan Teknik Informatika, Sistem Informasi, atau RPL.',
                'kuota'        => 10,
                'status'       => PositionStatus::Aktif,
                'mentor_name'  => 'Bambang Sudarsono, S.Kom',
                'mentor_nip'   => '198503152010011008',
            ]);
        }

        // 3. USER: Peserta Selesai Magang
        $email = 'peserta.selesai@simagang.test';
        $user = User::where('email', $email)->first();

        if (! $user) {
            $user = new User();
            $user->email = $email;
        }

        $user->name = 'Peserta Selesai Magang';
        $user->password = Hash::make('password');
        $user->role = UserRole::Peserta;
        $user->division_id = $division->id;
        $user->position_id = $position->id;
        $user->email_verified_at = now();
        $user->save();

        // 4. PROFIL PESERTA LENGKAP
        Profile::updateOrCreate(
            ['user_id' => $user->id],
            [
                'nama_lengkap'     => 'Peserta Selesai Magang',
                'participant_type' => ParticipantType::University,
                'nis_nim'          => '22051204099',
                'nim'              => '22051204099',
                'nik'              => '3523011710990001',
                'tempat_lahir'     => 'Tuban',
                'tanggal_lahir'    => '2001-10-17',
                'jenis_kelamin'    => JenisKelamin::LakiLaki,
                'alamat'           => 'Jl. Veteran No. 10, Tuban',
                'no_telepon'       => '081234567899',
                'institusi'        => 'Universitas Brawijaya',
                'jurusan'          => 'Teknik Informatika',
                'tahun_angkatan'   => '2022',
                'semester'         => 7,
            ]
        );

        // 5. GENERATE DOKUMEN & SERTIFIKAT DUMMY PDF FISIK
        $certificatePath = 'certificates/dummy-certificate.pdf';

        try {
            $pdf = Pdf::loadHTML('
                <!DOCTYPE html>
                <html>
                <head>
                    <meta charset="utf-8">
                    <title>Sertifikat Kelulusan Magang</title>
                    <style>
                        body { font-family: sans-serif; margin: 0; padding: 25px; background: #fff; }
                        .border-box { border: 4px double #1e40af; padding: 30px 25px; text-align: center; min-height: 620px; }
                        .header-logo { font-size: 14px; font-weight: bold; color: #475569; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 5px; }
                        .title { font-size: 26px; font-weight: 800; color: #1e3a8a; margin: 15px 0 5px; text-transform: uppercase; letter-spacing: 1.5px; }
                        .subtitle { font-size: 12px; color: #64748b; margin-bottom: 25px; }
                        .recipient-label { font-size: 13px; color: #334155; margin-bottom: 5px; }
                        .recipient-name { font-size: 24px; font-weight: bold; color: #047857; margin: 10px 0; border-bottom: 2px solid #047857; display: inline-block; padding-bottom: 4px; }
                        .recipient-meta { font-size: 12px; color: #475569; margin-bottom: 20px; font-weight: 600; }
                        .desc { font-size: 13px; line-height: 1.7; color: #334155; max-width: 580px; margin: 0 auto 30px; }
                        .footer { margin-top: 40px; text-align: right; padding-right: 30px; }
                        .footer-date { font-size: 12px; color: #475569; margin-bottom: 8px; }
                        .footer-signer { font-size: 13px; font-weight: bold; color: #0f172a; }
                        .badge-status { background-color: #dcfce7; color: #15803d; border: 1px solid #86efac; padding: 4px 12px; border-radius: 9999px; font-size: 11px; font-weight: bold; display: inline-block; margin-top: 15px; }
                    </style>
                </head>
                <body>
                    <div class="border-box">
                        <div class="header-logo">PEMERINTAH KABUPATEN TUBAN &bull; DISKOMINFO SP</div>
                        <div class="title">SERTIFIKAT KELULUSAN MAGANG</div>
                        <div class="subtitle">Nomor Sertifikat: CERT/MAGANG/TUBAN/2026/099</div>
                        
                        <div class="recipient-label">Diberikan dengan penuh apresiasi kepada:</div>
                        <div class="recipient-name">Peserta Selesai Magang</div>
                        <div class="recipient-meta">NIM: 22051204099 &bull; Universitas Brawijaya &bull; Teknik Informatika</div>
                        
                        <div class="desc">
                            Telah berhasil menyelesaikan seluruh penugasan, modul evaluasi, dan praktik kerja lapangan magang pada
                            <strong>Divisi Aplikasi dan Informatika</strong> di Dinas Komunikasi dan Informatika, Statistik dan Persandian
                            Kabupaten Tuban terhitung sejak tanggal <strong>17 September 2026</strong> sampai dengan <strong>17 Oktober 2026</strong>
                            dengan hasil <strong>SANGAT BAIK (LULUS)</strong>.
                        </div>

                        <div>
                            <span class="badge-status">&#10003; TERVERIFIKASI & DITERBITKAN RESMI</span>
                        </div>

                        <div class="footer">
                            <div class="footer-date">Tuban, 17 Oktober 2026</div>
                            <div class="footer-signer">Kepala Dinas Komunikasi dan Informatika SP</div>
                            <br><br><br>
                            <div class="footer-signer"><u>Drs. ARIF HANDOKO, M.Si</u></div>
                            <div style="font-size: 11px; color: #64748b;">NIP. 197405101998031004</div>
                        </div>
                    </div>
                </body>
                </html>
            ');
            $pdfContent = $pdf->output();
        } catch (\Throwable) {
            // Fallback minimal valid PDF binary format if dompdf is not available in environment
            $pdfContent = "%PDF-1.4\n1 0 obj << /Type /Catalog /Pages 2 0 R >> endobj\n2 0 obj << /Type /Pages /Kids [3 0 R] /Count 1 >> endobj\n3 0 obj << /Type /Page /Parent 2 0 R /MediaBox [0 0 612 792] /Contents 4 0 R /Resources << /Font << /F1 5 0 R >> >> >> endobj\n4 0 obj << /Length 120 >> stream\nBT /F1 20 Tf 50 700 Td (SERTIFIKAT KELULUSAN MAGANG DIGITAL - DISKOMINFO TUBAN) Tj ET\nendstream\nendobj\n5 0 obj << /Type /Font /Subtype /Type1 /BaseFont /Helvetica >> endobj\nxref\n0 6\n0000000000 65535 f \n0000000009 00000 n \n0000000058 00000 n \n0000000115 00000 n \n0000000244 00000 n \n0000000414 00000 n \ntrailer << /Size 6 /Root 1 0 R >>\nstartxref\n484\n%%EOF\n";
        }

        // Simpan file fisik dummy sertifikat ke storage publik
        Storage::disk('public')->put($certificatePath, $pdfContent);

        // Siapkan juga dummy dokumen pendukung agar preview CV & Surat Balasan tidak 404
        $cvPath = 'dokumen/dummy_cv.pdf';
        $spPath = 'dokumen/dummy_surat_pengantar.pdf';
        $pmPath = 'dokumen/dummy_proposal.pdf';
        $sbPath = 'surat_balasan/dummy_surat_balasan.pdf';

        Storage::disk('public')->put($cvPath, $pdfContent);
        Storage::disk('public')->put($spPath, $pdfContent);
        Storage::disk('public')->put($pmPath, $pdfContent);
        Storage::disk('public')->put($sbPath, $pdfContent);
        Storage::disk('local')->put($sbPath, $pdfContent);

        // 6. RECORD PENDAFTARAN / APLIKASI
        $registration = Registration::where('user_id', $user->id)->first();
        if (! $registration) {
            $registration = new Registration();
            $registration->user_id = $user->id;
            $registration->nomor_pendaftaran = 'MAGANG-2026-SELESAI';
        }

        $registration->position_id = $position->id;
        $registration->status = RegistrationStatus::Accepted;
        $registration->cv_path = $cvPath;
        $registration->surat_pengantar_path = $spPath;
        $registration->proposal_magang_path = $pmPath;
        $registration->surat_balasan_path = $sbPath;
        $registration->catatan_admin = 'Selamat! Program magang telah diselesaikan dengan sangat baik. Sertifikat kelulusan digital resmi telah diterbitkan.';
        $registration->tanggal_submit = Carbon::parse('2026-09-15 08:30:00');
        $registration->periode_mulai = Carbon::parse('2026-09-17');
        $registration->periode_selesai = Carbon::parse('2026-10-17');
        $registration->certificate_path = $certificatePath;
        $registration->certificate_issued_at = now();
        $registration->is_terminated = false;
        $registration->save();
    }
}
