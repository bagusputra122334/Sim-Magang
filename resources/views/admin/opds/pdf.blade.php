<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Data Master OPD Kabupaten Tuban</title>
    <style>
        @page {
            margin: 25pt 30pt;
            size: A4 landscape;
        }
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 9.5px;
            color: #1e293b;
            line-height: 1.3;
        }
        .header-kop {
            width: 100%;
            border-bottom: 3px double #0f172a;
            padding-bottom: 6px;
            margin-bottom: 12px;
        }
        .header-kop table {
            width: 100%;
            border-collapse: collapse;
        }
        .header-kop .title-gov {
            font-size: 13px;
            font-weight: bold;
            text-transform: uppercase;
            color: #0f172a;
        }
        .header-kop .title-dept {
            font-size: 15px;
            font-weight: bold;
            text-transform: uppercase;
            color: #1e3a8a;
        }
        .header-kop .subtext {
            font-size: 8.5px;
            color: #475569;
        }
        .doc-title {
            text-align: center;
            margin-bottom: 10px;
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
        .filter-info {
            background-color: #f1f5f9;
            border: 1px solid #cbd5e1;
            padding: 5px 8px;
            font-size: 8.5px;
            margin-bottom: 10px;
            border-radius: 3px;
        }
        .stats-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        .stats-table td {
            background-color: #f8fafc;
            border: 1px solid #cbd5e1;
            padding: 5px 8px;
            text-align: center;
            width: 25%;
        }
        .stats-table .stat-label {
            font-size: 8px;
            font-weight: bold;
            color: #64748b;
            text-transform: uppercase;
            display: block;
        }
        .stats-table .stat-value {
            font-size: 12px;
            font-weight: bold;
            color: #0f172a;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }
        .data-table th, .data-table td {
            border: 1px solid #94a3b8;
            padding: 5px 6px;
            text-align: left;
            vertical-align: top;
        }
        .data-table th {
            background-color: #f1f5f9;
            font-weight: bold;
            color: #1e293b;
            text-transform: uppercase;
            font-size: 8px;
            letter-spacing: 0.3px;
        }
        .badge {
            display: inline-block;
            padding: 2px 5px;
            border-radius: 3px;
            font-size: 7.5px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .badge-active {
            background-color: #dcfce7;
            color: #15803d;
            border: 1px solid #86efac;
        }
        .badge-inactive {
            background-color: #f1f5f9;
            color: #64748b;
            border: 1px solid #cbd5e1;
        }
        .code-pill {
            font-family: monospace;
            background-color: #e2e8f0;
            color: #334155;
            padding: 1px 4px;
            border-radius: 2px;
            font-weight: bold;
            font-size: 8px;
        }
        .footer-sig {
            width: 100%;
            margin-top: 25px;
            page-break-inside: avoid;
        }
        .footer-sig td {
            vertical-align: top;
            border: none;
            padding: 0;
        }
    </style>
</head>
<body>
    <!-- KOP SURAT RESMI KABUPATEN TUBAN -->
    <div class="header-kop">
        <table>
            <tr>
                <td style="width: 70px; text-align: center; vertical-align: middle;">
                    @if(file_exists(public_path('traveland/images/logo.png')))
                        <img src="{{ public_path('traveland/images/logo.png') }}" alt="Logo Tuban" style="width: 52px; height: auto;">
                    @endif
                </td>
                <td style="text-align: center; vertical-align: middle; padding-right: 70px;">
                    <div class="title-gov">Pemerintah Kabupaten Tuban</div>
                    <div class="title-dept">Dinas Komunikasi dan Informatika, Statistik dan Persandian</div>
                    <div class="subtext">Sistem Informasi Manajemen Magang (SIMANG) — Portal Monitoring & Integrasi Multi-OPD</div>
                    <div class="subtext">Jl. Mastrip No. 5 A, Sidorejo, Kec. Tuban, Kabupaten Tuban, Jawa Timur 62315 | Email: diskominfo@tubankab.go.id</div>
                </td>
            </tr>
        </table>
    </div>

    <!-- JUDUL DOKUMEN -->
    <div class="doc-title">
        <h2>Laporan Data Master Organisasi Perangkat Daerah (OPD)</h2>
        <p>Waktu Cetak: {{ $printedAt }} | Administrator: Super Admin Kabupaten Tuban</p>
    </div>

    <!-- FILTER INFORMATION (IF ACTIVE) -->
    @if($search || $status)
        <div class="filter-info">
            <strong>Parameter Filter:</strong>
            @if($search)
                <span>Kata Kunci: "<em>{{ $search }}</em>"</span>
            @endif
            @if($status)
                <span style="margin-left: 10px;">Status: <strong>{{ $status === 'active' ? 'Aktif' : 'Nonaktif' }}</strong></span>
            @endif
            <span style="margin-left: 15px; color: #64748b;">(Menampilkan {{ $opds->count() }} OPD hasil filter)</span>
        </div>
    @endif

    <!-- RINGKASAN METRIK -->
    <table class="stats-table">
        <tr>
            <td>
                <span class="stat-label">Total OPD</span>
                <span class="stat-value">{{ number_format($statistics['total_opd'] ?? 0, 0, ',', '.') }}</span>
            </td>
            <td>
                <span class="stat-label">OPD Status Aktif</span>
                <span class="stat-value" style="color: #15803d;">{{ number_format($statistics['active_opd'] ?? 0, 0, ',', '.') }}</span>
            </td>
            <td>
                <span class="stat-label">Total Formasi Terbuka</span>
                <span class="stat-value" style="color: #7c3aed;">{{ number_format($statistics['total_positions'] ?? 0, 0, ',', '.') }}</span>
            </td>
            <td>
                <span class="stat-label">Total Peserta Magang Aktif</span>
                <span class="stat-value" style="color: #0284c7;">{{ number_format($statistics['active_interns'] ?? 0, 0, ',', '.') }}</span>
            </td>
        </tr>
    </table>

    <!-- TABEL DATA OPD -->
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 25px; text-align: center;">No</th>
                <th style="width: 55px; text-align: center;">Kode</th>
                <th>Nama Organisasi Perangkat Daerah (OPD)</th>
                <th style="width: 170px;">Kepala Dinas / Pimpinan & NIP</th>
                <th style="width: 140px;">Kontak (Email / Telp)</th>
                <th style="width: 50px; text-align: center;">Formasi</th>
                <th style="width: 55px; text-align: center;">Peserta</th>
                <th style="width: 45px; text-align: center;">Admin</th>
                <th style="width: 60px; text-align: center;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($opds as $index => $opd)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td style="text-align: center;">
                        <span class="code-pill">{{ $opd->code }}</span>
                    </td>
                    <td>
                        <strong style="color: #0f172a; font-size: 10px;">{{ $opd->name }}</strong>
                        @if($opd->address)
                            <div style="font-size: 8px; color: #64748b; margin-top: 1px;">{{ $opd->address }}</div>
                        @endif
                    </td>
                    <td>
                        <div style="font-weight: bold; color: #1e293b;">{{ $opd->head_of_office_name ?? '-' }}</div>
                        <div style="font-size: 8px; color: #64748b;">{{ $opd->head_of_office_nip ? 'NIP. ' . $opd->head_of_office_nip : 'NIP: -' }}</div>
                    </td>
                    <td>
                        @if($opd->email)
                            <div>{{ $opd->email }}</div>
                        @endif
                        @if($opd->phone)
                            <div style="font-size: 8px; color: #64748b;">{{ $opd->phone }}</div>
                        @endif
                        @if(!$opd->email && !$opd->phone)
                            <span style="color: #94a3b8;">-</span>
                        @endif
                    </td>
                    <td style="text-align: center; font-weight: bold; color: #1e3a8a;">
                        {{ $opd->positions_count ?? 0 }}
                    </td>
                    <td style="text-align: center; font-weight: bold; color: #15803d;">
                        {{ $opd->active_interns_count ?? 0 }}
                    </td>
                    <td style="text-align: center; font-weight: bold; color: #7c3aed;">
                        {{ $opd->admins_count ?? 0 }}
                    </td>
                    <td style="text-align: center;">
                        @if($opd->is_active)
                            <span class="badge badge-active">Aktif</span>
                        @else
                            <span class="badge badge-inactive">Nonaktif</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" style="text-align: center; padding: 15px; color: #64748b;">
                        Tidak ada data Organisasi Perangkat Daerah yang sesuai dengan kriteria filter.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- TANDA TANGAN / PENGESAHAN -->
    <table class="footer-sig">
        <tr>
            <td style="width: 70%;"></td>
            <td style="width: 30%; text-align: center;">
                <div>Tuban, {{ now()->translatedFormat('d F Y') }}</div>
                <div style="font-weight: bold; margin-top: 3px;">Super Administrator SIMANG</div>
                <div style="font-size: 8px; color: #64748b;">Kabupaten Tuban</div>
                <div style="height: 45px;"></div>
                <div style="font-weight: bold; text-decoration: underline;">Pemerintah Kabupaten Tuban</div>
                <div style="font-size: 8px; color: #475569;">Sistem Terintegrasi Multi-OPD</div>
            </td>
        </tr>
    </table>
</body>
</html>

