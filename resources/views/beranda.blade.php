@extends('layouts.landing')

@section('title', 'Kebun Raya Sambas - Pusat Riset & Konservasi')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<style>
    #home-map { height: 600px; width: 100%; z-index: 10; border-radius: 1.75rem; }
    
    /* Fix Leaflet SVG rendering glitch with Tailwind CSS base styles */
    .leaflet-container svg {
        max-width: none !important;
        max-height: none !important;
    }
    
    .map-controls {
        position: absolute;
        top: 1rem;
        right: 1rem;
        z-index: 1000;
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }
    .map-left-controls {
        position: absolute;
        top: 1rem;
        left: 1rem;
        z-index: 1000;
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
        max-width: 220px;
    }
    .map-control-btn {
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid rgba(228, 228, 231, 0.6);
        border-radius: 1.25rem;
        padding: 0.75rem 1.25rem;
        font-size: 0.875rem;
        font-weight: 600;
        color: #3f3f46;
        cursor: pointer;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        display: flex;
        align-items: center;
        gap: 0.625rem;
        min-width: 140px;
    }
    .map-control-btn:hover {
        background: rgba(255, 255, 255, 0.95);
        border-color: rgba(212, 212, 216, 0.8);
        transform: translateY(-2px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }
    .map-control-btn.active {
        background: #10b981;
        color: white;
        border-color: #059669;
        box-shadow: 0 10px 15px -3px rgba(16, 185, 129, 0.3);
    }
    .map-wrapper { position: relative; }
    
    @media (max-width: 640px) {
        .map-left-controls {
            top: 0.5rem !important;
            left: 0.5rem !important;
            max-width: 140px !important;
        }
        .map-controls {
            top: 0.5rem !important;
            right: 0.5rem !important;
        }
        .map-control-btn, .map-left-controls button {
            padding: 0.5rem 0.75rem !important;
            font-size: 9px !important;
            border-radius: 0.75rem !important;
            min-width: auto !important;
        }
    }
    @media (max-width: 480px) {
        .map-control-btn span span, 
        .map-control-btn svg:last-child,
        .map-left-controls button span {
            display: none !important;
        }
        .map-left-controls {
            max-width: 45px !important;
        }
        .map-controls {
            max-width: 45px !important;
        }
    }
    
    /* Premium Leaflet Popup styles */
    .leaflet-popup-content-wrapper { 
        padding: 0; 
        overflow: hidden; 
        border-radius: 1.75rem; 
        border: 1px solid rgba(228, 228, 231, 0.5); 
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15); 
        background: white; 
    }
    .leaflet-popup-content { margin: 0 !important; width: 320px !important; line-height: 1.5; }
    @media (max-width: 640px) {
        .leaflet-popup-content { width: 250px !important; }
    }
    .leaflet-popup-tip { background: white; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1); }
    .leaflet-container a.leaflet-popup-close-button { 
        top: 14px; 
        right: 14px; 
        width: 32px; 
        height: 32px; 
        border-radius: 50%; 
        background: rgba(255, 255, 255, 0.95); 
        color: #27272a; 
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.08); 
        display: flex; 
        align-items: center; 
        justify-content: center; 
        font-size: 16px; 
        text-decoration: none; 
        transition: all 0.25s ease; 
        font-weight: bold; 
        padding-bottom: 2px;
        border: 1px solid rgba(228, 228, 231, 0.4);
    }
    .leaflet-container a.leaflet-popup-close-button:hover { 
        background: #ffffff; 
        color: #ef4444; 
        transform: scale(1.1) rotate(90deg); 
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    }

    /* Custom GPS User location marker styles */
    .user-location-marker {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .blue-dot {
        width: 14px;
        height: 14px;
        background-color: #3b82f6;
        border-radius: 50%;
        border: 2.5px solid white;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
        z-index: 2;
    }
    .pulse-ring {
        position: absolute;
        width: 28px;
        height: 28px;
        border: 3px solid #3b82f6;
        border-radius: 50%;
        background-color: rgba(59, 130, 246, 0.15);
        animation: pulse-animation 1.6s infinite ease-out;
        z-index: 1;
    }
    @keyframes pulse-animation {
        0% {
            transform: scale(0.5);
            opacity: 1;
        }
        100% {
            transform: scale(2.0);
            opacity: 0;
        }
    }
</style>
@endpush

@section('content')
{{-- 1. Hero Section --}}
<div id="home" class="relative h-screen w-full flex items-center justify-center text-center overflow-hidden">
    <video autoplay loop muted playsinline class="absolute z-0 w-auto min-w-full min-h-full max-w-none object-cover">
        <source src="{{ asset('videos/hero.mp4') }}" type="video/mp4" />
        Your browser does not support the video tag.
    </video>

    <div class="absolute inset-0 bg-black/70 z-1"></div>
    {{-- Gradient Hitam di Bawah Hero --}}
    <div class="absolute bottom-0 left-0 w-full h-16 bg-linear-to-t from-black/60 via-black/20 to-transparent z-2"></div>

    <div class="relative z-10 max-w-4xl mx-auto px-6 mt-8">

        <!-- Botanical leaf accent -->
        <div class="flex items-center justify-center gap-3 mb-5">
            <span class="block h-px w-10 bg-white/30 rounded-full"></span>
            <svg class="w-5 h-5 text-emerald-400 opacity-80" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                <path d="M17 8C8 10 5.9 16.17 3.82 19.34L5.71 21C8.15 17.4 10.55 14.32 17 8Z"/>
                <path d="M21 3C21 3 8 5 7 18L9 20C9.2 15.97 11.5 9.35 21 3Z" opacity="0.6"/>
            </svg>
            <span class="block h-px w-10 bg-white/30 rounded-full"></span>
        </div>

        <h1 class="font-heading leading-none mb-8 text-center">
            <!-- Prefix line: "Kebun Raya" - lighter, smaller -->
            <span class="block text-xl xs:text-2xl md:text-4xl font-medium text-white/80 tracking-[0.15em] sm:tracking-[0.18em] uppercase mb-2 drop-shadow-sm">
                Kebun Raya
            </span>

            <!-- Focal word: "Sambas" - massive, gradient, with SVG underline -->
            <span class="relative inline-block">
                <span class="text-5xl xs:text-6xl sm:text-7xl md:text-9xl font-black tracking-tight text-transparent bg-clip-text bg-linear-to-b from-emerald-500 to-emerald-800 drop-shadow-[0_0_60px_rgba(6,95,70,0.35)]">
                    Sambas
                </span>
                <!-- Decorative hand-drawn wave underline -->
                <svg class="absolute -bottom-2 md:-bottom-3 left-0 w-full overflow-visible" viewBox="0 0 420 14" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
                    <path d="M4 10C40 4 80 2 130 7C180 12 220 10 270 6C310 3 360 5 416 9"
                          stroke="url(#wave-gradient)" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"/>
                    <defs>
                        <linearGradient id="wave-gradient" x1="0%" y1="0%" x2="100%" y2="0%">
                            <stop offset="0%" stop-color="#065f46" stop-opacity="0.3"/>
                            <stop offset="40%" stop-color="#047857"/>
                            <stop offset="100%" stop-color="#064e3b" stop-opacity="0.5"/>
                        </linearGradient>
                    </defs>
                </svg>
            </span>
        </h1>

        <p class="text-base md:text-lg text-zinc-200/90 max-w-2xl mx-auto mb-10 leading-relaxed font-light font-inter text-center">
            Pusat konservasi tumbuhan, penelitian, dan edukasi lingkungan.
            Menjaga keanekaragaman hayati Kalimantan untuk masa depan yang lestari.
        </p>

        <div class="flex flex-col sm:flex-row justify-center gap-4 max-w-xs sm:max-w-none mx-auto">
            <a href="{{ route('pendaftaran.pengunjung') }}" class="w-full sm:w-auto px-6 sm:px-8 py-3.5 rounded-full bg-emerald-800 text-white font-semibold hover:bg-emerald-700 transition shadow-lg shadow-emerald-950/30 text-xs sm:text-sm">
                Pendaftaran Pengunjung
            </a>
            <a href="{{ route('register') }}" class="w-full sm:w-auto px-6 sm:px-8 py-3.5 rounded-full bg-white/10 text-white font-semibold border border-white/30 hover:bg-white/20 backdrop-blur-md transition shadow-md text-xs sm:text-sm">
                Pendaftaran Penelitian
            </a>
        </div>
    </div>


    {{-- Down arrow navigation indicator --}}
    <a href="#penelitian" class="absolute bottom-8 left-1/2 -translate-x-1/2 z-10 text-white hover:text-emerald-400 transition-colors duration-300 animate-bounce">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
        </svg>
    </a>
</div>

{{-- 2. Penelitian Section --}}
<div id="penelitian" class="max-w-7xl mx-auto px-6 py-16 scroll-mt-20">
    <div class="text-center mb-10 relative">
        <h2 class="text-3xl md:text-5xl font-bold tracking-tight mb-4 text-zinc-900 font-heading">
            Fasilitas Penelitian
        </h2>
        <p class="text-base md:text-lg text-zinc-650 max-w-2xl mx-auto leading-relaxed font-normal font-inter">
            Mendukung pengembangan ilmu pengetahuan melalui fasilitas penelitian yang memadai untuk para peneliti nasional maupun internasional.
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        {{-- Card 1 --}}
        <div class="group p-8 rounded-3xl border border-zinc-200/80 bg-zinc-50/50 hover:bg-white hover:shadow-xl hover:border-emerald-250 transition duration-300">
            <div class="w-12 h-12 rounded-2xl bg-emerald-100 text-emerald-600 flex items-center justify-center mb-6 transition-transform group-hover:scale-110">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
            </div>
            <h3 class="font-heading text-xl font-bold text-zinc-900 mb-3 group-hover:text-emerald-700 transition">Laboratorium Botani</h3>
            <p class="text-zinc-650 text-sm leading-relaxed mb-6 font-inter font-normal">
                Analisis morfologi dan anatomi tumbuhan, pengembangan kultur jaringan, serta identifikasi genetik flora hutan tropis.
            </p>
        </div>

        {{-- Card 2 --}}
        <div class="group p-8 rounded-3xl border border-zinc-200/80 bg-zinc-50/50 hover:bg-white hover:shadow-xl hover:border-emerald-250 transition duration-300">
            <div class="w-12 h-12 rounded-2xl bg-blue-100 text-blue-600 flex items-center justify-center mb-6 transition-transform group-hover:scale-110">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
            </div>
            <h3 class="font-heading text-xl font-bold text-zinc-900 mb-3 group-hover:text-blue-700 transition">Perpustakaan & Herbarium</h3>
            <p class="text-zinc-650 text-sm leading-relaxed mb-6 font-inter font-normal">
                Basis referensi pustaka botani Kalimantan lengkap dengan ratusan koleksi spesimen basah dan kering berstandar internasional.
            </p>
        </div>

        {{-- Card 3 --}}
        <div class="group p-8 rounded-3xl border border-zinc-200/80 bg-zinc-50/50 hover:bg-white hover:shadow-xl hover:border-emerald-250 transition duration-300">
            <div class="w-12 h-12 rounded-2xl bg-orange-100 text-orange-600 flex items-center justify-center mb-6 transition-transform group-hover:scale-110">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
            <h3 class="font-heading text-xl font-bold text-zinc-900 mb-3 group-hover:text-orange-700 transition">Edukasi & Magang</h3>
            <p class="text-zinc-650 text-sm leading-relaxed mb-6 font-inter font-normal">
                Program kunjungan sekolah, pelatihan perkebunan botani, serta fasilitasi magang mahasiswa Kehutanan dan Biologi.
            </p>
        </div>
    </div>

    <div class="mt-8 flex justify-center">
        <a href="{{ route('profil') }}" class="px-8 py-3 border border-zinc-200 hover:border-zinc-900 text-zinc-700 hover:text-zinc-900 rounded-full font-bold font-space text-xs tracking-wider transition">
            SELENGKAPNYA TENTANG KAMI
        </a>
    </div>
</div>

{{-- 3. Koleksi Section --}}
<div id="koleksi" class="bg-zinc-50/50 border-y border-zinc-100 py-16 scroll-mt-20">
    <div class="max-w-7xl mx-auto px-6">
        <div class="text-center mb-10 relative">
            <h2 class="text-3xl md:text-5xl font-bold tracking-tight mb-4 text-zinc-900 font-heading">
                Eksplorasi Keanekaragaman Flora
            </h2>
            <p class="text-base md:text-lg text-zinc-650 max-w-2xl mx-auto leading-relaxed font-normal font-inter">
                Jelajahi ragam spesies tumbuhan yang dirawat dan dilestarikan secara khusus di kawasan Kebun Raya Sambas.
            </p>
        </div>

        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-8">
            @forelse ($koleksis as $koleksi)
                <a href="{{ route('koleksi.show', $koleksi) }}" class="group block bg-white border border-zinc-200/80 rounded-2xl sm:rounded-3xl overflow-hidden shadow-xs hover:shadow-xl hover:border-zinc-300/60 transition-all duration-300">
                    <div class="relative overflow-hidden aspect-[4/3] bg-zinc-100 border-b border-zinc-100">
                        @if ($koleksi->photo)
                            <img src="{{ Storage::url($koleksi->photo) }}" alt="{{ $koleksi->title }}" class="h-full w-full object-cover img-zoom" loading="lazy" decoding="async" width="400" height="300">
                        @else
                            <div class="h-full w-full bg-emerald-50/20 flex flex-col items-center justify-center p-6 text-zinc-600">
                                <svg class="w-8 h-8 sm:w-10 sm:h-10 text-emerald-750/30 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m0-12.728l.707.707m12.728 12.728l.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z"></path>
                                </svg>
                                <span class="text-[8px] sm:text-[9px] text-zinc-600 font-space font-bold uppercase tracking-wider">No Photo Available</span>
                            </div>
                        @endif
                        @if ($koleksi->famili)
                            <div class="absolute top-2 left-2 sm:top-3 sm:left-3">
                                <span class="px-2.5 py-1 bg-white/90 backdrop-blur-xs text-[8px] sm:text-[9px] font-bold text-emerald-800 rounded-full shadow-xs uppercase tracking-wider font-space">
                                    {{ $koleksi->famili }}
                                </span>
                            </div>
                        @endif
                    </div>
                    
                    <div class="p-3.5 sm:p-6 space-y-1.5 sm:space-y-2">
                        <h3 class="text-xs sm:text-base font-bold text-zinc-950 group-hover:text-emerald-700 transition font-heading leading-tight line-clamp-2">
                            {{ $koleksi->title }}
                        </h3>
                        @if ($koleksi->genus || $koleksi->spesies)
                            @php
                                $species_cleaned = $koleksi->spesies;
                                if ($koleksi->genus && $koleksi->spesies) {
                                    $species_cleaned = trim(str_ireplace($koleksi->genus, '', $koleksi->spesies));
                                }
                            @endphp
                            <p class="text-[10px] sm:text-xs text-emerald-800 font-inter font-medium leading-relaxed">
                                <i class="italic">{{ $koleksi->genus }} {{ $species_cleaned }}</i>@if($koleksi->otoritas_1) ({{ $koleksi->otoritas_1 }})@endif @if($koleksi->otoritas_2) {{ $koleksi->otoritas_2 }}@endif
                            </p>
                        @else
                            <p class="text-[10px] sm:text-xs text-zinc-450 font-inter font-medium leading-relaxed">
                                Flora Konservasi
                            </p>
                        @endif
                    </div>
                </a>
            @empty
                {{-- Fallback Mock Data if DB is empty --}}
                @php
                    $mockFlora = [
                        ['title' => 'Anggrek Hutan Kalimantan', 'family' => 'Orchidaceae', 'desc' => 'Dendrobium eksotis', 'photo' => 'https://images.unsplash.com/photo-1744606251391-49bd85c1e840?w=500&auto=format&fit=crop'],
                        ['title' => 'Paku Sarang Burung', 'family' => 'Aspleniaceae', 'desc' => 'Asplenium nidus', 'photo' => 'https://media.istockphoto.com/id/1337428357/id/foto/tanaman-pakis-atau-tanaman-paku-pakuan-atau-daun-pakis.webp?a=1&b=1&s=612x612&w=0&k=20&c=g8Ny-q171nSQgMx1pfL2kq5Yo8eyx2M8hYhyhYLWg_w='],
                        ['title' => 'Bambu Betung Raksasa', 'family' => 'Poaceae', 'desc' => 'Dendrocalamus asper', 'photo' => 'https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?w=500&auto=format&fit=crop'],
                        ['title' => 'Kantong Semar Sambas', 'family' => 'Nepenthaceae', 'desc' => 'Nepenthes endemik', 'photo' => 'https://media.istockphoto.com/id/2206356193/id/foto/puncak-pohon-hutan-hujan-tropis-lebat.webp?a=1&b=1&s=612x612&w=0&k=20&c=kRSOoJsbwkD5x8v0FJszIRolmoYXmYOCnBy_K_amptI='],
                    ];
                @endphp
                @foreach ($mockFlora as $item)
                    <div class="group block bg-white border border-zinc-200/80 rounded-3xl overflow-hidden shadow-xs hover:shadow-xl hover:border-zinc-300/60 transition-all duration-300">
                        <div class="relative overflow-hidden aspect-[4/3] bg-zinc-100 border-b border-zinc-100">
                            <img src="{{ $item['photo'] }}" alt="{{ $item['title'] }}" class="h-full w-full object-cover img-zoom" loading="lazy" decoding="async" width="400" height="300">
                            <div class="absolute top-3 left-3">
                                <span class="px-2.5 py-1 bg-white/90 backdrop-blur-xs text-[9px] font-bold text-emerald-800 rounded-full shadow-xs uppercase tracking-wider font-space">
                                    {{ $item['family'] }}
                                </span>
                            </div>
                        </div>
                        <div class="p-6 space-y-2">
                            <h3 class="text-base font-bold text-zinc-950 group-hover:text-emerald-700 transition font-heading leading-tight">
                                {{ $item['title'] }}
                            </h3>
                            <p class="text-xs text-zinc-600 font-inter italic font-medium">
                                {{ $item['desc'] }}
                            </p>
                        </div>
                    </div>
                @endforeach
            @endforelse
        </div>

        <div class="mt-8 flex justify-center">
            <a href="{{ route('koleksi') }}" class="px-8 py-3 border border-zinc-200 hover:border-zinc-900 text-zinc-700 hover:text-zinc-900 rounded-full font-bold font-space text-xs tracking-wider transition">
                JELAJAHI SEMUA KOLEKSI FLORA
            </a>
        </div>
    </div>
</div>

{{-- 4. Peta Section --}}
<div id="peta" class="max-w-7xl mx-auto px-6 py-16 scroll-mt-20">
    <div class="text-center mb-10 relative">
        <h2 class="text-3xl md:text-5xl font-bold tracking-tight mb-4 text-zinc-900 font-heading">
            Peta Kawasan Interaktif
        </h2>
        <p class="text-base md:text-lg text-zinc-500 max-w-2xl mx-auto leading-relaxed font-light font-inter">
            Temukan lokasi fisik penyebaran spesies tumbuhan, zona taman konservasi, dan fasilitas umum Kebun Raya secara langsung.
        </p>
    </div>

    <div class="p-2.5 bg-white border border-zinc-200 rounded-[2.5rem] shadow-2xl shadow-zinc-250/30">
        <div x-data="{ 
                 isNavigating: false, 
                 destinationName: '', 
                 remainingDistance: '---', 
                 remainingTime: '---',
                 startNav(detail) {
                     this.isNavigating = true;
                     this.destinationName = detail.name;
                     this.remainingDistance = detail.distance;
                     this.remainingTime = detail.time;
                 },
                 updateNav(detail) {
                     this.remainingDistance = detail.distance;
                     this.remainingTime = detail.time;
                 },
                 cancelNavigation() {
                     this.isNavigating = false;
                     window.stopNavigation();
                 }
             }"
             @start-nav.window="startNav($event.detail)"
             @update-nav.window="updateNav($event.detail)"
             class="map-wrapper">
            <div id="home-map" class="bg-zinc-50" style="height: 600px; width: 100%; border-radius: 1.75rem;"></div>
            
            <!-- Floating Navigation Panel -->
            <div x-show="isNavigating" 
                 x-transition:enter="transition ease-out duration-355"
                 x-transition:enter-start="opacity-0 translate-y-12 scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                 x-transition:leave="transition ease-in duration-250"
                 x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                 x-transition:leave-end="opacity-0 translate-y-12 scale-95"
                 class="absolute bottom-4 sm:bottom-6 left-1/2 -translate-x-1/2 z-[1001] w-[88%] sm:w-[92%] max-w-[320px] sm:max-w-md bg-white/95 backdrop-blur-md border border-zinc-200/60 rounded-2xl sm:rounded-[2rem] p-4 sm:p-5 shadow-2xl flex flex-col gap-3 sm:gap-4">
                
                <div class="flex items-start justify-between">
                    <div class="flex items-start gap-3">
                        <div class="w-9 h-9 sm:w-11 sm:h-11 rounded-xl sm:rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center shadow-xs border border-emerald-100/50 shrink-0">
                            <svg class="w-4.5 h-4.5 sm:w-5 sm:h-5 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </div>
                        <div>
                            <span class="text-[8px] sm:text-[9px] font-bold text-emerald-600 uppercase tracking-widest flex items-center gap-1.5 mb-0.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-ping"></span> Navigasi Aktif
                            </span>
                            <h3 class="font-heading font-extrabold text-zinc-900 text-sm sm:text-base leading-tight line-clamp-1" x-text="destinationName">Nama Bangunan</h3>
                        </div>
                    </div>
                    <button @click="cancelNavigation()" class="p-1.5 sm:p-2 bg-zinc-100 hover:bg-red-50 text-zinc-400 hover:text-red-650 rounded-full transition-all duration-200 hover:rotate-90" aria-label="Batalkan Navigasi">
                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                
                <div class="h-px bg-zinc-200/60 w-full"></div>
                
                <div class="grid grid-cols-2 gap-3 sm:gap-4 text-zinc-700">
                    <div class="flex items-center gap-2.5 sm:gap-3 bg-zinc-50/50 p-2.5 sm:p-3 rounded-xl sm:rounded-2xl border border-zinc-100/75">
                        <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-lg sm:rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shadow-xs shrink-0">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-[7px] sm:text-[8px] font-bold text-zinc-450 uppercase tracking-wider">Sisa Jarak</span>
                            <span class="font-extrabold text-zinc-900 text-xs sm:text-sm" x-text="remainingDistance">---</span>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-2.5 sm:gap-3 bg-zinc-50/50 p-2.5 sm:p-3 rounded-xl sm:rounded-2xl border border-zinc-100/75">
                        <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-lg sm:rounded-xl bg-orange-50 text-orange-650 flex items-center justify-center shadow-xs shrink-0">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-[7px] sm:text-[8px] font-bold text-zinc-450 uppercase tracking-wider">Waktu Tempuh</span>
                            <span class="font-extrabold text-zinc-900 text-xs sm:text-sm" x-text="remainingTime">---</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Left Controls: Settings Modal Trigger -->
            <div class="map-left-controls" 
                 x-data="{ 
                     showSettingsModal: false, 
                     showNotification: false,
                     notificationTitle: '',
                     notificationMessage: '',
                     notificationType: 'info',
                     confirmCallback: null
                 }"
                 @map-alert.window="
                     notificationTitle = $event.detail.title;
                     notificationMessage = $event.detail.message;
                     notificationType = $event.detail.type;
                     confirmCallback = $event.detail.confirmCallback;
                     showNotification = true;
                 ">
                <!-- Trigger Button -->
                <button @click="showSettingsModal = true" 
                        class="px-3 py-2 bg-white/90 backdrop-blur-md border border-zinc-200/50 rounded-2xl shadow-md flex items-center gap-1.5 text-[10px] font-bold text-zinc-700 hover:bg-white transition-all cursor-pointer select-none">
                    <svg class="w-3.5 h-3.5 text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span>Pengaturan Peta</span>
                </button>

                <!-- Settings Modal (Responsive) -->
                <div x-show="showSettingsModal" 
                     class="fixed inset-0 z-[9999] flex items-center justify-center p-4 sm:p-6"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     x-cloak>
                    
                    <!-- Overlay -->
                    <div class="fixed inset-0 bg-black/60 backdrop-blur-xs" @click="showSettingsModal = false"></div>

                    <!-- Modal Card -->
                    <div class="relative w-full max-w-sm bg-white/95 backdrop-blur-md rounded-3xl shadow-2xl border border-zinc-200/50 p-6 flex flex-col gap-4 text-zinc-800 transform transition-all duration-300"
                         x-show="showSettingsModal"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                         x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                        
                        <!-- Header -->
                        <div class="flex items-center justify-between border-b border-zinc-100 pb-3">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-zinc-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <h3 class="font-heading font-bold text-base text-zinc-900">Pengaturan Peta</h3>
                            </div>
                            <button @click="showSettingsModal = false" class="text-zinc-400 hover:text-zinc-600 transition-colors p-1 rounded-lg hover:bg-zinc-100 cursor-pointer" aria-label="Tutup Pengaturan">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        <!-- Body -->
                        <div class="flex flex-col gap-4 py-2">
                            <!-- GPS Status Section -->
                            <div class="flex flex-col gap-1.5">
                                <span class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest pl-0.5">Status GPS</span>
                                <div id="gps-status" class="bg-zinc-50 border border-zinc-100 rounded-2xl p-3.5 flex items-center justify-between">
                                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[9px] font-semibold bg-zinc-100 text-zinc-500">
                                        GPS Menghubungkan...
                                    </span>
                                </div>
                            </div>

                            <!-- Offline Caching Section -->
                            <div class="flex flex-col gap-1.5">
                                <span class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest pl-0.5">Peta Offline</span>
                                <div class="bg-zinc-50 border border-zinc-100 rounded-2xl p-4 flex flex-col gap-3">
                                    <button id="download-btn" onclick="downloadVisibleArea()" class="w-full py-2.5 px-4 bg-zinc-950 hover:bg-emerald-600 text-white rounded-xl text-xs font-bold transition-all duration-200 hover:-translate-y-0.5 active:translate-y-0 cursor-pointer shadow-sm hover:shadow-md">
                                        Unduh Peta
                                    </button>
                                    <button id="clear-btn" onclick="clearCachedMap()" class="w-full py-2.5 px-4 bg-red-50 hover:bg-red-100 text-red-600 rounded-xl text-xs font-bold transition-all duration-200 cursor-pointer">
                                        Hapus Cache
                                    </button>
                                    
                                    <div id="download-progress" class="hidden mt-1">
                                        <div class="w-full bg-zinc-200 rounded-full h-1.5 overflow-hidden">
                                            <div id="progress-bar" class="bg-emerald-500 h-1.5 w-0 transition-all duration-150"></div>
                                        </div>
                                        <p id="progress-text" class="text-[9px] text-zinc-500 mt-1.5 text-center font-semibold"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Custom Alert/Confirm Modal -->
                <div x-show="showNotification" 
                     class="fixed inset-0 z-[10005] flex items-center justify-center p-4"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     x-cloak>
                    
                    <!-- Overlay -->
                    <div class="fixed inset-0 bg-black/60 backdrop-blur-xs" @click="if (notificationType !== 'confirm') showNotification = false"></div>

                    <!-- Card -->
                    <div class="relative w-full max-w-xs bg-white rounded-3xl shadow-2xl p-6 flex flex-col gap-4 text-center text-zinc-800"
                         x-show="showNotification"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95">
                        
                        <!-- Icon based on type -->
                        <div class="mx-auto w-12 h-12 rounded-full flex items-center justify-center shadow-inner"
                             :class="{
                                 'bg-emerald-50 text-emerald-600': notificationType === 'success',
                                 'bg-red-50 text-red-600': notificationType === 'error',
                                 'bg-amber-50 text-amber-600': notificationType === 'confirm' || notificationType === 'warning'
                             }">
                            <!-- Success Check Icon -->
                            <template x-if="notificationType === 'success'">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                            </template>
                            <!-- Error Close Icon -->
                            <template x-if="notificationType === 'error'">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </template>
                            <!-- Confirm/Warning Icon -->
                            <template x-if="notificationType === 'confirm' || notificationType === 'warning'">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            </template>
                        </div>

                        <div>
                            <h4 class="font-heading font-bold text-sm text-zinc-900" x-text="notificationTitle"></h4>
                            <p class="text-xs text-zinc-500 font-inter mt-1.5 leading-relaxed" x-text="notificationMessage"></p>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center gap-2 mt-2">
                            <template x-if="notificationType === 'confirm'">
                                <div class="flex w-full gap-2">
                                    <button @click="showNotification = false; if (confirmCallback) confirmCallback(false);" 
                                            class="flex-1 py-2.5 px-3 bg-zinc-100 hover:bg-zinc-200 text-zinc-700 text-xs font-bold rounded-xl transition-colors cursor-pointer select-none">
                                        Batal
                                    </button>
                                    <button @click="showNotification = false; if (confirmCallback) confirmCallback(true);" 
                                            class="flex-1 py-2.5 px-3 bg-zinc-950 hover:bg-zinc-800 text-white text-xs font-bold rounded-xl transition-colors cursor-pointer select-none">
                                        Ya, Lanjut
                                    </button>
                                </div>
                            </template>
                            <template x-if="notificationType !== 'confirm'">
                                <button @click="showNotification = false" 
                                        class="w-full py-2.5 px-3 bg-zinc-950 hover:bg-zinc-800 text-white text-xs font-bold rounded-xl transition-colors cursor-pointer select-none">
                                    Oke
                                </button>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="map-controls" x-data="{ open: false, selected: 'road' }" @click.away="open = false">
                <div class="relative">
                    <button @click="open = !open" class="map-control-btn">
                        <span class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg>
                            <span x-text="selected === 'road' ? 'Lapisan Default' : (selected === 'satellite' ? 'Lapisan Satelit' : 'Lapisan Medan')"></span>
                        </span>
                        <svg class="w-4 h-4 text-zinc-400 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-200" 
                         x-transition:enter-start="opacity-0 scale-95" 
                         x-transition:enter-end="opacity-100 scale-100" 
                         x-transition:leave="transition ease-in duration-100" 
                         x-transition:leave-start="opacity-100 scale-100" 
                         x-transition:leave-end="opacity-0 scale-95" 
                         class="absolute top-full right-0 mt-2 w-48 bg-white/95 backdrop-blur-md border border-zinc-200/50 rounded-2xl shadow-2xl py-1.5 z-[2000]">
                        <a @click="selected = 'road'; switchLayer('road'); open = false" href="#" class="flex items-center gap-3 px-4 py-2 text-xs font-bold text-zinc-700 hover:bg-emerald-50 hover:text-emerald-700 transition">Lapisan Default</a>
                        <a @click="selected = 'satellite'; switchLayer('satellite'); open = false" href="#" class="flex items-center gap-3 px-4 py-2 text-xs font-bold text-zinc-700 hover:bg-emerald-50 hover:text-emerald-700 transition">Lapisan Satelit</a>
                        <a @click="selected = 'terrain'; switchLayer('terrain'); open = false" href="#" class="flex items-center gap-3 px-4 py-2 text-xs font-bold text-zinc-700 hover:bg-emerald-50 hover:text-emerald-700 transition">Lapisan Medan</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-8 flex flex-col sm:flex-row items-center justify-between gap-6 p-6 bg-zinc-50 rounded-3xl border border-zinc-200">
        <div>
            <h4 class="font-heading text-lg font-bold text-zinc-950 mb-1">Butuh Sistem Navigasi Penuh?</h4>
            <p class="text-sm text-zinc-500 font-inter font-light">Buka peta utama untuk menikmati fitur GPS Real-Time, pengunduhan peta offline, dan filter per kategori wilayah.</p>
        </div>
        <a href="{{ route('peta') }}" class="px-7 py-3.5 bg-zinc-950 hover:bg-emerald-700 text-white rounded-full font-bold font-space text-xs tracking-wider transition-colors shrink-0">
            BUKA PETA INTERAKTIF
        </a>
    </div>
</div>

{{-- 5. CTA Akhir Section --}}
<div class="bg-zinc-950 text-white py-16 relative overflow-hidden">
    <div class="absolute inset-0 bg-grid-white/[0.02] pointer-events-none"></div>
    {{-- Glow effect decoration --}}
    <div class="absolute -top-40 left-1/3 w-96 h-96 bg-emerald-700/25 rounded-full filter blur-3xl pointer-events-none"></div>

    <div class="relative z-10 max-w-5xl mx-auto px-6 text-center">
        <h2 class="text-3xl md:text-6xl font-bold tracking-tight font-heading mb-6 leading-tight">
            Ikut Serta Melestarikan <br>
            Hutan Tropis Kalimantan
        </h2>
        <p class="text-base md:text-lg text-zinc-450 max-w-2xl mx-auto mb-12 font-inter font-light">
            Anda dapat berkunjung langsung untuk edukasi alam atau mendaftar sebagai peneliti Kebun Raya Sambas.
        </p>

        <div class="flex flex-col sm:flex-row justify-center items-center gap-5">
            <a href="{{ route('pendaftaran.pengunjung') }}" class="w-full sm:w-auto px-8 py-4 bg-emerald-800 hover:bg-emerald-700 text-white font-bold font-space text-xs tracking-wider rounded-full shadow-lg transition duration-300">
                PENDAFTARAN KUNJUNGAN
            </a>
            <a href="{{ route('register') }}" class="w-full sm:w-auto px-8 py-4 bg-white/10 hover:bg-white/20 text-white font-bold font-space text-xs tracking-wider rounded-full border border-white/20 backdrop-blur-xs transition duration-300">
                PENDAFTARAN PENELITI
            </a>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<!-- LocalForage library for IndexedDB caching -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/localforage/1.10.0/localforage.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    // Custom URL generator to bypass Leaflet's getTileUrl map-zoom caching bug
    function getTileUrlForCoords(layer, coords) {
        var url = layer._url;
        url = url.replace('{z}', coords.z)
                 .replace('{x}', coords.x)
                 .replace('{y}', coords.y);
        if (url.indexOf('{s}') !== -1 && layer.options.subdomains) {
            var subdomains = layer.options.subdomains;
            var index = Math.abs(coords.x + coords.y) % subdomains.length;
            var s = typeof subdomains === 'string' ? subdomains[index] : subdomains[index];
            url = url.replace('{s}', s);
        }
        return url;
    }

    // Custom Tile Layer to cache map tiles in IndexedDB via localForage
    L.TileLayer.Offline = L.TileLayer.extend({
        getTileKey: function(coords) {
            var cleanUrl = (this._url || "").replace(/[^a-zA-Z0-9]/g, "").substring(0, 20);
            return `${cleanUrl}_tile_${coords.z}_${coords.x}_${coords.y}`;
        },
        createTile: function(coords, done) {
            var tile = document.createElement('img');
            var url = getTileUrlForCoords(this, coords);
            var key = this.getTileKey(coords);

            localforage.getItem(key).then(function(blob) {
                if (blob) {
                    var objectUrl = URL.createObjectURL(blob);
                    tile.onload = function() {
                        URL.revokeObjectURL(objectUrl);
                        done(null, tile);
                    };
                    tile.onerror = function() {
                        done(new Error("Gagal render blob tile"), tile);
                    };
                    tile.src = objectUrl;
                } else {
                    fetch(url)
                        .then(function(res) {
                            if (!res.ok) throw new Error("Gagal mengambil tile");
                            return res.blob();
                        })
                        .then(function(blob) {
                            localforage.setItem(key, blob);
                            var objectUrl = URL.createObjectURL(blob);
                            tile.onload = function() {
                                URL.revokeObjectURL(objectUrl);
                                done(null, tile);
                            };
                            tile.onerror = function() {
                                done(new Error("Gagal render downloaded tile"), tile);
                            };
                            tile.src = objectUrl;
                        })
                        .catch(function(err) {
                            console.warn("Offline fallback ke normal image src:", url);
                            tile.onload = function() {
                                done(null, tile);
                            };
                            tile.onerror = function() {
                                done(err, tile);
                            };
                            tile.src = url;
                        });
                }
            }).catch(function(err) {
                console.error(err);
                tile.onload = function() {
                    done(null, tile);
                };
                tile.onerror = function() {
                    done(err, tile);
                };
                tile.src = url;
            });

            return tile;
        }
    });

    L.tileLayer.offline = function(urlTemplate, options) {
        return new L.TileLayer.Offline(urlTemplate, options);
    };

    var bounds = L.latLngBounds([[-3.0, 108.0], [2.5, 114.5]]); // Batas Kalimantan Barat
    var map = L.map('home-map', { 
        scrollWheelZoom: true,
        zoomControl: false,
        maxBounds: bounds,
        maxBoundsViscosity: 0.8,
        minZoom: 8
    }).setView([1.2706202914994014, 109.48517276551188], 14); 
    L.control.zoom({ position: 'bottomright' }).addTo(map);

    // Gunakan Offline Layer untuk melayani tile dengan offline support
    var roadLayer = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', { maxNativeZoom: 19, maxZoom: 20, attribution: '&copy; OpenStreetMap', crossOrigin: true });
    var satelliteLayer = L.tileLayer.offline('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', { maxNativeZoom: 18, maxZoom: 18, attribution: '&copy; Esri', crossOrigin: true });
    var terrainLayer = L.tileLayer('https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png', { 
        maxNativeZoom: 17, 
        maxZoom: 17, 
        attribution: 'Map data: &copy; OpenStreetMap contributors',
        subdomains: 'abc',
        crossOrigin: true
    });

    var currentLayer = roadLayer.addTo(map);

    // Auto-clear old corrupted cache from previous buggy version once
    if (!localStorage.getItem('tile_cache_cleaned_v3')) {
        localforage.clear().then(function() {
            localStorage.setItem('tile_cache_cleaned_v3', 'true');
            if (typeof currentLayer !== 'undefined' && currentLayer.redraw) {
                currentLayer.redraw();
            }
        }).catch(function(err) {
            console.error("Gagal auto-clear cache:", err);
        });
    }

    window.switchLayer = function(mode) {
        map.removeLayer(currentLayer);
        if(mode === 'satellite') currentLayer = satelliteLayer;
        else if(mode === 'terrain') currentLayer = terrainLayer;
        else currentLayer = roadLayer;
        currentLayer.addTo(map);
    };

    // --- Jalur Rekaman Perjalanan User (Linestring Navigasi Real-Time) ---
    var userPathCoords = [];
    var userPolyline = L.polyline(userPathCoords, {
        color: '#3b82f6', // Biru untuk navigasi aktif
        weight: 4.5,
        opacity: 0.9,
        dashArray: '5, 8'
    }).addTo(map);

    // --- Real-time User tracking logic ---
    var userMarker = null;
    var userAccuracyCircle = null;
    var firstLocationCheck = true;

    if ('geolocation' in navigator) {
        navigator.geolocation.watchPosition(
            function(position) {
                var lat = position.coords.latitude;
                var lng = position.coords.longitude;
                var accuracy = position.coords.accuracy;
                var latlng = L.latLng(lat, lng);

                if (userMarker) {
                    userMarker.setLatLng(latlng);
                    userAccuracyCircle.setLatLng(latlng);
                    userAccuracyCircle.setRadius(accuracy);
                } else {
                    var userIcon = L.divIcon({
                        className: 'user-location-marker',
                        html: '<div class="pulse-ring"></div><div class="blue-dot"></div>',
                        iconSize: [20, 20],
                        iconAnchor: [10, 10]
                    });
                    userMarker = L.marker(latlng, { icon: userIcon }).addTo(map);
                    userAccuracyCircle = L.circle(latlng, {
                        radius: accuracy,
                        color: '#3b82f6',
                        fillColor: '#3b82f6',
                        fillOpacity: 0.12,
                        weight: 1
                    }).addTo(map);
                }

                // Tambahkan koordinat ke riwayat rute user dan perbarui linestring
                userPathCoords.push(latlng);
                userPolyline.setLatLngs(userPathCoords);

                // Update rute navigasi jika sedang aktif
                if (window.currentNavTarget) {
                    window.updateNavigationRouting();
                }

                if (firstLocationCheck) {
                    map.setView(latlng, 16);
                    firstLocationCheck = false;
                }

                document.getElementById('gps-status').innerHTML = `
                    <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-md text-[9px] font-bold bg-green-50 text-green-700 border border-green-100">
                        GPS Aktif (${accuracy.toFixed(1)}m)
                    </span>
                `;
            },
            function(error) {
                console.warn("GPS error:", error);
                var text = "GPS Error";
                if (error.code === error.PERMISSION_DENIED) text = "Akses Ditolak";
                else if (error.code === error.POSITION_UNAVAILABLE) text = "Lokasi Hilang";
                else if (error.code === error.TIMEOUT) text = "GPS Timeout";

                document.getElementById('gps-status').innerHTML = `
                    <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-md text-[9px] font-bold bg-red-50 text-red-700 border border-red-100">
                        ${text}
                    </span>
                `;
            },
            {
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: 0
            }
        );
    } else {
        document.getElementById('gps-status').innerHTML = `
            <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-md text-[9px] font-bold bg-red-50 text-red-700 border border-red-100">
                Tak Didukung
            </span>
        `;
    }

    // --- Offline map seeding logic ---
    function getTileCoordsForBounds(bounds, zoom) {
        var tileSize = 256;
        var nw = bounds.getNorthWest();
        var se = bounds.getSouthEast();
        var nwPoint = map.project(nw, zoom);
        var sePoint = map.project(se, zoom);

        var minX = Math.floor(nwPoint.x / tileSize);
        var minY = Math.floor(nwPoint.y / tileSize);
        var maxX = Math.floor(sePoint.x / tileSize);
        var maxY = Math.floor(sePoint.y / tileSize);

        var tiles = [];
        for (var x = minX; x <= maxX; x++) {
            for (var y = minY; y <= maxY; y++) {
                tiles.push({ z: zoom, x: x, y: y });
            }
        }
        return tiles;
    }

    window.showMapAlert = function(title, message, type = 'info', confirmCallback = null) {
        window.dispatchEvent(new CustomEvent('map-alert', {
            detail: { title: title, message: message, type: type, confirmCallback: confirmCallback }
        }));
    };

    window.downloadVisibleArea = function() {
        // Custom URL generator to bypass Leaflet's getTileUrl map-zoom caching bug
        function getTileUrlForQueue(layer, tile) {
            var url = layer._url;
            url = url.replace('{z}', tile.z)
                     .replace('{x}', tile.x)
                     .replace('{y}', tile.y);
            if (url.indexOf('{s}') !== -1 && layer.options.subdomains) {
                var subdomains = layer.options.subdomains;
                var index = Math.abs(tile.x + tile.y) % subdomains.length;
                var s = typeof subdomains === 'string' ? subdomains[index] : subdomains[index];
                url = url.replace('{s}', s);
            }
            return url;
        }

        var bounds = map.getBounds();
        var currentZoom = map.getZoom();
        var zoomsToDownload = [currentZoom - 1, currentZoom, currentZoom + 1];
        var tiles = [];

        zoomsToDownload.forEach(function(z) {
            if (z >= map.getMinZoom() && z <= map.getMaxZoom()) {
                tiles = tiles.concat(getTileCoordsForBounds(bounds, z));
            }
        });

        if (tiles.length === 0) return;

        var startDownload = function() {
            var downloaded = 0;
            var failed = 0;
            var progressDiv = document.getElementById('download-progress');
            var bar = document.getElementById('progress-bar');
            var text = document.getElementById('progress-text');
            var btn = document.getElementById('download-btn');

            progressDiv.classList.remove('hidden');
            btn.disabled = true;
            btn.innerText = "Mengunduh...";

            function updateProgress() {
                var totalProcessed = downloaded + failed;
                var percentage = Math.round((totalProcessed / tiles.length) * 100);
                bar.style.width = percentage + '%';
                text.innerText = `Mengunduh: ${totalProcessed}/${tiles.length} (${percentage}%)`;

                if (totalProcessed === tiles.length) {
                    setTimeout(function() {
                        progressDiv.classList.add('hidden');
                        btn.disabled = false;
                        btn.innerText = "Unduh Peta";
                        if (downloaded > 0) {
                            var successMsg = `Selesai! ${downloaded} petak peta berhasil disimpan offline.` + (failed > 0 ? ` (${failed} petak gagal diunduh).` : '');
                            window.showMapAlert("Unduhan Selesai", successMsg, "success");
                        } else {
                            window.showMapAlert("Unduhan Gagal", "Gagal mengunduh peta. Periksa koneksi internet Anda atau coba lagi.", "error");
                        }
                    }, 1000);
                }
            }

            // Implement a queue with concurrency of 3 to avoid rate limits
            var index = 0;
            var maxConcurrency = 3;
            var activeRequests = 0;

            function downloadNext() {
                if (index >= tiles.length) return;

                var tile = tiles[index++];
                var url = getTileUrlForQueue(satelliteLayer, tile);
                var key = satelliteLayer.getTileKey(tile);

                activeRequests++;

                localforage.getItem(key).then(function(val) {
                    if (val) {
                        downloaded++;
                        activeRequests--;
                        updateProgress();
                        downloadNext();
                    } else {
                        // 50ms delay between fetches to respect tile servers and prevent rate limit blocks
                        setTimeout(function() {
                            fetch(url)
                                .then(function(r) { 
                                    if (!r.ok) throw new Error("Gagal load tile");
                                    return r.blob(); 
                                })
                                .then(function(blob) {
                                    return localforage.setItem(key, blob);
                                })
                                .then(function() {
                                    downloaded++;
                                    activeRequests--;
                                    updateProgress();
                                    downloadNext();
                                })
                                .catch(function(e) {
                                    failed++;
                                    activeRequests--;
                                    updateProgress();
                                    downloadNext();
                                });
                        }, 50);
                    }
                }).catch(function(err) {
                    failed++;
                    activeRequests--;
                    updateProgress();
                    downloadNext();
                });
            }

            for (var i = 0; i < Math.min(maxConcurrency, tiles.length); i++) {
                downloadNext();
            }
        };

        if (tiles.length > 250) {
            window.showMapAlert(
                "Konfirmasi Unduhan", 
                `Anda akan mengunduh ${tiles.length} petak peta. Unduh sekarang?`, 
                "confirm", 
                function(approved) {
                    if (approved) startDownload();
                }
            );
        } else {
            startDownload();
        }
    }

    window.clearCachedMap = function() {
        window.showMapAlert(
            "Hapus Cache Peta",
            "Apakah Anda yakin ingin menghapus semua peta offline yang tersimpan di browser ini?",
            "confirm",
            function(approved) {
                if (approved) {
                    var btn = document.getElementById('clear-btn');
                    btn.disabled = true;
                    btn.innerText = "Menghapus...";

                    localforage.clear().then(function() {
                        window.showMapAlert("Sukses", "Cache berhasil dibersihkan.", "success");
                        btn.disabled = false;
                        btn.innerText = "Hapus Cache";
                        currentLayer.redraw();
                    }).catch(function(err) {
                        btn.disabled = false;
                        btn.innerText = "Hapus Cache";
                    });
                }
            }
        );
    }

    function autoDownloadKRS() {
        if (!navigator.onLine) return; // Jangan download jika offline
        
        var bounds = L.latLngBounds([1.2599, 109.4751], [1.2799, 109.4951]);
        var zooms = [13, 14, 15, 16, 17];
        var tiles = [];

        zooms.forEach(function(z) {
            tiles = tiles.concat(getTileCoordsForBounds(bounds, z));
        });

        tiles.forEach(function(tile) {
            var url = satelliteLayer.getTileUrl(tile);
            var key = satelliteLayer.getTileKey(tile);

            localforage.getItem(key).then(function(val) {
                if (!val) {
                    fetch(url)
                        .then(function(r) { return r.blob(); })
                        .then(function(blob) {
                            localforage.setItem(key, blob);
                        })
                        .catch(function(e) {
                            // Abaikan
                        });
                }
            });
        });
    }

    setTimeout(autoDownloadKRS, 3000);

    var markers = @json($markers ?? []);
    var storageBase = "{{ \Illuminate\Support\Facades\Storage::url('') }}".replace(/\/$/, '');

    // --- Ekstrak Jalan Kustom untuk Sistem Navigasi ---
    var roadPolylines = [];
    markers.forEach(function(marker) {
        if ((marker.geometry_type === 'polyline' || marker.geometry_type === 'linestring') && marker.geojson) {
            // Jangan gunakan garis batas wilayah/batas KRS sebagai jalur jalan navigasi
            var nameLower = (marker.name || "").toLowerCase();
            var typeLower = (marker.type || "").toLowerCase();
            if (nameLower.includes("batas") || typeLower.includes("batas")) {
                return;
            }
            try {
                var coords = JSON.parse(marker.geojson);
                if (coords.length > 0) {
                    var path = coords.map(function(c) {
                        return { lat: parseFloat(c[0]), lng: parseFloat(c[1]) };
                    });
                    roadPolylines.push({
                        id: marker.id,
                        path: path
                    });
                }
            } catch(e) {
                console.error("Gagal memproses jalan kustom untuk rute:", e);
            }
        }
    });

    // --- Logika Matematika Navigasi (Haversine & Vector Snapping) ---
    function getHaversineDistance(p1, p2) {
        var R = 6371000; // Radius bumi dalam meter
        var phi1 = p1.lat * Math.PI / 180;
        var phi2 = p2.lat * Math.PI / 180;
        var deltaPhi = (p2.lat - p1.lat) * Math.PI / 180;
        var deltaLambda = (p2.lng - p1.lng) * Math.PI / 180;
        var a = Math.sin(deltaPhi / 2) * Math.sin(deltaPhi / 2) +
                Math.cos(phi1) * Math.cos(phi2) *
                Math.sin(deltaLambda / 2) * Math.sin(deltaLambda / 2);
        var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
        return R * c;
    }

    function projectPointOnSegment(p, a, b) {
        var dx = b.lng - a.lng;
        var dy = b.lat - a.lat;
        
        if (dx === 0 && dy === 0) {
            return { point: a, distance: getHaversineDistance(p, a), t: 0 };
        }
        
        var t = ((p.lng - a.lng) * dx + (p.lat - a.lat) * dy) / (dx * dx + dy * dy);
        t = Math.max(0, Math.min(1, t));
        
        var projected = {
            lat: a.lat + t * dy,
            lng: a.lng + t * dx
        };
        
        return {
            point: projected,
            distance: getHaversineDistance(p, projected),
            t: t
        };
    }

    function findClosestSnapPoint(p) {
        var minDistance = Infinity;
        var closestProjected = null;
        var closestSegment = null;
        
        for (var i = 0; i < roadPolylines.length; i++) {
            var polyline = roadPolylines[i];
            for (var j = 0; j < polyline.path.length - 1; j++) {
                var a = polyline.path[j];
                var b = polyline.path[j+1];
                var projection = projectPointOnSegment(p, a, b);
                
                if (projection.distance < minDistance) {
                    minDistance = projection.distance;
                    closestProjected = projection.point;
                    closestSegment = {
                        polylineIndex: i,
                        segmentIndex: j,
                        a: a,
                        b: b,
                        t: projection.t
                    };
                }
            }
        }
        
        return {
            point: closestProjected,
            distance: minDistance,
            segment: closestSegment
        };
    }

    function getNodeKey(p) {
        return p.lat.toFixed(6) + ',' + p.lng.toFixed(6);
    }

    // --- Algoritma Pembuat Graf & Dijkstra ---
    function buildGraphAndRunDijkstra(startLatLng, endLatLng) {
        var snapS = findClosestSnapPoint(startLatLng);
        var snapE = findClosestSnapPoint(endLatLng);
        
        if (!snapS.point || !snapE.point || roadPolylines.length === 0) {
            return {
                path: [startLatLng, endLatLng],
                distance: getHaversineDistance(startLatLng, endLatLng),
                isStraightLine: true
            };
        }
        
        var adj = {};
        function addEdge(p1, p2) {
            var key1 = getNodeKey(p1);
            var key2 = getNodeKey(p2);
            var dist = getHaversineDistance(p1, p2);
            
            if (!adj[key1]) adj[key1] = {};
            if (!adj[key2]) adj[key2] = {};
            
            adj[key1][key2] = dist;
            adj[key2][key1] = dist;
        }
        
        var keyToCoord = {};
        function registerCoord(p) {
            var key = getNodeKey(p);
            keyToCoord[key] = p;
        }
        
        roadPolylines.forEach(function(polyline) {
            polyline.path.forEach(function(p) { registerCoord(p); });
            for (var i = 0; i < polyline.path.length - 1; i++) {
                addEdge(polyline.path[i], polyline.path[i+1]);
            }
        });
        
        var nodeKeys = Object.keys(keyToCoord);
        for (var i = 0; i < nodeKeys.length; i++) {
            for (var j = i + 1; j < nodeKeys.length; j++) {
                var k1 = nodeKeys[i];
                var k2 = nodeKeys[j];
                var p1 = keyToCoord[k1];
                var p2 = keyToCoord[k2];
                var d = getHaversineDistance(p1, p2);
                if (d < 5.0) {
                    if (!adj[k1]) adj[k1] = {};
                    if (!adj[k2]) adj[k2] = {};
                    adj[k1][k2] = d;
                    adj[k2][k1] = d;
                }
            }
        }
        
        registerCoord(snapS.point);
        var sSeg = snapS.segment;
        if (sSeg) {
            addEdge(sSeg.a, snapS.point);
            addEdge(snapS.point, sSeg.b);
        }
        
        registerCoord(snapE.point);
        var eSeg = snapE.segment;
        if (eSeg) {
            addEdge(eSeg.a, snapE.point);
            addEdge(snapE.point, eSeg.b);
        }
        
        var startKey = getNodeKey(snapS.point);
        var endKey = getNodeKey(snapE.point);
        
        var distances = {};
        var previous = {};
        var unvisited = new Set();
        
        Object.keys(adj).forEach(function(key) {
            distances[key] = Infinity;
            unvisited.add(key);
        });
        distances[startKey] = 0;
        
        while (unvisited.size > 0) {
            var minNode = null;
            var minDist = Infinity;
            
            unvisited.forEach(function(key) {
                if (distances[key] < minDist) {
                    minDist = distances[key];
                    minNode = key;
                }
            });
            
            if (minNode === null || minNode === endKey) {
                break;
            }
            
            unvisited.delete(minNode);
            
            var neighbors = adj[minNode] || {};
            Object.keys(neighbors).forEach(function(neighbor) {
                if (unvisited.has(neighbor)) {
                    var alt = distances[minNode] + neighbors[neighbor];
                    if (alt < distances[neighbor]) {
                        distances[neighbor] = alt;
                        previous[neighbor] = minNode;
                    }
                }
            });
        }
        
        var pathCoords = [];
        var currentKey = endKey;
        
        if (distances[endKey] !== Infinity || startKey === endKey) {
            while (currentKey) {
                pathCoords.push(keyToCoord[currentKey]);
                currentKey = previous[currentKey];
            }
            pathCoords.reverse();
            
            return {
                path: pathCoords,
                distance: distances[endKey],
                snapStart: snapS.point,
                snapEnd: snapE.point,
                isStraightLine: false
            };
        } else {
            return {
                path: [startLatLng, endLatLng],
                distance: getHaversineDistance(startLatLng, endLatLng),
                isStraightLine: true
            };
        }
    }

    // --- Menggambar Garis Navigasi ---
    var navRoadLine = null;
    var navSnapStartLine = null;
    var navSnapEndLine = null;

    function clearNavigationLayers() {
        if (navRoadLine) map.removeLayer(navRoadLine);
        if (navSnapStartLine) map.removeLayer(navSnapStartLine);
        if (navSnapEndLine) map.removeLayer(navSnapEndLine);
        
        navRoadLine = null;
        navSnapStartLine = null;
        navSnapEndLine = null;
    }

    function drawNavigationRoute(userCoord, targetCoord, routingResult) {
        clearNavigationLayers();
        
        if (routingResult.isStraightLine) {
            navRoadLine = L.polyline([userCoord, targetCoord], {
                color: '#3b82f6',
                weight: 5,
                opacity: 0.8,
                dashArray: '8, 8'
            }).addTo(map);
        } else {
            navSnapStartLine = L.polyline([userCoord, routingResult.snapStart], {
                color: '#3b82f6',
                weight: 4,
                opacity: 0.8,
                dashArray: '5, 8'
            }).addTo(map);
            
            var pathLatLngs = routingResult.path.map(function(p) {
                return L.latLng(p.lat, p.lng);
            });
            navRoadLine = L.polyline(pathLatLngs, {
                color: '#1d4ed8',
                weight: 6,
                opacity: 0.95
            }).addTo(map);
            
            navSnapEndLine = L.polyline([routingResult.snapEnd, targetCoord], {
                color: '#3b82f6',
                weight: 4,
                opacity: 0.8,
                dashArray: '5, 8'
            }).addTo(map);
        }
    }

    // --- State Navigasi Global ---
    window.currentNavTarget = null;

    window.startNavigation = function(id, name, lat, lng) {
        window.currentNavTarget = { id: id, name: name, lat: lat, lng: lng };
        map.closePopup();
        window.updateNavigationRouting();
    };

    window.stopNavigation = function() {
        window.currentNavTarget = null;
        clearNavigationLayers();
    };

    function updateFloatingPanel(name, totalDistMeters) {
        var distStr = '';
        if (totalDistMeters < 1000) {
            distStr = Math.round(totalDistMeters) + ' m';
        } else {
            distStr = (totalDistMeters / 1000).toFixed(2) + ' km';
        }
        
        var timeMinutes = Math.round(totalDistMeters / (1.25 * 60)); // 1.25 m/s jalan kaki
        var timeStr = '';
        if (timeMinutes < 1) {
            timeStr = '< 1 mnt jalan kaki';
        } else {
            timeStr = timeMinutes + ' mnt jalan kaki';
        }
        
        window.dispatchEvent(new CustomEvent('start-nav', {
            detail: {
                name: name,
                distance: distStr,
                time: timeStr
            }
        }));
    }

    window.updateNavigationRouting = function() {
        if (!window.currentNavTarget) return;
        
        if (!userMarker) {
            window.dispatchEvent(new CustomEvent('start-nav', {
                detail: {
                    name: window.currentNavTarget.name,
                    distance: 'Mencari GPS...',
                    time: '---'
                }
            }));
            return;
        }
        
        var userLatLng = userMarker.getLatLng();
        var targetLatLng = L.latLng(window.currentNavTarget.lat, window.currentNavTarget.lng);
        
        var result = buildGraphAndRunDijkstra(userLatLng, targetLatLng);
        drawNavigationRoute(userLatLng, targetLatLng, result);
        
        var totalDistMeters = 0;
        if (result.isStraightLine) {
            totalDistMeters = result.distance;
        } else {
            var distUserToSnapS = getHaversineDistance(userLatLng, result.snapStart);
            var distSnapEToTarget = getHaversineDistance(result.snapEnd, targetLatLng);
            totalDistMeters = distUserToSnapS + result.distance + distSnapEToTarget;
        }
        
        updateFloatingPanel(window.currentNavTarget.name, totalDistMeters);
    };

    // Simpan referensi layer peta berdasarkan marker id
    window.mapLayers = window.mapLayers || {};

    markers.forEach(function(marker) {
        var typeLabel = marker.type.replace(/_/g, ' ').replace(/\b\w/g, function(c) { return c.toUpperCase(); });
        
        var imageHtml = marker.photo 
            ? `<div class="relative w-full h-40 bg-zinc-100 group overflow-hidden"><img src="${storageBase + '/' + marker.photo}" alt="${marker.name}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"></div>`
            : `<div class="w-full h-24 bg-zinc-100 flex items-center justify-center border-b border-zinc-50"><svg class="w-8 h-8 text-zinc-350" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg></div>`;

        var targetLat = null;
        var targetLng = null;
        var geomType = marker.geometry_type || 'point';

        if (geomType === 'point') {
            targetLat = marker.latitude;
            targetLng = marker.longitude;
        } else if (marker.geojson) {
            try {
                var coords = JSON.parse(marker.geojson);
                if (coords.length > 0) {
                    var flatCoords = [];
                    if (Array.isArray(coords[0]) && Array.isArray(coords[0][0])) {
                        flatCoords = coords[0];
                    } else {
                        flatCoords = coords;
                    }
                    var sumLat = 0, sumLng = 0;
                    flatCoords.forEach(function(c) {
                        sumLat += parseFloat(c[0]);
                        sumLng += parseFloat(c[1]);
                    });
                    targetLat = sumLat / flatCoords.length;
                    targetLng = sumLng / flatCoords.length;
                }
            } catch(e) {
                console.error("Gagal mendapatkan koordinat target:", e);
            }
        }

        var navButtonHtml = '';
        if (targetLat && targetLng && geomType !== 'polyline') {
            navButtonHtml = `<button onclick="window.startNavigation(${marker.id}, '${marker.name.replace(/'/g, "\\'")}', ${targetLat}, ${targetLng})" class="inline-flex items-center gap-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold px-3 py-1.5 rounded-lg text-[10px] transition-all duration-200 shadow-sm cursor-pointer">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                Navigasi
            </button>`;
        }

        var badgeHtml = `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider mb-2" style="color: ${marker.color}; border: 1px solid ${marker.color}40; background-color: ${marker.color}15;">${typeLabel}</span>`;
        var popupContent = `<div class="flex flex-col font-sans text-left bg-white w-full">${imageHtml}<div class="p-5"><div>${badgeHtml}<h3 class="font-heading font-bold text-lg leading-tight text-zinc-900 m-0">${marker.name}</h3></div>${marker.description ? `<p class="text-sm text-zinc-500 leading-relaxed m-0 mt-1 line-clamp-3">${marker.description}</p>` : ''}<div class="mt-4 pt-4 border-t border-zinc-100 flex items-center justify-between gap-2"><a href="{{ url('/peta') }}/${marker.id}" class="inline-flex items-center text-xs font-bold text-emerald-600 hover:text-emerald-700 transition-colors">LIHAT DETAIL</a>${navButtonHtml}</div></div></div>`;

        var leafletLayer = null;
        if (geomType === 'point' && marker.latitude && marker.longitude) {
            var customIcon = L.divIcon({
                className: 'custom-marker',
                html: '<div style="background-color: ' + marker.color + '; width: 22px; height: 22px; border-radius: 50%; border: 3px solid white; box-shadow: 0 2px 5px rgba(0,0,0,0.3);"></div>',
                iconSize: [22, 22],
                iconAnchor: [11, 11],
                popupAnchor: [0, -10]
            });
            var isMobilePopup = window.innerWidth < 640;
            leafletLayer = L.marker([marker.latitude, marker.longitude], { icon: customIcon })
                .addTo(map)
                .bindPopup(popupContent, { closeButton: true, maxWidth: isMobilePopup ? 260 : 320, minWidth: isMobilePopup ? 240 : 300 });
        } else if (marker.geojson) {
            try {
                var coordinates = JSON.parse(marker.geojson);
                if (coordinates.length > 0) {
                    if (geomType === 'polyline' || geomType === 'linestring') {
                        leafletLayer = L.polyline(coordinates, { 
                            color: marker.color, 
                            weight: 4.5 
                        }).addTo(map);
                    } else if (geomType === 'polygon') {
                        leafletLayer = L.polygon(coordinates, { 
                            color: marker.color, 
                            fillColor: marker.color, 
                            fillOpacity: 0.12, 
                            weight: 3,
                            dashArray: '6, 6'
                        }).addTo(map);
                    }
                }
            } catch(e) {
                console.error("Gagal menggambar fitur spasial:", e);
            }
        }

        if (leafletLayer && marker.id) {
            window.mapLayers[marker.id] = leafletLayer;
        }
    });

    window.focusOnMarker = function(id, geomType, lat, lng) {
        var layer = window.mapLayers[id];
        if (layer) {
            if (geomType === 'point' && lat && lng) {
                map.setView([lat, lng], 17, { animate: true });
                layer.openPopup();
            } else if (geomType === 'polygon') {
                var bounds = layer.getBounds();
                map.fitBounds(bounds, { maxZoom: 17 });
                layer.openPopup();
            }
        }
        
        var mapContainer = document.getElementById('map') || document.getElementById('home-map');
        if (mapContainer) {
            mapContainer.scrollIntoView({ behavior: 'smooth' });
        }
    };

    // Paksa render ulang ukuran Leaflet secara berkala selama transisi loading halaman selesai
    var invalidateInterval = setInterval(function() {
        if (map) {
            map.invalidateSize();
        }
    }, 1000);
    
    setTimeout(function() {
        clearInterval(invalidateInterval);
    }, 6000);

    window.addEventListener('load', function() {
        setTimeout(function() {
            if (map) {
                map.invalidateSize();
            }
        }, 600);
    });
});
</script>
@endpush