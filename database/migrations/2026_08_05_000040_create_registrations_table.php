<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_pendaftaran', 50)->unique();
            $table->foreignId('user_id')
                ->constrained()
                ->restrictOnDelete()
                ->cascadeOnUpdate();
            $table->foreignId('position_id')
                ->constrained()
                ->restrictOnDelete()
                ->cascadeOnUpdate();
            $table->string('cv_path');
            $table->string('surat_pengantar_path');
            $table->string('status', 30)
                ->default('submitted')
                ->index();
            $table->text('catatan_admin')->nullable();
            $table->string('surat_balasan_path')->nullable();
            $table->dateTime('tanggal_submit')->useCurrent();
            $table->timestamps();

            $table->index('user_id');
            $table->index('position_id');
            $table->index(['position_id', 'status']);
            $table->index('tanggal_submit');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registrations');
    }
};
