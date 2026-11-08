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
            font-size: 8.5px;
            vertical-align: top;
        }
        .badge {
            display: inline-block;
            padding: 2px 5px;
            border-radius: 3px;
            font-size: 7.5px;
            font-weight: bold;
            text-align: center;
        }
        .badge-success { background-color: #dcfce7; color: #166534; border: 1px solid #86efac; }
        .badge-warning { background-color: #fef3c7; color: #92400e; border: 1px solid #fcd34d; }
        .badge-secondary { background-color: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; }
        .footer-sign {
            width: 100%;
            margin-top: 20px;
            page-break-inside: avoid;
        }
    </style>
</head>
<body>
    <!-- KOP LAPORAN -->
    <table class="header-kop">
        <tr>
            <td style="width: 12%; text-align: center; vertical-align: middle;">
                @if(file_exists(public_path('traveland/images/logo.png')))
                    <img src="{{ public_path('traveland/images/logo.png') }}" style="height: 52px; width: auto;" alt="Logo">
                @endif
            </td>
            <td style="width: 88%; text-align: center; vertical-align: middle;">
                <h3 style="margin: 0; font-size: 13px; text-transform: uppercase; font-weight: bold; color: #0f172a;">
                    PEMERINTAH KABUPATEN TUBAN
                </h3>
                <h4 style="margin: 2px 0; font-size: 11px; text-transform: uppercase; color: #334155;">
                    {{ $opd->name ?? 'SISTEM INFORMASI MANAJEMEN MAGANG (SIMAGANG)' }}
                </h4>
                <p style="margin: 0; font-size: 8px; color: #64748b;">
                    {{ $opd->address ?? 'Pusat Pemerintahan Kabupaten Tuban — Jawa Timur' }}
                </p>
            </td>
        </tr>
    </table>

    <div class="doc-title">
        <h2>REKAPITULASI PENERBITAN SERTIFIKAT MAGANG DIGITAL</h2>
        <p>Laporan Resmi Rekapitulasi Sertifikat Peserta Magang • Dicetak pada: {{ $printedAt }}</p>
    </div>

    <!-- STATS -->
    <table class="stats-grid">
        <tr>
            <td style="width: 25%; padding: 3px;">
                <div class="stats-card">
                    <div class="val">{{ $stats['total'] }}</div>
                    <div class="lbl">Total Peserta</div>
                </div>
            </td>
            <td style="width: 25%; padding: 3px;">
                <div class="stats-card">
                    <div class="val" style="color: #166534;">{{ $stats['issued'] }}</div>
                    <div class="lbl">Sertifikat Terbit</div>
                </div>
            </td>
            <td style="width: 25%; padding: 3px;">
                <div class="stats-card">
                    <div class="val" style="color: #92400e;">{{ $stats['ready_to_issue'] }}</div>
                    <div class="lbl">Menunggu Terbit</div>
                </div>
            </td>
            <td style="width: 25%; padding: 3px;">
                <div class="stats-card">
                    <div class="val" style="color: #475569;">{{ $stats['in_progress'] }}</div>
                    <div class="lbl">Sedang Magang</div>
                </div>
            </td>
        </tr>
    </table>

    <!-- TABLE -->
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 4%; text-align: center;">No</th>
                <th style="width: 20%;">Peserta & Instansi</th>
                <th style="width: 22%;">Posisi & Unit/OPD</th>
                <th style="width: 20%;">Periode Magang</th>
                <th style="width: 16%; text-align: center;">Status Sertifikat</th>
                <th style="width: 18%;">No. Pendaftaran & File</th>
            </tr>
        </thead>
        <tbody>
            @forelse($interns as $idx => $intern)
                @php
                    $prof = $intern->user?->profile;
                    $certExists = !empty($intern->certificate_path) && \Illuminate\Support\Facades\Storage::disk('public')->exists($intern->certificate_path);
                    $targetOpd = $intern->opd ?? $intern->position?->opd;
                @endphp
                <tr>
                    <td style="text-align: center;">{{ $idx + 1 }}</td>
                    <td>
                        <strong>{{ $intern->user?->name ?? '—' }}</strong><br>
                        <span style="color: #64748b; font-size: 7.5px;">{{ $prof?->institusi ?? '—' }} • {{ $prof?->jurusan ?? '-' }}</span>
                    </td>
                    <td>
                        <strong>{{ $intern->position?->nama_posisi ?? '—' }}</strong><br>
                        <span style="color: #64748b; font-size: 7.5px;">{{ $targetOpd->name ?? '-' }}</span>
                    </td>
                    <td>
                        {{ $intern->periode_mulai?->translatedFormat('d M Y') ?? '-' }} s/d {{ $intern->periode_selesai?->translatedFormat('d M Y') ?? '-' }}
                    </td>
                    <td style="text-align: center;">
                        @if($certExists)
                            <span class="badge badge-success">Sertifikat Terbit</span>
                        @elseif($intern->is_graduation_eligible)
                            <span class="badge badge-warning">Menunggu Terbit</span>
                        @else
                            <span class="badge badge-secondary">Sedang Magang</span>
                        @endif
                    </td>
                    <td>
                        <code>{{ $intern->nomor_pendaftaran }}</code><br>
                        @if($certExists)
                            <span style="color: #166534; font-size: 7.5px;">{{ basename($intern->certificate_path) }}</span>
                        @else
                            <span style="color: #94a3b8; font-size: 7.5px;">Belum Diunggah</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 15px; color: #94a3b8;">
                        Tidak ada data peserta magang yang memenuhi kriteria filter.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- TANDA TANGAN -->
    <table class="footer-sign">
        <tr>
            <td style="width: 60%;"></td>
            <td style="width: 40%; text-align: center;">
                <p style="margin: 0 0 45px 0;">
                    Tuban, {{ now()->translatedFormat('d F Y') }}<br>
                    <strong>{{ $opd ? $opd->name : 'Pemerintah Kabupaten Tuban' }}</strong>
                </p>
                <p style="margin: 0; font-weight: bold; text-decoration: underline;">
                    {{ $opd->head_of_office_name ?? ($user->name ?? 'Administrator SIMAGANG') }}
                </p>
                <p style="margin: 0; font-size: 8px; color: #64748b;">
                    NIP. {{ $opd->head_of_office_nip ?? ($user->nip ?? '—') }}
                </p>
            </td>
        </tr>
    </table>
</body>
</html>
