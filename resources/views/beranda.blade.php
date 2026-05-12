@extends('layouts.landing')

@section('title', 'Kebun Raya Sambas')

@section('content')
<div id="home" class="relative h-screen w-full flex items-center justify-center text-center overflow-hidden -mt-32">
    <video autoplay loop muted playsinline class="absolute z-0 w-auto min-w-full min-h-full max-w-none object-cover">
        <source src="https://videos.pexels.com/video-files/3209828/3209828-hd_1920_1080_25fps.mp4" type="video/mp4" />
        Your browser does not support the video tag.
    </video>

    <div class="absolute inset-0 bg-black/30"></div>
    {{-- Gradient Putih di Bawah Hero --}}
    <div class="absolute bottom-0 w-full h-32 bg-linear-to-t from-white to-transparent"></div>

    <div class="relative z-10 max-w-4xl mx-auto px-6">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full border border-zinc-200/50 bg-white/10 text-xs text-white mb-6 backdrop-blur-sm">
            <span class="w-1.5 h-1.5 rounded-full bg-green-400 animate-pulse"></span>
            Digitalisasi Konservasi Alam
        </div>

        <h1 class="text-5xl md:text-7xl font-bold tracking-tight mb-6 text-white">
            Sambas Botanical <br>
            <span class="text-transparent bg-clip-text bg-linear-to-b from-zinc-200 to-white">
                Garden & Research
            </span>
        </h1>

        <p class="text-lg text-zinc-200 max-w-2xl mx-auto mb-10 leading-relaxed font-light">
            Pusat konservasi tumbuhan, penelitian, dan edukasi lingkungan.
            Menjaga keanekaragaman hayati Kalimantan untuk masa depan yang lestari.
        </p>

        <div class="flex flex-col sm:flex-row justify-center gap-4">
            <a href="{{ route('pendaftaran.pengunjung') }}" class="px-8 py-3 rounded-full bg-white text-zinc-900 font-semibold hover:bg-zinc-100 transition shadow-xl shadow-zinc-200/10">
                Pendaftaran Pengunjung
            </a>
            <a href="{{ route('register') }}" class="px-8 py-3 rounded-full bg-white/10 text-white font-semibold border border-white/50 hover:bg-white/20 backdrop-blur-md transition shadow-lg">
                Pendaftaran Penelitian
            </a>
        </div>
    </div>
</div>

<div id="gallery" class="max-w-7xl mx-auto px-6 scroll-mt-32 mt-24 pb-24">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 auto-rows-[300px]">

        <div class="group relative md:col-span-2 row-span-2 overflow-hidden rounded-3xl bg-zinc-100">
            <img src="https://media.istockphoto.com/id/2206356193/id/foto/puncak-pohon-hutan-hujan-tropis-lebat.webp?a=1&b=1&s=612x612&w=0&k=20&c=kRSOoJsbwkD5x8v0FJszIRolmoYXmYOCnBy_K_amptI=" class="h-full w-full object-cover img-zoom" alt="Tropical Forest">
            <div class="absolute inset-0 bg-linear-to-t from-black/80 via-transparent to-transparent opacity-80"></div>
            <div class="absolute bottom-0 left-0 p-8">
                <h3 class="text-2xl font-bold text-white mb-2">Hutan Tropis Borneo</h3>
                <p class="text-zinc-300 text-sm">Rumah bagi ribuan spesies endemik yang dilindungi.</p>
            </div>
        </div>

        <div class="relative overflow-hidden rounded-3xl border border-zinc-200 bg-zinc-50 p-8 flex flex-col justify-center items-center text-center hover:border-zinc-300 transition">
            <div class="text-5xl font-bold text-zinc-900 mb-2">1,200+</div>
            <div class="text-zinc-500 font-medium">Spesies Flora</div>
        </div>

        <div class="group relative overflow-hidden rounded-3xl bg-zinc-100">
            <img src="https://media.istockphoto.com/id/1337428357/id/foto/tanaman-pakis-atau-tanaman-paku-pakuan-atau-daun-pakis.webp?a=1&b=1&s=612x612&w=0&k=20&c=g8Ny-q171nSQgMx1pfL2kq5Yo8eyx2M8hYhyhYLWg_w=" class="h-full w-full object-cover img-zoom" alt="Fern Leaf">
            <div class="absolute inset-0 bg-linear-to-t from-black/70 to-transparent"></div>
            <div class="absolute bottom-0 left-0 p-6">
                <h3 class="text-xl font-bold text-white">Paku-pakuan</h3>
                <p class="text-zinc-300 text-xs">Koleksi Pteridophyta</p>
            </div>
        </div>

        <div class="group relative md:col-span-2 overflow-hidden rounded-3xl bg-zinc-100">
            <img src="https://images.unsplash.com/photo-1744606251391-49bd85c1e840?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTV8fGFuZ2dyZWslMjBodXRhbnxlbnwwfHwwfHx8MA%3D%3D" class="h-full w-full object-cover img-zoom" alt="Orchid">
            <div class="absolute inset-0 bg-linear-to-t from-black/70 to-transparent"></div>
            <div class="absolute bottom-0 left-0 p-8 flex justify-between items-end w-full">
                <div>
                    <h3 class="text-xl font-bold text-white">Anggrek Hutan</h3>
                    <p class="text-zinc-300 text-sm">Orchidaceae Eksotis</p>
                </div>
                <div class="h-10 w-10 rounded-full bg-white/20 backdrop-blur-md flex items-center justify-center border border-white/30">
                     <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection