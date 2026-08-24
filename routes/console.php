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
