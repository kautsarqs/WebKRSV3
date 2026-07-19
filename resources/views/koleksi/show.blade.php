@extends('layouts.landing')

@section('title', $koleksi->title . ' - Kebun Raya Sambas')

@section('content')
    <div class="max-w-6xl mx-auto px-6 py-8">

        <div class="mb-8">
            <a href="{{ route('koleksi') }}" class="inline-flex items-center text-sm font-semibold text-zinc-500 hover:text-emerald-700 transition-colors duration-250 font-space group">
                <div class="w-8 h-8 rounded-full bg-white border border-zinc-200 flex items-center justify-center mr-2 shadow-xs group-hover:bg-emerald-50 group-hover:border-emerald-200 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                </div>
                Kembali ke Eksplorasi
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">

            <div class="lg:col-span-5 space-y-6">
                <div class="relative overflow-hidden rounded-3xl border border-zinc-200/80 bg-zinc-50 shadow-lg group">
                    @if ($koleksi->photo)
                        <img src="{{ Storage::url($koleksi->photo) }}" alt="{{ $koleksi->title }}" class="w-full object-cover aspect-[4/5] img-zoom">
                    @else
                        <div class="w-full aspect-[4/5] bg-emerald-50/20 flex flex-col items-center justify-center p-8 text-zinc-400">
                            <svg class="w-20 h-20 text-emerald-800/10 mb-4 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m0-12.728l.707.707m12.728 12.728l.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z"></path>
                            </svg>
                            <span class="text-xs font-bold font-space uppercase tracking-wider text-zinc-400/80">Foto tidak tersedia</span>
                        </div>
                    @endif

                    @if ($koleksi->famili)
                    <div class="absolute bottom-4 left-4 z-10">
                        <span class="px-3.5 py-1.5 bg-black/60 backdrop-blur-md text-[10px] font-bold text-white rounded-full shadow-sm uppercase tracking-wider font-space">
                            {{ $koleksi->famili }}
                        </span>
                    </div>
                    @endif
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div class="flex flex-col items-center justify-center p-3.5 bg-zinc-50 border border-zinc-200/80 rounded-2xl text-center space-y-1">
                        <span class="text-[9px] font-bold text-zinc-400 uppercase tracking-widest font-space">Spesimen</span>
                        <span class="text-xs font-bold text-zinc-700 font-inter">{{ $koleksi->spesies ? 'Teridentifikasi' : 'General' }}</span>
                    </div>
                    <div class="flex flex-col items-center justify-center p-3.5 bg-zinc-50 border border-zinc-200/80 rounded-2xl text-center space-y-1">
                        <span class="text-[9px] font-bold text-zinc-400 uppercase tracking-widest font-space">Famili</span>
                        <span class="text-xs font-bold text-zinc-700 font-inter">{{ $koleksi->famili ?: '-' }}</span>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-7 space-y-8">

                <div class="space-y-2">
                    <h1 class="text-4xl md:text-5xl font-bold tracking-tight text-zinc-950 font-heading leading-tight">
                        {{ $koleksi->title }}
                    </h1>
                    @if ($koleksi->genus || $koleksi->spesies)
                        @php
                            $species_cleaned = $koleksi->spesies;
                            if ($koleksi->genus && $koleksi->spesies) {
                                $species_cleaned = trim(str_ireplace($koleksi->genus, '', $koleksi->spesies));
                            }
                        @endphp
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="text-base md:text-lg font-serif text-emerald-800 font-medium">
                                <i class="italic">{{ $koleksi->genus }} {{ $species_cleaned }}</i>@if($koleksi->otoritas_1) ({{ $koleksi->otoritas_1 }})@endif @if($koleksi->otoritas_2) {{ $koleksi->otoritas_2 }}@endif
                            </span>
                            <span class="text-xs text-zinc-400 font-inter font-normal">Nama Ilmiah / Botani</span>
                        </div>
                    @endif
                </div>

                <div class="space-y-3">
                    <h3 class="text-sm font-bold text-zinc-900 uppercase tracking-wider font-space">Deskripsi & Karakteristik</h3>
                    <div class="text-zinc-600 font-inter leading-relaxed font-light text-base text-justify whitespace-pre-line bg-zinc-50/50 p-6 rounded-2xl border border-zinc-200/60 shadow-xs">
                        {!! nl2br(e($koleksi->description)) !!}
                    </div>
                </div>

                <div class="space-y-4">
                    <h3 class="text-sm font-bold text-zinc-900 uppercase tracking-wider font-space">Klasifikasi Taksonomi (Scientific)</h3>

                    <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-2 xl:grid-cols-3 gap-3">
                        <div class="p-4 bg-white border border-zinc-200 rounded-2xl shadow-xs hover:border-emerald-200 hover:bg-emerald-50/10 transition-colors duration-250">
                            <div class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest font-space mb-1">Kerajaan</div>
                            <div class="text-sm font-bold text-zinc-800 font-inter">{{ $koleksi->kerajaan ?: 'Plantae' }}</div>
                        </div>

                        <div class="p-4 bg-white border border-zinc-200 rounded-2xl shadow-xs hover:border-emerald-200 hover:bg-emerald-50/10 transition-colors duration-250">
                            <div class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest font-space mb-1">Divisi</div>
                            <div class="text-sm font-bold text-zinc-800 font-inter">{{ $koleksi->divisi ?: '-' }}</div>
                        </div>

                        <div class="p-4 bg-white border border-zinc-200 rounded-2xl shadow-xs hover:border-emerald-200 hover:bg-emerald-50/10 transition-colors duration-250">
                            <div class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest font-space mb-1">Kelas</div>
                            <div class="text-sm font-bold text-zinc-800 font-inter">{{ $koleksi->kelas ?: '-' }}</div>
                        </div>

                        <div class="p-4 bg-white border border-zinc-200 rounded-2xl shadow-xs hover:border-emerald-200 hover:bg-emerald-50/10 transition-colors duration-250">
                            <div class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest font-space mb-1">Order / Ordo</div>
                            <div class="text-sm font-bold text-zinc-800 font-inter">{{ $koleksi->order ?: '-' }}</div>
                        </div>

                        <div class="p-4 bg-white border border-zinc-200 rounded-2xl shadow-xs hover:border-emerald-200 hover:bg-emerald-50/10 transition-colors duration-250">
                            <div class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest font-space mb-1">Famili</div>
                            <div class="text-sm font-bold text-zinc-800 font-inter">{{ $koleksi->famili ?: '-' }}</div>
                        </div>

                        <div class="p-4 bg-white border border-zinc-200 rounded-2xl shadow-xs hover:border-emerald-200 hover:bg-emerald-50/10 transition-colors duration-250">
                            <div class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest font-space mb-1">Genus</div>
                            <div class="text-sm font-bold text-zinc-800 font-inter italic">{{ $koleksi->genus ?: '-' }}</div>
                        </div>

                        <div class="p-4 bg-white border border-zinc-200 rounded-2xl shadow-xs hover:border-emerald-200 hover:bg-emerald-50/10 transition-colors duration-250">
                            <div class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest font-space mb-1">Spesies</div>
                            <div class="text-sm font-bold text-zinc-800 font-inter italic">{{ $species_cleaned ?: '-' }}</div>
                        </div>

                        @if($koleksi->otoritas_1)
                            <div class="p-4 bg-white border border-zinc-200 rounded-2xl shadow-xs hover:border-emerald-200 hover:bg-emerald-50/10 transition-colors duration-250">
                                <div class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest font-space mb-1">Otoritas 1</div>
                                <div class="text-sm font-bold text-zinc-800 font-inter">{{ $koleksi->otoritas_1 }}</div>
                            </div>
                        @endif

                        @if($koleksi->otoritas_2)
                            <div class="p-4 bg-white border border-zinc-200 rounded-2xl shadow-xs hover:border-emerald-200 hover:bg-emerald-50/10 transition-colors duration-250">
                                <div class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest font-space mb-1">Otoritas 2</div>
                                <div class="text-sm font-bold text-zinc-800 font-inter">{{ $koleksi->otoritas_2 }}</div>
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
