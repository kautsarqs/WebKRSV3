<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Peserta Magang Kebun Raya Sambas - {{ \Carbon\Carbon::now('Asia/Jakarta')->translatedFormat('d F Y') }}</title>
    <style>
        body { font-family: 'Helvetica Neue', Arial, sans-serif; color: #333; margin: 35px; font-size: 11px; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #333; padding-bottom: 15px; }
        .header h1 { margin: 0; font-size: 20px; font-weight: bold; text-transform: uppercase; }
        .header p { margin: 5px 0 0 0; color: #666; font-size: 11px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #aaa; padding: 8px; text-align: left; }
        th { background-color: #f0f0f0; font-weight: bold; text-transform: uppercase; font-size: 9px; }
        .text-center { text-align: center; }
        .footer { margin-top: 45px; text-align: right; font-size: 10px; color: #777; }
        @media print {
            body { margin: 15px; }
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="header">
        <h1>Daftar Peserta Magang Kebun Raya Sambas</h1>
        <p>Laporan Data Peserta Magang Terdaftar dan Disetujui - Dicetak pada {{ \Carbon\Carbon::now('Asia/Jakarta')->translatedFormat('d-m-Y H:i') }} WIB</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 4%;" class="text-center">No</th>
                <th style="width: 17%;">Nama Lengkap</th>
                <th style="width: 13%;">Nomor HP</th>
                <th style="width: 17%;">Institusi</th>
                <th style="width: 7%;">Jenjang</th>
                <th style="width: 18%;">Judul / Topik Magang</th>
                <th style="width: 7%;">Mulai</th>
                <th style="width: 7%;">Selesai</th>
                <th style="width: 10%;" class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($magangs as $index => $row)
                @php
                    $isSelesai = $row->status_magang === 'selesai' || ($row->tanggal_selesai && \Carbon\Carbon::parse($row->tanggal_selesai)->endOfDay()->isPast());
                    $statusText = $isSelesai ? 'Selesai' : 'Masih Magang';
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td><strong>{{ $row->nama_lengkap }}</strong></td>
                    <td>{{ $row->nomor_hp }}</td>
                    <td>{{ $row->institusi }} ({{ $row->program_studi ?? '-' }})</td>
                    <td>{{ $row->jenjang }}</td>
                    <td>{{ $row->judul_magang }} ({{ $row->bidang_magang }})</td>
                    <td>{{ \Carbon\Carbon::parse($row->tanggal_mulai)->translatedFormat('d-m-Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($row->tanggal_selesai)->translatedFormat('d-m-Y') }}</td>
                    <td class="text-center">
                        <strong style="color: {{ $isSelesai ? '#666666' : '#059669' }}; font-size: 10px;">
                            {{ $statusText }}
                        </strong>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center">Tidak ada data peserta magang yang disetujui.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Kebun Raya Sambas &copy; {{ date('Y') }} - Halaman Cetak Laporan Resmi (WIB)</p>
    </div>
</body>
</html>
