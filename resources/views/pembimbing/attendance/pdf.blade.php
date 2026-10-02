<!DOCTYPE html>
<html lang="id">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title>Rekap Absensi - {{ $internName }}</title>
<style>
    @page { margin: 18mm 14mm; }
    * { font-family: DejaVu Sans, Arial, Helvetica, sans-serif; color: #0f172a; }
    html, body { margin: 0; padding: 0; font-size: 11px; line-height: 1.4; }
    .kop-center { text-align: center; }
    .kop-title { font-size: 14px; font-weight: 700; letter-spacing: 0.3px; }
    .kop-sub { font-size: 9px; color: #334155; margin-top: 2px; }
    .kop-line { border-top: 2px solid #000000; margin-top: 10px; margin-bottom: 14px; }
    .doc-title { font-size: 12px; font-weight: 700; text-align: center; text-transform: uppercase; margin-bottom: 2px; }
    .doc-period { font-size: 10px; text-align: center; color: #475569; margin-bottom: 16px; }

    .bio-wrap { margin-bottom: 16px; }
    table.bio { width: 100%; border-collapse: collapse; font-size: 10.5px; }
    table.bio td { padding: 2px 6px; vertical-align: top; }
    table.bio td.label { width: 150px; color: #475569; font-weight: 600; }
    table.bio td.sep { width: 12px; color: #cbd5e1; }
    table.bio td.value { font-weight: 600; }

    .section-title { font-size: 11px; font-weight: 700; margin-bottom: 6px; color: #0f172a; border-left: 3px solid #2563eb; padding-left: 8px; }

    table.data { width: 100%; border-collapse: collapse; font-size: 9.5px; page-break-inside: auto; }
    table.data thead tr { background-color: #eef2ff; }
    table.data th, table.data td { border: 1px solid #000000; padding: 5px 6px; vertical-align: top; }
    table.data th { font-weight: 700; text-align: center; color: #1e293b; }
    table.data tbody tr { page-break-inside: avoid; page-break-after: auto; }

    .status { display: inline-block; padding: 2px 8px; border-radius: 3px; font-weight: 700; font-size: 9px; color: #0f172a; border: 1px solid rgba(0,0,0,0.08); }
    .status-hadir { background-color: #d1fae5; color: #065f46; }
    .status-libur { background-color: #fee2e2; color: #991b1b; }
    .status-belum { background-color: #f3f4f6; color: #374151; }
    .row-hadir td { background-color: #ecfdf5; }
    .row-libur td { background-color: #fef2f2; }

    .ttd { margin-top: 40px; font-size: 10.5px; }
    table.ttd { width: 100%; }
    table.ttd td { vertical-align: top; }
    .ttd-right { text-align: center; width: 55%; }
    .ttd-line { border-top: 1px solid #000; display: inline-block; width: 260px; margin-top: 80px; margin-bottom: 2px; }
    .ttd-name { font-weight: 700; }
    .ttd-nip { color: #334155; font-size: 10px; }
    .ttd-place { margin-bottom: 3px; }

    .stats-row { margin-bottom: 12px; }
    table.stats { width: 100%; border-collapse: collapse; font-size: 10px; }
    table.stats td { padding: 8px 10px; border: 1px solid #e2e8f0; border-radius: 4px; }
    .stat-num { font-size: 16px; font-weight: 800; }
    .stat-label { font-size: 9.5px; color: #475569; font-weight: 700; text-transform: uppercase; letter-spacing: 0.04em; }
    .stat-hadir .stat-num { color: #065f46; }
    .stat-belum .stat-num { color: #334155; }
    .stat-libur .stat-num { color: #b91c1c; }
    .stat-hadir td { border-left: 3px solid #10b981; }
    .stat-belum td { border-left: 3px solid #64748b; }
    .stat-libur td { border-left: 3px solid #ef4444; }

    .text-muted { color: #64748b; }
    .fst-italic { font-style: italic; }
    .fw-semibold { font-weight: 600; }
</style>
</head>
<body>

<div class="kop-center">
    <div class="kop-title">DINAS KOMUNIKASI DAN INFORMATIKA</div>
    <div class="kop-title">KABUPATEN TUBAN</div>
    <div class="kop-sub">Jl. Pemuda No. 1, Tuban, Jawa Timur 62311 &bull; Telp. (0356) 123-456 &bull; Email: kominfo@tubankab.go.id</div>
    <div class="kop-line"></div>
    <div class="doc-title">Laporan Rekap Absensi &amp; Aktivitas Harian Peserta Magang</div>
    <div class="doc-period">Periode: {{ $start->translatedFormat('d F Y') }} &mdash; {{ $end->translatedFormat('d F Y') }}</div>
</div>

<div class="bio-wrap">
    <table class="bio">
        <tr>
            <td class="label">Nama Peserta</td><td class="sep">:</td>
            <td class="value">{{ $internName }}</td>
        </tr>
        <tr>
            <td class="label">Email / Kontak</td><td class="sep">:</td>
            <td class="value">{{ $internEmail ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Instansi / Universitas</td><td class="sep">:</td>
            <td class="value">{{ $institution }}</td>
        </tr>
        @if($jurusan)
        <tr>
            <td class="label">Jurusan / Program Studi</td><td class="sep">:</td>
            <td class="value">{{ $jurusan }}</td>
        </tr>
        @endif
        @if($posisiName)
        <tr>
            <td class="label">Posisi Magang</td><td class="sep">:</td>
            <td class="value">{{ $posisiName }}</td>
        </tr>
        @endif
        <tr>
            <td class="label">Bidang / Divisi</td><td class="sep">:</td>
            <td class="value">{{ $divisiName }}</td>
        </tr>
        <tr>
            <td class="label">Periode Magang</td><td class="sep">:</td>
            <td class="value">{{ $start->translatedFormat('d F Y') }} &mdash; {{ $end->translatedFormat('d F Y') }} <span class="text-muted">({{ $totalPeriodDays }} hari total)</span></td>
        </tr>
        <tr>
            <td class="label">Status</td><td class="sep">:</td>
            <td class="value">{{ $opStatusLabel }}</td>
        </tr>
    </table>
</div>

<div class="section-title">Ringkasan Kehadiran</div>
<table class="stats-row stats">
    <tr>
        <td class="stat-hadir" style="width: 33%;">
            <div class="stat-num">{{ $totalHadir }} <span style="font-size: 10px; font-weight: 600; color: #059669;">hari</span></div>
            <div class="stat-label">Sudah Absen</div>
        </td>
        <td class="stat-belum" style="width: 34%;">
            <div class="stat-num">{{ $totalBelum }} <span style="font-size: 10px; font-weight: 600; color: #64748b;">hari</span></div>
            <div class="stat-label">Belum Absen</div>
        </td>
        <td class="stat-libur" style="width: 33%;">
            <div class="stat-num">{{ $totalWeekendDays }} <span style="font-size: 10px; font-weight: 600; color: #f87171;">hari</span></div>
            <div class="stat-label">Libur / Akhir Pekan</div>
        </td>
    </tr>
</table>

<div class="section-title">Rekap Harian Detail</div>
<table class="data">
    <thead>
        <tr>
            <th style="width: 36px;">No</th>
            <th style="width: 80px;">Tanggal</th>
            <th style="width: 75px;">Hari</th>
            <th style="width: 82px;">Status</th>
            <th>Uraian Kegiatan</th>
            <th style="width: 80px;">Jam Submit</th>
        </tr>
    </thead>
    <tbody>
    @foreach($rows as $row)
        @php
            $rowClass = '';
            if ($row['status'] === 'hadir') { $rowClass = 'row-hadir'; }
            elseif ($row['status'] === 'libur') { $rowClass = 'row-libur'; }
            if ($row['status'] === 'hadir') { $statusClass = 'status status-hadir'; $statusLabel = 'HADIR'; }
            elseif ($row['status'] === 'libur') { $statusClass = 'status status-libur'; $statusLabel = 'LIBUR'; }
            else { $statusClass = 'status status-belum'; $statusLabel = 'BELUM ABSEN'; }
        @endphp
        <tr class="{{ $rowClass }}">
            <td style="text-align: center; color: #64748b;">{{ $row['no'] }}</td>
            <td style="text-align: center; font-weight: 600;">{{ $row['date'] }}</td>
            <td style="text-align: center;">{{ $row['day'] }}</td>
            <td style="text-align: center;"><span class="{{ $statusClass }}">{{ $statusLabel }}</span></td>
            <td>
                @if($row['activity'])
                    <span style="font-size: 9.5px;">{!! nl2br(e($row['activity'])) !!}</span>
                    @if(strlen($row['activity']) > 250)
                        <div style="font-size: 8px; color: #64748b; margin-top: 2px;">({{ strlen($row['activity']) }} karakter)</div>
                    @endif
                @else
                    <span class="fst-italic text-muted" style="font-size: 9.5px;">-</span>
                @endif
            </td>
            <td style="text-align: center;">
                @if($row['submitted'])
                    <span class="fw-semibold">{{ $row['submitted'] }}</span>
                @else
                    <span class="fst-italic text-muted">-</span>
                @endif
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

<div class="ttd">
    <table class="ttd">
        <tr>
            <td></td>
            <td class="ttd-right">
                <div class="ttd-place">Tuban, {{ $end->translatedFormat('d F Y') }}</div>
                <div style="margin-bottom: 2px;">Mengetahui,</div>
                <div style="font-weight: 700;">Pembimbing Magang</div>
                <div class="ttd-line"></div>
                <div class="ttd-name">{{ $mentorName }}</div>
                <div class="ttd-nip">NIP. {{ $mentorNip }}</div>
            </td>
        </tr>
    </table>
</div>

</body>
</html>
