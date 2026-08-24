<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->unique()
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->string('nis_nim', 50)->unique();
            $table->string('tempat_lahir', 100);
            $table->date('tanggal_lahir');
            $table->string('jenis_kelamin', 15);
            $table->text('alamat');
            $table->string('no_telepon', 20);
            $table->string('institusi', 200);
            $table->string('jurusan', 150);
            $table->string('tahun_angkatan', 10);
            $table->timestamps();

            $table->index('institusi');
            $table->index('jurusan');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
