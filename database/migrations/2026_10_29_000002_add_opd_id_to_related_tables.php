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
        if (Schema::hasTable('users') && !Schema::hasColumn('users', 'opd_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->foreignId('opd_id')->nullable()->after('id')->constrained('opds')->nullOnDelete();
                $table->index('opd_id');
            });
        }

        if (Schema::hasTable('divisions') && !Schema::hasColumn('divisions', 'opd_id')) {
            Schema::table('divisions', function (Blueprint $table) {
                $table->foreignId('opd_id')->nullable()->after('id')->constrained('opds')->nullOnDelete();
                $table->index('opd_id');
            });
        }

        if (Schema::hasTable('positions') && !Schema::hasColumn('positions', 'opd_id')) {
            Schema::table('positions', function (Blueprint $table) {
                $table->foreignId('opd_id')->nullable()->after('id')->constrained('opds')->nullOnDelete();
                $table->index('opd_id');
            });
        }

        if (Schema::hasTable('registrations') && !Schema::hasColumn('registrations', 'opd_id')) {
            Schema::table('registrations', function (Blueprint $table) {
                $table->foreignId('opd_id')->nullable()->after('id')->constrained('opds')->nullOnDelete();
                $table->index('opd_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('registrations') && Schema::hasColumn('registrations', 'opd_id')) {
            Schema::table('registrations', function (Blueprint $table) {
                $table->dropForeign(['opd_id']);
                $table->dropIndex(['opd_id']);
                $table->dropColumn('opd_id');
            });
        }

        if (Schema::hasTable('positions') && Schema::hasColumn('positions', 'opd_id')) {
            Schema::table('positions', function (Blueprint $table) {
                $table->dropForeign(['opd_id']);
                $table->dropIndex(['opd_id']);
                $table->dropColumn('opd_id');
            });
        }

        if (Schema::hasTable('divisions') && Schema::hasColumn('divisions', 'opd_id')) {
            Schema::table('divisions', function (Blueprint $table) {
                $table->dropForeign(['opd_id']);
                $table->dropIndex(['opd_id']);
                $table->dropColumn('opd_id');
            });
        }

        if (Schema::hasTable('users') && Schema::hasColumn('users', 'opd_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropForeign(['opd_id']);
                $table->dropIndex(['opd_id']);
                $table->dropColumn('opd_id');
            });
        }
    }
};
