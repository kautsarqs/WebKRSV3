@extends('layouts.landing')

@section('title', 'Tentang Kami - Kebun Raya Sambas')

@section('content')
<div class="max-w-4xl mx-auto px-6 text-center mb-8">
    <span class="text-zinc-500 font-medium tracking-widest text-sm uppercase mb-2 block">Tentang Kami</span>
    <h1 class="font-heading text-4xl md:text-5xl font-bold text-zinc-900 mb-6">Mengenal Kebun Raya Sambas</h1>
    <p class="text-lg text-zinc-500 leading-relaxed">
        Kawasan konservasi tumbuhan ex-situ yang memiliki peran strategis dalam pelestarian flora Kalimantan, pendidikan lingkungan, dan pariwisata alam.
    </p>
</div>

<div class="max-w-7xl mx-auto px-6 grid grid-cols-1 md:grid-cols-2 gap-12 items-center mb-20">
    <div class="rounded-3xl overflow-hidden shadow-2xl shadow-zinc-200">
        <img src="{{ asset('storage/images/KRSprofil.avif') }}" alt="Kebun Raya" class="w-full h-[400px] object-cover hover:scale-105 transition duration-700">
    </div>
    <div class="space-y-6">
        <h2 class="font-heading text-3xl font-bold">Sejarah Singkat</h2>
        <p class="text-zinc-600 leading-relaxed">
            Kebun Raya Sambas didirikan sebagai respon atas mendesaknya kebutuhan pelestarian keanekaragaman hayati di wilayah Kalimantan Barat. Dengan luas area yang memadai, kami berkomitmen menjadi benteng terakhir bagi spesies tanaman endemik yang terancam punah.
        </p>
        <div class="grid grid-cols-2 gap-6 mt-4">
            <div class="p-4 bg-zinc-50 rounded-2xl border border-zinc-100">
                <div class="text-3xl font-bold text-zinc-900 mb-1 font-heading">300 Ha</div>
                <div class="text-sm text-zinc-500">Luas Area</div>
            </div>
            <div class="p-4 bg-zinc-50 rounded-2xl border border-zinc-100">
                <div class="text-3xl font-bold text-zinc-900 mb-1 font-heading">2002</div>
                <div class="text-sm text-zinc-500">Tahun Berdiri</div>
            </div>
        </div>
    </div>
</div>

<div class="bg-zinc-50 py-12 border-y border-zinc-200 -mx-6 px-6">
    <div class="max-w-7xl mx-auto">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
            <div>
                <h3 class="font-heading text-2xl font-bold mb-4">Visi</h3>
                <p class="text-zinc-600">"Menjadi Kebun Raya bertaraf internasional yang unggul dalam konservasi tumbuhan tropis Kalimantan, penelitian, pendidikan, dan wisata lingkungan."</p>
            </div>
            <div>
                <h3 class="font-heading text-2xl font-bold mb-4">Misi</h3>
                <ul class="space-y-3 text-zinc-600 list-disc pl-5">
                    <li>Melestarikan keanekaragaman tumbuhan secara ex-situ.</li>
                    <li>Mengembangkan penelitian botani dan hortikultura.</li>
                    <li>Menyediakan sarana edukasi lingkungan yang interaktif.</li>
                    <li>Mengembangkan potensi ekowisata daerah.</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div id="penelitian" class="max-w-7xl mx-auto px-6 pt-16 pb-10 scroll-mt-28">
    <div class="text-center mb-10">
        <h2 class="font-heading text-3xl font-bold text-zinc-900 mb-4">Fasilitas Penelitian</h2>
        <p class="text-zinc-500 max-w-xl mx-auto leading-relaxed font-light">
            Mendukung pengembangan ilmu pengetahuan melalui fasilitas penelitian yang memadai untuk para peneliti nasional maupun internasional.
        </p>
    </div>


    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="p-8 rounded-3xl border border-zinc-200 bg-zinc-50/50 hover:bg-white hover:shadow-lg transition duration-300">
            <div class="w-12 h-12 rounded-2xl bg-emerald-100 text-emerald-600 flex items-center justify-center mb-6">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
            </div>
            <h3 class="font-heading text-xl font-bold mb-3">Laboratorium Botani</h3>
            <p class="text-zinc-500 text-sm leading-relaxed font-light">Fasilitas lengkap untuk analisis morfologi dan anatomi tumbuhan, kultur jaringan, dan identifikasi spesies.</p>
        </div>

        <div class="p-8 rounded-3xl border border-zinc-200 bg-zinc-50/50 hover:bg-white hover:shadow-lg transition duration-300">
            <div class="w-12 h-12 rounded-2xl bg-blue-100 text-blue-600 flex items-center justify-center mb-6">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
            </div>
            <h3 class="font-heading text-xl font-bold mb-3">Perpustakaan & Herbarium</h3>
            <p class="text-zinc-500 text-sm leading-relaxed font-light">Pusat data referensi flora Kalimantan dan koleksi spesimen kering yang terawat dengan standar internasional.</p>
        </div>

        <div class="p-8 rounded-3xl border border-zinc-200 bg-zinc-50/50 hover:bg-white hover:shadow-lg transition duration-300">
            <div class="w-12 h-12 rounded-2xl bg-orange-100 text-orange-600 flex items-center justify-center mb-6">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
            <h3 class="font-heading text-xl font-bold mb-3">Edukasi & Magang</h3>
            <p class="text-zinc-500 text-sm leading-relaxed font-light">Program kunjungan sekolah, pelatihan berkebun, dan kesempatan magang bagi mahasiswa biologi/kehutanan.</p>
        </div>
    </div>

</div>
@endsection
