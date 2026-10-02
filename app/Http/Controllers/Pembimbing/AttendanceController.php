<?php

namespace App\Http\Controllers\Pembimbing;

use App\Http\Controllers\Controller;
use App\Models\DailyCheckin;
use App\Models\Registration;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AttendanceController extends Controller
{
    public function show(Registration $registration)
    {
        $data = $this->loadAttendanceData($registration);

        return view('pembimbing.attendance.show', $data);
    }

    public function exportPdf(Registration $registration)
    {
        $shared = $this->loadAttendanceData($registration);

        $rows = $this->buildRowsArray($shared['start'], $shared['end'], $shared['checkins']);

        $mentor = Auth::user();

        $viewData = [
            'internName'      => $shared['registration']->user?->name ?? '-',
            'internEmail'     => $shared['registration']->user?->email ?? '-',
            'institution'     => $shared['registration']->institution
                                ?? $shared['registration']->user?->profile?->institusi
                                ?? '-',
            'jurusan'         => $shared['registration']->user?->profile?->jurusan ?? null,
            'posisiName'      => $shared['registration']->position?->nama_posisi
                                ?? $shared['registration']->position?->name
                                ?? null,
            'divisiName'      => $shared['registration']->user?->division?->nama_divisi
                                ?? $shared['registration']->user?->division?->name
                                ?? '-',
            'opStatusLabel'   => $shared['registration']->operational_status_label ?? '-',
            'totalPeriodDays' => $shared['totalPeriodDays'],
            'totalWeekendDays'=> $shared['totalWeekendDays'],
            'totalHadir'      => $shared['totalHadir'],
            'totalBelum'      => $shared['totalBelum'],
            'start'           => $shared['start'],
            'end'             => $shared['end'],
            'rows'            => $rows,
            'mentorName'      => $mentor->name,
            'mentorNip'       => $mentor->nip ?? '-',
        ];

        $filename = sprintf(
            'Rekap-Absensi-%s-%s-%s.pdf',
            Str::slug($viewData['internName'] ?: 'peserta'),
            $shared['start']->format('Ymd'),
            $shared['end']->format('Ymd')
        );

        $pdf = Pdf::loadView('pembimbing.attendance.pdf', $viewData)
            ->setPaper('a4', 'portrait')
            ->setOption('defaultFont', 'DejaVu Sans')
            ->setOption('isRemoteEnabled', false)
            ->setOption('isHtml5ParserEnabled', true);

        return $pdf->download($filename);
    }

    /**
     * @return array<string, mixed>
     */
    private function loadAttendanceData(Registration $registration): array
    {
        $registration->loadMissing(['user.profile', 'user.division', 'position']);

        $user = Auth::user();
        abort_unless(optional($registration->user)->division_id === $user->division_id, 403);

        $today = Carbon::now();
        $start = $registration->periode_mulai
            ? $registration->periode_mulai->copy()->startOfDay()
            : $today->copy()->startOfMonth();
        $end = $registration->periode_selesai
            ? $registration->periode_selesai->copy()->endOfDay()
            : $today->copy()->endOfMonth();

        if ($end->lt($start)) {
            $end = $start->copy()->addMonth()->endOfMonth();
        }

        $checkins = DailyCheckin::where('user_id', $registration->user_id)
            ->whereBetween('date', [$start->toDateString(), $end->toDateString()])
            ->orderBy('date', 'asc')
            ->get()
            ->keyBy(static fn (DailyCheckin $c) => $c->date->toDateString());

        $totalPeriodDays = (int) $start->diffInDays($end) + 1;

        $totalWeekendDays = 0;
        $cursor = $start->copy();
        while ($cursor->lte($end)) {
            if ($cursor->isWeekend()) {
                $totalWeekendDays++;
            }
            $cursor->addDay();
        }

        $totalHadir = $checkins->count();
        $totalExpectedWorkDays = $totalPeriodDays - $totalWeekendDays;
        $totalBelum = max(0, $totalExpectedWorkDays - $totalHadir);

        return [
            'registration'    => $registration,
            'checkins'        => $checkins,
            'start'           => $start,
            'end'             => $end,
            'totalPeriodDays' => $totalPeriodDays,
            'totalWeekendDays'=> $totalWeekendDays,
            'totalHadir'      => $totalHadir,
            'totalBelum'      => $totalBelum,
            'mentor'          => $user,
        ];
    }

    /**
     * @param  \Illuminate\Support\Collection<string, DailyCheckin> $checkins
     * @return array<int, array<string, mixed>>
     */
    private function buildRowsArray(Carbon $start, Carbon $end, mixed $checkins): array
    {
        $rows = [];
        $no = 1;
        $cursor = $start->copy();
        while ($cursor->lte($end)) {
            $key = $cursor->toDateString();
            $checkin = $checkins->get($key);
            $isWeekend = $cursor->isWeekend();
            $hasData = (bool) $checkin;

            if ($hasData && ! $isWeekend) {
                $status = 'hadir';
            } elseif ($isWeekend) {
                $status = 'libur';
            } else {
                $status = 'belum';
            }

            $rows[] = [
                'no'        => $no++,
                'date'      => $cursor->translatedFormat('d/m/Y'),
                'day'       => $cursor->translatedFormat('l'),
                'status'    => $status,
                'activity'  => $hasData ? (string) $checkin->activity : null,
                'submitted' => $hasData && $checkin->submitted_at
                    ? $checkin->submitted_at->translatedFormat('H:i:s')
                    : null,
            ];
            $cursor->addDay();
        }

        return $rows;
    }
}
