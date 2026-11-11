<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('materials') && !Schema::hasColumn('materials', 'target_type')) {
            Schema::table('materials', function (Blueprint $table) {
                $table->string('target_type', 20)->default('all')->after('pembimbing_id')->index();
            });
        }

        if (!Schema::hasTable('material_assignments')) {
            Schema::create('material_assignments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('material_id')->constrained('materials')->cascadeOnDelete();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->timestamps();

                $table->unique(['material_id', 'user_id']);
                $table->index('user_id');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('material_assignments');

        if (Schema::hasTable('materials') && Schema::hasColumn('materials', 'target_type')) {
            Schema::table('materials', function (Blueprint $table) {
                $table->dropColumn('target_type');
            });
        }
    }
};

