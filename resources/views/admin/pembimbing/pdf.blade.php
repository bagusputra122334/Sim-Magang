<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Pembimbing Magang — SIMAGANG</title>
    <style>
        @page {
            margin: 25pt 30pt;
            size: A4 portrait;
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
            font-size: 14px;
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
            margin: 3px 0 0 0;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }
        .data-table th, .data-table td {
            border: 1px solid #94a3b8;
            padding: 5px 6px;
            text-align: left;
            vertical-align: middle;
        }
        .data-table th {
            background-color: #f1f5f9;
            font-weight: bold;
            color: #1e293b;
            text-transform: uppercase;
            font-size: 8px;
            letter-spacing: 0.3px;
        }
        .code-pill {
            font-family: monospace;
            background-color: #e2e8f0;
            color: #334155;
            padding: 1px 4px;
            border-radius: 2px;
            font-size: 8px;
        }
        .footer-sig {
            width: 100%;
            margin-top: 30px;
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
    <!-- KOP SURAT RESMI -->
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
                    <div class="title-dept">
                        {{ $selectedOpd ? $selectedOpd->name : 'Dinas Komunikasi dan Informatika, Statistik dan Persandian' }}
                    </div>
                    <div class="subtext">Sistem Informasi Manajemen Magang (SIMAGANG) — Portal Monitoring Terpadu</div>
                    <div class="subtext">Jl. Mastrip No. 5 A, Sidorejo, Kec. Tuban, Kabupaten Tuban, Jawa Timur 62315 | Email: diskominfo@tubankab.go.id</div>
                </td>
            </tr>
        </table>
    </div>

    <!-- JUDUL DOKUMEN -->
    <div class="doc-title">
        <h2>Daftar Data Pembimbing Lapangan Magang</h2>
        <p>Waktu Cetak: {{ $printedAt }} | Administrator: {{ $isSuperAdmin ? 'Super Admin Kabupaten Tuban' : ($selectedOpd?->name ?? 'Admin OPD') }}</p>
    </div>

    <!-- DATA TABLE -->
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 25px; text-align: center;">No.</th>
                <th>Nama Pembimbing</th>
                <th style="width: 110px;">NIP</th>
                <th>Email Kontak</th>
                @if($isSuperAdmin)
                    <th>Wilayah OPD</th>
                @endif
                <th>Divisi / Bidang</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pembimbings as $p)
                <tr>
                    <td style="text-align: center;">{{ $loop->iteration }}</td>
                    <td style="font-weight: bold;">{{ $p->name }}</td>
                    <td><span class="code-pill">{{ $p->nip ?? '-' }}</span></td>
                    <td>{{ $p->email }}</td>
                    @if($isSuperAdmin)
                        <td>{{ $p->opd?->name ?? $p->division?->opd?->name ?? 'Dinas Komunikasi dan Informatika Tuban' }}</td>
                    @endif
                    <td>{{ $p->division->nama_divisi ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="{{ $isSuperAdmin ? 6 : 5 }}" style="text-align: center; padding: 15px; color: #64748b;">
                        Tidak ada data pembimbing yang cocok.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- FOOTER TANDA TANGAN -->
    <table class="footer-sig">
        <tr>
            <td style="width: 60%;"></td>
            <td style="width: 40%; text-align: center;">
                <p style="margin: 0 0 45px 0;">
                    Tuban, {{ now()->translatedFormat('d F Y') }}<br>
                    <strong>{{ $isSuperAdmin ? 'Super Administrator Kabupaten' : 'Administrator Wilayah OPD' }}</strong>
                </p>
                <p style="margin: 0; font-weight: bold; text-decoration: underline;">
                    {{ auth()->user()?->name ?? 'Administrator SIMAGANG' }}
                </p>
                <p style="margin: 0; color: #64748b;">
                    NIP. {{ auth()->user()?->nip ?? '—' }}
                </p>
            </td>
        </tr>
    </table>
</body>
</html>

