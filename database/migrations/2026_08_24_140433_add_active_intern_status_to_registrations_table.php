<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            $table->boolean('is_terminated')->default(false)->after('surat_balasan_path');
            $table->text('catatan_penonaktifan')->nullable()->after('is_terminated');
            $table->dateTime('terminated_at')->nullable()->after('catatan_penonaktifan');
        });
    }

    public function down(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            $table->dropColumn(['is_terminated', 'catatan_penonaktifan', 'terminated_at']);
        });
    }
};
