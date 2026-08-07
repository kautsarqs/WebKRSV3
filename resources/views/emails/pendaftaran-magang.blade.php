<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifikasi Pendaftaran Magang Baru</title>
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
            background-color: #065f46;
            color: #ffffff !important;
            padding: 14px 28px;
            font-size: 14px;
            font-weight: 700;
            text-decoration: none;
            border-radius: 14px;
            box-shadow: 0 4px 6px -1px rgba(6, 95, 70, 0.2);
        }
        .footer {
            background-color: #fafafa;
            padding: 25px 30px;
            text-align: center;
            border-top: 1px solid #f4f4f5;
            font-size: 12px;
            color: #a1a1aa;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container">
            <div class="header">
                <h1>UPTD Kebun Raya Sambas</h1>
            </div>
            <div class="content">
                <h2>Permohonan Magang Baru</h2>
                <p class="subtitle">Halo Admin, ada permohonan pendaftaran magang baru yang perlu ditinjau.</p>

                <table class="details-table">
                    <tr>
                        <th>Nama Pemohon</th>
                        <td>{{ $pendaftaran->nama_lengkap }}</td>
                    </tr>
                    <tr>
                        <th>Institusi / Kampus</th>
                        <td>{{ $pendaftaran->institusi }} ({{ $pendaftaran->program_studi ?? '-' }})</td>
                    </tr>
                    <tr>
                        <th>Jenjang</th>
                        <td>{{ $pendaftaran->jenjang }}</td>
                    </tr>
                    <tr>
                        <th>Nomor WhatsApp</th>
                        <td>{{ $pendaftaran->nomor_hp }}</td>
                    </tr>
                    <tr>
                        <th>Judul / Topik Magang</th>
                        <td>{{ $pendaftaran->judul_magang }}</td>
                    </tr>
                    <tr>
                        <th>Bidang Magang</th>
                        <td>{{ $pendaftaran->bidang_magang }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal Pelaksanaan</th>
                        <td>{{ \Carbon\Carbon::parse($pendaftaran->tanggal_mulai)->format('d/m/Y') }} s/d {{ \Carbon\Carbon::parse($pendaftaran->tanggal_selesai)->format('d/m/Y') }}</td>
                    </tr>
                </table>

                <div class="button-wrapper">
                    <a href="{{ route('admin.magang.index') }}" class="button">Buka Dashboard Admin</a>
                </div>
            </div>
            <div class="footer">
                &copy; {{ date('Y') }} UPTD Kebun Raya Sambas. All rights reserved.
            </div>
        </div>
    </div>
</body>
</html>
