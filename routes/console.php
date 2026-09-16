<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('db:audit-schema', function (): void {
    $this->info('=== DATABASE SCHEMA AUDIT ===');
    $this->line('Connection : '.config('database.default'));
    $this->line('Database   : '.config('database.connections.'.config('database.default').'.database'));
    $this->newLine();

    $tables = \Illuminate\Support\Facades\DB::select('SHOW TABLES');
    $tableKey = 'Tables_in_'.config('database.connections.'.config('database.default').'.database');

    $this->info('DAFTAR TABEL ('.count($tables).'):');
    foreach ($tables as $t) {
        $tableName = $t->$tableKey;
        $cols = \Illuminate\Support\Facades\DB::select("SHOW COLUMNS FROM `{$tableName}`");
        $this->line(sprintf('  • %-35s (%d kolom)', $tableName, count($cols)));
        foreach ($cols as $c) {
            $nullable = $c->Null === 'YES' ? ' NULL ' : 'NOT NULL';
            $def = $c->Default === null ? '' : " DEFAULT '{$c->Default}'";
            $extra = $c->Extra ? " [{$c->Extra}]" : '';
            $this->line(sprintf('      · %-25s %-22s %-8s %s%s',
                $c->Field,
                $c->Type,
                $nullable,
                $c->Key ? "KEY:{$c->Key}" : '',
                $def.$extra
            ));
        }
        $this->newLine();
    }
})->purpose('Audit seluruh tabel & kolom database aktif');

Artisan::command('mail:test {email?}', function (?string $email = null): void {
    $recipient = $email ?? (string) config('mail.from.address', 'test@example.com');
    $this->info("Mengirimkan email uji coba ke: {$recipient}");

    try {
        \Illuminate\Support\Facades\Mail::raw("Halo! Ini adalah email uji coba dari SIM-MAGANG Diskominfo SP Tuban.\n\nJika Anda menerima pesan ini, konfigurasi pengiriman email pada aplikasi SIM-MAGANG telah BERHASIL dan SIAP DIGUNAKAN.", function ($message) use ($recipient): void {
            $message->to($recipient)
                    ->subject('[SIMAGANG] Uji Coba Pengiriman Email System');
        });
        $this->info("SUCCESS: Email uji coba berhasil dikirim ke {$recipient}!");
    } catch (\Throwable $e) {
        $this->error("ERROR: Gagal mengirimkan email. Detail kesalahan: " . $e->getMessage());
    }
})->purpose('Kirim email uji coba untuk memverifikasi konfigurasi mailer');

