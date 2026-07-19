<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PendaftaranPeneliti;
use App\Models\PendaftaranPengunjung;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class PendaftaranManageController extends Controller
{

    public function indexPengunjung(Request $request)
    {
        $status = $request->get('status');
        $query = PendaftaranPengunjung::with('user')->latest();

        if ($status) {
            $query->where('status', $status);
        }

        $pengunjungs = $query->paginate(15);
        return view('admin.pengunjung.index', compact('pengunjungs'));
    }

    public function updatePengunjungStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:disetujui,ditolak',
            'catatan_admin' => 'nullable|string|max:500',
        ]);

        $pengunjung = PendaftaranPengunjung::findOrFail($id);
        $pengunjung->update([
            'status' => $request->status,
            'catatan_admin' => $request->status === 'ditolak' ? $request->catatan_admin : null,
        ]);

        return back()->with('success', 'Status pendaftaran pengunjung berhasil diperbarui.');
    }

    public function indexPeneliti(Request $request)
    {
        $status = $request->get('status');
        $query = PendaftaranPeneliti::with('user')->latest();

        if ($status) {
            $query->where('status', $status);
        }

        $penelitis = $query->paginate(15);
        return view('admin.peneliti.index', compact('penelitis'));
    }

    public function updatePenelitiStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:disetujui,ditolak',
            'catatan_admin' => 'nullable|string|max:500',
        ]);

        $peneliti = PendaftaranPeneliti::findOrFail($id);
        $peneliti->update([
            'status' => $request->status,
            'catatan_admin' => $request->catatan_admin,
        ]);

        return back()->with('success', 'Status pendaftaran peneliti berhasil diperbarui.');
    }

    public function exportPengunjung(Request $request, $format)
    {
        $pengunjungs = PendaftaranPengunjung::with('user')->where('status', 'disetujui')->latest()->get();

        if ($format === 'excel' || $format === 'csv') {
            $filename = 'daftar-pengunjung-' . date('Y-m-d') . '.csv';

            $csvData = "\xEF\xBB\xBF";

            $headers_row = ['No', 'Nama Lengkap', 'Nomor HP', 'Instansi', 'Tanggal Kunjungan', 'Jumlah Rombongan', 'Tujuan / Keperluan'];

            $csvData .= implode(';', array_map(function($val) {
                return '"' . str_replace('"', '""', $val) . '"';
            }, $headers_row)) . "\r\n";

            foreach ($pengunjungs as $index => $row) {
                $row_data = [
                    $index + 1,
                    $row->nama_lengkap,
                    $row->nomor_hp,
                    $row->instansi ?? '-',
                    $row->tanggal_kunjungan,
                    $row->jumlah_rombongan,
                    $row->keperluan ?? '-'
                ];
                $csvData .= implode(';', array_map(function($val) {
                    return '"' . str_replace('"', '""', $val) . '"';
                }, $row_data)) . "\r\n";
            }

            return response($csvData, 200, [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
                'Cache-Control' => 'no-cache, no-store, must-revalidate',
                'Pragma' => 'no-cache',
                'Expires' => '0',
            ]);
        }

        return view('admin.pengunjung.print', compact('pengunjungs'));
    }

    public function exportPeneliti(Request $request, $format)
    {
        $penelitis = PendaftaranPeneliti::with('user')->where('status', 'disetujui')->latest()->get();

        if ($format === 'excel' || $format === 'csv') {
            $filename = 'daftar-peneliti-' . date('Y-m-d') . '.csv';

            $csvData = "\xEF\xBB\xBF";

            $headers_row = [
                'No',
                'Nama Lengkap',
                'No NIK',
                'Nomor HP',
                'Institusi',
                'Program Studi',
                'Jenjang',
                'Judul Penelitian',
                'Mulai',
                'Selesai'
            ];

            $csvData .= implode(';', array_map(function($val) {
                return '"' . str_replace('"', '""', $val) . '"';
            }, $headers_row)) . "\r\n";

            foreach ($penelitis as $index => $row) {
                $row_data = [
                    $index + 1,
                    $row->nama_lengkap,
                    $row->no_identitas,
                    $row->nomor_hp,
                    $row->institusi,
                    $row->program_studi ?? '-',
                    $row->jenjang,
                    $row->judul_penelitian,
                    $row->tanggal_mulai->format('Y-m-d'),
                    $row->tanggal_selesai->format('Y-m-d')
                ];
                $csvData .= implode(';', array_map(function($val) {
                    return '"' . str_replace('"', '""', $val) . '"';
                }, $row_data)) . "\r\n";
            }

            return response($csvData, 200, [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
                'Cache-Control' => 'no-cache, no-store, must-revalidate',
                'Pragma' => 'no-cache',
                'Expires' => '0',
            ]);
        }

        return view('admin.peneliti.print', compact('penelitis'));
    }

    public function destroyPengunjung($id)
    {
        $pengunjung = PendaftaranPengunjung::findOrFail($id);
        $pengunjung->delete();
        return back()->with('success', 'Pendaftaran pengunjung berhasil dihapus.');
    }

    public function bulkDestroyPengunjung(Request $request)
    {
        $ids = json_decode($request->ids_json, true);
        if (empty($ids) || !is_array($ids)) {
            return back()->with('error', 'Tidak ada data terpilih untuk dihapus.');
        }

        PendaftaranPengunjung::whereIn('id', $ids)->delete();
        return back()->with('success', 'Pendaftaran pengunjung terpilih berhasil dihapus massal.');
    }

    public function destroyPeneliti($id)
    {
        $peneliti = PendaftaranPeneliti::findOrFail($id);
        if ($peneliti->surat_pengantar) {
            $existingPaths = json_decode($peneliti->surat_pengantar, true);
            if (is_array($existingPaths)) {
                if (!empty($existingPaths['surat_izin'])) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($existingPaths['surat_izin']);
                }
                if (!empty($existingPaths['cv'])) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($existingPaths['cv']);
                }
            } else {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($peneliti->surat_pengantar);
            }
        }
        $peneliti->delete();
        return back()->with('success', 'Pendaftaran peneliti berhasil dihapus.');
    }

    public function bulkDestroyPeneliti(Request $request)
    {
        $ids = json_decode($request->ids_json, true);
        if (empty($ids) || !is_array($ids)) {
            return back()->with('error', 'Tidak ada data terpilih untuk dihapus.');
        }

        $penelitis = PendaftaranPeneliti::whereIn('id', $ids)->get();
        foreach ($penelitis as $peneliti) {
            if ($peneliti->surat_pengantar) {
                $existingPaths = json_decode($peneliti->surat_pengantar, true);
                if (is_array($existingPaths)) {
                    if (!empty($existingPaths['surat_izin'])) {
                        \Illuminate\Support\Facades\Storage::disk('public')->delete($existingPaths['surat_izin']);
                    }
                    if (!empty($existingPaths['cv'])) {
                        \Illuminate\Support\Facades\Storage::disk('public')->delete($existingPaths['cv']);
                    }
                } else {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($peneliti->surat_pengantar);
                }
            }
            $peneliti->delete();
        }

        return back()->with('success', 'Pendaftaran peneliti terpilih berhasil dihapus massal.');
    }

    public function updatePenelitiStatusPenelitian(Request $request, $id)
    {
        $request->validate([
            'status_penelitian' => 'required|in:sedang,selesai',
        ]);

        $peneliti = PendaftaranPeneliti::findOrFail($id);
        $peneliti->update([
            'status_penelitian' => $request->status_penelitian,
        ]);

        return back()->with('success', 'Status penelitian berhasil diperbarui.');
    }
}
