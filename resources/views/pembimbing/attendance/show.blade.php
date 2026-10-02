@extends('layouts.app')
@php
    $profile = $registration->user?->profile;
    $intern  = $registration->user;
    $posisi  = $registration->position;
    $divisi  = $intern?->division;
    $period  = new \DatePeriod($start, new \DateInterval('P1D'), $end->copy()->addDay());
    $periodeLabel = $registration->periode_label ?? null;
    $institution = $registration->institution ?? $profile?->institusi ?? '-';
    $jurusan = $profile?->jurusan ?? null;
@endphp
@section('title', 'Rekap Absensi - ' . ($intern?->name ?? 'Peserta'))
@section('content')
<div class="container-fluid" id="print-area" style="padding-top: 0.25rem !important;">
    <style>
        .print-only { display: none !important; }
        .no-print { }
        @media print {
            body * { visibility: hidden; }
            #print-area, #print-area * { visibility: visible; }
            #print-area { position: absolute; left: 0; top: 0; width: 100%; }
            .no-print { display: none !important; }
            .print-only { display: block !important; }
            @page { size: A4 portrait; margin: 18mm 14mm; }
            table.print-table { border-collapse: collapse; width: 100%; }
            table.print-table, table.print-table th, table.print-table td { border: 1px solid #000 !important; }
            table.print-table th, table.print-table td { padding: 5px 7px; font-size: 10pt; }
            .card { box-shadow: none !important; border: 0 !important; }
            .badge-print { display: inline-block; padding: 2px 7px; font-size: 9pt; border-radius: 2px; }
            a.no-print-link { text-decoration: none !important; color: inherit !important; pointer-events: none; }
        }
    </style>

    {{-- Kop Surat: hanya muncul di print / PDF --}}
    <div class="print-only mb-4 text-center">
        <div style="font-weight: 700; font-size: 14pt; letter-spacing: 0.03em;">DINAS KOMUNIKASI DAN INFORMATIKA</div>
        <div style="font-weight: 700; font-size: 13pt; letter-spacing: 0.03em;">KABUPATEN TUBAN</div>
        <div style="font-size: 9pt; margin-top: 2px; color: #333;">Jl. Pemuda No. 1, Tuban, Jawa Timur 62311 &bull; Telp. (0356) 123-456</div>
        <div style="border-bottom: 2px solid #000; margin-top: 8px; margin-bottom: 14px;"></div>
        <div style="font-weight: 700; font-size: 12pt; text-transform: uppercase;">Laporan Rekap Absensi &amp; Aktivitas Harian Peserta Magang</div>
        <div style="margin-top: 2px; font-size: 10pt;">Periode: {{ $start->translatedFormat('d F Y') }} &mdash; {{ $end->translatedFormat('d F Y') }}</div>
    </div>

    {{-- Header Action (No Print) --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-2 no-print">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <a href="{{ route('pembimbing.dashboard') }}"
                   class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-1 fw-semibold"
                   style="border-radius: 0.5rem; font-size: 0.78rem;">
                    <i class="bi bi-arrow-left"></i>
                    Kembali
                </a>
                <nav aria-label="breadcrumb" class="mb-0" style="--bs-breadcrumb-divider: '›';">
                    <ol class="breadcrumb mb-0" style="font-size: 0.75rem;">
                        <li class="breadcrumb-item">
                            <a href="{{ route('pembimbing.dashboard') }}" class="text-decoration-none text-muted">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item active text-gray-700 fw-semibold" aria-current="page">Rekap Absensi</li>
                    </ol>
                </nav>
            </div>
            <h1 class="h4 mb-0 fw-bold" style="line-height: 1.25; color: #0f172a;">Rekap Absensi Harian</h1>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('pembimbing.attendance.pdf', $registration) }}"
               class="btn btn-sm btn-danger no-print-link d-inline-flex align-items-center gap-1.5 fw-semibold"
               style="border-radius: 0.5rem; font-size: 0.78rem; min-width: 135px;">
                <i class="bi bi-filetype-pdf"></i>
                Ekspor PDF
            </a>
        </div>
    </div>

    {{-- Kartu Identitas Peserta --}}
    <div class="card shadow-sm mb-3 rounded-xl" style="border-radius: 0.75rem;">
        <div class="card-body p-3">
            <div class="row g-3 align-items-start">
                <div class="col-md-6">
                    <div class="d-flex align-items-center gap-3 mb-2">
                        <div class="d-flex align-items-center justify-content-center rounded-circle flex-shrink-0 fw-bold text-white"
                             style="width: 46px; height: 46px; background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%); font-size: 1.05rem;">
                            {{ \Illuminate\Support\Str::limit(mb_substr($intern?->name ?? '?', 0, 1), 1, '') }}
                        </div>
                        <div class="min-w-0 d-flex flex-column">
                            <div class="fw-bold text-gray-900 text-truncate" style="font-size: 1.05rem; color: #0f172a !important;">
                                {{ $intern?->name ?? '-' }}
                            </div>
                            <small class="text-muted text-truncate" style="font-size: 0.72rem;">
                                <i class="bi bi-envelope-fill me-1"></i>{{ $intern?->email ?? '-' }}
                            </small>
                        </div>
                    </div>
                    <div class="d-flex flex-column gap-1" style="font-size: 0.82rem;">
                        <div class="d-flex align-items-start gap-2">
                            <span class="text-muted" style="min-width: 70px;">Instansi</span>
                            <span class="text-gray-900 fw-medium">
                                <i class="bi bi-building me-1" style="color: #475569;"></i>
                                {{ $institution }}
                            </span>
                        </div>
                        @if($jurusan)
                        <div class="d-flex align-items-start gap-2">
                            <span class="text-muted" style="min-width: 70px;">Jurusan</span>
                            <span class="text-gray-900 fw-medium">{{ $jurusan }}</span>
                        </div>
                        @endif
                        @if($posisi)
                        <div class="d-flex align-items-start gap-2">
                            <span class="text-muted" style="min-width: 70px;">Posisi</span>
                            <span class="text-gray-900 fw-medium">{{ $posisi->nama_posisi ?? $posisi->name ?? '-' }}</span>
                        </div>
                        @endif
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="d-flex flex-column gap-1 ms-md-3" style="font-size: 0.82rem;">
                        <div class="d-flex align-items-start gap-2">
                            <span class="text-muted" style="min-width: 110px;">Divisi</span>
                            <span class="text-gray-900 fw-medium">
                                <i class="bi bi-diagram-3 me-1" style="color: #475569;"></i>
                                {{ $divisi?->nama_divisi ?? $divisi?->name ?? '-' }}
                            </span>
                        </div>
                        <div class="d-flex align-items-start gap-2">
                            <span class="text-muted" style="min-width: 110px;">Periode Magang</span>
                            <span class="text-gray-900 fw-medium">
                                <i class="bi bi-calendar3 me-1"></i>
                                {{ $start->translatedFormat('d M Y') }} &mdash; {{ $end->translatedFormat('d M Y') }}
                            </span>
                        </div>
                        <div class="d-flex align-items-start gap-2">
                            <span class="text-muted" style="min-width: 110px;">Total Hari</span>
                            <span class="text-gray-900 fw-medium">{{ $totalPeriodDays }} hari</span>
                        </div>
                        <div class="d-flex align-items-start gap-2">
                            <span class="text-muted" style="min-width: 110px;">Status</span>
                            <span class="badge {{ $registration->operational_status_badge_class }} rounded-pill px-3 py-1 fw-semibold border" style="font-size: 0.72rem;">
                                @if($registration->operational_status === 'active' || $registration->operational_status === 'upcoming')
                                    <i class="bi bi-dot me-1" style="color: #16a34a !important;"></i>
                                @else
                                    <i class="bi bi-dot me-1" style="color: #ef4444 !important;"></i>
                                @endif
                                {{ $registration->operational_status_label }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 3 Statistik --}}
    <div class="row g-2 mb-3">
        <div class="col-md-4">
            <div class="card shadow-sm h-100 rounded-xl" style="border-left: 3px solid #10b981 !important; border-radius: 0.5rem;">
                <div class="card-body py-2 px-3">
                    <div class="row no-gutters align-items-center g-2">
                        <div class="col mr-2">
                            <div class="text-uppercase fw-bold mb-0.5" style="font-size: 0.6875rem; line-height: 1.15; color: #047857 !important; letter-spacing: 0.05em;">
                                Sudah Absen
                            </div>
                            <div class="h6 mb-0 fw-bold" style="color: #065f46 !important; line-height: 1.15; font-size: 1.15rem;">
                                {{ $totalHadir }}
                                <small class="fw-semibold" style="font-size: 0.7rem; color: #059669;">
                                    hari
                                    @if($totalPeriodDays > 0)
                                        / {{ $totalPeriodDays - $totalWeekendDays }}
                                    @endif
                                </small>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-calendar2-check-fill" style="font-size: 1.25rem; color: #34d399 !important;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm h-100 rounded-xl" style="border-left: 3px solid #64748b !important; border-radius: 0.5rem;">
                <div class="card-body py-2 px-3">
                    <div class="row no-gutters align-items-center g-2">
                        <div class="col mr-2">
                            <div class="text-uppercase fw-bold mb-0.5" style="font-size: 0.6875rem; line-height: 1.15; color: #475569 !important; letter-spacing: 0.05em;">
                                Belum Absen
                            </div>
                            <div class="h6 mb-0 fw-bold" style="color: #334155 !important; line-height: 1.15; font-size: 1.15rem;">
                                {{ $totalBelum }}
                                <small class="fw-semibold" style="font-size: 0.7rem; color: #64748b;">hari</small>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-calendar-x-fill" style="font-size: 1.25rem; color: #94a3b8 !important;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm h-100 rounded-xl" style="border-left: 3px solid #ef4444 !important; border-radius: 0.5rem;">
                <div class="card-body py-2 px-3">
                    <div class="row no-gutters align-items-center g-2">
                        <div class="col mr-2">
                            <div class="text-uppercase fw-bold mb-0.5" style="font-size: 0.6875rem; line-height: 1.15; color: #991b1b !important; letter-spacing: 0.05em;">
                                Libur / Akhir Pekan
                            </div>
                            <div class="h6 mb-0 fw-bold" style="color: #b91c1c !important; line-height: 1.15; font-size: 1.15rem;">
                                {{ $totalWeekendDays }}
                                <small class="fw-semibold" style="font-size: 0.7rem; color: #f87171;">hari</small>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-calendar-week-fill" style="font-size: 1.25rem; color: #fca5a5 !important;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($totalHadir === 0 && $totalPeriodDays > 0)
    <div class="alert alert-info py-2 px-3 mb-3 d-flex align-items-center gap-2 no-print" style="font-size: 0.82rem; border-radius: 0.5rem;">
        <i class="bi bi-info-circle-fill flex-shrink-0" style="font-size: 1.05rem;"></i>
        <span>Belum ada data absensi yang di-submit peserta pada periode ini. Tabel di bawah menunjukkan jadwal lengkap periode magang.</span>
    </div>
    @endif

    {{-- Tabel Rekap Harian --}}
    <div class="card shadow-sm rounded-xl" style="border-radius: 0.75rem;">
        <div class="card-header py-2 px-3 bg-white border-bottom d-flex flex-wrap justify-content-between align-items-center gap-2">
            <h6 class="m-0 fw-bold" style="font-size: 0.95rem; color: #0f172a;">
                <i class="bi bi-list-columns-reverse me-1" style="color: #2563eb;"></i>
                Rekap Harian
                <small class="fw-semibold text-muted" style="font-size: 0.72rem;">
                    ({{ $start->translatedFormat('d M Y') }} &mdash; {{ $end->translatedFormat('d M Y') }})
                </small>
            </h6>
            <div class="d-none d-md-flex flex-wrap gap-2 align-items-center no-print" style="font-size: 0.72rem;">
                <span class="d-inline-flex align-items-center gap-1.5 text-muted fw-semibold">
                    <span class="d-inline-block rounded" style="width: 0.8rem; height: 0.8rem; background-color: #10b981;"></span>
                    Sudah Absen
                </span>
                <span class="d-inline-flex align-items-center gap-1.5 text-muted fw-semibold">
                    <span class="d-inline-block rounded" style="width: 0.8rem; height: 0.8rem; background-color: #e5e7eb;"></span>
                    Belum
                </span>
                <span class="d-inline-flex align-items-center gap-1.5 text-muted fw-semibold">
                    <span class="d-inline-block rounded" style="width: 0.8rem; height: 0.8rem; background-color: #ef4444;"></span>
                    Libur
                </span>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0 print-table" style="font-size: 0.82rem;">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" style="width: 48px;">No</th>
                            <th class="text-center" style="width: 110px;">Tanggal</th>
                            <th class="text-center" style="width: 110px;">Hari</th>
                            <th class="text-center" style="width: 110px;">Status</th>
                            <th>Uraian Kegiatan</th>
                            <th class="text-center" style="width: 110px;">Jam Submit</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $no = 1; @endphp
                        @foreach($period as $date)
                            @php
                                $date = \Carbon\Carbon::instance($date);
                                $key = $date->toDateString();
                                $checkin = $checkins->get($key);
                                $isWeekend = $date->isWeekend();
                                $hasData = (bool) $checkin;
                                if ($isWeekend) {
                                    $statusBadge = 'bg-red-500 text-white';
                                    $statusStyle = 'background-color:#ef4444 !important; color:#ffffff !important;';
                                    $statusLabel = 'Libur';
                                } elseif ($hasData) {
                                    $statusBadge = 'bg-emerald-500 text-white';
                                    $statusStyle = 'background-color:#10b981 !important; color:#ffffff !important;';
                                    $statusLabel = 'Hadir';
                                } else {
                                    $statusBadge = 'bg-gray-200 text-gray-700';
                                    $statusStyle = 'background-color:#e5e7eb !important; color:#374151 !important;';
                                    $statusLabel = 'Belum Absen';
                                }
                            @endphp
                            <tr @class([
                                'bg-emerald-50/30' => $hasData && ! $isWeekend,
                                'bg-red-50/30' => $isWeekend,
                            ])>
                                <td class="text-center text-muted fw-semibold">{{ $no++ }}</td>
                                <td class="text-center fw-medium">{{ $date->translatedFormat('d/m/Y') }}</td>
                                <td class="text-center">{{ $date->translatedFormat('l') }}</td>
                                <td class="text-center">
                                    <span class="badge rounded-pill px-3 py-1 fw-semibold {!! $statusBadge !!}" style="{!! $statusStyle !!} font-size: 0.72rem;">
                                        {{ $statusLabel }}
                                    </span>
                                </td>
                                <td class="text-gray-900" style="line-height: 1.5;">
                                    @if($hasData)
                                        <div class="text-sm">{!! \Illuminate\Support\Str::limit(nl2br(e($checkin->activity)), 450, ' …') !!}</div>
                                        @if(strlen($checkin->activity) > 450)
                                            <span class="text-muted d-block mt-1" style="font-size: 0.68rem;">
                                                <i class="bi bi-text-paragraph me-1"></i>
                                                {{ strlen($checkin->activity) }} karakter total
                                            </span>
                                        @endif
                                    @else
                                        <span class="text-muted fst-italic small">-</span>
                                    @endif
                                </td>
                                <td class="text-center text-gray-700">
                                    @if($hasData && $checkin->submitted_at)
                                        <span class="fw-semibold" style="color: #0f172a;">
                                            {{ $checkin->submitted_at->translatedFormat('H:i:s') }}
                                        </span>
                                        <div class="text-muted d-block" style="font-size: 0.64rem;">
                                            {{ $checkin->submitted_at->translatedFormat('d M Y') }}
                                        </div>
                                    @else
                                        <span class="text-muted fst-italic small">-</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Tanda Tangan: Hanya Print / PDF --}}
    <div class="print-only mt-5">
        <div class="row">
            <div class="col-6"></div>
            <div class="col-6">
                <div class="text-center" style="font-size: 10pt;">
                    <div style="margin-bottom: 10px;">Tuban, {{ $end->translatedFormat('d F Y') }}</div>
                    <div style="margin-bottom: 3px;">Mengetahui,</div>
                    <div style="font-weight: 700;">Pembimbing Magang</div>
                    <div style="height: 90px;"></div>
                    <div style="border-top: 1px solid #000; display: inline-block; min-width: 260px;"></div>
                    <div style="margin-top: 4px; font-weight: 700;">{{ \Illuminate\Support\Facades\Auth::user()->name }}</div>
                    <div style="color: #333;">NIP. {{ \Illuminate\Support\Facades\Auth::user()->nip ?? '-' }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
