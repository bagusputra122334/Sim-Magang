<?php

namespace App\Http\Controllers\Participant;

use App\Http\Requests\Participant\StoreProfileRequest;
use App\Http\Requests\Participant\UpdateProfileRequest;
use App\Models\Profile;
use App\Services\ProfileService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfileController extends ParticipantController
{
    public function __construct(protected ProfileService $profileService) {}

    public function chooseType(Request $request): View|RedirectResponse
    {
        $user = $request->user();

        if ($this->profileService->userHasProfile($user->id)) {
            return redirect()->route('participant.dashboard')
                ->with('info', 'Profil Anda sudah lengkap.');
        }

        return view('participant.profile.choose-type');
    }

    public function index(Request $request): View|RedirectResponse
    {
        $user = $request->user();

        if (! $this->profileService->userHasProfile($user->id)) {
            return redirect()->route('participant.profile.choose-type')
                ->with('info', 'Silakan pilih kategori peserta terlebih dahulu sebelum melengkapi profil.');
        }

        $profile = $this->profileService->getProfileByUserOrFail($user->id);

        return view('participant.profile.index', compact('profile'));
    }

    public function show(Request $request): View|RedirectResponse
    {
        return $this->index($request);
    }

    public function create(Request $request): View|RedirectResponse
    {
        $user = $request->user();

        if ($this->profileService->userHasProfile($user->id)) {
            return redirect()->route('participant.dashboard')
                ->with('info', 'Anda sudah memiliki profil. Silakan edit jika perlu perubahan.');
        }

        $rawType = strtolower((string) $request->query('type', 'university'));
        $participantType = in_array($rawType, ['student', 'siswa'], true) ? 'student' : 'university';

        return view('participant.profile.create', compact('participantType'));
    }

    public function store(StoreProfileRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        try {
            $profile = $this->profileService->createProfile($request->user(), $validated);
        } catch (\Throwable $e) {
            return back()->withInput()
                ->with('error', 'Gagal menyimpan profil. Silakan coba beberapa saat lagi. Error: '.$e->getMessage());
        }

        return redirect()->route('participant.onboarding.success')
            ->with('success', 'Profil Anda berhasil disimpan! Anda kini dapat mengajukan pendaftaran magang.');
    }

    public function edit(Request $request): View|RedirectResponse
    {
        $user = $request->user();

        if (! $this->profileService->userHasProfile($user->id)) {
            return redirect()->route('participant.profile.choose-type')
                ->with('info', 'Anda belum memiliki profil. Silakan pilih kategori peserta.');
        }

        $profile = $this->profileService->getProfileByUserOrFail($user->id);

        return view('participant.profile.edit', compact('profile'));
    }

    public function update(UpdateProfileRequest $request, ?Profile $profile = null): RedirectResponse
    {
        $validated = $request->validated();
        $user = $request->user();

        if ($profile === null) {
            $profile = $this->profileService->getProfileByUserOrFail($user->id);
        }

        try {
            $this->profileService->updateProfile($profile, $user->id, $validated);
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            abort(403, $e->getMessage());
        } catch (\Throwable $e) {
            return back()->withInput()
                ->with('error', 'Gagal memperbarui profil. Silakan coba beberapa saat lagi. Error: '.$e->getMessage());
        }

        return redirect()->route('participant.profile.index')
            ->with('success', 'Profil Anda berhasil diperbarui!');
    }

    public function destroy(): never
    {
        abort(404);
    }
}
