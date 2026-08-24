<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('positions', function (Blueprint $table) {
            $table->string('mentor_name', 255)->nullable()->after('kuota');
            $table->string('mentor_nip', 30)->nullable()->after('mentor_name');
        });

        // Seed default realistic mentor metadata for existing position records
        $defaultMentors = [
            ['name' => 'Drs. Eko Prasetyo, M.Kom', 'nip' => '19820315 200801 1 004'],
            ['name' => 'Siti Rahmawati, S.ST, M.T.', 'nip' => '19850722 201001 2 012'],
            ['name' => 'Budi Santoso, S.Kom, M.Eng', 'nip' => '19791104 200501 1 008'],
            ['name' => 'Ir. Ahmad Zulkarnain, M.T.', 'nip' => '19810418 200902 1 003'],
            ['name' => 'Dewi Lestari, S.T., M.Sc.', 'nip' => '19870912 201101 2 009'],
        ];

        $positions = DB::table('positions')->get();
        foreach ($positions as $index => $pos) {
            if (empty($pos->mentor_name)) {
                $mentor = $defaultMentors[$index % count($defaultMentors)];
                DB::table('positions')
                    ->where('id', $pos->id)
                    ->update([
                        'mentor_name' => $mentor['name'],
                        'mentor_nip'  => $mentor['nip'],
                    ]);
            }
        }
    }

    public function down(): void
    {
        Schema::table('positions', function (Blueprint $table) {
            $table->dropColumn(['mentor_name', 'mentor_nip']);
        });
    }
};
