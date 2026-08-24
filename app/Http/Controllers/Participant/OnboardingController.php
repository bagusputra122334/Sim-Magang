<?php

namespace App\Http\Controllers\Participant;

use App\Services\ProfileService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OnboardingController extends ParticipantController
{
    public function __construct(protected ProfileService $profileService) {}

    public function welcome(Request $request): View|RedirectResponse
    {
        $user = $request->user();

        if ($this->profileService->userHasProfile($user->id)) {
            return redirect()->route('participant.dashboard');
        }

        return view('participant.onboarding.welcome');
    }

    public function chooseType(Request $request): View|RedirectResponse
    {
        $user = $request->user();

        if ($this->profileService->userHasProfile($user->id)) {
            return redirect()->route('participant.dashboard');
        }

        return view('participant.onboarding.choose-type');
    }

    public function success(Request $request): View|RedirectResponse
    {
        $user = $request->user();

        if (! $this->profileService->userHasProfile($user->id)) {
            return redirect()->route('participant.onboarding.welcome')
                ->with('info', 'Silakan lengkapi profil Anda terlebih dahulu.');
        }

        return view('participant.onboarding.success');
    }
}
