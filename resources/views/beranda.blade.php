@extends('layouts.landing')

@section('title', 'Kebun Raya Sambas - Pusat Riset & Konservasi')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<style>
    #home-map { height: 480px; width: 100%; z-index: 10; border-radius: 2rem; }
    /* Leaflet popup styling override to match main map page */
    .leaflet-popup-content-wrapper { padding: 0; overflow: hidden; border-radius: 20px; border: none; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15); background: white; }
    .leaflet-popup-content { margin: 0 !important; width: 280px !important; line-height: 1.5; }
    .leaflet-popup-tip { background: white; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1); }
    .leaflet-container a.leaflet-popup-close-button { top: 12px; right: 12px; width: 28px; height: 28px; border-radius: 50%; background: rgba(255, 255, 255, 0.95); color: #3f3f46; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); display: flex; align-items: center; justify-content: center; font-size: 14px; text-decoration: none; transition: all 0.2s; font-weight: bold; }
</style>
@endpush

@section('content')
{{-- 1. Hero Section --}}
<div id="home" class="relative h-screen w-full flex items-center justify-center text-center overflow-hidden">
    <video autoplay loop muted playsinline class="absolute z-0 w-auto min-w-full min-h-full max-w-none object-cover">
        <source src="https://videos.pexels.com/video-files/3209828/3209828-hd_1920_1080_25fps.mp4" type="video/mp4" />
        Your browser does not support the video tag.
    </video>

    <div class="absolute inset-0 bg-black/45 z-1"></div>
    {{-- Gradient Putih di Bawah Hero --}}
    <div class="absolute bottom-0 left-0 w-full h-36 bg-linear-to-t from-white via-white/40 to-transparent z-2"></div>

    <div class="relative z-10 max-w-4xl mx-auto px-6 mt-8">
        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full border border-white/20 bg-white/10 text-xs text-white mb-6 backdrop-blur-md animate-fade-in">
            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
            Digitalisasi Konservasi Alam
        </div>

        <h1 class="text-4xl md:text-7xl font-bold tracking-tight mb-6 text-white font-heading leading-tight drop-shadow-sm">
            Sambas Botanical <br>
            <span class="text-transparent bg-clip-text bg-linear-to-b from-emerald-250 to-emerald-400">
                Garden & Research
            </span>
        </h1>

        <p class="text-base md:text-lg text-zinc-200 max-w-2xl mx-auto mb-10 leading-relaxed font-light font-inter">
            Pusat konservasi tumbuhan, penelitian, dan edukasi lingkungan.
            Menjaga keanekaragaman hayati Kalimantan untuk masa depan yang lestari.
        </p>

        <div class="flex flex-col sm:flex-row justify-center gap-4">
            <a href="{{ route('pendaftaran.pengunjung') }}" class="px-8 py-3.5 rounded-full bg-emerald-600 text-white font-semibold hover:bg-emerald-700 transition shadow-lg shadow-emerald-950/20 text-sm">
                Pendaftaran Pengunjung
            </a>
            <a href="{{ route('register') }}" class="px-8 py-3.5 rounded-full bg-white/10 text-white font-semibold border border-white/30 hover:bg-white/20 backdrop-blur-md transition shadow-md text-sm">
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
<div id="penelitian" class="max-w-7xl mx-auto px-6 py-28 scroll-mt-20">
    <div class="text-center mb-16 relative">
        <div class="inline-flex items-center gap-1.5 px-3.5 py-1 bg-emerald-50 border border-emerald-100 rounded-full text-emerald-700 text-xs font-bold font-space mb-4 tracking-wider uppercase">
            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="text-emerald-600"><path d="M4.5 16.5c-1.5 1.26-2 2.5-2 3.5 0 1 1 2 2 2s2-1 2-2c0-1-.5-2.24-2-3.5Z"/><path d="M12 2C6.5 2 2 6.5 2 12c0 2.2.72 4.22 1.94 5.86L18.14 3.94C16.5 2.72 14.48 2 12 2Z"/><path d="M22 12c0 5.5-4.5 10-10 10-2.2 0-4.22-.72-5.86-1.94l14.2-14.2c1.22 1.64 1.94 3.66 1.94 5.86Z"/></svg>
            Penelitian & Edukasi
        </div>
        <h2 class="text-3xl md:text-5xl font-bold tracking-tight mb-4 text-zinc-900 font-heading">
            Pusat Riset & Sains
        </h2>
        <p class="text-base md:text-lg text-zinc-500 max-w-2xl mx-auto leading-relaxed font-light font-inter">
            Mendorong pemahaman ekologi hutan tropis Borneo melalui fasilitas penelitian dan sarana edukasi modern.
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        {{-- Card 1 --}}
        <div class="group p-8 rounded-3xl border border-zinc-200/80 bg-zinc-50/50 hover:bg-white hover:shadow-xl hover:border-emerald-250 transition duration-300">
            <div class="w-12 h-12 rounded-2xl bg-emerald-100 text-emerald-600 flex items-center justify-center mb-6 transition-transform group-hover:scale-110">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
            </div>
            <h3 class="font-heading text-xl font-bold text-zinc-900 mb-3 group-hover:text-emerald-700 transition">Laboratorium Botani</h3>
            <p class="text-zinc-500 text-sm leading-relaxed mb-6 font-inter font-light">
                Analisis morfologi dan anatomi tumbuhan, pengembangan kultur jaringan, serta identifikasi genetik flora hutan tropis.
            </p>
            <span class="text-xs font-bold font-space text-emerald-600 inline-flex items-center gap-1 group-hover:underline">
                Pelajari Fasilitas
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
            </span>
        </div>

        {{-- Card 2 --}}
        <div class="group p-8 rounded-3xl border border-zinc-200/80 bg-zinc-50/50 hover:bg-white hover:shadow-xl hover:border-emerald-250 transition duration-300">
            <div class="w-12 h-12 rounded-2xl bg-blue-100 text-blue-600 flex items-center justify-center mb-6 transition-transform group-hover:scale-110">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
            </div>
            <h3 class="font-heading text-xl font-bold text-zinc-900 mb-3 group-hover:text-blue-700 transition">Perpustakaan & Herbarium</h3>
            <p class="text-zinc-500 text-sm leading-relaxed mb-6 font-inter font-light">
                Basis referensi pustaka botani Kalimantan lengkap dengan ratusan koleksi spesimen basah dan kering berstandar internasional.
            </p>
            <span class="text-xs font-bold font-space text-blue-600 inline-flex items-center gap-1 group-hover:underline">
                Buka Referensi
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
            </span>
        </div>

        {{-- Card 3 --}}
        <div class="group p-8 rounded-3xl border border-zinc-200/80 bg-zinc-50/50 hover:bg-white hover:shadow-xl hover:border-emerald-250 transition duration-300">
            <div class="w-12 h-12 rounded-2xl bg-orange-100 text-orange-600 flex items-center justify-center mb-6 transition-transform group-hover:scale-110">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
            <h3 class="font-heading text-xl font-bold text-zinc-900 mb-3 group-hover:text-orange-700 transition">Edukasi & Magang</h3>
            <p class="text-zinc-500 text-sm leading-relaxed mb-6 font-inter font-light">
                Program kunjungan sekolah, pelatihan perkebunan botani, serta fasilitasi magang mahasiswa Kehutanan dan Biologi.
            </p>
            <span class="text-xs font-bold font-space text-orange-600 inline-flex items-center gap-1 group-hover:underline">
                Lihat Program
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
            </span>
        </div>
    </div>

    <div class="mt-14 flex justify-center">
        <a href="{{ route('profil') }}#penelitian" class="px-8 py-3 border border-zinc-200 hover:border-zinc-900 text-zinc-700 hover:text-zinc-900 rounded-full font-bold font-space text-xs tracking-wider transition">
            SELENGKAPNYA TENTANG PENELITIAN
        </a>
    </div>
</div>

{{-- 3. Koleksi Section --}}
<div id="koleksi" class="bg-zinc-50/50 border-y border-zinc-100 py-28 scroll-mt-20">
    <div class="max-w-7xl mx-auto px-6">
        <div class="text-center mb-16 relative">
            <div class="inline-flex items-center gap-1.5 px-3.5 py-1 bg-emerald-50 border border-emerald-100 rounded-full text-emerald-700 text-xs font-bold font-space mb-4 tracking-wider uppercase">
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="text-emerald-600"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
                Koleksi Flora
            </div>
            <h2 class="text-3xl md:text-5xl font-bold tracking-tight mb-4 text-zinc-900 font-heading">
                Eksplorasi Keanekaragaman Flora
            </h2>
            <p class="text-base md:text-lg text-zinc-500 max-w-2xl mx-auto leading-relaxed font-light font-inter">
                Spesies tumbuhan endemik dan langka yang dibudidayakan secara khusus dalam naungan konservasi Kebun Raya Sambas.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            @forelse ($koleksis as $koleksi)
                <a href="{{ route('koleksi.show', $koleksi) }}" class="group block bg-white border border-zinc-200/80 rounded-3xl overflow-hidden shadow-xs hover:shadow-xl hover:border-zinc-300/60 transition-all duration-300">
                    <div class="relative overflow-hidden aspect-[4/3] bg-zinc-100 border-b border-zinc-100">
                        @if ($koleksi->photo)
                            <img src="{{ Storage::url($koleksi->photo) }}" alt="{{ $koleksi->title }}" class="h-full w-full object-cover img-zoom">
                        @else
                            <div class="h-full w-full bg-emerald-50/20 flex flex-col items-center justify-center p-6 text-zinc-450">
                                <svg class="w-10 h-10 text-emerald-750/30 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m0-12.728l.707.707m12.728 12.728l.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z"></path>
                                </svg>
                                <span class="text-[9px] text-zinc-400 font-space font-bold uppercase tracking-wider">No Photo Available</span>
                            </div>
                        @endif
                        @if ($koleksi->famili)
                            <div class="absolute top-3 left-3">
                                <span class="px-2.5 py-1 bg-white/90 backdrop-blur-xs text-[9px] font-bold text-emerald-800 rounded-full shadow-xs uppercase tracking-wider font-space">
                                    {{ $koleksi->famili }}
                                </span>
                            </div>
                        @endif
                    </div>
                    
                    <div class="p-6 space-y-2">
                        <h3 class="text-base font-bold text-zinc-950 group-hover:text-emerald-700 transition font-heading leading-tight line-clamp-1">
                            {{ $koleksi->title }}
                        </h3>
                        @if ($koleksi->genus || $koleksi->spesies)
                            <p class="text-xs text-zinc-400 font-inter italic font-medium">
                                {{ implode(' ', array_filter([$koleksi->genus, $koleksi->spesies])) }}
                            </p>
                        @else
                            <p class="text-xs text-zinc-450 font-inter font-medium">
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
                            <img src="{{ $item['photo'] }}" alt="{{ $item['title'] }}" class="h-full w-full object-cover img-zoom">
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
                            <p class="text-xs text-zinc-400 font-inter italic font-medium">
                                {{ $item['desc'] }}
                            </p>
                        </div>
                    </div>
                @endforeach
            @endforelse
        </div>

        <div class="mt-14 flex justify-center">
            <a href="{{ route('koleksi') }}" class="px-8 py-3 border border-zinc-200 hover:border-zinc-900 text-zinc-700 hover:text-zinc-900 rounded-full font-bold font-space text-xs tracking-wider transition">
                JELAJAHI SEMUA KOLEKSI FLORA
            </a>
        </div>
    </div>
</div>

{{-- 4. Peta Section --}}
<div id="peta" class="max-w-7xl mx-auto px-6 py-28 scroll-mt-20">
    <div class="text-center mb-16 relative">
        <div class="inline-flex items-center gap-1.5 px-3.5 py-1 bg-emerald-50 border border-emerald-100 rounded-full text-emerald-700 text-xs font-bold font-space mb-4 tracking-wider uppercase">
            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="text-emerald-600"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0Z"/><circle cx="12" cy="10" r="3"/></svg>
            Peta Digital & WebGIS
        </div>
        <h2 class="text-3xl md:text-5xl font-bold tracking-tight mb-4 text-zinc-900 font-heading">
            Peta Kawasan Interaktif
        </h2>
        <p class="text-base md:text-lg text-zinc-500 max-w-2xl mx-auto leading-relaxed font-light font-inter">
            Temukan lokasi fisik penyebaran spesies tumbuhan, zona taman konservasi, dan fasilitas umum Kebun Raya secara langsung.
        </p>
    </div>

    <div class="p-2.5 bg-white border border-zinc-200 rounded-4xl shadow-2xl shadow-zinc-200/50">
        <div id="home-map" class="bg-zinc-100"></div>
    </div>

    <div class="mt-14 flex flex-col sm:flex-row items-center justify-between gap-6 p-8 bg-zinc-50 rounded-3xl border border-zinc-200/70">
        <div>
            <h4 class="font-heading text-lg font-bold text-zinc-950 mb-1">Butuh Sistem Navigasi Penuh?</h4>
            <p class="text-sm text-zinc-500 font-inter font-light">Buka peta utama untuk menikmati fitur GPS Real-Time, pengunduhan peta offline, dan filter per kategori wilayah.</p>
        </div>
        <a href="{{ route('peta') }}" class="px-7 py-3.5 bg-zinc-950 hover:bg-emerald-800 text-white rounded-full font-bold font-space text-xs tracking-wider transition-colors shrink-0">
            BUKA PETA INTERAKTIF
        </a>
    </div>
</div>

{{-- 5. CTA Akhir Section --}}
<div class="bg-zinc-950 text-white py-24 relative overflow-hidden">
    <div class="absolute inset-0 bg-grid-white/[0.02] pointer-events-none"></div>
    {{-- Glow effect decoration --}}
    <div class="absolute -top-40 left-1/3 w-96 h-96 bg-emerald-700/25 rounded-full filter blur-3xl pointer-events-none"></div>

    <div class="relative z-10 max-w-5xl mx-auto px-6 text-center">
        <h2 class="text-3xl md:text-6xl font-bold tracking-tight font-heading mb-6 leading-tight">
            Ikut Serta Melestarikan <br>
            Hutan Tropis Kalimantan
        </h2>
        <p class="text-base md:text-lg text-zinc-450 max-w-2xl mx-auto mb-12 font-inter font-light">
            Dukung program digitalisasi konservasi botani kami. Anda dapat berkunjung langsung untuk edukasi alam atau mendaftar sebagai peneliti mitra riset.
        </p>

        <div class="flex flex-col sm:flex-row justify-center items-center gap-5">
            <a href="{{ route('pendaftaran.pengunjung') }}" class="w-full sm:w-auto px-8 py-4 bg-emerald-600 hover:bg-emerald-750 text-white font-bold font-space text-xs tracking-wider rounded-full shadow-lg transition duration-300">
                DAFTAR KUNJUNGAN PENGUNJUNG
            </a>
            <a href="{{ route('register') }}" class="w-full sm:w-auto px-8 py-4 bg-white/10 hover:bg-white/20 text-white font-bold font-space text-xs tracking-wider rounded-full border border-white/20 backdrop-blur-xs transition duration-300">
                DAFTAR PENELITIAN MITRA RISET
            </a>
            <a href="#contact" class="w-full sm:w-auto px-8 py-4 bg-zinc-900 hover:bg-zinc-800 text-zinc-350 hover:text-white font-bold font-space text-xs tracking-wider rounded-full transition duration-300">
                HUBUNGI KAMI
            </a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        var map = L.map('home-map', { scrollWheelZoom: false, zoomControl: false }).setView([1.2706202914994014, 109.48517276551188], 15);
        L.control.zoom({ position: 'bottomright' }).addTo(map);

        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap'
        }).addTo(map);

        var markers = @json($markers ?? []);
        var storageBase = "{{ \Illuminate\Support\Facades\Storage::url('') }}".replace(/\/$/, '');

        markers.forEach(function(marker) {
            var typeLabel = 'Lokasi';
            var badgeClass = 'bg-zinc-100 text-zinc-600';
            switch(marker.type) {
                case 'area_koleksi': typeLabel = 'Area Koleksi'; badgeClass = 'bg-green-50 text-green-700 border border-green-100'; break;
                case 'fasilitas_umum': typeLabel = 'Fasilitas Umum'; badgeClass = 'bg-blue-50 text-blue-700 border border-blue-100'; break;
                case 'kantor_pengelola': typeLabel = 'Kantor'; badgeClass = 'bg-red-50 text-red-700 border border-red-100'; break;
                case 'pos_keamanan': typeLabel = 'Keamanan'; badgeClass = 'bg-yellow-50 text-yellow-700 border border-yellow-100'; break;
            }

            var imageHtml = marker.photo 
                ? `<div class="relative w-full h-32 bg-zinc-100 overflow-hidden"><img src="${storageBase + '/' + marker.photo}" alt="${marker.name}" class="w-full h-full object-cover"></div>`
                : `<div class="w-full h-16 bg-zinc-100 flex items-center justify-center border-b border-zinc-50"><svg class="w-6 h-6 text-zinc-350" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg></div>`;

            var popupContent = `
                <div class="flex flex-col font-sans text-left bg-white w-full">
                    ${imageHtml}
                    <div class="p-4">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-bold uppercase tracking-wider ${badgeClass} mb-1">${typeLabel}</span>
                        <h3 class="font-heading font-bold text-sm leading-tight text-zinc-900 m-0">${marker.name}</h3>
                        <div class="mt-3 pt-3 border-t border-zinc-100">
                            <a href="{{ route('peta') }}" class="inline-flex items-center text-[10px] font-bold text-emerald-600 hover:text-emerald-700 transition-colors">LIHAT DI PETA</a>
                        </div>
                    </div>
                </div>`;

            if (marker.latitude && marker.longitude) {
                var customIcon = L.divIcon({
                    className: 'custom-marker',
                    html: '<div style="background-color: ' + marker.color + '; width: 18px; height: 18px; border-radius: 50%; border: 2.5px solid white; box-shadow: 0 1px 4px rgba(0,0,0,0.3);"></div>',
                    iconSize: [18, 18],
                    iconAnchor: [9, 9],
                    popupAnchor: [0, -8]
                });
                L.marker([marker.latitude, marker.longitude], { icon: customIcon })
                    .addTo(map)
                    .bindPopup(popupContent, { closeButton: true, maxWidth: 300, minWidth: 260 });
            }
        });
    });
</script>
@endpush