@extends('layouts.landing')

@section('title', $koleksi->title)

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <style>
        #map { height: 400px; width: 100%; z-index: 10; border-radius: 1.5rem; }
        .leaflet-popup-content-wrapper { padding: 0; overflow: hidden; border-radius: 16px; border: none; box-shadow: 0 10px 15px -3px rgba(0,0,0,.1), 0 4px 6px -4px rgba(0,0,0,.1); background: white; }
        .leaflet-popup-content { margin: 0 !important; width: 280px !important; line-height: 1.5; }
        .leaflet-popup-tip { background: white; box-shadow: 0 10px 15px -3px rgba(0,0,0,.1); }
        .leaflet-container a.leaflet-popup-close-button { top: 10px; right: 10px; width: 30px; height: 30px; border-radius: 50%; background: rgba(255, 255, 255, 0.9); color: #3f3f46; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); display: flex; align-items: center; justify-content: center; font-size: 16px; text-decoration: none; transition: all 0.2s; }
        .leaflet-container a.leaflet-popup-close-button:hover { background: #fff; color: #ef4444; }
    </style>
@endpush

@section('content')
    <div class="max-w-4xl mx-auto px-6">
        
        <div class="mb-8">
            <a href="{{ route('koleksi') }}" class="inline-flex items-center text-sm font-semibold text-zinc-600 hover:text-blue-600">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali ke Koleksi
            </a>
        </div>

        <div class="text-center mb-10">
            <h1 class="text-4xl md:text-5xl font-bold tracking-tight mb-4 text-zinc-900 font-heading">
                {{ $koleksi->title }}
            </h1>
        </div>

        <div class="relative overflow-hidden rounded-3xl bg-zinc-100 h-96 mb-10 shadow-lg">
            @if ($koleksi->photo)
                <img src="{{ Storage::url($koleksi->photo) }}" alt="{{ $koleksi->title }}" class="h-full w-full object-cover">
            @else
                <div class="h-full w-full bg-zinc-200 flex items-center justify-center">
                    <svg class="w-16 h-16 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l-1.586-1.586a2 2 0 00-2.828 0L6 14m6-6l.01.01"></path></svg>
                </div>
            @endif
        </div>

        <div class="prose prose-lg max-w-none mx-auto text-zinc-700 leading-relaxed font-light">
            {!! nl2br(e($koleksi->description)) !!}
        </div>

        @if($koleksi->locations->isNotEmpty())
            <div class="mt-16">
                <h2 class="text-2xl font-bold font-heading text-zinc-900 mb-6">Lokasi di Peta</h2>
            
                <div class="mt-4 space-y-2">
                    <p class="text-sm text-zinc-600">Koleksi ini dapat ditemukan di lokasi berikut:</p>
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($koleksi->locations as $location)
                            <li class="text-zinc-700 font-medium">{{ $location->name }}</li>
                        @endforeach
                    </ul>
                    <div class="mt-4">
                        <a href="{{ route('koleksi.peta', $koleksi) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition-all">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                            Lihat di Peta Penuh
                        </a>
                    </div>
                </div>
            </div>
        @endif

    </div>

@endsection
