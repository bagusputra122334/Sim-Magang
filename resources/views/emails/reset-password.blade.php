<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atur Ulang Kata Sandi — SIM-MAGANG Diskominfo Tuban</title>
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
            background: linear-gradient(135deg, #0b1329 0%, #0d6efd 100%);
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
            color: #cbd5e1;
        }
        .email-body {
            padding: 36px 32px;
        }
        .email-body p {
            margin: 0 0 18px 0;
            font-size: 15px;
            color: #475569;
        }
        .btn-container {
            text-align: center;
            margin: 32px 0;
        }
        .btn-reset {
            display: inline-block;
            background-color: #0d6efd;
            color: #ffffff !important;
            font-weight: 700;
            font-size: 15px;
            padding: 14px 32px;
            border-radius: 50px;
            text-decoration: none;
            box-shadow: 0 4px 12px rgba(13, 110, 253, 0.25);
        }
        .email-footer {
            background-color: #f1f5f9;
            padding: 24px 28px;
            text-align: center;
            border-top: 1px solid #e2e8f0;
            font-size: 12px;
            color: #64748b;
        }
        .email-footer p {
            margin: 0 0 4px 0;
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="email-container">
            <div class="email-header">
                <h1>Sistem Informasi Magang</h1>
                <p>Dinas Komunikasi, Informatika, Statistik dan Persandian Kabupaten Tuban</p>
            </div>

            <div class="email-body">
                <p>Halo<strong>{{ isset($notifiable->name) ? ' ' . $notifiable->name : '' }}</strong>,</p>

                <p>Kami menerima permintaan untuk mengatur ulang kata sandi akun Anda pada Sistem Informasi Magang (SIM-MAGANG) Diskominfo Kabupaten Tuban.</p>

                <p>Silakan klik tombol di bawah ini untuk membuat kata sandi baru akun Anda:</p>

                <div class="btn-container">
                    <a href="{{ $resetUrl }}" class="btn-reset">Atur Ulang Kata Sandi</a>
                </div>

                <p style="font-size: 13px; color: #64748b; margin-top: 24px;">
                    Tautan atur ulang kata sandi ini akan kedaluwarsa dalam <strong>60 menit</strong>.
                </p>

                <p style="font-size: 13px; color: #64748b;">
                    Jika Anda tidak meminta pengaturan ulang kata sandi, tidak ada tindakan lebih lanjut yang diperlukan dan akun Anda tetap aman.
                </p>

                <hr style="border: none; border-top: 1px solid #e2e8f0; margin: 24px 0;">

                <p style="font-size: 12px; color: #94a3b8; word-break: break-all;">
                    Jika Anda mengalami kendala saat menekan tombol di atas, salin dan tempel tautan URL berikut pada peramban web Anda:<br>
                    <a href="{{ $resetUrl }}" style="color: #0d6efd;">{{ $resetUrl }}</a>
                </p>
            </div>

            <div class="email-footer">
                <p>Email ini dikirim secara otomatis oleh Sistem Informasi Magang Diskominfo Kabupaten Tuban.</p>
                <p>Mohon tidak membalas email ini.</p>
            </div>
        </div>
    </div>
</body>
</html>
