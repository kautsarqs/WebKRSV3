<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifikasi Pendaftaran Peneliti Baru</title>
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
            margin-bottom: 10px;
            color: #0f172a;
        }
        .subtitle {
            font-size: 14px;
            color: #71717a;
            margin-top: 0;
            margin-bottom: 30px;
        }
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin: 25px 0;
            font-size: 14px;
            border: 1px solid #e4e4e7;
            border-radius: 12px;
            overflow: hidden;
        }
        .details-table th, .details-table td {
            padding: 14px 16px;
            text-align: left;
            vertical-align: top;
        }
        .details-table th {
            width: 160px;
            font-weight: 700;
            color: #3f3f46;
            background-color: #f9f9fb;
            border-bottom: 1px solid #e4e4e7;
            border-right: 1px solid #e4e4e7;
        }
        .details-table td {
            color: #18181b;
            border-bottom: 1px solid #e4e4e7;
        }
        .details-table tr:last-child th, .details-table tr:last-child td {
            border-bottom: none;
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
                <h2>Permohonan Izin Penelitian Baru</h2>
                <div class="subtitle">Sistem Notifikasi Administrator Kebun Raya Sambas</div>
                
                <p>Halo Admin, satu permohonan izin penelitian baru telah masuk ke sistem. Berikut ringkasan informasi pendaftar:</p>
                
                <table class="details-table">
                    <tr>
                        <th>Nama Lengkap</th>
                        <td>{{ $pendaftaran->nama_lengkap }}</td>
                    </tr>
                    <tr>
                        <th>Institusi</th>
                        <td>{{ $pendaftaran->institusi }}</td>
                    </tr>
                    <tr>
                        <th>Program Studi</th>
                        <td>{{ $pendaftaran->program_studi ?? '-' }} ({{ $pendaftaran->jenjang }})</td>
                    </tr>
                    <tr>
                        <th>Judul Penelitian</th>
                        <td><strong>{{ $pendaftaran->judul_penelitian }}</strong></td>
                    </tr>
                    <tr>
                        <th>Bidang Penelitian</th>
                        <td>{{ $pendaftaran->bidang_penelitian }}</td>
                    </tr>
                    <tr>
                        <th>Durasi Waktu</th>
                        <td>{{ $pendaftaran->tanggal_mulai->format('d F Y') }} s/d {{ $pendaftaran->tanggal_selesai->format('d F Y') }}</td>
                    </tr>
                </table>
                
                <p>Silakan masuk ke panel admin untuk meninjau permohonan izin penelitian ini (menyetujui atau menolak permohonan).</p>
                
                <div class="button-wrapper">
                    <a href="{{ route('admin.peneliti.index') }}" class="button">Tinjau Permohonan</a>
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
