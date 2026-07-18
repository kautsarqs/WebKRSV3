@extends('layouts.landing')

@section('title', 'Kebijakan Privasi - Kebun Raya Sambas')

@section('content')
<div class="max-w-4xl mx-auto px-6 py-12">
    <div class="mb-10 text-center">
        <span class="text-zinc-650 font-medium tracking-widest text-sm uppercase mb-2 block">Informasi Legal</span>
        <h1 class="font-heading text-4xl font-bold text-zinc-900 mb-4">Kebijakan Privasi</h1>
        <p class="text-zinc-650">Terakhir diperbarui: {{ date('d F Y') }}</p>
    </div>

    <div class="prose prose-zinc max-w-none text-zinc-800 bg-white p-8 md:p-12 rounded-3xl shadow-sm border border-zinc-200">
        <h2 class="text-2xl font-bold text-zinc-900 mb-4">1. Pendahuluan</h2>
        <p class="mb-6 leading-relaxed">
            Selamat datang di Kebun Raya Sambas. Kami menghargai privasi Anda dan berkomitmen untuk melindungi data pribadi yang Anda bagikan kepada kami. Kebijakan Privasi ini menjelaskan bagaimana kami mengumpulkan, menggunakan, dan melindungi informasi Anda saat menggunakan situs web kami (termasuk fitur pendaftaran pengunjung dan peneliti).
        </p>

        <h2 class="text-2xl font-bold text-zinc-900 mb-4">2. Informasi yang Kami Kumpulkan</h2>
        <p class="mb-4 leading-relaxed">Kami dapat mengumpulkan jenis informasi berikut saat Anda menggunakan layanan kami:</p>
        <ul class="list-disc pl-6 mb-6 space-y-2">
            <li><strong>Informasi Pribadi:</strong> Nama, alamat email, nomor telepon, dan institusi (untuk peneliti) saat Anda mendaftar atau membuat akun.</li>
            <li><strong>Informasi Kunjungan:</strong> Tanggal kunjungan, jumlah rombongan, dan keperluan kunjungan.</li>
            <li><strong>Data Teknis:</strong> Alamat IP, jenis peramban (browser), dan data penggunaan situs secara anonim.</li>
        </ul>

        <h2 class="text-2xl font-bold text-zinc-900 mb-4">3. Penggunaan Informasi</h2>
        <p class="mb-4 leading-relaxed">Data yang kami kumpulkan akan digunakan untuk:</p>
        <ul class="list-disc pl-6 mb-6 space-y-2">
            <li>Memproses pendaftaran tiket kunjungan dan izin penelitian.</li>
            <li>Mengirimkan konfirmasi dan notifikasi terkait layanan.</li>
            <li>Meningkatkan pengalaman pengguna pada platform digital kami.</li>
            <li>Mematuhi kewajiban hukum yang berlaku.</li>
        </ul>

        <h2 class="text-2xl font-bold text-zinc-900 mb-4">4. Keamanan Data</h2>
        <p class="mb-6 leading-relaxed">
            Kami mengimplementasikan langkah-langkah keamanan teknis dan organisasi yang ketat untuk melindungi informasi pribadi Anda dari akses yang tidak sah, kehilangan, atau penyalahgunaan. Kata sandi akun (jika ada) dienkripsi dengan standar industri (BCrypt).
        </p>

        <h2 class="text-2xl font-bold text-zinc-900 mb-4">5. Pembagian Informasi</h2>
        <p class="mb-6 leading-relaxed">
            Kami tidak akan menjual, menyewakan, atau menukar informasi pribadi Anda kepada pihak ketiga. Data Anda hanya akan dibagikan kepada pihak berwenang jika diwajibkan oleh hukum atau untuk keperluan perlindungan keamanan kawasan Kebun Raya Sambas.
        </p>

        <h2 class="text-2xl font-bold text-zinc-900 mb-4">6. Hubungi Kami</h2>
        <p class="mb-6 leading-relaxed">
            Jika Anda memiliki pertanyaan mengenai Kebijakan Privasi ini atau pengelolaan data pribadi Anda, silakan hubungi pengelola melalui kontak yang tersedia di halaman utama situs ini.
        </p>
    </div>
</div>
@endsection
