@extends('layouts.landing')

@section('title', 'Detail Lokasi - ' . $map->name)

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <a href="{{ route('peta') }}?focus={{ $map->id }}" class="inline-flex items-center text-sm font-bold text-zinc-500 hover:text-zinc-900 transition-colors mb-8 group font-space">
        <div class="w-8 h-8 rounded-full bg-white border border-zinc-200 flex items-center justify-center mr-2 shadow-sm group-hover:bg-zinc-100 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
        </div>
        Kembali ke Peta
    </a>

    <div class="bg-white border border-zinc-200/80 rounded-[2.5rem] shadow-xl shadow-zinc-200/30 p-6 sm:p-8">
        <div class="flex flex-col md:flex-row gap-8 items-start">

            <div class="w-full md:w-80 shrink-0">
                <div class="aspect-square w-full bg-zinc-50 border border-zinc-200/50 rounded-3xl overflow-hidden shadow-inner flex items-center justify-center relative">
                    @if($map->photo)
                        <img src="{{ Storage::url($map->photo) }}" alt="{{ $map->name }}" class="w-full h-full object-cover">
                    @else
                        <div class="flex flex-col items-center justify-center p-8 text-zinc-400">
                            <svg class="w-12 h-12 text-zinc-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span class="text-[10px] font-bold uppercase tracking-wider font-space text-zinc-400">Tidak Ada Foto</span>
                        </div>
                    @endif
                </div>
            </div>

            <div class="flex-1 flex flex-col justify-between self-stretch">
                <div>

                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider mb-4"
                          style="color: {{ $map->color }}; border: 1px solid {{ $map->color }}40; background-color: {{ $map->color }}15;">
                        {{ Str::of($map->type)->replace('_', ' ')->title() }}
                    </span>

                    <h1 class="font-heading text-2xl sm:text-3xl font-bold text-zinc-900 tracking-tight leading-tight mb-4">
                        {{ $map->name }}
                    </h1>

                    <div class="text-zinc-650 leading-relaxed font-light font-inter mb-6 space-y-4">
                        @if($map->description)
                            <p class="whitespace-pre-line text-sm sm:text-base text-zinc-650 leading-relaxed">{{ $map->description }}</p>
                        @else
                            <p class="italic text-sm text-zinc-400">Tidak ada deskripsi tambahan untuk lokasi ini.</p>
                        @endif
                    </div>
                </div>

                <div class="pt-6 border-t border-zinc-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex flex-col">
                        <span class="text-[10px] font-bold text-zinc-400 font-space uppercase tracking-wider">Koordinat Geografis</span>
                        <span class="text-sm font-mono text-zinc-600 font-semibold mt-0.5">
                            @if($map->geometry_type === 'point')
                                {{ number_format($map->latitude, 6) }}, {{ number_format($map->longitude, 6) }}
                            @else
                                Area Kawasan ({{ Str::title($map->geometry_type) }})
                            @endif
                        </span>
                    </div>

                    <a href="{{ route('peta') }}?focus={{ $map->id }}"
                       class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold font-space uppercase tracking-wider rounded-xl transition duration-200 shadow-md shadow-emerald-600/10">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        Tunjukkan di Peta
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
