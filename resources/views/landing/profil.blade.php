@extends('layouts.landing')

@section('title', 'Profil - Kebun Raya Sambas')

@section('content')
<div class="max-w-4xl mx-auto px-6 text-center mb-16">
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

<div class="bg-zinc-50 py-20 border-y border-zinc-200 -mx-6 px-6">
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
@endsection