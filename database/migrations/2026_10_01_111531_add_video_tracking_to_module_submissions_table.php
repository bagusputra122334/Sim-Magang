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
        Schema::table('module_submissions', function (Blueprint $table) {
            $table->boolean('is_video_watched')->default(false)->after('status');
            $table->timestamp('video_watched_at')->nullable()->after('is_video_watched');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('module_submissions', function (Blueprint $table) {
            $table->dropColumn(['is_video_watched', 'video_watched_at']);
        });
    }
};
