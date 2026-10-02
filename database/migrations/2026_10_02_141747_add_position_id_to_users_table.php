<?php

use App\Models\Position;
use App\Models\User;
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
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('position_id')->nullable()->after('division_id')
                ->constrained('positions')->nullOnDelete()->cascadeOnUpdate();
            $table->index('position_id');
        });

        self::seedPembimbingPositionFromMentorMetadata();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['position_id']);
            $table->dropIndex(['position_id']);
            $table->dropColumn('position_id');
        });
    }

    private static function seedPembimbingPositionFromMentorMetadata(): void
    {
        $pembimbings = User::with(['division'])->where('role', 'pembimbing')->cursor();

        foreach ($pembimbings as $user) {
            $nip = trim((string) $user->nip);
            $nameLower = mb_strtolower(trim((string) $user->name));
            $divNameLower = null;
            if ($user->division) {
                $dn = trim((string) ($user->division->nama_divisi ?? ''));
                if ($dn !== '') $divNameLower = mb_strtolower($dn);
            }

            $byName = collect();
            $byNip = collect();
            $candidates = collect();

            // PRIO-1: Match by mentor_name (case-insensitive) — LEBIH AKURAT
            if ($nameLower !== '') {
                $byName = Position::whereRaw('LOWER(TRIM(mentor_name)) = ?', [$nameLower])
                    ->get(['id','nama_posisi','mentor_name','mentor_nip']);
                if ($byName->isNotEmpty()) $candidates = $byName;
            }

            // PRIO-2: Match by mentor_nip — HANYA JIKA PRIO-1 KOSONG
            if ($candidates->isEmpty() && $nip !== '') {
                $byNip = Position::where('mentor_nip', $nip)
                    ->get(['id','nama_posisi','mentor_name','mentor_nip']);
                if ($byNip->isNotEmpty()) $candidates = $byNip;
            }

            $chosen = null;
            if ($candidates->isNotEmpty()) {
                if ($candidates->count() === 1) {
                    $chosen = $candidates->first();
                } else {
                    if ($divNameLower !== null) {
                        $matchedDiv = $candidates->first(static function($c) use ($divNameLower) {
                            return mb_strtolower(trim((string) $c->nama_posisi)) === $divNameLower;
                        });
                        if ($matchedDiv) $chosen = $matchedDiv;
                    }
                    if ($chosen === null) $chosen = $candidates->first();
                }
            }

            if ($chosen !== null) {
                $user->forceFill(['position_id' => $chosen->id])->save();
            }
        }
    }
};
