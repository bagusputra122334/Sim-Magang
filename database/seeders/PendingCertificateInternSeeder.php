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

class PendingCertificateInternSeeder extends Seeder
{
    /**
     * Jalankan seeder untuk membuat HANYA satu akun peserta magang uji coba baru
     * dengan status magang Selesai namun sertifikat belum diunggah.
     * Tidak mengubah atau menyentuh data akun admin sama sekali.
     */
    public function run(): void
    {
        // 1. DIVISI: Aplikasi dan Informatika
        $division = Division::firstOrCreate(
            ['nama_divisi' => 'Aplikasi dan Informatika']
        );

        // 2. POSISI: Aplikasi dan Informatika
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

        // 3. USER PESERTA UJI COBA (HANYA PESERTA, TANPA MENYENTUH ADMIN)
        $email = 'peserta.belum@simagang.test';
        $user = User::where('email', $email)->first();

        if (! $user) {
            $user = new User();
            $user->email = $email;
        }

        $user->name = 'Peserta Menunggu Sertifikat';
        $user->password = Hash::make('password');
        $user->role = UserRole::Peserta;
        $user->division_id = $division->id;
        $user->position_id = $position->id;
        $user->email_verified_at = now();
        $user->save();

        // 4. BIODATA / PROFIL PESERTA
        Profile::updateOrCreate(
            ['user_id' => $user->id],
            [
                'nama_lengkap'     => 'Peserta Menunggu Sertifikat',
                'participant_type' => ParticipantType::University,
                'nis_nim'          => '22051204101',
                'nim'              => '22051204101',
                'nik'              => '3523011710990002',
                'tempat_lahir'     => 'Tuban',
                'tanggal_lahir'    => '2002-05-15',
                'jenis_kelamin'    => JenisKelamin::LakiLaki,
                'alamat'           => 'Jl. Veteran No. 45, Tuban',
                'no_telepon'       => '081234567891',
                'institusi'        => 'Universitas Brawijaya',
                'jurusan'          => 'Sistem Informasi',
                'tahun_angkatan'   => '2022',
                'semester'         => 7,
            ]
        );

        // 5. SIAPKAN FILE FISIK DUMMY DOKUMEN PERSYARATAN (AGAR PREVIEW DOKUMEN TIDAK 404)
        $dummyPdfContent = "%PDF-1.4\n1 0 obj << /Type /Catalog /Pages 2 0 R >> endobj\n2 0 obj << /Type /Pages /Kids [3 0 R] /Count 1 >> endobj\n3 0 obj << /Type /Page /Parent 2 0 R /MediaBox [0 0 612 792] /Contents 4 0 R /Resources << /Font << /F1 5 0 R >> >> >> endobj\n4 0 obj << /Length 120 >> stream\nBT /F1 20 Tf 50 700 Td (DOKUMEN PESERTA MAGANG - SIMAGANG DISKOMINFO TUBAN) Tj ET\nendstream\nendobj\n5 0 obj << /Type /Font /Subtype /Type1 /BaseFont /Helvetica >> endobj\nxref\n0 6\n0000000000 65535 f \n0000000009 00000 n \n0000000058 00000 n \n0000000115 00000 n \n0000000244 00000 n \n0000000414 00000 n \ntrailer << /Size 6 /Root 1 0 R >>\nstartxref\n484\n%%EOF\n";

        $cvPath = 'dokumen/dummy_cv.pdf';
        $spPath = 'dokumen/dummy_surat_pengantar.pdf';
        $pmPath = 'dokumen/dummy_proposal.pdf';
        $sbPath = 'surat_balasan/dummy_surat_balasan.pdf';

        if (! Storage::disk('public')->exists($cvPath)) {
            Storage::disk('public')->put($cvPath, $dummyPdfContent);
        }
        if (! Storage::disk('public')->exists($spPath)) {
            Storage::disk('public')->put($spPath, $dummyPdfContent);
        }
        if (! Storage::disk('public')->exists($pmPath)) {
            Storage::disk('public')->put($pmPath, $dummyPdfContent);
        }
        if (! Storage::disk('public')->exists($sbPath)) {
            Storage::disk('public')->put($sbPath, $dummyPdfContent);
        }
        if (! Storage::disk('local')->exists($sbPath)) {
            Storage::disk('local')->put($sbPath, $dummyPdfContent);
        }

        // 6. RECORD PENDAFTARAN (STATUS ACCEPTED, SELESAI MAGANG, TANPA SERTIFIKAT)
        $registration = Registration::where('user_id', $user->id)->first();
        if (! $registration) {
            $registration = new Registration();
            $registration->user_id = $user->id;
            $registration->nomor_pendaftaran = 'MAGANG-2026-MENUNGGU-SERTIFIKAT';
        }

        $registration->position_id = $position->id;
        $registration->status = RegistrationStatus::Accepted;
        $registration->cv_path = $cvPath;
        $registration->surat_pengantar_path = $spPath;
        $registration->proposal_magang_path = $pmPath;
        $registration->surat_balasan_path = $sbPath;
        $registration->catatan_admin = 'Peserta telah diterima dan menyelesaikan masa magang pada Divisi Aplikasi dan Informatika. Menunggu penerbitan sertifikat kelulusan.';
        $registration->tanggal_submit = Carbon::parse('2026-09-15 08:30:00');
        $registration->periode_mulai = Carbon::parse('2026-09-19');
        $registration->periode_selesai = Carbon::parse('2026-10-18');
        $registration->certificate_path = null;
        $registration->certificate_issued_at = null;
        $registration->is_terminated = false;
        $registration->catatan_penonaktifan = null;
        $registration->terminated_at = null;
        $registration->save();
    }
}
