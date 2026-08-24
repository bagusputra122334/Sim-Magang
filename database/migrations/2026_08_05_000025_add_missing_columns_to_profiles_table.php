<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->string('nik', 16)->after('user_id')->unique()->nullable();
            $table->string('nama_lengkap', 150)->after('nik')->nullable();
            $table->string('nim', 30)->after('nis_nim')->unique()->nullable();
            $table->unsignedTinyInteger('semester')->after('tahun_angkatan')->nullable();
            $table->string('foto')->after('no_telepon')->nullable();

            $table->index(['nik', 'nama_lengkap']);
        });
    }

    public function down(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->dropIndex(['nik', 'nama_lengkap']);

            $table->dropColumn([
                'nik',
                'nama_lengkap',
                'nim',
                'semester',
                'foto',
            ]);
        });
    }
};
