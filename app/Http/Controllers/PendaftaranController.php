<?php

namespace App\Http\Controllers;

use App\Mail\PendaftaranPenelitiMail;
use App\Models\PendaftaranPeneliti;
use App\Models\PendaftaranPengunjung;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class PendaftaranController extends Controller
{

    public function createPengunjung()
    {
        if (Auth::check() && !Auth::user()->hasVerifiedEmail()) {
            return redirect()->route('verification.notice');
        }
        return view('landing.pengunjung');
    }

    public function storePengunjung(Request $request)
    {
        if (Auth::guest()) {
            return redirect()->route('login')->with('error', 'Anda harus login untuk melakukan pendaftaran.');
        }
        if (!Auth::user()->hasVerifiedEmail()) {
            return redirect()->route('verification.notice');
        }

        $cleanRombongan = [];
        if ($request->has('rombongan')) {
            foreach ($request->rombongan as $idx => $member) {
                if (isset($member['nomor_hp'])) {
                    $member['nomor_hp'] = str_replace([' ', '-', '(', ')', '/'], '', $member['nomor_hp']);
                }
                $cleanRombongan[$idx] = $member;
            }
        }

        $request->merge([
            'nomor_hp' => str_replace([' ', '-', '(', ')', '/'], '', $request->nomor_hp),
            'rombongan' => $cleanRombongan,
        ]);

        $request->validate([
            'nama_lengkap'      => ['required', 'string', 'max:255', 'regex:/^[\pL\s]+$/u'],
            'nomor_hp'          => 'required|regex:/^\+?[0-9]{10,15}$/',
            'tanggal_kunjungan' => 'required|date|after_or_equal:today',
            'instansi'          => 'required|string',
            'keperluan'         => 'required|string|max:500',

            'rombongan.*.nama'     => 'nullable|string|max:255',
            'rombongan.*.nomor_hp' => 'nullable|regex:/^\+?[0-9]{10,15}$/',
            'rombongan.*.instansi' => 'nullable|string|max:255',
        ], [
            'nama_lengkap.regex'               => 'Nama lengkap hanya boleh berisi huruf dan spasi.',
            'nomor_hp.regex'                   => 'Nomor HP harus berupa angka kode negara yang valid (e.g. +62812345...).',
            'tanggal_kunjungan.after_or_equal' => 'Tanggal kunjungan tidak boleh tanggal yang sudah lewat.',
        ]);

        $rombonganDetails = [];
        if ($request->has('rombongan')) {
            foreach ($request->rombongan as $item) {
                if (!empty($item['nama'])) {
                    $rombonganDetails[] = [
                        'nama'     => $item['nama'],
                        'nomor_hp' => $item['nomor_hp'] ?? '',
                        'instansi' => $item['instansi'] ?? $request->instansi,
                    ];
                }
            }
        }

        $jumlahRombongan = 1 + count($rombonganDetails);

        PendaftaranPengunjung::create([
            'user_id'           => Auth::id(),
            'nama_lengkap'      => $request->nama_lengkap,
            'no_identitas'      => '0000000000000000',
            'nomor_hp'          => $request->nomor_hp,
            'tanggal_kunjungan' => $request->tanggal_kunjungan,
            'jumlah_rombongan'  => $jumlahRombongan,
            'keperluan'         => $request->keperluan,
            'instansi'          => $request->instansi,
            'rombongan_details' => $rombonganDetails,
            'status'            => 'pending',
        ]);

        return redirect()->route('dashboard')->with('success', 'Pendaftaran pengunjung berhasil dikirim! Menunggu konfirmasi admin.');
    }

    public function editPengunjung($id)
    {
        $pengunjung = PendaftaranPengunjung::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($pengunjung->status === 'disetujui') {
            return redirect()->route('dashboard')->with('error', 'Pendaftaran yang sudah disetujui tidak dapat diubah.');
        }

        return view('dashboard.pengunjung.edit', compact('pengunjung'));
    }

    public function updatePengunjung(Request $request, $id)
    {
        $pengunjung = PendaftaranPengunjung::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($pengunjung->status === 'disetujui') {
            return redirect()->route('dashboard')->with('error', 'Pendaftaran yang sudah disetujui tidak dapat diubah.');
        }

        $cleanRombongan = [];
        if ($request->has('rombongan')) {
            foreach ($request->rombongan as $idx => $member) {
                if (isset($member['nomor_hp'])) {
                    $member['nomor_hp'] = str_replace([' ', '-', '(', ')', '/'], '', $member['nomor_hp']);
                }
                $cleanRombongan[$idx] = $member;
            }
        }

        $request->merge([
            'nomor_hp' => str_replace([' ', '-', '(', ')', '/'], '', $request->nomor_hp),
            'rombongan' => $cleanRombongan,
        ]);

        $request->validate([
            'nama_lengkap'      => ['required', 'string', 'max:255', 'regex:/^[\pL\s]+$/u'],
            'nomor_hp'          => 'required|regex:/^\+?[0-9]{10,15}$/',
            'tanggal_kunjungan' => 'required|date|after_or_equal:today',
            'instansi'          => 'required|string',
            'keperluan'         => 'required|string|max:500',

            'rombongan.*.nama'     => 'nullable|string|max:255',
            'rombongan.*.nomor_hp' => 'nullable|regex:/^\+?[0-9]{10,15}$/',
            'rombongan.*.instansi' => 'nullable|string|max:255',
        ], [
            'nama_lengkap.regex'               => 'Nama lengkap hanya boleh berisi huruf dan spasi.',
            'nomor_hp.regex'                   => 'Nomor HP harus berupa angka kode negara yang valid.',
            'tanggal_kunjungan.after_or_equal' => 'Tanggal kunjungan tidak boleh tanggal yang sudah lewat.',
        ]);

        $rombonganDetails = [];
        if ($request->has('rombongan')) {
            foreach ($request->rombongan as $item) {
                if (!empty($item['nama'])) {
                    $rombonganDetails[] = [
                        'nama'     => $item['nama'],
                        'nomor_hp' => $item['nomor_hp'] ?? '',
                        'instansi' => $item['instansi'] ?? $request->instansi,
                    ];
                }
            }
        }

        $jumlahRombongan = 1 + count($rombonganDetails);

        if ($pengunjung->status === 'ditolak') {
            PendaftaranPengunjung::create([
                'user_id'           => Auth::id(),
                'nama_lengkap'      => $request->nama_lengkap,
                'no_identitas'      => $pengunjung->no_identitas ?? '0000000000000000',
                'nomor_hp'          => $request->nomor_hp,
                'tanggal_kunjungan' => $request->tanggal_kunjungan,
                'jumlah_rombongan'  => $jumlahRombongan,
                'keperluan'         => $request->keperluan,
                'instansi'          => $request->instansi,
                'rombongan_details' => $rombonganDetails,
                'status'            => 'pending',
                'parent_id'         => $pengunjung->id,
            ]);

            return redirect()->route('dashboard')->with('success', 'Pendaftaran pengunjung baru berhasil dikirim dari perbaikan pendaftaran sebelumnya.');
        } else {
            $pengunjung->update([
                'nama_lengkap'      => $request->nama_lengkap,
                'nomor_hp'          => $request->nomor_hp,
                'tanggal_kunjungan' => $request->tanggal_kunjungan,
                'jumlah_rombongan'  => $jumlahRombongan,
                'keperluan'         => $request->keperluan,
                'instansi'          => $request->instansi,
                'rombongan_details' => $rombonganDetails,
                'status'            => 'pending',
            ]);

            return redirect()->route('dashboard')->with('success', 'Pendaftaran pengunjung berhasil diperbarui.');
        }
    }

    public function destroyPengunjungUser($id)
    {
        $pengunjung = PendaftaranPengunjung::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($pengunjung->status === 'disetujui') {
            return redirect()->route('dashboard')->with('error', 'Pendaftaran yang sudah disetujui tidak dapat dibatalkan.');
        }

        $pengunjung->delete();
        return redirect()->route('dashboard')->with('success', 'Pendaftaran pengunjung berhasil dibatalkan.');
    }

    public function createPeneliti()
    {
        if (Auth::check() && !Auth::user()->hasVerifiedEmail()) {
            return redirect()->route('verification.notice');
        }
        return view('landing.peneliti');
    }

    public function storePeneliti(Request $request)
    {
        if (Auth::guest()) {
            return redirect()->route('login')->with('error', 'Anda harus login untuk melakukan pendaftaran.');
        }
        if (!Auth::user()->hasVerifiedEmail()) {
            return redirect()->route('verification.notice');
        }

        $request->merge([
            'nomor_hp' => str_replace([' ', '-', '(', ')', '/'], '', $request->nomor_hp),
        ]);

        $request->validate([
            'nama_lengkap'         => ['required', 'string', 'max:255', 'regex:/^[\pL\s]+$/u'],
            'nomor_hp'             => 'required|regex:/^\+?[0-9]{10,15}$/',
            'institusi'            => 'required|string|max:255',
            'program_studi'        => 'nullable|string|max:255',
            'jenjang'              => 'required|in:S1,S2,S3,Dosen,Peneliti,Umum',
            'judul_penelitian'     => 'required|string|max:500',
            'bidang_penelitian'    => 'required|string|max:500',
            'tanggal_mulai'        => 'required|date|after_or_equal:today',
            'tanggal_selesai'      => 'required|date|after_or_equal:tanggal_mulai',
            'tujuan_penelitian'    => 'required|string',
            'surat_izin_meneliti'  => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'cv'                   => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ], [
            'nama_lengkap.regex'      => 'Nama lengkap hanya boleh berisi huruf dan spasi.',
            'nomor_hp.regex'          => 'Nomor HP harus berupa angka kode negara yang valid.',
            'tanggal_mulai.after_or_equal' => 'Tanggal mulai tidak boleh tanggal yang sudah lewat.',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai tidak boleh kurang dari tanggal mulai.',
            'surat_izin_meneliti.required' => 'Dokumen Surat Izin Meneliti wajib diunggah.',
            'cv.required'             => 'Dokumen Curriculum Vitae (CV) wajib diunggah.',
        ]);

        $izinPath = $request->file('surat_izin_meneliti')->store('surat_pengantar', 'public');
        $cvPath = $request->file('cv')->store('surat_pengantar', 'public');

        $suratPengantarPaths = json_encode([
            'surat_izin' => $izinPath,
            'cv' => $cvPath
        ]);

        $pendaftaran = PendaftaranPeneliti::create([
            'user_id'           => Auth::id(),
            'nama_lengkap'      => $request->nama_lengkap,
            'no_identitas'      => '0000000000000000',
            'nomor_hp'          => $request->nomor_hp,
            'institusi'         => $request->institusi,
            'program_studi'     => $request->program_studi,
            'jenjang'           => $request->jenjang,
            'judul_penelitian'  => $request->judul_penelitian,
            'bidang_penelitian' => $request->bidang_penelitian,
            'tanggal_mulai'     => $request->tanggal_mulai,
            'tanggal_selesai'   => $request->tanggal_selesai,
            'jumlah_anggota'    => 1,
            'tujuan_penelitian' => $request->tujuan_penelitian,
            'surat_pengantar'   => $suratPengantarPaths,
            'status'            => 'pending',
        ]);

        $adminEmail = env('ADMIN_EMAIL', config('mail.from.address'));
        try {
            Mail::to($adminEmail)->send(new PendaftaranPenelitiMail($pendaftaran));
        } catch (\Exception $e) {
            Log::error('Gagal mengirim email notifikasi peneliti: ' . $e->getMessage());
        }

        return redirect()->route('dashboard')
            ->with('success', 'Permohonan penelitian berhasil dikirim! Notifikasi telah dikirimkan ke admin. Menunggu konfirmasi.');
    }

    public function editPeneliti($id)
    {
        $peneliti = PendaftaranPeneliti::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($peneliti->status === 'disetujui') {
            return redirect()->route('dashboard')->with('error', 'Pendaftaran yang sudah disetujui tidak dapat diubah.');
        }

        return view('dashboard.peneliti.edit', compact('peneliti'));
    }

    public function updatePeneliti(Request $request, $id)
    {
        $peneliti = PendaftaranPeneliti::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($peneliti->status === 'disetujui') {
            return redirect()->route('dashboard')->with('error', 'Pendaftaran yang sudah disetujui tidak dapat diubah.');
        }

        $request->merge([
            'nomor_hp' => str_replace([' ', '-', '(', ')', '/'], '', $request->nomor_hp),
        ]);

        $request->validate([
            'nama_lengkap'         => ['required', 'string', 'max:255', 'regex:/^[\pL\s]+$/u'],
            'nomor_hp'             => 'required|regex:/^\+?[0-9]{10,15}$/',
            'institusi'            => 'required|string|max:255',
            'program_studi'        => 'nullable|string|max:255',
            'jenjang'              => 'required|in:S1,S2,S3,Dosen,Peneliti,Umum',
            'judul_penelitian'     => 'required|string|max:500',
            'bidang_penelitian'    => 'required|string|max:500',
            'tanggal_mulai'        => 'required|date|after_or_equal:today',
            'tanggal_selesai'      => 'required|date|after_or_equal:tanggal_mulai',
            'tujuan_penelitian'    => 'required|string',
            'surat_izin_meneliti'  => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'cv'                   => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ], [
            'nama_lengkap.regex'      => 'Nama lengkap hanya boleh berisi huruf dan spasi.',
            'nomor_hp.regex'          => 'Nomor HP harus berupa angka kode negara yang valid.',
            'tanggal_mulai.after_or_equal' => 'Tanggal mulai tidak boleh tanggal yang sudah lewat.',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai tidak boleh kurang dari tanggal mulai.',
        ]);

        $existingPaths = json_decode($peneliti->surat_pengantar, true) ?? [];

        $izinPath = $existingPaths['surat_izin'] ?? null;
        if ($request->hasFile('surat_izin_meneliti')) {
            if ($izinPath && $peneliti->status !== 'ditolak') {
                Storage::disk('public')->delete($izinPath);
            }
            $izinPath = $request->file('surat_izin_meneliti')->store('surat_pengantar', 'public');
        }

        $cvPath = $existingPaths['cv'] ?? null;
        if ($request->hasFile('cv')) {
            if ($cvPath && $peneliti->status !== 'ditolak') {
                Storage::disk('public')->delete($cvPath);
            }
            $cvPath = $request->file('cv')->store('surat_pengantar', 'public');
        }

        $suratPengantarPaths = json_encode([
            'surat_izin' => $izinPath,
            'cv' => $cvPath
        ]);

        if ($peneliti->status === 'ditolak') {
            $newPeneliti = PendaftaranPeneliti::create([
                'user_id'           => Auth::id(),
                'nama_lengkap'      => $request->nama_lengkap,
                'no_identitas'      => $peneliti->no_identitas ?? '0000000000000000',
                'nomor_hp'          => $request->nomor_hp,
                'institusi'         => $request->institusi,
                'program_studi'     => $request->program_studi,
                'jenjang'           => $request->jenjang,
                'judul_penelitian'  => $request->judul_penelitian,
                'bidang_penelitian' => $request->bidang_penelitian,
                'tanggal_mulai'     => $request->tanggal_mulai,
                'tanggal_selesai'   => $request->tanggal_selesai,
                'jumlah_anggota'    => $peneliti->jumlah_anggota ?? 1,
                'tujuan_penelitian' => $request->tujuan_penelitian,
                'surat_pengantar'   => $suratPengantarPaths,
                'status'            => 'pending',
                'parent_id'         => $peneliti->id,
            ]);

            $adminEmail = env('ADMIN_EMAIL', config('mail.from.address'));
            try {
                Mail::to($adminEmail)->send(new \App\Mail\PendaftaranPenelitiMail($newPeneliti));
            } catch (\Exception $e) {
                Log::error('Gagal mengirim email notifikasi peneliti: ' . $e->getMessage());
            }

            return redirect()->route('dashboard')->with('success', 'Permohonan penelitian baru berhasil dikirim dari perbaikan permohonan sebelumnya.');
        } else {
            $peneliti->update([
                'nama_lengkap'      => $request->nama_lengkap,
                'nomor_hp'          => $request->nomor_hp,
                'institusi'         => $request->institusi,
                'program_studi'     => $request->program_studi,
                'jenjang'           => $request->jenjang,
                'judul_penelitian'  => $request->judul_penelitian,
                'bidang_penelitian' => $request->bidang_penelitian,
                'tanggal_mulai'     => $request->tanggal_mulai,
                'tanggal_selesai'   => $request->tanggal_selesai,
                'tujuan_penelitian' => $request->tujuan_penelitian,
                'surat_pengantar'   => $suratPengantarPaths,
                'status'            => 'pending',
            ]);

            return redirect()->route('dashboard')->with('success', 'Pendaftaran peneliti berhasil diperbarui.');
        }
    }

    public function destroyPenelitiUser($id)
    {
        $peneliti = PendaftaranPeneliti::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($peneliti->status === 'disetujui') {
            return redirect()->route('dashboard')->with('error', 'Pendaftaran yang sudah disetujui tidak dapat dibatalkan.');
        }

        $existingPaths = json_decode($peneliti->surat_pengantar, true);
        if (is_array($existingPaths)) {
            if (!empty($existingPaths['surat_izin'])) {
                Storage::disk('public')->delete($existingPaths['surat_izin']);
            }
            if (!empty($existingPaths['cv'])) {
                Storage::disk('public')->delete($existingPaths['cv']);
            }
        }

        $peneliti->delete();
        return redirect()->route('dashboard')->with('success', 'Pendaftaran peneliti berhasil dibatalkan.');
    }
}