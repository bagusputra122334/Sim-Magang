<?php

namespace App\Console\Commands;

use App\Enums\RegistrationStatus;
use App\Mail\ApplicationRejectedMail;
use App\Models\Position;
use App\Models\Registration;
use App\Models\User;
use App\Notifications\CustomResetPasswordNotification;
use App\Notifications\InternDeactivatedNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class TestMissingEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:missing-emails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test targeted missing SIMAGANG email templates (Rejected, Terminated, Reset Password)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $targetEmail = 'bagusdwijunior@gmail.com';
        $this->info("Firing targeted missing email templates directly to: {$targetEmail}...");

        // Ensure prerequisite models exist in DB for model serialization & relations
        $user = User::firstOrCreate(
            ['email' => $targetEmail],
            [
                'name'     => 'Bagus Dwi',
                'password' => bcrypt('password123'),
            ]
        );

        $position = Position::first() ?? Position::create([
            'nama_posisi' => 'Teknik Informatika & Development',
            'slug'        => 'teknik-informatika-development',
            'kuota'       => 10,
            'deskripsi'   => 'Posisi pengujian sistem',
            'is_active'   => true,
        ]);

        $registration = Registration::first() ?? Registration::create([
            'nomor_pendaftaran'    => 'MAGANG-2026-TEST-999',
            'user_id'              => $user->id,
            'position_id'          => $position->id,
            'status'               => RegistrationStatus::Rejected,
            'periode_label'        => 'Oktober - Desember 2026',
            'tanggal_mulai'        => '2026-10-01',
            'tanggal_selesai'      => '2026-12-31',
            'cv_path'              => 'dummy/cv.pdf',
            'surat_pengantar_path' => 'dummy/surat_pengantar.pdf',
            'catatan_admin'        => 'Mohon maaf, kuota pendaftaran untuk divisi yang Anda pilih telah penuh pada periode ini.',
        ]);
        $registration->loadMissing(['user', 'position']);

        // 1. Forceful Direct Mail Dispatch for ApplicationRejectedMail
        $catatanPenolakan = 'Mohon maaf, berkas persyaratan Anda belum memenuhi kriteria kualifikasi yang dibutuhkan pada periode ini.';
        Mail::to($targetEmail)->send(new ApplicationRejectedMail($registration, $catatanPenolakan));
        $this->info('1/3: ApplicationRejectedMail sent directly to ' . $targetEmail);

        // 2. Forceful Notification Route Dispatch for InternDeactivatedNotification
        $catatanPenonaktifan = 'Status kepesertaan magang telah dinonaktifkan oleh Administrator SIMAGANG.';
        Notification::route('mail', $targetEmail)->notify(new InternDeactivatedNotification($registration, $catatanPenonaktifan));
        $this->info('2/3: InternDeactivatedNotification sent directly to ' . $targetEmail);

        // 3. User Notification Dispatch for CustomResetPasswordNotification
        $user->notify(new CustomResetPasswordNotification('dummy-reset-token-123456789'));
        $this->info('3/3: CustomResetPasswordNotification sent directly to ' . $targetEmail);

        $this->newLine();
        $this->info('Targeted missing emails sent!');

        return Command::SUCCESS;
    }
}
