<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Akun Anda</title>
    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: #f4f4f5;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
        }
        .wrapper {
            width: 100%;
            background-color: #f4f4f5;
            padding: 40px 20px;
            box-sizing: border-box;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border: 1px solid #e4e4e7;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.02);
        }
        .header {
            background-color: #064e3b;
            padding: 35px 30px;
            text-align: center;
        }
        .header img {
            height: 56px;
            width: auto;
            margin-bottom: 12px;
            display: inline-block;
        }
        .header h1 {
            color: #ffffff;
            font-size: 18px;
            font-weight: 700;
            margin: 0;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }
        .content {
            padding: 45px 35px;
            color: #27272a;
        }
        .content h2 {
            font-size: 22px;
            font-weight: 700;
            margin-top: 0;
            margin-bottom: 18px;
            color: #0f172a;
        }
        .content p {
            font-size: 15px;
            line-height: 1.6;
            margin-top: 0;
            margin-bottom: 22px;
            color: #52525b;
        }
        .button-wrapper {
            text-align: center;
            margin: 35px 0;
        }
        .button {
            display: inline-block;
            padding: 14px 32px;
            background-color: #065f46;
            color: #ffffff !important;
            text-decoration: none;
            font-weight: 700;
            font-size: 13px;
            border-radius: 14px;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            box-shadow: 0 4px 10px rgba(6, 95, 70, 0.25);
            transition: background-color 0.2s;
        }
        .button:hover {
            background-color: #047857;
        }
        .security-notice {
            background-color: #fcfcfd;
            border: 1px solid #f3f3f6;
            border-radius: 16px;
            padding: 20px;
            margin-top: 30px;
            font-size: 13px;
            color: #71717a;
            line-height: 1.5;
        }
        .security-notice strong {
            color: #3f3f46;
        }
        .footer {
            background-color: #fafafa;
            padding: 28px;
            text-align: center;
            border-top: 1px solid #f4f4f5;
            font-size: 12px;
            color: #a1a1aa;
        }
        .footer p {
            margin: 0;
            line-height: 1.6;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container">
            <div class="header">
                <h1>Kebun Raya Sambas</h1>
            </div>
            <div class="content">
                <h2>Halo, {{ $user->name }}!</h2>
                <p>Terima kasih telah melakukan registrasi di platform digital <strong>Kebun Raya Sambas</strong>. Silakan verifikasi alamat email Anda agar dapat menikmati seluruh akses navigasi, pendaftaran kunjungan, serta perizinan penelitian.</p>

                <div class="button-wrapper">
                    <a href="{{ $url }}" class="button">Verifikasi Alamat Email</a>
                </div>

                <p>Tautan verifikasi ini akan kedaluwarsa dalam 60 menit demi alasan keamanan akun Anda.</p>

                <div class="security-notice">
                    <strong>Pemberitahuan Keamanan:</strong> Jika Anda tidak merasa membuat akun baru atau merasa salah menerima email ini, abaikan pesan ini dengan aman.
                </div>
            </div>
            <div class="footer">
                <p>&copy; {{ date('Y') }} UPTD Kebun Raya Sambas. All rights reserved.</p>
                <p>Kawasan Konservasi & Pusat Penelitian Flora, Kalimantan Barat</p>
            </div>
        </div>
    </div>
</body>
</html>
