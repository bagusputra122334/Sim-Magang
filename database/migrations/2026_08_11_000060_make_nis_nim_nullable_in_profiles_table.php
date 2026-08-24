<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->string('nis_nim', 50)->nullable()->change();
        });

        // Clean up any legacy corrupt '[object HTMLInputElement]' values in profiles.nim
        DB::table('profiles')
            ->where('nim', 'like', '%[object%')
            ->update([
                'nim' => DB::raw('nis_nim'),
            ]);
    }

    public function down(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->string('nis_nim', 50)->nullable(false)->change();
        });
    }
};
