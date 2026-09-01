<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class FrontendSurveyController extends Controller
{
    /**
     * Store a new satisfaction survey submission from the frontend footer.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'komentar' => 'nullable|string|max:500',
        ]);

        Survey::create([
            'rating' => (int) $request->rating,
            'komentar' => $request->komentar,
            'ip_address' => (string) $request->ip(),
        ]);

        return back()->with('success', 'Terima kasih atas penilaian dan masukan Anda!');
    }
}
