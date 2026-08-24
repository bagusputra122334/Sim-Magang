<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('positions', function (Blueprint $table) {
            $table->id();
            $table->string('nama_posisi', 150)->unique();
            $table->string('slug', 150)->unique();
            $table->text('deskripsi');
            $table->text('kualifikasi')->nullable();
            $table->unsignedInteger('kuota')->default(1);
            $table->string('status', 20)
                ->default('aktif')
                ->index();
            $table->date('tanggal_buka');
            $table->date('tanggal_tutup');
            $table->softDeletes();
            $table->timestamps();

            $table->index(['status', 'tanggal_buka', 'tanggal_tutup']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('positions');
    }
};
