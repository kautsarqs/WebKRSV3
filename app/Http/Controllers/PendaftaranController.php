<?php

namespace App\Http\Controllers;

use App\Models\PendaftaranPengunjung;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PendaftaranController extends Controller
{
    // Menampilkan Form Pengunjung
    public function createPengunjung()
    {
        return view('landing.pengunjung');
    }

    // Menyimpan Data Pengunjung
    public function storePengunjung(Request $request)
    {
        // Tambahan: Pastikan hanya user yang sudah login yang bisa submit
        if (Auth::guest()) {
            return redirect()->route('login')->with('error', 'Anda harus login untuk melakukan pendaftaran.');
        }

        // Aturan validasi dengan pesan kustom dalam bahasa Indonesia
        $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:255', 'regex:/^[\pL\s]+$/u'],
            'no_identitas' => 'required|numeric|digits:16',
            'nomor_hp' => 'required|numeric|digits_between:10,13',
            'tanggal_kunjungan' => 'required|date|after_or_equal:today',
            'jumlah_rombongan' => 'required|integer|min:1',
            'keperluan' => 'nullable|string',
        ], [
            'nama_lengkap.regex' => 'Nama lengkap hanya boleh berisi huruf dan spasi.',
            'no_identitas.numeric' => 'No. identitas harus berupa angka.',
            'no_identitas.digits' => 'No. identitas harus terdiri dari 16 digit.',
            'nomor_hp.numeric' => 'Nomor WhatsApp harus berupa angka.',
            'nomor_hp.digits_between' => 'Nomor WhatsApp harus terdiri dari 10 hingga 13 digit.',
            'tanggal_kunjungan.after_or_equal' => 'Tanggal kunjungan tidak boleh tanggal yang sudah lewat.',
        ]);

        // Simpan ke database
        PendaftaranPengunjung::create([
            'user_id' => Auth::id(), // Auth::id() dijamin ada karena cek di atas
            'nama_lengkap' => $request->nama_lengkap,
            'no_identitas' => $request->no_identitas,
            'nomor_hp' => $request->nomor_hp,
            'tanggal_kunjungan' => $request->tanggal_kunjungan,
            'jumlah_rombongan' => $request->jumlah_rombongan,
            'keperluan' => $request->keperluan,
            'status' => 'pending'
        ]);

        // Redirect kembali dengan pesan sukses
        // Nanti bisa diarahkan ke halaman ringkasan/dashboard jika sudah ada
        return redirect()->route('dashboard')->with('success', 'Pendaftaran pengunjung berhasil dikirim! Menunggu konfirmasi admin.');
    }
}