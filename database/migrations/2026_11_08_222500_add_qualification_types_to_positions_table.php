<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('positions', function (Blueprint $table) {
            $table->text('kualifikasi_siswa')->nullable()->after('kualifikasi');
            $table->text('kualifikasi_mahasiswa')->nullable()->after('kualifikasi_siswa');
            $table->string('tipe_pendaftar', 30)->default('all')->after('kualifikasi_mahasiswa');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('positions', function (Blueprint $table) {
            $table->dropColumn(['kualifikasi_siswa', 'kualifikasi_mahasiswa', 'tipe_pendaftar']);
        });
    }
};

