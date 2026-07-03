@extends('layouts.landing')

@section('title', 'Eksplorasi Tumbuhan - Kebun Raya Sambas')

@section('content')
    <div class="max-w-7xl mx-auto px-6 py-8">
        
        <!-- Header Section -->
        <div class="text-center mb-8 relative">
            <h1 class="text-4xl md:text-6xl font-bold tracking-tight mb-4 text-zinc-900 font-heading">
                Eksplorasi Tumbuhan
            </h1>
            <p class="text-base md:text-lg text-zinc-500 max-w-2xl mx-auto leading-relaxed font-light font-inter">
                Temukan keanekaragaman flora, peta sebaran, dan klasifikasi ilmiah tumbuhan yang dilestarikan di Kebun Raya Sambas.
            </p>
        </div>


        <!-- Search & Filter Controls -->
        <div class="max-w-3xl mx-auto mb-8 space-y-6">
            <form method="GET" action="{{ route('koleksi') }}" class="relative group">
                <div class="flex items-center bg-white border border-zinc-200 focus-within:border-emerald-500 rounded-full p-2 pl-6 shadow-md shadow-zinc-100 hover:shadow-lg focus-within:shadow-emerald-100/40 transition-all duration-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-zinc-400 group-focus-within:text-emerald-600 transition-colors mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" name="search" value="{{ request('search') }}" 
                        placeholder="Cari nama tumbuhan, genus, famili, atau kata kunci..." 
                        class="w-full bg-transparent border-none outline-none text-zinc-800 text-sm font-inter placeholder-zinc-400 pr-4" />
                    
                    @if(request()->filled('search'))
                        <a href="{{ route('koleksi') }}" class="p-2 text-zinc-400 hover:text-zinc-600 mr-1 transition-colors" title="Clear Search">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                        </a>
                    @endif

                    <button type="submit" class="px-6 py-2.5 bg-zinc-900 hover:bg-emerald-800 text-white rounded-full text-sm font-bold font-space transition-all duration-300 shadow-sm shadow-zinc-950/10">
                        Cari
                    </button>
                </div>
            </form>
        </div>

        <!-- Plant Cards Grid -->
        @if ($koleksis->isEmpty())
            <div class="text-center py-20 bg-zinc-50/50 border border-dashed border-zinc-200 rounded-3xl max-w-xl mx-auto px-6">
                <div class="w-16 h-16 rounded-full bg-zinc-100 flex items-center justify-center mx-auto mb-4 text-zinc-400">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" /></svg>
                </div>
                <h3 class="text-lg font-bold text-zinc-800 font-space mb-1">Tumbuhan Tidak Ditemukan</h3>
                <p class="text-sm text-zinc-500 font-inter">Coba masukkan kata kunci pencarian lain atau pilih kategori yang berbeda.</p>
                <div class="mt-5">
                    <a href="{{ route('koleksi') }}" class="inline-flex items-center justify-center px-4 py-2 border border-zinc-300 bg-white text-zinc-700 text-xs font-bold rounded-lg hover:bg-zinc-50 transition-all shadow-sm">
                        Reset Pencarian
                    </a>
                </div>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                @foreach ($koleksis as $koleksi)
                    <a href="{{ route('koleksi.show', $koleksi) }}" class="group block bg-white border border-zinc-200/80 rounded-3xl overflow-hidden shadow-xs hover:shadow-xl hover:border-zinc-300/60 transition-all duration-300 transform hover:scale-[1.01]">
                        <div class="relative overflow-hidden aspect-[4/3] bg-zinc-50 border-b border-zinc-100">
                            @if ($koleksi->photo)
                                <img src="{{ Storage::url($koleksi->photo) }}" alt="{{ $koleksi->title }}"
                                    class="h-full w-full object-cover img-zoom">
                            @else
                                <div class="h-full w-full bg-emerald-50/20 flex flex-col items-center justify-center p-6 text-zinc-400">
                                    <svg class="w-12 h-12 text-emerald-700/30 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m0-12.728l.707.707m12.728 12.728l.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z"></path>
                                    </svg>
                                    <span class="text-[10px] text-zinc-400 font-space font-bold uppercase tracking-wider">No Photo Available</span>
                                </div>
                            @endif
                            @if ($koleksi->famili)
                                <div class="absolute top-3 left-3">
                                    <span class="px-2.5 py-1 bg-white/90 backdrop-blur-xs text-[10px] font-bold text-emerald-800 rounded-full shadow-sm uppercase tracking-wider font-space">
                                        {{ $koleksi->famili }}
                                    </span>
                                </div>
                            @endif
                        </div>
                        
                        <div class="p-6 space-y-3.5">
                            <div class="space-y-1">
                                <h3 class="text-lg font-bold text-zinc-900 group-hover:text-emerald-700 transition duration-300 font-heading leading-snug">
                                    {{ $koleksi->title }}
                                </h3>
                                @if ($koleksi->genus || $koleksi->spesies)
                                    <p class="text-xs text-zinc-400 font-inter italic font-medium leading-relaxed">
                                        {{ implode(' ', array_filter([$koleksi->genus, $koleksi->spesies])) }}
                                    </p>
                                @elseif ($koleksi->famili)
                                    <p class="text-xs text-zinc-400 font-inter font-medium leading-relaxed">
                                        Famili: {{ $koleksi->famili }}
                                    </p>
                                @else
                                    <p class="text-xs text-zinc-400 font-inter font-medium leading-relaxed">
                                        Flora Kebun Raya Sambas
                                    </p>
                                @endif
                            </div>

                            <div class="flex items-center gap-1.5 pt-2 border-t border-zinc-50 text-xs font-semibold text-emerald-700 font-space">
                                <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m0-12.728l.707.707m12.728 12.728l.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z"></path>
                                </svg>
                                <span>Kebun Raya Sambas</span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
@endsection
