<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Pemberitahuan Pendaftaran Akun</title>
</head>
<body style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; background-color: #f8fafc; margin: 0; padding: 24px; color: #1e293b;">
    <table width="100%" cellpadding="0" cellspacing="0" style="max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 12px; overflow: hidden; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);">
        <tr>
            <td style="padding: 24px; background: #2563eb; color: #ffffff; text-align: center;">
                <h2 style="margin: 0; font-size: 20px; font-weight: 700;">SIMAGANG Kab. Tuban</h2>
                <p style="margin: 4px 0 0; font-size: 13px; opacity: 0.9;">Pemberitahuan Keamanan Akun</p>
            </td>
        </tr>
        <tr>
            <td style="padding: 32px 24px;">
                <p style="margin-top: 0; font-size: 15px;">Halo <strong>{{ $user->name }}</strong>,</p>
                <p style="font-size: 14px; line-height: 1.6; color: #475569;">
                    Kami mendeteksi adanya upaya pendaftaran akun baru pada portal <strong>SIMAGANG</strong> menggunakan alamat email ini (<strong>{{ $user->email }}</strong>).
                </p>
                <p style="font-size: 14px; line-height: 1.6; color: #475569;">
                    Karena alamat email Anda sudah terdaftar secara resmi di sistem kami, pendaftaran baru tidak dilakukan. Jika Anda lupa kata sandi akun Anda, Anda dapat melakukan reset kata sandi menggunakan tautan di bawah ini:
                </p>
                <div style="text-align: center; margin: 28px 0;">
                    <a href="{{ route('password.request') }}" style="background: #2563eb; color: #ffffff; text-decoration: none; padding: 12px 24px; border-radius: 8px; font-weight: 600; font-size: 14px; display: inline-block;">
                        Atur Ulang Kata Sandi
                    </a>
                </div>
                <p style="font-size: 13px; line-height: 1.6; color: #64748b; margin-bottom: 0;">
                    Jika Anda tidak merasa melakukan upaya pendaftaran ini, Anda dapat mengabaikan email ini dengan aman. Akun dan data Anda tetap terlindungi.
                </p>
            </td>
        </tr>
        <tr>
            <td style="padding: 16px 24px; background: #f1f5f9; text-align: center; font-size: 12px; color: #94a3b8;">
                &copy; {{ date('Y') }} SIMAGANG — Dinas Komunikasi dan Informatika, Statistik dan Persandian Kabupaten Tuban.
            </td>
        </tr>
    </table>
</body>
</html>

