@extends('layouts.landing')

@section('title', 'Penelitian - Kebun Raya Sambas')

@section('content')
<div class="max-w-7xl mx-auto px-6">
    <div class="flex flex-col md:flex-row items-end justify-between mb-12 gap-6">
        <div>
            <h1 class="font-heading text-4xl font-bold mb-2">Pusat Riset & Edukasi</h1>
            <p class="text-zinc-500 max-w-xl">Mendukung pengembangan ilmu pengetahuan melalui fasilitas penelitian yang memadai.</p>
        </div>
        <a href="{{ route('kontak') }}" class="px-6 py-3 bg-zinc-900 text-white rounded-xl font-medium hover:bg-zinc-800 transition">Ajukan Kerjasama</a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <div class="p-8 rounded-3xl border border-zinc-200 bg-zinc-50 hover:bg-white hover:shadow-lg transition duration-300">
            <div class="w-12 h-12 rounded-xl bg-green-100 text-green-600 flex items-center justify-center mb-6">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
            </div>
            <h3 class="font-heading text-xl font-bold mb-3">Laboratorium Botani</h3>
            <p class="text-zinc-500 text-sm leading-relaxed">Fasilitas lengkap untuk analisis morfologi dan anatomi tumbuhan, kultur jaringan, dan identifikasi spesies.</p>
        </div>

        <div class="p-8 rounded-3xl border border-zinc-200 bg-zinc-50 hover:bg-white hover:shadow-lg transition duration-300">
            <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center mb-6">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
            </div>
            <h3 class="font-heading text-xl font-bold mb-3">Perpustakaan & Herbarium</h3>
            <p class="text-zinc-500 text-sm leading-relaxed">Pusat data referensi flora Kalimantan dan koleksi spesimen kering yang terawat dengan standar internasional.</p>
        </div>

        <div class="p-8 rounded-3xl border border-zinc-200 bg-zinc-50 hover:bg-white hover:shadow-lg transition duration-300">
            <div class="w-12 h-12 rounded-xl bg-orange-100 text-orange-600 flex items-center justify-center mb-6">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
            <h3 class="font-heading text-xl font-bold mb-3">Edukasi & Magang</h3>
            <p class="text-zinc-500 text-sm leading-relaxed">Program kunjungan sekolah, pelatihan berkebun, dan kesempatan magang bagi mahasiswa biologi/kehutanan.</p>
        </div>
    </div>
</div>
@endsection