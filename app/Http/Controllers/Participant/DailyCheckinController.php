<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use App\Models\DailyCheckin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class DailyCheckinController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $month = $request->input('month', now()->format('Y-m'));
        try {
            $dateRef = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
        } catch (\Throwable) {
            $dateRef = now()->startOfMonth();
        }

        $startOfMonth = $dateRef->copy()->startOfMonth();
        $endOfMonth   = $dateRef->copy()->endOfMonth();

        $checkins = DailyCheckin::where('user_id', $user->id)
            ->whereBetween('date', [$startOfMonth->toDateString(), $endOfMonth->toDateString()])
            ->orderBy('date', 'asc')
            ->get();

        return view('participant.daily-checkins.index', compact('checkins', 'month', 'startOfMonth', 'endOfMonth'));
    }

    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $user = Auth::user();

        $validated = $request->validate([
            'date'     => 'required|date_format:Y-m-d',
            'activity' => 'required|string|min:5|max:5000',
        ]);

        try {
            $dateCheck = Carbon::createFromFormat('Y-m-d', $validated['date']);
        } catch (\Throwable) {
            return response()->json([
                'success' => false,
                'message' => 'Format tanggal tidak valid.',
            ], 422);
        }

        $checkin = DailyCheckin::updateOrCreate(
            [
                'user_id' => $user->id,
                'date'    => $dateCheck->toDateString(),
            ],
            [
                'activity'     => $validated['activity'],
                'submitted_at' => now(),
            ]
        );

        return response()->json([
            'success'      => true,
            'message'      => 'Check-in harian berhasil disimpan.',
            'checkin'      => [
                'id'           => $checkin->id,
                'date'         => $checkin->date->toDateString(),
                'activity'     => $checkin->activity,
                'submitted_at' => $checkin->submitted_at?->toIso8601String(),
                'created_at'   => $checkin->created_at?->toIso8601String(),
                'updated_at'   => $checkin->updated_at?->toIso8601String(),
            ],
        ], 200);
    }

    public function getMonthData(Request $request): \Illuminate\Http\JsonResponse
    {
        $user = Auth::user();

        $month = $request->input('month', now()->format('Y-m'));
        try {
            $dateRef = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
        } catch (\Throwable) {
            $dateRef = now()->startOfMonth();
        }

        $startOfMonth = $dateRef->copy()->startOfMonth();
        $endOfMonth   = $dateRef->copy()->endOfMonth();

        $checkins = DailyCheckin::where('user_id', $user->id)
            ->whereBetween('date', [$startOfMonth->toDateString(), $endOfMonth->toDateString()])
            ->orderBy('date', 'asc')
            ->get()
            ->map(function ($row) {
                return [
                    'id'           => $row->id,
                    'date'         => $row->date->toDateString(),
                    'activity'     => $row->activity,
                    'submitted_at' => $row->submitted_at?->toIso8601String(),
                ];
            });

        return response()->json([
            'success'      => true,
            'month'        => $dateRef->format('Y-m'),
            'month_label'  => $dateRef->locale('id_ID')->isoFormat('MMMM YYYY'),
            'start_date'   => $startOfMonth->toDateString(),
            'end_date'     => $endOfMonth->toDateString(),
            'days_in_month'=> $dateRef->daysInMonth,
            'checkins'     => $checkins,
            'checkin_dates'=> $checkins->pluck('date')->toArray(),
        ], 200);
    }
}
