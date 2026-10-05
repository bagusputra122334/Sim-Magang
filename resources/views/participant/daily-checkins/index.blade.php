@extends('layouts.participant')
@section('title', 'Absensi Harian - Aktivitas Magang')
@section('content')
@php
    $today = \Illuminate\Support\Carbon::now()->locale('id_ID');
    $defaultMonth = $startOfMonth->format('Y-m');
    $defaultMonthLabel = $startOfMonth->locale('id_ID')->isoFormat('MMMM YYYY');
@endphp
<div class="max-w-7xl mx-auto px-0 pt-0 pb-6" id="dc-root">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div class="d-flex align-items-center gap-3 flex-wrap">
            <h1 class="h2 fw-bold mb-0 text-gray-900" style="letter-spacing: -0.02em;">
                <i class="bi bi-calendar2-check me-2 text-primary"></i>
                Absensi Harian
            </h1>
            <span class="badge bg-primary-subtle text-primary border border-primary rounded-pill px-3 py-2 small fw-bold">
                <i class="bi bi-calendar-event me-1"></i>Hari ini: {{ $today->isoFormat('dddd, D MMM YYYY') }}
            </span>
        </div>
        <div class="d-flex align-items-center gap-2">
            <button type="button" id="dc-prev-month" class="btn btn-outline-secondary btn-sm fw-semibold rounded-3 px-3 py-2 d-inline-flex align-items-center gap-1 shadow-sm">
                <i class="bi bi-chevron-left"></i>
                <span class="d-none d-sm-inline">Bulan Lalu</span>
            </button>
            <select id="dc-month-picker" class="form-select form-select-sm fw-semibold rounded-3 px-3 py-2 border border-secondary shadow-sm" style="min-width: 180px;">
                @for($i = -6; $i <= 2; $i++)
                    @php
                        $d = \Illuminate\Support\Carbon::now()->addMonthsNoOverflow($i)->startOfMonth();
                    @endphp
                    <option value="{{ $d->format('Y-m') }}" @selected($d->format('Y-m') === $defaultMonth)>{{ $d->locale('id_ID')->isoFormat('MMMM YYYY') }}</option>
                @endfor
            </select>
            <button type="button" id="dc-next-month" class="btn btn-outline-secondary btn-sm fw-semibold rounded-3 px-3 py-2 d-inline-flex align-items-center gap-1 shadow-sm">
                <span class="d-none d-sm-inline">Bulan Depan</span>
                <i class="bi bi-chevron-right"></i>
            </button>
        </div>
    </div>

    {{-- STATISTICS ROW (UNIFORM SLIM 1-ROW LAYOUT — HORIZONTALLY ALIGNED) --}}
    @if(isset($isActive) && !$isActive)
    <div class="card border-0 rounded-4 shadow-sm overflow-hidden text-center py-5 mb-4 mt-2">
        <div class="card-body">
            <div class="mb-3">
                <div class="d-inline-flex align-items-center justify-content-center rounded-circle" style="width: 80px; height: 80px; background-color: #fffbeb;">
                    <i class="bi bi-shield-lock-fill text-warning" style="font-size: 2.5rem;"></i>
                </div>
            </div>
            <h4 class="fw-bold text-gray-900 mb-2">Akses Terbatas</h4>
            <p class="text-muted fs-6 mx-auto mb-0" style="max-width: 550px; line-height: 1.6;">
                Akun Anda belum aktif atau belum ditempatkan pada divisi magang. Fitur absensi harian akan terbuka otomatis setelah status magang Anda disetujui oleh admin.
            </p>
        </div>
    </div>
    @else
    <div class="row g-2 mb-3" id="stats-row">
        @php
            $_statCards = [
                [
                    'icon'    => 'bi-calendar3',
                    'label'   => 'Total Hari',
                    'iconBg'  => '#dbeafe',
                    'iconFg'  => '#1d4ed8',
                    'valueId' => 'stat-days-total',
                    'value'   => $startOfMonth->daysInMonth,
                    'suffix'  => 'hari',
                    'valueFg' => '#0f172a',
                ],
                [
                    'icon'    => 'bi-check-circle-fill',
                    'label'   => 'Sudah Absen',
                    'iconBg'  => '#d1fae5',
                    'iconFg'  => '#065f46',
                    'valueId' => 'stat-days-done',
                    'value'   => $checkins->count(),
                    'suffix'  => 'hari',
                    'valueFg' => '#059669',
                ],
                [
                    'icon'    => 'bi-x-circle-fill',
                    'label'   => 'Belum Absen',
                    'iconBg'  => '#fee2e2',
                    'iconFg'  => '#991b1b',
                    'valueId' => 'stat-days-miss',
                    'value'   => max(0, $startOfMonth->daysInMonth - $checkins->count()),
                    'suffix'  => 'hari',
                    'valueFg' => '#dc2626',
                ],
            ];
            $_progressPct = $startOfMonth->daysInMonth > 0 ? (int) round(($checkins->count() / $startOfMonth->daysInMonth) * 100) : 0;
        @endphp

        @foreach($_statCards as $_s)
        <div class="col-6 col-lg-3">
            <div class="card shadow-sm border rounded-3 overflow-hidden h-100">
                <div class="card-body py-2 px-3 px-lg-3">
                    <div class="d-flex align-items-center justify-content-between gap-2 flex-nowrap">
                        <div class="d-flex align-items-center gap-2 min-w-0 flex-grow-1">
                            <div class="d-flex align-items-center justify-content-center rounded-2 flex-shrink-0" style="width: 1.75rem; height: 1.75rem; background-color: {{ $_s['iconBg'] }} !important;">
                                <i class="bi {{ $_s['icon'] }}" style="font-size: 0.9rem !important; color: {{ $_s['iconFg'] }} !important;"></i>
                            </div>
                            <small class="text-muted fw-semibold text-uppercase text-truncate" style="letter-spacing: 0.04em; font-size: 0.6875rem;">{{ $_s['label'] }}</small>
                        </div>
                        <div class="d-flex align-items-baseline gap-1 flex-shrink-0">
                            <span class="mb-0 fw-bold d-inline-block" id="{{ $_s['valueId'] }}" style="font-size: 1.25rem !important; line-height: 1 !important; color: {{ $_s['valueFg'] }} !important;">{{ $_s['value'] }}</span>
                            <small class="text-muted fw-medium" style="font-size: 0.7rem !important; line-height: 1;">{{ $_s['suffix'] }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach

        {{-- Card ke-4: Progres (dengan mini progress bar bawah) — tetap tinggi sama berkat padding & layout seragam --}}
        <div class="col-6 col-lg-3">
            <div class="card shadow-sm border rounded-3 overflow-hidden h-100">
                <div class="card-body py-2 px-3 px-lg-3">
                    <div class="d-flex align-items-center justify-content-between gap-2 flex-nowrap mb-1">
                        <div class="d-flex align-items-center gap-2 min-w-0 flex-grow-1">
                            <div class="d-flex align-items-center justify-content-center rounded-2 flex-shrink-0" style="width: 1.75rem; height: 1.75rem; background-color: #ede9fe !important;">
                                <i class="bi bi-percent" style="font-size: 0.9rem !important; color: #5b21b6 !important;"></i>
                            </div>
                            <small class="text-muted fw-semibold text-uppercase text-truncate" style="letter-spacing: 0.04em; font-size: 0.6875rem;">Progres</small>
                        </div>
                        <div class="d-flex align-items-baseline gap-0.5 flex-shrink-0">
                            <span class="mb-0 fw-bold d-inline-block" id="stat-percent" style="font-size: 1.25rem !important; line-height: 1 !important; color: #6d28d9 !important;">{{ $_progressPct }}</span>
                            <small class="text-muted fw-medium" style="font-size: 0.7rem !important; line-height: 1;">%</small>
                        </div>
                    </div>
                    <div class="progress rounded-pill" style="height: 3px !important; background-color: #e5e7eb !important;">
                        <div id="stat-progress-bar" class="progress-bar rounded-pill" role="progressbar" style="width: {{ $_progressPct }}%; background-color: #6d28d9 !important;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        {{-- ========================================= --}}
        {{-- KIRI: KALENDER GRID + RIWAYAT (col-lg-8) --}}
        {{-- ========================================= --}}
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-4 rounded-4 overflow-hidden">
                <div class="card-header bg-white border-bottom py-3 px-4 d-flex flex-wrap align-items-center justify-content-between gap-3">
                    <h6 class="m-0 fw-bold text-primary fs-5">
                        <i class="bi bi-grid-3x3-gap me-2"></i>Kalender Aktivitas
                        <span class="ms-2 badge rounded-pill px-3 py-1.5 small fw-bold" id="calendar-month-label" style="background-color: #e0e7ff !important; color: #3730a3 !important;">
                            {{ $defaultMonthLabel }}
                        </span>
                    </h6>
                    <div class="flex flex-wrap items-center gap-3 text-sm">
                        <div class="flex items-center gap-1.5">
                            <span class="inline-block rounded w-3.5 h-3.5 bg-gray-200"></span>
                            <span class="text-gray-500 font-semibold">Belum</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="inline-block rounded w-3.5 h-3.5 bg-emerald-500"></span>
                            <span class="text-gray-500 font-semibold">Sudah Absen</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="inline-block rounded w-3.5 h-3.5 bg-blue-600"></span>
                            <span class="text-gray-500 font-semibold">Aktif/Dipilih</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="inline-block rounded w-3.5 h-3.5 bg-red-500"></span>
                            <span class="text-gray-500 font-semibold">Libur/Akhir Pekan</span>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="mb-3">
                        <div class="grid grid-cols-7 gap-1.5 text-center font-semibold mb-2">
                            @foreach(['Sen','Sel','Rab','Kam','Jum','Sab','Min'] as $dowLabel)
                                <div class="flex items-center justify-center py-1">
                                    <small class="uppercase font-bold text-gray-500 tracking-[0.08em] text-[0.65rem]">{{ $dowLabel }}</small>
                                </div>
                            @endforeach
                        </div>
                        <div id="dc-calendar-grid" class="grid grid-cols-7 gap-1.5"></div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
                <div class="card-header bg-white border-bottom py-3 px-4 d-flex flex-wrap align-items-center justify-content-between gap-3">
                    <h6 class="m-0 fw-bold text-indigo fs-5">
                        <i class="bi bi-clock-history me-2"></i>Riwayat Aktivitas Bulan Ini
                    </h6>
                    <span class="badge rounded-pill px-3 py-1.5 small fw-bold" id="history-count-badge" style="background-color: #eef2ff !important; color: #3730a3 !important;">
                        {{ $checkins->count() }} entri
                    </span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive" style="max-height: 480px; overflow-y: auto;">
                        <table class="table mb-0 align-middle" id="dc-history-table">
                            <thead class="bg-light sticky-top" style="z-index: 1;">
                                <tr>
                                    <th class="fw-bold text-uppercase small text-muted px-3 py-2" style="letter-spacing: 0.05em; width: 18%;">Tanggal</th>
                                    <th class="fw-bold text-uppercase small text-muted px-3 py-2" style="letter-spacing: 0.05em;">Uraian Kegiatan</th>
                                    <th class="fw-bold text-uppercase small text-muted px-3 py-2" style="letter-spacing: 0.05em; width: 14%;">Status</th>
                                    <th class="fw-bold text-uppercase small text-muted px-3 py-2" style="letter-spacing: 0.05em; width: 12%;">Jam Submit</th>
                                </tr>
                            </thead>
                            <tbody id="dc-history-tbody">
                                @forelse($checkins->sortByDesc('date') as $ci)
                                    <tr data-date="{{ $ci->date->toDateString() }}" class="border-bottom border-gray-100">
                                        <td class="px-3 py-2">
                                            <div class="fw-bold text-gray-900">{{ $ci->date->locale('id_ID')->isoFormat('ddd, D MMM YYYY') }}</div>
                                        </td>
                                        <td class="px-3 py-2">
                                            <div class="text-gray-800 lh-base" style="white-space: pre-wrap; word-break: break-word;">{!! nl2br(e(\Illuminate\Support\Str::limit($ci->activity, 240))) !!}</div>
                                        </td>
                                        <td class="px-3 py-2">
                                            <span class="d-inline-flex align-items-center px-2.5 py-1 small fw-bold rounded-pill border" style="background-color: #d1fae5 !important; color: #065f46 !important; border-color: #6ee7b7 !important;">
                                                <i class="bi bi-check-circle-fill me-1"></i>Terkirim
                                            </span>
                                        </td>
                                        <td class="px-3 py-2">
                                            <div class="d-flex align-items-center gap-1 fw-bold text-gray-700">
                                                <i class="bi bi-alarm small text-muted"></i>
                                                <span>{{ $ci->submitted_at ? $ci->submitted_at->format('H:i') : '—' }}</span>
                                                @if($ci->submitted_at)
                                                    <small class="fw-semibold text-muted" style="font-size: 0.72rem; letter-spacing: 0.02em;">WIB</small>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr id="dc-empty-row">
                                        <td colspan="4" class="px-3 py-5 text-center text-muted">
                                            <i class="bi bi-inbox fs-1 d-block mb-2 text-gray-300"></i>
                                            Belum ada aktivitas absensi pada bulan ini. Klik tanggal di kalender untuk mulai.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- ========================================= --}}
        {{-- KANAN: FORM URAIAN KEGIATAN (col-lg-4)   --}}
        {{-- ========================================= --}}
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 rounded-4 overflow-hidden sticky-top" style="top: 1rem;">
                <div class="card-header bg-white border-bottom py-2 px-4">
                    <h6 class="m-0 fw-bold text-success fs-5">
                        <i class="bi bi-pencil-square me-2"></i>Form Uraian Kegiatan
                    </h6>
                </div>
                <div class="card-body py-3 px-4">
                    <div class="mb-3 p-2.5 rounded-3 border" style="background-color: #f8fafc !important; border-color: #e2e8f0 !important;">
                        <small class="text-uppercase fw-bold text-muted d-block mb-0.5" style="letter-spacing: 0.05em; font-size: 0.7rem;">Tanggal Aktif</small>
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-calendar-date text-primary fs-5 flex-shrink-0"></i>
                            <h5 class="mb-0 fw-bold text-gray-900" id="dc-active-date-label" style="font-size: 1.05rem !important;">{{ $today->isoFormat('D MMMM YYYY') }}</h5>
                        </div>
                    </div>

                    <form id="dc-checkin-form" autocomplete="off" novalidate>
                        <div class="mb-2">
                            <label for="dc-activity" class="form-label fw-bold mb-1">
                                <i class="bi bi-list-task me-1 text-indigo"></i>Uraian Kegiatan
                                <span class="text-danger ms-1">*</span>
                            </label>
                            <textarea
                                id="dc-activity"
                                name="activity"
                                rows="7"
                                class="form-control rounded-3"
                                required
                                minlength="5"
                                maxlength="5000"
                                placeholder="Jelaskan secara rinci kegiatan magang Anda hari ini (misal: Memperbaiki bug login page, Membuat migration untuk tabel users, dsb.)"></textarea>
                            <div class="d-flex justify-content-between mt-1">
                                <small class="text-muted" style="font-size: 0.7rem;"><i class="bi bi-info-circle me-1"></i>Min. 5 karakter</small>
                                <small class="fw-semibold" id="dc-char-count" style="color: #64748b !important; font-size: 0.7rem;">0 / 5000</small>
                            </div>
                            <div class="invalid-feedback mt-0.5" id="dc-activity-error" style="font-size: 0.75rem;"></div>
                        </div>

                        <button type="submit" id="dc-submit-btn"
                                class="btn btn-success w-100 fw-bold py-2 rounded-3 shadow-sm d-inline-flex align-items-center justify-content-center gap-2 text-white transition-all"
                                style="background-color: #059669 !important; --bs-btn-bg: #059669 !important; --bs-btn-hover-bg: #047857 !important; --bs-btn-border-color: #059669 !important; --bs-btn-hover-border-color: #047857 !important; font-size: 0.95rem;">
                            <span id="dc-submit-icon" class="d-inline-flex align-items-center">
                                <i class="bi bi-cloud-arrow-up-fill"></i>
                            </span>
                            <span id="dc-submit-text">Simpan Absensi</span>
                        </button>
                    </form>

                    <div id="dc-form-alert" class="mt-2 d-none"></div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
(function () {
    'use strict';

    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const storeEndpoint = @json(route('participant.daily-checkins.store'));
    const monthlyEndpoint = @json(route('participant.daily-checkins.monthly'));

    const MONTH_NAMES = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
    const DAY_NAMES_SHORT = ['Sen','Sel','Rab','Kam','Jum','Sab','Min'];
    const DAY_NAMES_LONG  = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'];

    function pad(n){ return n < 10 ? '0'+n : ''+n; }
    function fmtYMD(d){ return d.getFullYear()+'-'+pad(d.getMonth()+1)+'-'+pad(d.getDate()); }
    function fmtHumanLong(d){ return d.getDate()+' '+MONTH_NAMES[d.getMonth()]+' '+d.getFullYear(); }
    function fmtHumanDmy(d){ return (DAY_NAMES_SHORT[getDowIdx0FromMonday(d)]||'')+', '+d.getDate()+' '+(MONTH_NAMES[d.getMonth()].slice(0,3))+' '+d.getFullYear(); }

    function getDowIdx0FromMonday(d){
        var jsDow = d.getDay();
        return (jsDow + 6) % 7;
    }

    var state = {
        activeMonth : @json($defaultMonth),
        todayStr    : @json(\Illuminate\Support\Carbon::now()->format('Y-m-d')),
        activeDate  : @json(\Illuminate\Support\Carbon::now()->format('Y-m-d')),
        periodeMulai: @json($periodeMulai ?? null),
        holidays    : @json($holidays ?? []),
        checkins    : {},
        checkinArr  : []
    };

    @foreach($checkins as $ci)
        state.checkins[@json($ci->date->toDateString())] = {
            id: {{ $ci->id }},
            activity: @json($ci->activity),
            submitted_at: @json($ci->submitted_at ? $ci->submitted_at->toIso8601String() : null),
            created_at: @json($ci->created_at ? $ci->created_at->toIso8601String() : null)
        };
    @endforeach

    function parseCheckinsFromMapToArray(){
        state.checkinArr = Object.keys(state.checkins).map(function(k){
            return Object.assign({ date: k }, state.checkins[k]);
        }).sort(function(a,b){ return a.date < b.date ? 1 : -1; });
    }
    parseCheckinsFromMapToArray();

    var els = {
        grid      : document.getElementById('dc-calendar-grid'),
        monthLabel: document.getElementById('calendar-month-label'),
        picker    : document.getElementById('dc-month-picker'),
        prevBtn   : document.getElementById('dc-prev-month'),
        nextBtn   : document.getElementById('dc-next-month'),
        activeLbl : document.getElementById('dc-active-date-label'),
        form      : document.getElementById('dc-checkin-form'),
        textarea  : document.getElementById('dc-activity'),
        charCount : document.getElementById('dc-char-count'),
        submitBtn : document.getElementById('dc-submit-btn'),
        submitTxt : document.getElementById('dc-submit-text'),
        submitIco : document.getElementById('dc-submit-icon'),
        alertBox  : document.getElementById('dc-form-alert'),
        activityErr: document.getElementById('dc-activity-error'),
        tbody     : document.getElementById('dc-history-tbody'),
        emptyRow  : document.getElementById('dc-empty-row'),
        histBadge : document.getElementById('history-count-badge'),
        statDaysTotal: document.getElementById('stat-days-total'),
        statDaysDone : document.getElementById('stat-days-done'),
        statDaysMiss : document.getElementById('stat-days-miss'),
        statPercent  : document.getElementById('stat-percent'),
        statProgress : document.getElementById('stat-progress-bar')
    };

    function updateStats(){
        var year = +state.activeMonth.slice(0,4);
        var mo = +state.activeMonth.slice(5,7)-1;
        var firstOf = new Date(year, mo, 1);
        var daysIn = firstOf.getDaysInMonth ? firstOf.getDaysInMonth() : new Date(year, mo+1, 0).getDate();

        var doneCount = 0;
        var startStr = fmtYMD(new Date(year,mo,1));
        var endStr   = fmtYMD(new Date(year,mo,daysIn));
        Object.keys(state.checkins).forEach(function(k){
            if (k >= startStr && k <= endStr) doneCount++;
        });
        var miss = Math.max(0, daysIn - doneCount);
        var pct  = daysIn > 0 ? Math.round((doneCount / daysIn) * 100) : 0;

        if (els.statDaysTotal) els.statDaysTotal.textContent = daysIn;
        if (els.statDaysDone)  els.statDaysDone.textContent = doneCount;
        if (els.statDaysMiss)  els.statDaysMiss.textContent = miss;
        if (els.statPercent)   els.statPercent.innerHTML = pct + '<small class="ms-0.5" style="font-size: 0.7rem !important; line-height: 1;">%</small>';
        if (els.statProgress)  els.statProgress.style.width = pct + '%';
    }

    function renderCalendar(){
        var year = +state.activeMonth.slice(0,4);
        var mo = +state.activeMonth.slice(5,7)-1;
        var firstOf = new Date(year, mo, 1);
        var daysIn = new Date(year, mo+1, 0).getDate();
        var startIdx = getDowIdx0FromMonday(firstOf);

        if (els.monthLabel){
            els.monthLabel.textContent = MONTH_NAMES[mo]+' '+year;
        }

        var totalCells = Math.ceil((startIdx + daysIn) / 7) * 7;
        var html = '';

        for (var i = 0; i < totalCells; i++){
            var dayNum = i - startIdx + 1;
            var isInMonth = (dayNum >= 1 && dayNum <= daysIn);
            var curDateObj = isInMonth ? new Date(year, mo, dayNum) : null;
            var dateStr = isInMonth ? fmtYMD(curDateObj) : null;
            var hasCheckin = !!(dateStr && state.checkins[dateStr]);
            var isActive = (dateStr === state.activeDate);
            var dowIdx = curDateObj ? getDowIdx0FromMonday(curDateObj) : -1;
            var isWeekend = (dowIdx === 5 || dowIdx === 6);
            var holidayName = dateStr && state.holidays[dateStr] ? state.holidays[dateStr] : null;
            var isWeekendOrHoliday = isWeekend || !!holidayName;

            if (!isInMonth){
                html += '<div class="w-9 h-9"></div>';
                continue;
            }

            var base = [
                'dc-day-btn',
                'border-0',
                'p-0',
                'w-9',
                'h-9',
                'mx-auto',
                'flex',
                'items-center',
                'justify-center',
                'rounded-md',
                'text-xs',
                'font-medium',
                'transition-all',
                'cursor-pointer',
                'relative',
                'select-none'
            ];

            var colors = [];
            if (isActive) {
                colors = ['bg-blue-600', 'text-white', 'font-bold'];
            } else if (hasCheckin) {
                colors = ['bg-emerald-500', 'text-white', 'font-semibold'];
            } else if (isWeekendOrHoliday) {
                colors = ['bg-red-500', 'text-white', 'font-semibold'];
            } else {
                colors = ['bg-gray-100', 'text-gray-700', 'hover:bg-gray-200'];
            }

            var cls = base.concat(colors).join(' ');

            var titleTxt = fmtHumanDmy(curDateObj);
            if (hasCheckin) titleTxt += ' • Sudah absen';
            else if (isWeekendOrHoliday) {
                if (holidayName) titleTxt += ' • Libur: ' + holidayName;
                else titleTxt += ' • Libur/Akhir Pekan';
            }
            else titleTxt += ' • Belum diisi';
            if (dateStr === state.todayStr) titleTxt += ' • Hari Ini';

            html +=
                '<button type="button" class="'+cls+'" ' +
                    'data-date="'+dateStr+'" ' +
                    'title="'+titleTxt+'">' +
                    '<span class="dc-day-num">'+dayNum+'</span>' +
                    (hasCheckin ? '<i class="dc-day-check bi bi-check2-all absolute top-0 right-0.5 leading-none text-white text-[10px]"></i>' : '') +
                '</button>';
        }
        if (els.grid) els.grid.innerHTML = html;

        var btns = els.grid ? els.grid.querySelectorAll('.dc-day-btn') : [];
        btns.forEach(function(btn){
            btn.addEventListener('click', function(){
                var dt = btn.getAttribute('data-date');
                if (!dt) return;
                selectActiveDate(dt);
            });
        });
    }

    function selectActiveDate(dateStr){
        if (!dateStr) return;
        state.activeDate = dateStr;
        var d = new Date(dateStr.slice(0,4), +dateStr.slice(5,7)-1, +dateStr.slice(8,10));
        if (els.activeLbl) els.activeLbl.textContent = fmtHumanLong(d);

        var existing = state.checkins[dateStr] || null;
        var hasData = !!(existing && existing.activity);

        if (els.textarea){
            els.textarea.value = hasData ? String(existing.activity) : '';
            updateCharCount();
        }
        setFormAlert(null);
        setTextareaError(null);

        if (state.periodeMulai && dateStr < state.periodeMulai) {
            if (els.textarea) {
                els.textarea.disabled = true;
                els.textarea.placeholder = 'Belum Dimulai - Anda tidak dapat mengisi absensi sebelum tanggal mulai magang.';
            }
            if (els.submitBtn) els.submitBtn.disabled = true;
            if (els.submitTxt) els.submitTxt.textContent = 'Belum Dimulai';
            if (els.submitIco) els.submitIco.innerHTML = '<i class="bi bi-lock-fill"></i>';
        } else {
            if (els.textarea) {
                els.textarea.disabled = false;
                els.textarea.placeholder = 'Jelaskan secara rinci kegiatan magang Anda hari ini (misal: Memperbaiki bug login page, Membuat migration untuk tabel users, dsb.)';
            }
            if (els.submitBtn) els.submitBtn.disabled = false;
            if (els.submitTxt) {
                els.submitTxt.textContent = hasData ? 'Perbarui Absensi' : 'Simpan Absensi';
            }
            if (els.submitIco) {
                els.submitIco.innerHTML = '<i class="bi bi-cloud-arrow-up-fill"></i>';
            }
        }

        renderCalendar();
        if (els.textarea && (!state.periodeMulai || dateStr >= state.periodeMulai)) {
            try { els.textarea.focus(); } catch(e){}
        }
    }

    function updateCharCount(){
        if (!els.textarea || !els.charCount) return;
        var len = (els.textarea.value || '').length;
        els.charCount.textContent = len + ' / 5000';
        if (len > 4500) els.charCount.style.color = '#b45309 !important';
        else if (len < 5) els.charCount.style.color = '#991b1b !important';
        else els.charCount.style.color = '#065f46 !important';
    }

    function setFormAlert(type, message){
        if (!els.alertBox) return;
        if (!type || !message){
            els.alertBox.className = 'mt-3 d-none';
            els.alertBox.innerHTML = '';
            return;
        }
        var clsMap = {
            success: 'alert alert-success border-2 border-success rounded-3 d-flex align-items-start',
            error  : 'alert alert-danger border-2 border-danger rounded-3 d-flex align-items-start',
            info   : 'alert alert-info border-2 border-info rounded-3 d-flex align-items-start'
        };
        var iconMap = { success: 'bi-check-circle-fill', error: 'bi-x-circle-fill', info: 'bi-info-circle-fill' };
        var headingMap = { success: 'Berhasil!', error: 'Gagal!', info: 'Info' };
        els.alertBox.className = 'mt-3 ' + (clsMap[type] || clsMap.info);
        els.alertBox.innerHTML = '<i class="bi '+(iconMap[type]||'')+' fs-4 me-2 flex-shrink-0 mt-0.5"></i>' +
            '<div><h6 class="alert-heading fw-bold mb-1">'+(headingMap[type]||'')+'</h6><p class="mb-0 small">'+message+'</p></div>';
    }

    function setTextareaError(msg){
        if (!els.textarea) return;
        if (!msg){
            els.textarea.classList.remove('is-invalid');
            if (els.activityErr) els.activityErr.textContent = '';
            return;
        }
        els.textarea.classList.add('is-invalid');
        if (els.activityErr) els.activityErr.textContent = msg;
    }

    function setSubmitLoading(isLoading){
        if (!els.submitBtn || !els.submitTxt || !els.submitIco) return;
        if (isLoading){
            els.submitBtn.disabled = true;
            els.submitBtn.style.opacity = '0.75';
            els.submitTxt.textContent = 'Menyimpan...';
            els.submitIco.innerHTML = '<span class="spinner-border spinner-border-sm text-white" role="status" aria-hidden="true"></span>';
        } else {
            els.submitBtn.disabled = false;
            els.submitBtn.style.opacity = '';
            var existing = state.checkins[state.activeDate];
            els.submitTxt.textContent = existing ? 'Perbarui Absensi' : 'Simpan Absensi';
            els.submitIco.innerHTML = '<i class="bi bi-cloud-arrow-up-fill"></i>';
        }
    }

    function upsertHistoryRow(dateStr, payload){
        if (!els.tbody) return;
        var d = new Date(dateStr.slice(0,4), +dateStr.slice(5,7)-1, +dateStr.slice(8,10));
        var submittedAt = payload.submitted_at ? new Date(payload.submitted_at) : null;
        var jamStr = submittedAt ? pad(submittedAt.getHours())+':'+pad(submittedAt.getMinutes()) : '—';
        var dateLabel = (DAY_NAMES_SHORT[getDowIdx0FromMonday(d)]||'')+', '+d.getDate()+' '+(MONTH_NAMES[d.getMonth()].slice(0,3))+' '+d.getFullYear();

        var activityEsc = (payload.activity||'')
            .replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;')
            .replace(/\r\n|\r|\n/g,'<br>');
        if (activityEsc.length > 280) activityEsc = activityEsc.slice(0,280) + '...';

        var existingTr = els.tbody.querySelector('tr[data-date="'+dateStr+'"]');
        var htmlRow = '<tr data-date="'+dateStr+'" class="border-bottom border-gray-100">' +
            '<td class="px-3 py-2">' +
                '<div class="fw-bold text-gray-900">'+dateLabel+'</div>' +
            '</td>' +
            '<td class="px-3 py-2">' +
                '<div class="text-gray-800 lh-base" style="white-space: pre-wrap; word-break: break-word;">'+activityEsc+'</div>' +
            '</td>' +
            '<td class="px-3 py-2">' +
                '<span class="d-inline-flex align-items-center px-2.5 py-1 small fw-bold rounded-pill border" style="background-color: #d1fae5 !important; color: #065f46 !important; border-color: #6ee7b7 !important;">' +
                    '<i class="bi bi-check-circle-fill me-1"></i>Terkirim' +
                '</span>' +
            '</td>' +
            '<td class="px-3 py-2">' +
                '<div class="d-flex align-items-center gap-1 fw-bold text-gray-700"><i class="bi bi-alarm small text-muted"></i><span>'+jamStr+'</span>' +
                (submittedAt ? '<small class="fw-semibold text-muted" style="font-size: 0.72rem; letter-spacing: 0.02em;">WIB</small>' : '') +
                '</div>' +
            '</td>' +
        '</tr>';

        if (existingTr){
            existingTr.outerHTML = htmlRow;
        } else {
            if (els.emptyRow){
                els.emptyRow.remove();
                els.emptyRow = null;
            }
            els.tbody.insertAdjacentHTML('afterbegin', htmlRow);
        }

        parseCheckinsFromMapToArray();
        if (els.histBadge){
            var startStr = state.activeMonth+'-01';
            var y = +state.activeMonth.slice(0,4);
            var m = +state.activeMonth.slice(5,7)-1;
            var daysIn = new Date(y,m+1,0).getDate();
            var endStr = state.activeMonth+'-'+pad(daysIn);
            var cnt = state.checkinArr.filter(function(r){ return r.date >= startStr && r.date <= endStr; }).length;
            els.histBadge.textContent = cnt + ' entri';
        }
    }

    function submitForm(e){
        e.preventDefault();
        setFormAlert(null);
        setTextareaError(null);

        var activity = (els.textarea ? els.textarea.value : '').trim();
        if (activity.length < 5){
            setTextareaError('Uraian kegiatan minimal 5 karakter.');
            return;
        }
        if (activity.length > 5000){
            setTextareaError('Uraian kegiatan maksimal 5000 karakter.');
            return;
        }

        setSubmitLoading(true);
        var bodyObj = { date: state.activeDate, activity: activity };

        fetch(storeEndpoint, {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(bodyObj)
        })
        .then(function(resp){
            var ctype = resp.headers.get('content-type') || '';
            if (ctype.indexOf('application/json') !== -1){
                return resp.json().then(function(data){ return { status: resp.status, data: data }; });
            }
            return resp.text().then(function(t){ return { status: resp.status, text: t }; });
        })
        .then(function(result){
            var status = result.status;
            if (status >= 200 && status < 300 && result.data && result.data.success){
                var checkin = result.data.checkin || {};
                state.checkins[checkin.date || state.activeDate] = {
                    id: checkin.id || '',
                    activity: checkin.activity || activity,
                    submitted_at: checkin.submitted_at || (new Date()).toISOString(),
                    created_at: checkin.created_at || null
                };
                parseCheckinsFromMapToArray();
                renderCalendar();
                updateStats();
                var key = checkin.date || state.activeDate;
                upsertHistoryRow(key, state.checkins[key]);
                setFormAlert('success', result.data.message || 'Absensi berhasil disimpan.');
                if (els.submitTxt) els.submitTxt.textContent = 'Perbarui Absensi';
                setTextareaError(null);
                if (els.textarea) {
                    els.textarea.value = '';
                    updateCharCount();
                }
            } else {
                var msg = 'Gagal menyimpan. Silakan coba lagi.';
                if (result.data && result.data.message) msg = result.data.message;
                else if (result.data && result.data.errors){
                    var errs = [];
                    Object.keys(result.data.errors).forEach(function(k){
                        (result.data.errors[k]||[]).forEach(function(v){ errs.push(v); });
                    });
                    if (errs.length) msg = errs.join(' ');
                }
                setFormAlert('error', msg);
            }
        })
        .catch(function(){
            setFormAlert('error', 'Terjadi kesalahan jaringan. Periksa koneksi lalu coba lagi.');
        })
        .finally(function(){
            setSubmitLoading(false);
        });
    }

    function loadMonth(monthKey){
        fetch(monthlyEndpoint + '?month=' + encodeURIComponent(monthKey), {
            method: 'GET',
            credentials: 'same-origin',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(function(r){ return r.json().catch(function(){ return null; }); })
        .then(function(resp){
            if (!resp || !resp.success) return;
            (resp.checkins || []).forEach(function(row){
                state.checkins[row.date] = {
                    id: row.id || '',
                    activity: row.activity || '',
                    submitted_at: row.submitted_at || null,
                    created_at: null
                };
            });
            parseCheckinsFromMapToArray();
            if (resp.holidays) {
                state.holidays = resp.holidays;
            }
            if (resp.month){
                state.activeMonth = resp.month;
                if (els.picker) els.picker.value = resp.month;
            }
            if (els.monthLabel && resp.month_label) els.monthLabel.textContent = resp.month_label;
            renderCalendar();
            updateStats();
            rebuildHistoryTableForActiveMonth();
        })
        .catch(function(){});
    }

    function rebuildHistoryTableForActiveMonth(){
        if (!els.tbody) return;
        var startStr = state.activeMonth+'-01';
        var y = +state.activeMonth.slice(0,4);
        var m = +state.activeMonth.slice(5,7)-1;
        var daysIn = new Date(y,m+1,0).getDate();
        var endStr = state.activeMonth+'-'+pad(daysIn);

        var rows = state.checkinArr.filter(function(r){ return r.date >= startStr && r.date <= endStr; });

        els.tbody.innerHTML = '';
        if (rows.length === 0){
            els.tbody.innerHTML = '<tr id="dc-empty-row"><td colspan="4" class="px-4 py-5 text-center text-muted">' +
                '<i class="bi bi-inbox fs-1 d-block mb-2 text-gray-300"></i>' +
                'Belum ada aktivitas absensi pada bulan ini. Klik tanggal di kalender untuk mulai.' +
                '</td></tr>';
            els.emptyRow = els.tbody.querySelector('#dc-empty-row');
        } else {
            rows.forEach(function(r){
                upsertHistoryRow(r.date, r);
            });
        }

        if (els.histBadge) els.histBadge.textContent = rows.length + ' entri';
    }

    function shiftMonth(delta){
        var y = +state.activeMonth.slice(0,4);
        var m = +state.activeMonth.slice(5,7)-1;
        var d = new Date(y, m + delta, 1);
        var key = d.getFullYear()+'-'+pad(d.getMonth()+1);
        state.activeMonth = key;
        if (els.picker){
            var optExists = false;
            for (var i=0; i<els.picker.options.length; i++){
                if (els.picker.options[i].value === key){ els.picker.selectedIndex = i; optExists = true; break; }
            }
            if (!optExists){
                var newOpt = new Option( MONTH_NAMES[d.getMonth()]+' '+d.getFullYear(), key, true, true);
                els.picker.appendChild(newOpt);
            }
        }
        loadMonth(key);
    }

    function bindEvents(){
        if (els.prevBtn)  els.prevBtn.addEventListener('click', function(){ shiftMonth(-1); });
        if (els.nextBtn)  els.nextBtn.addEventListener('click', function(){ shiftMonth(1); });
        if (els.picker)   els.picker.addEventListener('change', function(){
            var v = els.picker.value; if (!v) return;
            state.activeMonth = v; loadMonth(v);
        });
        if (els.form)     els.form.addEventListener('submit', submitForm);
        if (els.textarea){
            els.textarea.addEventListener('input', updateCharCount);
            els.textarea.addEventListener('keydown', function(e){
                if ((e.ctrlKey || e.metaKey) && e.key === 'Enter'){
                    submitForm(e);
                }
            });
        }
    }

    function init(){
        renderCalendar();
        updateStats();
        updateCharCount();
        bindEvents();
        if (state.todayStr) selectActiveDate(state.todayStr);
    }

    if (document.readyState === 'loading'){
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
</script>
@endpush
