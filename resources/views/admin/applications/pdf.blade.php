<!DOCTYPE html>
<html>
<head>
    <title>Laporan Pendaftaran Magang</title>
    <style>
        body { font-family: 'Times New Roman', Times, serif; font-size: 12px; }
        .kop-surat { width: 100%; text-align: center; margin-bottom: 20px; }
        .kop-surat h3, .kop-surat h2, .kop-surat p { margin: 0; line-height: 1.3; }
        .kop-surat h3 { font-size: 16px; font-weight: normal; }
        .kop-surat h2 { font-size: 18px; font-weight: bold; }
        .kop-surat p { font-size: 12px; }
        .garis-tebal { border-top: 3px solid black; margin-top: 10px; margin-bottom: 2px; }
        .garis-tipis { border-top: 1px solid black; margin-top: 0; margin-bottom: 20px; }
        h4.title { text-align: center; text-transform: uppercase; font-size: 14px; text-decoration: underline; margin-bottom: 15px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #333; padding: 6px 8px; text-align: left; vertical-align: top; }
        th { background-color: #f2f2f2; font-weight: bold; text-align: center; }
    </style>
</head>
<body>
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
    <h4 class="title">Data Verifikasi Pendaftaran Peserta Magang</h4>
    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 15%;">No. Pendaftaran</th>
                <th style="width: 25%;">Nama Peserta & Kontak</th>
                <th style="width: 25%;">Instansi / Sekolah</th>
                <th style="width: 15%;">Posisi Dilamar</th>
                <th style="width: 15%;">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($applications as $index => $app)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td>{{ $app->registration_number ?? $app->nomor_pendaftaran ?? '-' }}</td>
                <td>
                    <strong>{{ $app->name ?? $app->user?->name ?? '-' }}</strong><br>
                    <span style="font-size: 10px; color: #555;">{{ $app->email ?? $app->user?->email ?? '-' }}</span>
                </td>
                <td>{{ $app->institution ?? $app->user?->profile?->institusi ?? '-' }}</td>
                <td>{{ $app->position->name ?? $app->position?->nama_posisi ?? '-' }}</td>
                <td style="text-align: center;">{{ strtoupper(is_object($app->status) ? ($app->status->value ?? $app->status->label()) : ($app->status ?? '-')) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
