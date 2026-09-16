<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LandingContent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LandingContentController extends Controller
{
    /**
     * Display a listing of landing page contents.
     */
    public function index(Request $request): View
    {
        $selectedSection = $request->query('section') ?? $request->query('category');
        $search = $request->query('search');

        $query = LandingContent::query();

        if ($selectedSection) {
            $query->where('section', $selectedSection);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $perPage = $request->input('per_page', 10);
        $perPage = (is_numeric($perPage) && $perPage > 0 && $perPage <= 100) ? (int) $perPage : 10;

        $contents = $query->orderBy('section')
            ->orderBy('order', 'asc')
            ->orderBy('id', 'asc')
            ->paginate($perPage)
            ->withQueryString();

        $statistics = [
            'total'     => LandingContent::count(),
            'active'    => LandingContent::where('is_active', true)->count(),
            'about'     => LandingContent::where('section', 'about')->count(),
            'advantage' => LandingContent::where('section', 'advantage')->count(),
            'workflow'  => LandingContent::where('section', 'workflow')->count(),
            'faq'       => LandingContent::where('section', 'faq')->count(),
        ];

        $sections = [
            'about'     => 'Tentang',
            'advantage' => 'Keunggulan',
            'workflow'  => 'Alur Pendaftaran',
            'faq'       => 'FAQ',
        ];

        return view('admin.landing-contents.index', compact(
            'contents',
            'statistics',
            'sections',
            'selectedSection',
            'search',
            'perPage'
        ));
    }

    /**
     * Show the form for creating a new landing content item.
     */
    public function create(): View
    {
        $sections = [
            'about'     => 'Tentang',
            'advantage' => 'Keunggulan',
            'workflow'  => 'Alur Pendaftaran',
            'faq'       => 'FAQ',
        ];

        return view('admin.landing-contents.create', compact('sections'));
    }

    /**
     * Store a newly created landing content item in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'section'     => 'required|string|in:about,advantage,workflow,faq',
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon'        => 'nullable|string|max:255',
            'order'       => 'required|integer|min:0',
            'is_active'   => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        LandingContent::create($validated);

        return redirect()
            ->route('admin.landing-contents.index')
            ->with('success', 'Konten landing page berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified landing content item.
     */
    public function edit(LandingContent $landingContent): View
    {
        $sections = [
            'about'     => 'Tentang',
            'advantage' => 'Keunggulan',
            'workflow'  => 'Alur Pendaftaran',
            'faq'       => 'FAQ',
        ];

        return view('admin.landing-contents.edit', compact('landingContent', 'sections'));
    }

    /**
     * Update the specified landing content item in storage.
     */
    public function update(Request $request, LandingContent $landingContent): RedirectResponse
    {
        $validated = $request->validate([
            'section'     => 'required|string|in:about,advantage,workflow,faq',
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon'        => 'nullable|string|max:255',
            'order'       => 'required|integer|min:0',
            'is_active'   => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $landingContent->update($validated);

        return redirect()
            ->route('admin.landing-contents.index')
            ->with('success', 'Konten landing page berhasil diperbarui.');
    }

    /**
     * Remove the specified landing content item from storage.
     */
    public function destroy(LandingContent $landingContent): RedirectResponse
    {
        $landingContent->delete();

        return redirect()
            ->route('admin.landing-contents.index')
            ->with('success', 'Konten landing page berhasil dihapus.');
    }
}
