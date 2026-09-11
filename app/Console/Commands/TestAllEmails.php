<?php

namespace App\Console\Commands;

use App\Enums\RegistrationStatus;
use App\Enums\UserRole;
use App\Mail\ApplicationAcceptedMail;
use App\Mail\ApplicationRejectedMail;
use App\Mail\ApplicationReviewedMail;
use App\Mail\ApplicationSubmittedMail;
use App\Mail\ContactMessageMail;
use App\Mail\ReplyLetterAvailableMail;
use App\Models\Position;
use App\Models\Registration;
use App\Models\User;
use App\Notifications\InternDeactivatedNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestAllEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:all-emails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test all SIMAGANG email templates';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $recipient = 'bagusdwijunior@gmail.com';
        $this->info("Firing all SIMAGANG system email templates to: {$recipient}...");

        // 1. Ensure Dummy User & Position exist in Database for Model Serialization
        $user = User::firstOrCreate(
            ['email' => $recipient],
            [
                'name'     => 'Bagus Dwi',
                'password' => bcrypt('password123'),
                'role'     => UserRole::Peserta ?? 'peserta',
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
            'status'               => RegistrationStatus::Submitted,
            'periode_label'        => 'Oktober - Desember 2026',
            'tanggal_mulai'        => '2026-10-01',
            'tanggal_selesai'      => '2026-12-31',
            'cv_path'              => 'dummy/cv.pdf',
            'surat_pengantar_path' => 'dummy/surat_pengantar.pdf',
            'catatan_admin'        => 'Selamat! Berkas dan kualifikasi Anda telah sesuai dengan kriteria Diskominfo SP Kab. Tuban.',
            'surat_balasan_path'   => 'surat_balasan/dummy_test.pdf',
        ]);
        $registration->loadMissing(['user', 'position']);

        // --- STEP 1: Registration Submitted (Received) ---
        $registration->update(['status' => RegistrationStatus::Submitted]);
        Mail::to($recipient)->send(new ApplicationSubmittedMail($registration));
        $this->info('Email 1/7: Registration Received (ApplicationSubmittedMail) sent successfully!');

        // --- STEP 2: Application Reviewed / In Process ---
        $registration->update(['status' => RegistrationStatus::UnderReview]);
        Mail::to($recipient)->send(new ApplicationReviewedMail($registration));
        $this->info('Email 2/7: Application Reviewed (ApplicationReviewedMail) sent successfully!');

        // --- STEP 3: Application Accepted ---
        $registration->update(['status' => RegistrationStatus::Accepted]);
        Mail::to($recipient)->send(new ApplicationAcceptedMail($registration));
        $this->info('Email 3/7: Application Accepted (ApplicationAcceptedMail) sent successfully!');

        // --- STEP 4: Application Rejected ---
        $registration->update([
            'status'        => RegistrationStatus::Rejected,
            'catatan_admin' => 'Mohon maaf, kuota pendaftaran untuk divisi yang Anda pilih telah penuh pada periode ini.',
        ]);
        Mail::to($recipient)->send(new ApplicationRejectedMail($registration, $registration->catatan_admin));
        $this->info('Email 4/7: Application Rejected (ApplicationRejectedMail) sent successfully!');

        // --- STEP 5: Reply Letter Available ---
        $registration->update(['status' => RegistrationStatus::Accepted]);
        Mail::to($recipient)->send(new ReplyLetterAvailableMail($registration));
        $this->info('Email 5/7: Reply Letter Available (ReplyLetterAvailableMail) sent successfully!');

        // --- STEP 6: Intern Deactivated / Terminated / Completed Notification ---
        $user->notify(new InternDeactivatedNotification($registration, 'Masa pelaksanaan magang telah diselesaikan dengan baik (Completed).'));
        $this->info('Email 6/7: Terminated/Completed (InternDeactivatedNotification) sent successfully!');

        // --- STEP 7: Contact Message Mail ---
        Mail::to($recipient)->send(new ContactMessageMail([
            'name'           => 'Bagus Dwi',
            'phone'          => '081234567890',
            'email'          => $recipient,
            'category'       => 'mahasiswa',
            'messageContent' => 'Pengujian otomatis seluruh template email SIMAGANG Diskominfo Kabupaten Tuban.',
            'submittedAt'    => now()->translatedFormat('d F Y, H:i') . ' WIB',
        ]));
        $this->info('Email 7/7: Contact Message (ContactMessageMail) sent successfully!');

        $this->newLine();
        $this->info('🎉 All 7 SIMAGANG lifecycle email templates have been dispatched to ' . $recipient . '!');

        return Command::SUCCESS;
    }
}
