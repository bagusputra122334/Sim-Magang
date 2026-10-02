<?php

namespace App\Http\Controllers\Pembimbing;

use App\Http\Controllers\Controller;
use App\Models\Position;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user()->load([
            'division',
            'position',
            'mentoredPositions',
        ]);

        $candidates = collect();
        $nameLower = mb_strtolower(trim((string) ($user->name ?? '')));
        $nip = trim((string) ($user->nip ?? ''));
        $divNameLower = null;
        if ($user->division) {
            $dn = trim((string) ($user->division->nama_divisi ?? ''));
            if ($dn !== '') $divNameLower = mb_strtolower($dn);
        }

        if ($nameLower !== '') {
            $byName = Position::whereRaw('LOWER(TRIM(mentor_name)) = ?', [$nameLower])
                ->limit(10)
                ->get(['id','nama_posisi','mentor_name','mentor_nip']);
            if ($byName->isNotEmpty()) $candidates = $byName;
        }

        if ($candidates->isEmpty() && $nip !== '') {
            $byNip = Position::where('mentor_nip', $nip)
                ->limit(10)
                ->get(['id','nama_posisi','mentor_name','mentor_nip']);
            if ($byNip->isNotEmpty()) $candidates = $byNip;
        }

        $positionLabel = null;

        if ($user->relationLoaded('position') && $user->position !== null) {
            $positionLabel = trim((string) ($user->position->nama_posisi ?? ($user->position->name ?? '')));
        }

        if (($positionLabel === '' || $positionLabel === null) && $candidates->isNotEmpty()) {
            $chosen = null;
            if ($candidates->count() === 1) {
                $chosen = $candidates->first();
            } elseif ($divNameLower !== null) {
                $matchedDiv = $candidates->first(static function($c) use ($divNameLower) {
                    return mb_strtolower(trim((string) $c->nama_posisi)) === $divNameLower;
                });
                $chosen = $matchedDiv ?? $candidates->first();
            } else {
                $chosen = $candidates->first();
            }
            if ($chosen !== null) {
                $positionLabel = trim((string) ($chosen->nama_posisi ?? ''));
            }
        }

        if (($positionLabel === '' || $positionLabel === null) && !empty($user->position_title)) {
            $positionLabel = trim((string) $user->position_title);
        }

        return view('pembimbing.profile.index', compact('user', 'positionLabel'));
    }

    public function updateAvatar(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'avatar' => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ]);

        $user = Auth::user();
        $oldAvatar = $user->avatar;

        try {
            $path = $request->file('avatar')->store('avatars', 'public');

            $user->avatar = $path;
            $user->save();

            if ($oldAvatar !== null && trim($oldAvatar) !== ''
                && !str_starts_with($oldAvatar, 'http://')
                && !str_starts_with($oldAvatar, 'https://')
                && Storage::disk('public')->exists($oldAvatar)) {
                Storage::disk('public')->delete($oldAvatar);
            }
        } catch (\Throwable $e) {
            return back()
                ->with('error', 'Gagal mengunggah foto profil. Silakan coba beberapa saat lagi. Error: ' . $e->getMessage());
        }

        return redirect()->route('pembimbing.profile.index')
            ->with('success', 'Foto profil berhasil diperbarui!');
    }
}
