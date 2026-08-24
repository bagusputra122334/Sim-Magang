<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport;

class SmtpTestCommand extends Command
{
    protected $signature = 'smtp:test {email? : Destination email address to send test email}';

    protected $description = 'Diagnose email connectivity, port availability, and Symfony Mailer transport';

    public function handle(): int
    {
        $this->info('====================================================');
        $this->info('          SIM-MAGANG MAIL DIAGNOSTIC SUITE          ');
        $this->info('====================================================');

        $host = config('mail.mailers.smtp.host', env('MAIL_HOST', '127.0.0.1'));
        $username = config('mail.mailers.smtp.username', env('MAIL_USERNAME'));
        $password = config('mail.mailers.smtp.password', env('MAIL_PASSWORD'));
        $mailer = config('mail.default', env('MAIL_MAILER'));

        $resendKey = config('services.resend.key', env('RESEND_API_KEY'));

        $this->table(
            ['Configuration Key', 'Value'],
            [
                ['MAIL_MAILER (Active Driver)', $mailer],
                ['MAIL_HOST', $host],
                ['MAIL_PORT', config('mail.mailers.smtp.port', env('MAIL_PORT'))],
                ['MAIL_ENCRYPTION', env('MAIL_ENCRYPTION', 'not set')],
                ['MAIL_USERNAME', $username ? $username : '<NOT SET>'],
                ['RESEND_API_KEY', $resendKey ? 're_******** (configured)' : '<NOT SET>'],
                ['MAIL_FROM_ADDRESS', config('mail.from.address')],
                ['MAIL_FROM_NAME', config('mail.from.name')],
            ]
        );

        $this->newLine();
        $this->info('----------------------------------------------------');
        $this->info(' 1. TESTING NETWORK CONNECTIVITY (HTTPS 443 & SMTP)');
        $this->info('----------------------------------------------------');

        $endpointsToTest = [
            ['api.resend.com', 443, 'ssl://', 'Resend HTTPS API (Port 443)'],
            ['smtp.gmail.com', 587, '', 'Gmail STARTTLS (Port 587)'],
            ['smtp.gmail.com', 465, 'ssl://', 'Gmail Implicit SSL (Port 465)'],
            ['sandbox.smtp.mailtrap.io', 2525, '', 'Mailtrap Sandbox (Port 2525)'],
        ];

        foreach ($endpointsToTest as [$testHost, $port, $prefix, $desc]) {
            $this->output->write(sprintf('Testing %s:%d (%s)... ', $testHost, $port, $desc));
            $errno = 0;
            $errstr = '';

            $start = microtime(true);
            $fp = @fsockopen($prefix . $testHost, $port, $errno, $errstr, 3);
            $elapsed = round((microtime(true) - $start) * 1000, 1);

            if ($fp) {
                $greeting = ($port !== 443) ? @fgets($fp, 512) : null;
                fclose($fp);
                $this->info("OPEN ({$elapsed}ms)" . ($greeting ? ' - ' . trim($greeting) : ''));
            } else {
                $this->warn("BLOCKED/UNREACHABLE ({$elapsed}ms) - Err {$errno}: " . ($errstr ? trim($errstr) : 'Timeout'));
            }
        }

        $this->newLine();
        $this->info('----------------------------------------------------');
        $this->info(" 2. TESTING ACTIVE MAILER DISPATCH ({$mailer})");
        $this->info('----------------------------------------------------');

        $destEmail = $this->argument('email') ?? config('mail.from.address') ?? 'test@example.com';

        try {
            $this->info("Attempting to dispatch test email to: {$destEmail}");

            Mail::raw('Test email from SIM-MAGANG Diagnostic Command.', function ($message) use ($destEmail): void {
                $message->to($destEmail)
                    ->subject('SIM-MAGANG Email Delivery Test');
            });

            $this->info('SUCCESS: Email dispatched successfully via Symfony Mailer!');

            if ($mailer === 'sendmail') {
                $this->info('Note: Transactional sendmail output written to mailoutput/ on disk.');
            }

            return Command::SUCCESS;
        } catch (\Throwable $e) {
            $this->error('FAILED: ' . get_class($e));
            $this->error('Error Message: ' . $e->getMessage());
            $this->newLine();
            $this->warn('Trace excerpt:');
            $this->line(substr($e->getTraceAsString(), 0, 500));

            return Command::FAILURE;
        }
    }
}
