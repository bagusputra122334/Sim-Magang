<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Survey;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SurveyController extends Controller
{
    /**
     * Display a listing of satisfaction survey submissions.
     */
    public function index(Request $request): View
    {
        $perPage = (int) $request->input('per_page', 10);
        if (! in_array($perPage, [5, 10, 25, 50], true)) {
            $perPage = 10;
        }

        $query = Survey::query()
            ->when($request->filled('year'), function ($q) use ($request) {
                $q->whereYear('created_at', $request->input('year'));
            })
            ->latest('created_at');

        $surveys = $query->paginate($perPage)->withQueryString();

        $dbYears = Survey::whereNotNull('created_at')
            ->pluck('created_at')
            ->map(fn($dt) => (int) \Illuminate\Support\Carbon::parse($dt)->format('Y'))
            ->unique()
            ->values();

        $currentYear = (int) date('Y');
        $availableYears = $dbYears->merge([$currentYear, $currentYear + 1])
            ->unique()
            ->sortDesc()
            ->values();

        $statistics = [
            'total' => Survey::count(),
            'average' => Survey::avg('rating') ? round((float) Survey::avg('rating'), 1) : 0,
            'counts' => [
                5 => Survey::where('rating', 5)->count(),
                4 => Survey::where('rating', 4)->count(),
                3 => Survey::where('rating', 3)->count(),
                2 => Survey::where('rating', 2)->count(),
                1 => Survey::where('rating', 1)->count(),
            ],
        ];

        return view('admin.surveys.index', compact('surveys', 'statistics', 'availableYears', 'perPage'));
    }

    /**
     * Export IKM survey report to downloadable PDF.
     */
    public function exportPdf(Request $request)
    {
        $year = $request->input('year');

        $query = Survey::query()
            ->when(! empty($year), function ($q) use ($year) {
                $q->whereYear('created_at', $year);
            })
            ->latest('created_at');

        $surveys = $query->get();

        $statsQuery = Survey::query()
            ->when(! empty($year), function ($q) use ($year) {
                $q->whereYear('created_at', $year);
            });

        $statistics = [
            'total' => (clone $statsQuery)->count(),
            'average' => (clone $statsQuery)->avg('rating') ? round((float) (clone $statsQuery)->avg('rating'), 1) : 0,
            'counts' => [
                5 => (clone $statsQuery)->where('rating', 5)->count(),
                4 => (clone $statsQuery)->where('rating', 4)->count(),
                3 => (clone $statsQuery)->where('rating', 3)->count(),
                2 => (clone $statsQuery)->where('rating', 2)->count(),
                1 => (clone $statsQuery)->where('rating', 1)->count(),
            ],
        ];

        $printedAt = now()->translatedFormat('d F Y H:i').' WIB';

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.surveys.pdf', compact('surveys', 'statistics', 'printedAt', 'year'));
        return $pdf->download('Laporan_IKM_SIM_MAGANG_Tuban.pdf');
    }
}
