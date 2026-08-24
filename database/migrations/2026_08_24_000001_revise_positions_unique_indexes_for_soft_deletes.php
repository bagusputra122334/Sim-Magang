<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Revisi index unik pada tabel positions agar tidak memblokir pembuatan formasi baru
     * dengan nama/slug yang sama dari data yang sudah di-soft-delete (deleted_at IS NOT NULL).
     */
    public function up(): void
    {
        Schema::table('positions', function (Blueprint $table) {
            // Drop rigid single-column unique indexes
            $table->dropUnique('positions_nama_posisi_unique');
            $table->dropUnique('positions_slug_unique');

            // Add normal lookup indexes
            $table->index('nama_posisi', 'positions_nama_posisi_index');
            $table->index('slug', 'positions_slug_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('positions', function (Blueprint $table) {
            $table->dropIndex('positions_nama_posisi_index');
            $table->dropIndex('positions_slug_index');

            $table->unique('nama_posisi', 'positions_nama_posisi_unique');
            $table->unique('slug', 'positions_slug_unique');
        });
    }
};
