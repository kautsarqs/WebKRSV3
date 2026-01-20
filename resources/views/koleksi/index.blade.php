@extends('layouts.landing')

@section('title', 'Koleksi Kami')

@section('content')
    <div class="max-w-7xl mx-auto px-6">
        <div class="text-center mb-12">
            <h1 class="text-5xl md:text-6xl font-bold tracking-tight mb-4 text-zinc-900 font-heading">
                Jelajahi Koleksi Kami
            </h1>
            <p class="text-lg text-zinc-500 max-w-2xl mx-auto leading-relaxed font-light">
                Temukan keanekaragaman flora yang kami lestarikan di Kebun Raya Sambas.
            </p>
        </div>

        @if ($categories->isEmpty())
            <div class="text-center py-16">
                <p class="text-zinc-500">Belum ada koleksi yang ditambahkan.</p>
            </div>
        @else
            <div class="space-y-16">
                @foreach ($categories as $category)
                    <section>
                        <h2 class="text-3xl font-bold tracking-tight text-zinc-900 font-heading mb-8">{{ $category->name }}
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                            @foreach ($category->koleksis as $koleksi)
                                <a href="{{ route('koleksi.show', $koleksi) }}" class="group block">
                                    <div class="relative overflow-hidden rounded-3xl bg-zinc-100 h-72">
                                        @if ($koleksi->photo)
                                            <img src="{{ Storage::url($koleksi->photo) }}" alt="{{ $koleksi->title }}"
                                                class="h-full w-full object-cover img-zoom">
                                        @else
                                            <div class="h-full w-full bg-zinc-200 flex items-center justify-center">
                                                <svg class="w-12 h-12 text-zinc-400" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l-1.586-1.586a2 2 0 00-2.828 0L6 14m6-6l.01.01">
                                                    </path>
                                                </svg>
                                            </div>
                                        @endif
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"></div>
                                    </div>
                                    <div class="pt-4">
                                        <h3
                                            class="text-xl font-bold text-zinc-900 group-hover:text-blue-600 transition font-heading">
                                            {{ $koleksi->title }}</h3>
                                        <p class="text-zinc-500 text-sm mt-1 line-clamp-2">
                                            {{ Str::limit($koleksi->description, 100) }}</p>
                                        @if ($koleksi->locations->isNotEmpty())
                                            <div class="flex items-center gap-2 mt-3 text-sm text-blue-600 font-semibold">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                                    </path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                </svg>
                                                <span>{{ $koleksi->locations->count() }} Lokasi di Peta</span>
                                            </div>
                                        @endif
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </section>
                @endforeach
            </div>
        @endif
    </div>
@endsection
