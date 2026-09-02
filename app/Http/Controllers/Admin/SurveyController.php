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
        $surveys = Survey::query()
            ->latest('created_at')
            ->paginate(10)
            ->withQueryString();

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

        return view('admin.surveys.index', compact('surveys', 'statistics'));
    }

    /**
     * Export IKM survey report to downloadable PDF.
     */
    public function exportPdf(Request $request)
    {
        $surveys = Survey::query()
            ->latest('created_at')
            ->get();

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

        $printedAt = now()->translatedFormat('d F Y H:i').' WIB';

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.surveys.pdf', compact('surveys', 'statistics', 'printedAt'));
        return $pdf->download('Laporan_IKM_SIM_MAGANG_Tuban.pdf');
    }
}
