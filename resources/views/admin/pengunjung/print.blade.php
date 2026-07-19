<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pengunjung Kebun Raya Sambas - {{ \Carbon\Carbon::now('Asia/Jakarta')->translatedFormat('d F Y') }}</title>
    <style>
        body { font-family: 'Helvetica Neue', Arial, sans-serif; color: #333; margin: 30px; font-size: 13px; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #333; padding-bottom: 15px; }
        .header h1 { margin: 0; font-size: 20px; font-weight: bold; text-transform: uppercase; }
        .header p { margin: 5px 0 0 0; color: #666; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #f7f7f7; font-weight: bold; text-transform: uppercase; font-size: 10px; }
        .text-center { text-align: center; }
        .footer { margin-top: 40px; text-align: right; font-size: 11px; color: #777; }
        .role-badge { font-size: 9px; padding: 2px 6px; background-color: #eee; border-radius: 4px; color: #555; font-weight: bold; margin-left: 5px; }
        @media print {
            body { margin: 15px; }
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="header">
        <h1>Daftar Pengunjung Kebun Raya Sambas</h1>
        <p>Laporan Data Semua Pengunjung dan Rombongan Terdaftar - Dicetak pada {{ \Carbon\Carbon::now('Asia/Jakarta')->translatedFormat('d-m-Y H:i') }} WIB</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%;" class="text-center">No</th>
                <th style="width: 15%;">Tanggal</th>
                <th style="width: 25%;">Nama Pengunjung</th>
                <th style="width: 15%;">Nomor HP</th>
                <th style="width: 20%;">Asal Daerah / Instansi</th>
                <th style="width: 20%;">Tujuan / Keperluan</th>
            </tr>
        </thead>
        <tbody>
            @php $counter = 1; @endphp
            @forelse($pengunjungs as $row)

                <tr>
                    <td class="text-center">{{ $counter++ }}</td>
                    <td>{{ \Carbon\Carbon::parse($row->tanggal_kunjungan)->translatedFormat('d-m-Y') }}</td>
                    <td><strong>{{ $row->nama_lengkap }}</strong> <span class="role-badge">Perwakilan</span></td>
                    <td>{{ $row->nomor_hp }}</td>
                    <td>{{ $row->instansi ?? '-' }}</td>
                    <td>{{ $row->keperluan ?? '-' }}</td>
                </tr>

                @if(!empty($row->rombongan_details))
                    @foreach($row->rombongan_details as $friend)
                        <tr>
                            <td class="text-center">{{ $counter++ }}</td>
                            <td>{{ \Carbon\Carbon::parse($row->tanggal_kunjungan)->translatedFormat('d-m-Y') }}</td>
                            <td>{{ $friend['nama'] }}</td>
                            <td>{{ !empty($friend['nomor_hp']) ? $friend['nomor_hp'] : $row->nomor_hp }}</td>
                            <td>{{ !empty($friend['instansi']) ? $friend['instansi'] : ($row->instansi ?? '-') }}</td>
                            <td>{{ $row->keperluan ?? '-' }}</td>
                        </tr>
                    @endforeach
                @endif
            @empty
                <tr>
                    <td colspan="6" class="text-center">Tidak ada data pengunjung yang disetujui.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Kebun Raya Sambas &copy; {{ date('Y') }} - Halaman Cetak Laporan Resmi (WIB)</p>
    </div>
</body>
</html>
