<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            $table->date('periode_mulai')->after('tanggal_submit')->nullable();
            $table->date('periode_selesai')->after('periode_mulai')->nullable();

            $table->index(['periode_mulai', 'periode_selesai']);
        });
    }

    public function down(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            $table->dropIndex(['periode_mulai', 'periode_selesai']);
            $table->dropColumn(['periode_mulai', 'periode_selesai']);
        });
    }
};
