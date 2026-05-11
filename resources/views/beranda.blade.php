<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kebun Raya Sambas</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Space+Grotesk:wght@500;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Inter', sans-serif; scroll-behavior: smooth; }
        h1, h2, h3, .font-heading { font-family: 'Space Grotesk', sans-serif; }
        .img-zoom { transition: transform 0.7s ease; }
        .group:hover .img-zoom { transform: scale(1.05); }
    </style>
</head>

<body class="bg-white text-zinc-900 antialiased selection:bg-zinc-900 selection:text-white">

    <div class="fixed inset-0 z-0 pointer-events-none">
        <div class="absolute inset-0 bg-white"></div>
        <div class="absolute inset-0 bg-grid-black/[0.03]"></div>
        <div class="absolute inset-0 bg-white mask-[radial-gradient(ellipse_at_center,transparent_20%,black)]"></div>
    </div>

    <x-landing.navbar />

    <main class="relative z-10">

        <div id="home" class="relative h-screen w-full flex items-center justify-center text-center overflow-hidden">
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
                    <a href="{{ route('register') }}" class="px-8 py-3 rounded-full bg-white text-zinc-900 font-semibold hover:bg-zinc-100 transition shadow-xl shadow-zinc-200/10">
                        Pendaftaran Pengunjung
                    </a>
                    <a href="{{ route('register') }}" class="px-8 py-3 rounded-full bg-white/10 text-white font-semibold border border-white/50 hover:bg-white/20 backdrop-blur-md transition shadow-lg">
                        Pendaftaran Penelitian
                    </a>
                </div>
            </div>
        </div>

        <div id="gallery" class="max-w-7xl mx-auto px-6 scroll-mt-32 mt-24">
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

                {{-- <div id="research" class="relative overflow-hidden rounded-3xl border border-zinc-200 bg-zinc-50 p-8 flex flex-col justify-between hover:bg-white hover:shadow-lg transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-zinc-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                    </svg>
                    <div>
                        <h3 class="text-lg font-bold text-zinc-900 mb-2">Penelitian</h3>
                        <p class="text-zinc-500 text-sm leading-relaxed">Fasilitas laboratorium dan herbarium untuk peneliti botani.</p>
                    </div>
                </div> --}}

            </div>
        </div>

        <x-landing.footer />

    </main>
</body>
</html>