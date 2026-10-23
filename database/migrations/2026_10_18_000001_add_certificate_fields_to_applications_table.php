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
        if (Schema::hasTable('registrations')) {
            Schema::table('registrations', function (Blueprint $table) {
                if (!Schema::hasColumn('registrations', 'certificate_path')) {
                    $table->string('certificate_path')->nullable()->after('surat_balasan_path');
                }
                if (!Schema::hasColumn('registrations', 'certificate_issued_at')) {
                    $table->timestamp('certificate_issued_at')->nullable()->after('certificate_path');
                }
            });
        }

        if (Schema::hasTable('applications')) {
            Schema::table('applications', function (Blueprint $table) {
                if (!Schema::hasColumn('applications', 'certificate_path')) {
                    $table->string('certificate_path')->nullable();
                }
                if (!Schema::hasColumn('applications', 'certificate_issued_at')) {
                    $table->timestamp('certificate_issued_at')->nullable();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('registrations')) {
            Schema::table('registrations', function (Blueprint $table) {
                if (Schema::hasColumn('registrations', 'certificate_issued_at')) {
                    $table->dropColumn('certificate_issued_at');
                }
                if (Schema::hasColumn('registrations', 'certificate_path')) {
                    $table->dropColumn('certificate_path');
                }
            });
        }

        if (Schema::hasTable('applications')) {
            Schema::table('applications', function (Blueprint $table) {
                if (Schema::hasColumn('applications', 'certificate_issued_at')) {
                    $table->dropColumn('certificate_issued_at');
                }
                if (Schema::hasColumn('applications', 'certificate_path')) {
                    $table->dropColumn('certificate_path');
                }
            });
        }
    }
};
