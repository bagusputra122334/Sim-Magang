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
        if (Schema::hasTable('materials') && !Schema::hasColumn('materials', 'files')) {
            Schema::table('materials', function (Blueprint $table) {
                $table->json('files')->nullable()->after('file_materi');
            });
        }

        if (Schema::hasTable('module_submissions') && !Schema::hasColumn('module_submissions', 'files')) {
            Schema::table('module_submissions', function (Blueprint $table) {
                $table->json('files')->nullable()->after('file_path');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('materials') && Schema::hasColumn('materials', 'files')) {
            Schema::table('materials', function (Blueprint $table) {
                $table->dropColumn('files');
            });
        }

        if (Schema::hasTable('module_submissions') && Schema::hasColumn('module_submissions', 'files')) {
            Schema::table('module_submissions', function (Blueprint $table) {
                $table->dropColumn('files');
            });
        }
    }
};

