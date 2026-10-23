<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Sertifikat Kelulusan Magang Digital</title>
    <style>
        @page {
            margin: 25pt 30pt;
            size: A4 landscape;
        }
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10px;
            color: #1e293b;
            line-height: 1.3;
        }
        .header-kop {
            width: 100%;
            border-bottom: 3px double #0f172a;
            padding-bottom: 6px;
            margin-bottom: 12px;
        }
        .doc-title {
            text-align: center;
            margin-bottom: 12px;
        }
        .doc-title h2 {
            font-size: 13px;
            font-weight: bold;
            text-transform: uppercase;
            margin: 0;
            color: #0f172a;
            letter-spacing: 0.5px;
        }
        .doc-title p {
            font-size: 8.5px;
            color: #64748b;
            margin: 2px 0 0 0;
        }
        .stats-grid {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }
        .stats-card {
            border: 1px solid #cbd5e1;
            background-color: #f8fafc;
            padding: 6px 10px;
            text-align: center;
            border-radius: 4px;
        }
        .stats-card .val {
            font-size: 14px;
            font-weight: bold;
            color: #1e3a8a;
        }
        .stats-card .lbl {
            font-size: 7.5px;
            color: #64748b;
            text-transform: uppercase;
            font-weight: bold;
        }
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }
        table.data-table th {
            background-color: #1e293b;
            color: #ffffff;
            font-size: 8.5px;
            font-weight: bold;
            text-transform: uppercase;
            padding: 5px 6px;
            border: 1px solid #0f172a;
            text-align: left;
        }
        table.data-table td {
            padding: 5px 6px;
            border: 1px solid #cbd5e1;
            font-size: 9px;
            vertical-align: top;
        }
        table.data-table tr:nth-child(even) {
            background-color: #f8fafc;
        }
        .badge {
            display: inline-block;
            padding: 2px 5px;
            border-radius: 3px;
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .badge-issued {
            background-color: #dcfce7;
            color: #166534;
            border: 1px solid #bbf7d0;
        }
        .badge-ready {
            background-color: #eff6ff;
            color: #1e40af;
            border: 1px solid #bfdbfe;
        }
        .badge-progress {
            background-color: #fef3c7;
            color: #92400e;
            border: 1px solid #fde68a;
        }
        .badge-terminated {
            background-color: #fee2e2;
            color: #991b1b;
            border: 1px solid #fca5a5;
        }
        .footer-sign {
            width: 100%;
            margin-top: 15px;
            page-break-inside: avoid;
        }
        .sign-box {
            text-align: center;
            width: 35%;
            float: right;
        }
        .sign-box p {
            margin: 1px 0;
        }
    </style>
</head>
<body>

    <!-- KOP SURAT INSTANSI RESMI -->
    <table style="width: 100%; border-collapse: collapse; border: none; margin-bottom: 2px;">
        <tr>
            <!-- LOGO COLUMN -->
            <td style="width: 15%; text-align: center; vertical-align: middle; border: none; padding: 0;">
                <img src="{{ public_path('traveland/images/logo.png') }}" alt="Logo Tuban" style="width: 80px; height: auto;">
            </td>
            <!-- TEXT COLUMN -->
            <td style="width: 85%; text-align: center; vertical-align: middle; border: none; padding: 0; padding-right: 15%;">
                <h3 style="margin: 0; font-size: 16px; font-weight: normal; line-height: 1.3;">PEMERINTAH KABUPATEN TUBAN</h3>
                <h2 style="margin: 0; font-size: 18px; font-weight: bold; line-height: 1.3;">DINAS KOMUNIKASI DAN INFORMATIKA, STATISTIK DAN PERSANDIAN</h2>
                <p style="margin: 0; font-size: 12px; line-height: 1.3;">Jl. Mastrip No. 5 A, Sidorejo, Kec. Tuban, Kabupaten Tuban, Jawa Timur 62315</p>
                <p style="margin: 0; font-size: 12px; line-height: 1.3;">Email: diskominfo@tubankab.go.id | Telp: (0356) 8832697 | Website: diskominfo.tubankab.go.id</p>
            </td>
        </tr>
    </table>
    <div style="border-top: 3px solid black; margin-top: 10px; margin-bottom: 2px;"></div>
    <div style="border-top: 1px solid black; margin-top: 0; margin-bottom: 15px;"></div>

    <!-- JUDUL DOKUMEN -->
    <div class="doc-title">
        <h2>LAPORAN STATUS SERTIFIKAT KELULUSAN MAGANG DIGITAL</h2>
        <p>Bidang / Divisi: <strong>{{ $divisionName }}</strong> &bull; Pembimbing Lapangan: <strong>{{ $user->name }}</strong></p>
        <p>Kriteria Filter: Status ({{ !empty($status) && $status !== 'all' ? strtoupper($status) : 'SEMUA STATUS' }}) | Tahun: {{ $year ?: 'Semua Tahun' }} | Kata Kunci: "{{ $search ?: '-' }}" | Dicetak: {{ $printedAt }}</p>
    </div>

    <!-- SUMMARY KPI STATS -->
    <table class="stats-grid">
        <tr>
            <td style="width: 25%; padding: 2px;">
                <div class="stats-card">
                    <div class="val">{{ number_format($stats['total'] ?? 0) }}</div>
                    <div class="lbl">Total Bimbingan</div>
                </div>
            </td>
            <td style="width: 25%; padding: 2px;">
                <div class="stats-card">
                    <div class="val" style="color: #166534;">{{ number_format($stats['issued'] ?? 0) }}</div>
                    <div class="lbl">Sertifikat Terbit</div>
                </div>
            </td>
            <td style="width: 25%; padding: 2px;">
                <div class="stats-card">
                    <div class="val" style="color: #1e40af;">{{ number_format($stats['ready_to_issue'] ?? 0) }}</div>
                    <div class="lbl">Siap Diterbitkan</div>
                </div>
            </td>
            <td style="width: 25%; padding: 2px;">
                <div class="stats-card">
                    <div class="val" style="color: #92400e;">{{ number_format($stats['in_progress'] ?? 0) }}</div>
                    <div class="lbl">Sedang Berlangsung</div>
                </div>
            </td>
        </tr>
    </table>

    <!-- DATA TABEL SERTIFIKAT -->
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 4%; text-align: center;">No</th>
                <th style="width: 14%;">No. Pendaftaran</th>
                <th style="width: 20%;">Nama &amp; Email</th>
                <th style="width: 22%;">Instansi &amp; Jurusan</th>
                <th style="width: 16%;">Posisi / Penempatan</th>
                <th style="width: 12%;">Periode Magang</th>
                <th style="width: 12%; text-align: center;">Status Sertifikat</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($interns as $index => $intern)
                @php
                    $prof = $intern->user?->profile;
                    $certExists = ! empty($intern->certificate_path) && \Illuminate\Support\Facades\Storage::disk('public')->exists($intern->certificate_path);
                    $isGraduationReached = $intern->isGraduationEligible();
                    $isTerminated = (bool) ($intern->is_terminated || $intern->operational_status === 'terminated');
                @endphp
                <tr>
                    <td style="text-align: center; font-weight: bold;">{{ $index + 1 }}</td>
                    <td style="font-family: monospace; font-weight: bold;">{{ $intern->nomor_pendaftaran }}</td>
                    <td>
                        <strong>{{ $intern->user?->name ?? '—' }}</strong><br>
                        <span style="color: #64748b; font-family: monospace; font-size: 8.5px;">{{ $intern->user?->email ?? '-' }}</span>
                    </td>
                    <td>
                        <strong>{{ $prof?->institusi ?? ($intern->institution ?? '—') }}</strong><br>
                        <span style="color: #475569;">{{ $prof?->jurusan ?? '-' }}</span>
                    </td>
                    <td>
                        <strong>{{ $intern->position?->nama_posisi ?? '—' }}</strong><br>
                        <span style="color: #64748b; font-size: 8px;">{{ $divisionName }}</span>
                    </td>
                    <td>
                        {{ $intern->periode_mulai?->format('d/m/Y') ?? '-' }}
                        <br>s/d<br>
                        {{ $intern->periode_selesai?->format('d/m/Y') ?? '-' }}
                    </td>
                    <td style="text-align: center;">
                        @if($certExists)
                            <span class="badge badge-issued">SUDAH TERBIT</span>
                            @if($intern->certificate_uploaded_at)
                                <br><span style="font-size: 7.5px; color: #64748b;">{{ \Illuminate\Support\Carbon::parse($intern->certificate_uploaded_at)->format('d/m/Y') }}</span>
                            @endif
                        @elseif($isTerminated)
                            <span class="badge badge-terminated">NONAKTIF</span>
                        @elseif($isGraduationReached)
                            <span class="badge badge-ready">SIAP TERBIT</span>
                        @else
                            <span class="badge badge-progress">SEDANG MAGANG</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 15px; color: #64748b;">
                        Belum ada data sertifikat anak bimbing yang memenuhi kriteria filter.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- TANDA TANGAN PEMBIMBING -->
    <div class="footer-sign">
        <table style="width: 100%;">
            <tr>
                <td style="width: 65%;"></td>
                <td style="width: 35%; text-align: center;">
                    <p>Tuban, {{ date('d F Y') }}</p>
                    <p><strong>Pembimbing Lapangan</strong></p>
                    <p>{{ $divisionName }}</p>
                    <br><br><br>
                    <p><strong><u>{{ $user->name }}</u></strong></p>
                    <p style="font-size: 8px; color: #64748b;">NIP. {{ $user->nip ?? '-' }}</p>
                </td>
            </tr>
        </table>
    </div>

</body>
</html>
