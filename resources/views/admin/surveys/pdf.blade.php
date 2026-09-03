<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Survei Kepuasan Masyarakat (IKM)</title>
    <style>
        @page {
            margin: 30pt 40pt;
            size: A4 portrait;
        }
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
            color: #1e293b;
            line-height: 1.4;
        }
        .header-kop {
            width: 100%;
            border-bottom: 3px double #0f172a;
            padding-bottom: 8px;
            margin-bottom: 15px;
        }
        .header-kop table {
            width: 100%;
            border-collapse: collapse;
        }
        .header-kop .title-gov {
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
            color: #0f172a;
        }
        .header-kop .title-dept {
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
            color: #1e3a8a;
        }
        .header-kop .subtext {
            font-size: 9px;
            color: #475569;
        }
        .doc-title {
            text-align: center;
            margin-bottom: 15px;
        }
        .doc-title h2 {
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
            margin: 0;
            color: #0f172a;
            letter-spacing: 0.5px;
        }
        .doc-title p {
            font-size: 9px;
            color: #64748b;
            margin: 3px 0 0 0;
        }
        .stats-grid {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .stats-card {
            border: 1px solid #cbd5e1;
            background-color: #f8fafc;
            padding: 8px 12px;
            text-align: center;
            border-radius: 4px;
        }
        .stats-card .val {
            font-size: 16px;
            font-weight: bold;
            color: #1e3a8a;
        }
        .stats-card .lbl {
            font-size: 8px;
            color: #64748b;
            text-transform: uppercase;
            font-weight: bold;
        }
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        table.data-table th {
            background-color: #1e293b;
            color: #ffffff;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            padding: 6px 8px;
            border: 1px solid #0f172a;
            text-align: left;
        }
        table.data-table td {
            padding: 6px 8px;
            border: 1px solid #cbd5e1;
            font-size: 10px;
            vertical-align: top;
        }
        table.data-table tr:nth-child(even) {
            background-color: #f8fafc;
        }
        .star-rating {
            color: #d97706;
            font-weight: bold;
        }
        .badge-star {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
            background-color: #fef3c7;
            color: #92400e;
            border: 1px solid #fde68a;
        }
        .footer-sign {
            width: 100%;
            margin-top: 20px;
            page-break-inside: avoid;
        }
        .footer-sign table {
            width: 100%;
            border-collapse: collapse;
        }
        .sign-box {
            text-align: center;
            width: 40%;
            float: right;
        }
        .sign-box p {
            margin: 2px 0;
        }
    </style>
</head>
<body>

    <!-- KOP SURAT INSTANSI -->
    <table style="width: 100%; border-collapse: collapse; border: none; margin-bottom: 2px;">
        <tr>
            <!-- LOGO COLUMN -->
            <td style="width: 15%; text-align: center; vertical-align: middle; border: none; padding: 0;">
                <img src="{{ public_path('traveland/images/logo.png') }}" alt="Logo Tuban" style="width: 80px; height: auto;">
            </td>
            <!-- TEXT COLUMN -->
            <td style="width: 85%; text-align: center; vertical-align: middle; border: none; padding: 0; padding-right: 15%;">
                <h3 style="margin: 0; font-size: 16px; font-weight: normal; line-height: 1.3;">PEMERINTAH KABUPATEN TUBAN</h3>
                <h2 style="margin: 0; font-size: 18px; font-weight: bold; line-height: 1.3;">DINAS KOMUNIKASI, INFORMATIKA, STATISTIK DAN PERSANDIAN</h2>
                <p style="margin: 0; font-size: 12px; line-height: 1.3;">Jl. Mastrip No. 5 A, Sidorejo, Kec. Tuban, Kabupaten Tuban, Jawa Timur 62315</p>
                <p style="margin: 0; font-size: 12px; line-height: 1.3;">Email: diskominfo@tubankab.go.id | Telp: (0356) 8832697 | Website: diskominfo.tubankab.go.id</p>
            </td>
        </tr>
    </table>
    <div style="border-top: 3px solid black; margin-top: 10px; margin-bottom: 2px;"></div>
    <div style="border-top: 1px solid black; margin-top: 0; margin-bottom: 20px;"></div>

    <!-- JUDUL DOKUMEN -->
    <div class="doc-title">
        <h2>LAPORAN REKAPITULASI SURVEI KEPUASAN MASYARAKAT (IKM)</h2>
        <p>Portal Pelayanan Pengajuan Magang Terpadu (SIM-MAGANG)</p>
        <p>Dicetak Pada: {{ $printedAt }}</p>
    </div>

    <!-- EXECUTIVE SUMMARY KPI STATS -->
    <table class="stats-grid">
        <tr>
            <td style="width: 25%; padding: 3px;">
                <div class="stats-card">
                    <div class="val">{{ number_format($statistics['total']) }}</div>
                    <div class="lbl">Total Responden</div>
                </div>
            </td>
            <td style="width: 25%; padding: 3px;">
                <div class="stats-card">
                    <div class="val">{{ $statistics['average'] }} / 5.0</div>
                    <div class="lbl">Rata-Rata Indeks Kepuasan</div>
                </div>
            </td>
            <td style="width: 25%; padding: 3px;">
                <div class="stats-card">
                    <div class="val">{{ number_format($statistics['counts'][5] ?? 0) }}</div>
                    <div class="lbl">Sangat Puas (5 Bintang)</div>
                </div>
            </td>
            <td style="width: 25%; padding: 3px;">
                <div class="stats-card">
                    <div class="val">{{ number_format(($statistics['counts'][1] ?? 0) + ($statistics['counts'][2] ?? 0) + ($statistics['counts'][3] ?? 0)) }}</div>
                    <div class="lbl">Ulasan Perbaikan (1-3 Bintang)</div>
                </div>
            </td>
        </tr>
    </table>

    <!-- DATA TABEL SURVEI -->
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 5%; text-align: center;">No</th>
                <th style="width: 18%;">Rating Kepuasan</th>
                <th style="width: 47%;">Saran / Komentar Pengguna</th>
                <th style="width: 15%;">IP Address</th>
                <th style="width: 15%;">Tanggal Submit</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($surveys as $index => $survey)
                <tr>
                    <td style="text-align: center; font-weight: bold;">{{ $index + 1 }}</td>
                    <td>
                        <div style="font-weight: bold; font-size: 14px; color: #334155;">{{ $survey->rating }} / 5</div>
                        <div style="font-size: 11px; color: #64748b; margin-top: 2px;">Bintang</div>
                    </td>
                    <td>
                        @if (!empty($survey->komentar))
                            {{ $survey->komentar }}
                        @else
                            <em style="color: #94a3b8;">Tidak ada catatan komentar.</em>
                        @endif
                    </td>
                    <td style="font-family: monospace;">{{ $survey->ip_address ?? '-' }}</td>
                    <td>{{ $survey->created_at ? $survey->created_at->format('d/m/Y H:i') : '-' }} WIB</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; padding: 20px; color: #64748b;">
                        Belum ada data tanggapan survei kepuasan yang tercatat.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- TANDA TANGAN INSTANSI -->
    <div class="footer-sign">
        <table style="width: 100%;">
            <tr>
                <td style="width: 60%;"></td>
                <td style="width: 40%; text-align: center;">
                    <p>Tuban, {{ date('d F Y') }}</p>
                    <p><strong>Pengelola Sistem SIM-MAGANG</strong></p>
                    <p>Diskominfo SP Kabupaten Tuban</p>
                    <br><br><br><br>
                    <p><strong><u>Tim Administrator SPBE</u></strong></p>
                    <p style="font-size: 8px; color: #64748b;">Dokumen Otomatis Sistem SIM-MAGANG</p>
                </td>
            </tr>
        </table>
    </div>

</body>
</html>
