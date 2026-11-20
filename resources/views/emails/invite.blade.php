<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Undangan Akun Administrator - Literasi Tamsel</title>
</head>
<body style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #f8fafc; color: #1e293b; margin: 0; padding: 24px;">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
        <!-- Header -->
        <tr>
            <td style="background-color: #0A2373; padding: 28px 32px; text-align: center;">
                <h1 style="color: #ffffff; margin: 0; font-size: 20px; font-weight: 700; letter-spacing: -0.5px;">Komunitas Literasi Tamsel</h1>
                <p style="color: #93c5fd; margin: 6px 0 0 0; font-size: 13px;">Ruang Baca & Komunitas Literasi Tambun Selatan</p>
            </td>
        </tr>

        <!-- Content -->
        <tr>
            <td style="padding: 32px;">
                <h2 style="font-size: 18px; font-weight: 600; color: #0f172a; margin-top: 0;">Halo, {{ $nama }}!</h2>
                <p style="font-size: 14px; line-height: 1.6; color: #475569; margin: 12px 0;">
                    Selamat, Anda telah didaftarkan sebagai <strong>Administrator</strong> di portal resmi Komunitas Literasi Tamsel
                </p>

                <!-- Credentials Card -->
                <div style="background-color: #f1f5f9; border-radius: 12px; padding: 20px; margin: 24px 0; border: 1px solid #e2e8f0;">
                    <div style="font-size: 12px; font-weight: bold; text-transform: uppercase; color: #64748b; margin-bottom: 12px; letter-spacing: 0.5px;">Detail Kredensial Login:</div>
                    <table style="width: 100%; font-size: 14px; color: #1e293b;">
                        <tr>
                            <td style="padding: 4px 0; width: 110px; color: #64748b;">Username:</td>
                            <td style="padding: 4px 0; font-weight: 600; color: #0A2373;">{{ $username }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 4px 0; width: 110px; color: #64748b;">Password:</td>
                            <td style="padding: 4px 0; font-family: monospace; font-size: 15px; font-weight: bold; color: #0f172a;">{{ $passwordPlain }}</td>
                        </tr>
                    </table>
                </div>

                <!-- Call to Action -->
                <div style="text-align: center; margin: 32px 0;">
                    <a href="https://52rxx3br-8000.asse.devtunnels.ms/admin" style="display: inline-block; background-color: #0A2373; color: #ffffff; text-decoration: none; font-size: 14px; font-weight: 600; padding: 12px 28px; border-radius: 8px; box-shadow: 0 2px 4px rgba(10,35,115,0.2);">
                        Masuk ke Dashboard Admin &rarr;
                    </a>
                </div>
            </td>
        </tr>

        <!-- Footer -->
        <tr>
            <td style="background-color: #f8fafc; border-top: 1px solid #e2e8f0; padding: 20px 32px; text-align: center;">
                <p style="margin: 0; font-size: 12px; color: #94a3b8;">
                    Email ini dikirimkan otomatis oleh sistem Literasi Tamsel. Harap jangan membalas email ini secara langsung.
                </p>
            </td>
        </tr>
    </table>
</body>
</html>
