<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <title>Koleksi Flora - Kebun Raya Sambas</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Space+Grotesk:wght@500;700&display=swap"
        rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .font-heading {
            font-family: 'Space Grotesk', sans-serif;
        }

        .img-zoom {
            transition: transform 0.5s ease;
        }

        .group:hover .img-zoom {
            transform: scale(1.1);
        }
    </style>
</head>

<body class="bg-white text-zinc-900 antialiased">
    <x-landing.navbar />

    <main class="pt-32 pb-20">
        <div class="max-w-7xl mx-auto px-6">
            <h1 class="font-heading text-4xl font-bold mb-2">Koleksi Flora</h1>
            <p class="text-zinc-500 mb-10 max-w-2xl">Jelajahi keunikan spesies tumbuhan yang kami lestarikan di Kebun
                Raya Sambas.</p>

            <div class="mb-10 flex flex-wrap justify-center gap-1">
                @foreach (range('A', 'Z') as $letter)
                    <a href="#letter-{{ $letter }}"
                        class="text-zinc-600 hover:bg-zinc-100 p-2 rounded-md transition text-sm font-medium">
                        {{ $letter }}
                    </a>
                @endforeach
            </div>

            <div class="flex flex-wrap gap-2 mb-10">
                <button class="px-5 py-2 rounded-full bg-zinc-900 text-white text-sm font-medium">Semua</button>
                <button
                    class="px-5 py-2 rounded-full bg-zinc-100 text-zinc-600 text-sm font-medium hover:bg-zinc-200 transition">Anggrek</button>
                <button
                    class="px-5 py-2 rounded-full bg-zinc-100 text-zinc-600 text-sm font-medium hover:bg-zinc-200 transition">Paku-pakuan</button>
                <button
                    class="px-5 py-2 rounded-full bg-zinc-100 text-zinc-600 text-sm font-medium hover:bg-zinc-200 transition">Tanaman
                    Obat</button>
                <button
                    class="px-5 py-2 rounded-full bg-zinc-100 text-zinc-600 text-sm font-medium hover:bg-zinc-200 transition">Kayu-kayuan</button>
            </div>

            @php
                $groupedKoleksis = [];
                $currentLetter = '';
                // Simulating $koleksis for demonstration, assuming real data is passed and sorted
                $koleksis = collect([
                    (object) [
                        'title' => 'Anggrek Hutan 1',
                        'photo' => 'https://source.unsplash.com/random/500x600?plant&sig=1',
                    ],
                    (object) [
                        'title' => 'Anggrek Bulan 2',
                        'photo' => 'https://source.unsplash.com/random/500x600?plant&sig=2',
                    ],
                    (object) [
                        'title' => 'Bunga Matahari 3',
                        'photo' => 'https://source.unsplash.com/random/500x600?plant&sig=3',
                    ],
                    (object) [
                        'title' => 'Bunga Mawar 4',
                        'photo' => 'https://source.unsplash.com/random/500x600?plant&sig=4',
                    ],
                    (object) [
                        'title' => 'Cendana 5',
                        'photo' => 'https://source.unsplash.com/random/500x600?plant&sig=5',
                    ],
                    (object) [
                        'title' => 'Dahlia 6',
                        'photo' => 'https://source.unsplash.com/random/500x600?plant&sig=6',
                    ],
                ])->sortBy('title'); // Ensure data is sorted

                foreach ($koleksis as $koleksi) {
                    $firstLetter = strtoupper(substr($koleksi->title, 0, 1));
                    if (!isset($groupedKoleksis[$firstLetter])) {
                        $groupedKoleksis[$firstLetter] = [];
                    }
                    $groupedKoleksis[$firstLetter][] = $koleksi;
                }
            @endphp

            @foreach ($groupedKoleksis as $letter => $koleksiGroup)
                <div id="letter-{{ $letter }}" class="mb-8">
                    <h2 class="font-heading text-3xl font-bold mb-6 mt-10">{{ $letter }}</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
                        @foreach ($koleksiGroup as $koleksi)
                            <div class="group relative overflow-hidden rounded-2xl bg-zinc-100 aspect-4/5">
                                <img src="{{ $koleksi->photo }}" class="w-full h-full object-cover img-zoom"
                                    alt="{{ $koleksi->title }}">
                                <div
                                    class="absolute inset-0 bg-linear-to-t from-black/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition duration-300">
                                    <div class="absolute bottom-0 left-0 p-6">
                                        {{-- Assuming category is available, otherwise remove or adapt --}}
                                        <span class="text-zinc-300 text-xs uppercase tracking-wider">Orchidaceae</span>
                                        <h3 class="text-white text-lg font-bold">{{ $koleksi->title }}</h3>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach

            <div class="mt-12 flex justify-center">
                <button
                    class="px-6 py-3 border border-zinc-200 rounded-full text-zinc-500 hover:text-zinc-900 hover:border-zinc-900 transition">Muat
                    Lebih Banyak</button>
            </div>
        </div>
    </main>

    <x-landing.footer />
</body>

</html>
