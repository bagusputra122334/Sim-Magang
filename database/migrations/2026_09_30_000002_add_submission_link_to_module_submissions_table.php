<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('module_submissions', function (Blueprint $table) {
            if (!Schema::hasColumn('module_submissions', 'submission_link')) {
                $table->string('submission_link')->nullable()->after('file_path');
            }
            if (!Schema::hasColumn('module_submissions', 'review_note')) {
                $table->text('review_note')->nullable()->after('grade');
            }
            if (!Schema::hasColumn('module_submissions', 'grade_note')) {
                $table->text('grade_note')->nullable()->after('grade');
            }
        });
    }

    public function down(): void
    {
        Schema::table('module_submissions', function (Blueprint $table) {
            if (Schema::hasColumn('module_submissions', 'submission_link')) {
                $table->dropColumn('submission_link');
            }
            if (Schema::hasColumn('module_submissions', 'review_note')) {
                $table->dropColumn('review_note');
            }
            if (Schema::hasColumn('module_submissions', 'grade_note')) {
                $table->dropColumn('grade_note');
            }
        });
    }
};
