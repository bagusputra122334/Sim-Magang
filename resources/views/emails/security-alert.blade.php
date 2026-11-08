<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peringatan Keamanan Akun — SIMAGANG Pemkab Tuban</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: #f8fafc;
            color: #334155;
            margin: 0;
            padding: 0;
            line-height: 1.6;
        }
        .email-wrapper {
            width: 100%;
            background-color: #f8fafc;
            padding: 40px 15px;
        }
        .email-container {
            max-width: 580px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            border: 1px solid #e2e8f0;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05);
        }
        .email-header {
            background: linear-gradient(135deg, #7f1d1d 0%, #dc2626 100%);
            color: #ffffff;
            padding: 32px 28px;
            text-align: center;
        }
        .email-header h1 {
            margin: 0 0 6px 0;
            font-size: 20px;
            font-weight: 800;
            letter-spacing: -0.02em;
        }
        .email-header p {
            margin: 0;
            font-size: 13px;
            color: #fecaca;
        }
        .email-body {
            padding: 36px 32px;
        }
        .alert-box {
            background-color: #fff1f2;
            border-left: 4px solid #e11d48;
            padding: 16px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .alert-box p {
            margin: 0;
            color: #9f1239;
            font-size: 14px;
            font-weight: 500;
        }
        .detail-table {
            width: 100%;
            margin: 20px 0;
            border-collapse: collapse;
            font-size: 13px;
        }
        .detail-table td {
            padding: 8px 12px;
            border-bottom: 1px solid #f1f5f9;
        }
        .detail-table td.label {
            font-weight: 600;
            color: #64748b;
            width: 35%;
        }
        .detail-table td.value {
            color: #1e293b;
            font-weight: 500;
        }
        .button-wrapper {
            text-align: center;
            margin: 30px 0 20px 0;
        }
        .btn-action {
            display: inline-block;
            background: #2563eb;
            color: #ffffff !important;
            text-decoration: none;
            padding: 12px 28px;
            border-radius: 8px;
            font-weight: 700;
            font-size: 14px;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
        }
        .email-footer {
            background-color: #f8fafc;
            padding: 24px 32px;
            text-align: center;
            border-top: 1px solid #e2e8f0;
            font-size: 12px;
            color: #64748b;
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="email-container">
            <!-- Header -->
            <div class="email-header">
                <h1>SIMAGANG KABUPATEN TUBAN</h1>
                <p>Pemberitahuan Keamanan Akun Pengguna</p>
            </div>

            <!-- Body -->
            <div class="email-body">
                <p style="font-size: 16px; font-weight: 700; margin-top: 0;">Halo, {{ $user->name }}!</p>

                <div class="alert-box">
                    <p>⚠️ Terdeteksi 10x percobaan gagal masuk ke akun SIMAGANG Anda. Demi keamanan, akun saat ini dikunci sementara selama 5 menit.</p>
                </div>

                <p style="font-size: 14px; color: #475569;">
                    Rincian aktivitas percobaan masuk yang tercatat di sistem:
                </p>

                <table class="detail-table">
                    <tr>
                        <td class="label">Alamat IP</td>
                        <td class="value"><code>{{ $ipAddress }}</code></td>
                    </tr>
                    <tr>
                        <td class="label">Waktu Kejadian</td>
                        <td class="value">{{ $attemptTime }} WIB</td>
                    </tr>
                    <tr>
                        <td class="label">Perangkat</td>
                        <td class="value">{{ \Illuminate\Support\Str::limit($userAgent, 50) }}</td>
                    </tr>
                </table>

                <p style="font-size: 14px; color: #475569;">
                    Jika Anda lupa kata sandi akun Anda, silakan klik tombol di bawah ini untuk mengatur ulang kata sandi dengan aman:
                </p>

                <div class="button-wrapper">
                    <a href="{{ $resetUrl }}" class="btn-action">
                        Lupa Kata Sandi / Atur Ulang Sandi
                    </a>
                </div>

                <p style="font-size: 13px; color: #64748b; margin-top: 24px;">
                    <em>Catatan: Jika Anda tidak merasa melakukan percobaan ini, kemungkinan ada pihak lain yang mencoba mengakses akun Anda. Kami menyarankan untuk segera mereset kata sandi Anda dan jangan berikan kode apapun kepada pihak lain.</em>
                </p>
            </div>

            <!-- Footer -->
            <div class="email-footer">
                <p style="margin: 0 0 6px 0;"><strong>Tim Pengelola SIMAGANG</strong></p>
                <p style="margin: 0;">Dinas Komunikasi, Informatika, Statistik dan Persandian Kabupaten Tuban</p>
            </div>
        </div>
    </div>
</body>
</html>

