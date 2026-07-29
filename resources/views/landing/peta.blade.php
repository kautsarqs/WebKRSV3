@extends('layouts.landing')

@section('title', 'Peta Kawasan - Kebun Raya Sambas')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<style>
    #map { height: 600px; width: 100%; z-index: 10; border-radius: 1.75rem; }

    .leaflet-container:focus,
    .leaflet-container *:focus,
    .leaflet-interactive:focus,
    path.leaflet-interactive:focus,
    svg.leaflet-zoom-animated path:focus {
        outline: none !important;
        box-shadow: none !important;
    }

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

    .user-location-marker {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .blue-dot {
        width: 14px;
        height: 14px;
        background-color: #2563eb;
        border-radius: 50%;
        border: 2.5px solid #ffffff;
        box-shadow: 0 2px 8px rgba(37, 99, 235, 0.5);
        z-index: 2;
    }
    .pulse-ring {
        position: absolute;
        width: 34px;
        height: 34px;
        border: 3px solid #3b82f6;
        border-radius: 50%;
        background-color: rgba(59, 130, 246, 0.25);
        animation: pulse-animation 1.8s infinite ease-out;
        z-index: 1;
    }
    @keyframes pulse-animation {
        0% {
            transform: scale(0.4);
            opacity: 1;
        }
        100% {
            transform: scale(2.4);
            opacity: 0;
        }
    }
</style>
@endpush

@section('content')
<div class="max-w-7xl mx-auto px-6">
    <div class="text-center mb-10">
        <h1 class="font-heading text-4xl font-bold mb-2">Peta Digital Kawasan</h1>
        <p class="text-zinc-650 font-normal">Sistem Informasi Geografis (WebGIS) persebaran koleksi tumbuhan.</p>
    </div>

    <div class="p-2.5 bg-white border border-zinc-200 rounded-[2.5rem] shadow-2xl shadow-zinc-250/30">
        <div x-data="{
                 isNavigating: false,
                 destinationName: '',
                 remainingDistance: '---',
                 remainingTime: '---',
                 showMultiRouteModal: false,
                 waypoints: [],
                 totalDistanceStr: '0 m',
                 totalTimeStr: '0 mnt',
                 legsDetails: [],
                 selectedMarkerToAdd: '',
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
             @update-multi-route.window="
                 waypoints = JSON.parse(JSON.stringify($event.detail.waypoints || []));
                 totalDistanceStr = $event.detail.totalDistance || '0 m';
                 totalTimeStr = $event.detail.totalTime || '0 mnt';
                 legsDetails = JSON.parse(JSON.stringify($event.detail.legs || []));
             "
             class="map-wrapper">
            <div id="map" class="bg-zinc-50"></div>

            <div x-show="isNavigating"
                 x-transition:enter="transition ease-out duration-350"
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
                        <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-lg sm:rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center shadow-xs shrink-0">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-[7px] sm:text-[8px] font-bold text-zinc-450 uppercase tracking-wider">Waktu Tempuh</span>
                            <span class="font-extrabold text-zinc-900 text-xs sm:text-sm" x-text="remainingTime">---</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Floating Multi-Route Active Bar --}}
            <div x-show="waypoints.length >= 2 && !isNavigating"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-8"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 translate-y-8"
                 class="absolute bottom-4 left-1/2 -translate-x-1/2 z-[1001] w-[90%] max-w-md bg-white/95 backdrop-blur-md border border-zinc-200/80 rounded-2xl p-3 shadow-2xl flex items-center justify-between gap-3 text-zinc-900">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-9 h-9 rounded-xl bg-emerald-600 text-white flex items-center justify-center font-bold text-xs shrink-0 shadow-sm">
                        🗺️
                    </div>
                    <div class="min-w-0">
                        <div class="text-[9px] font-bold text-emerald-600 uppercase tracking-widest flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-ping"></span> Rute Multi-Point (<span x-text="waypoints.length"></span> Titik)
                        </div>
                        <div class="text-xs font-extrabold text-zinc-900 truncate">
                            <span x-text="totalDistanceStr"></span> &bull; <span x-text="totalTimeStr"></span>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-1.5 shrink-0">
                    <button @click="showMultiRouteModal = true" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-[10px] font-bold transition-all shadow-xs cursor-pointer">
                        Rincian
                    </button>
                    <button @click="window.clearMultiRoute()" class="p-1.5 bg-zinc-100 hover:bg-red-50 text-zinc-400 hover:text-red-600 rounded-xl transition-all cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            </div>

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

                <button @click="showSettingsModal = true; $dispatch('close-layer-dropdown')"
                        class="px-3 py-2 bg-white/90 backdrop-blur-md border border-zinc-200/50 rounded-2xl shadow-md flex items-center gap-1.5 text-[10px] font-bold text-zinc-700 hover:bg-white transition-all cursor-pointer select-none">
                    <svg class="w-3.5 h-3.5 text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span>Pengaturan Peta</span>
                </button>

                <button @click="showMultiRouteModal = true; $dispatch('close-layer-dropdown')"
                        class="px-3 py-2 bg-emerald-600 hover:bg-emerald-700 text-white border border-emerald-500/80 rounded-2xl shadow-md flex items-center gap-1.5 text-[10px] font-bold transition-all cursor-pointer select-none">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                    </svg>
                    <span>Rencana Rute</span>
                    <span x-show="waypoints.length > 0" class="px-1.5 py-0.2 bg-white text-emerald-800 rounded-full text-[9px] font-extrabold" x-text="waypoints.length"></span>
                </button>

                {{-- Modal Perencanaan Rute Multi-Point --}}
                <template x-teleport="body">
                <div x-show="showMultiRouteModal"
                     class="fixed inset-0 z-[9999] flex items-center justify-center p-4 sm:p-6"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     x-cloak>

                    <div class="fixed inset-0 bg-black/60 backdrop-blur-xs" @click="showMultiRouteModal = false"></div>

                    <div class="relative w-full max-w-lg bg-white rounded-3xl shadow-2xl border border-zinc-200/50 p-6 flex flex-col gap-4 text-zinc-800 max-h-[90vh] overflow-y-auto">
                        <div class="flex items-center justify-between border-b border-zinc-100 pb-3">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-sm">
                                    📍
                                </div>
                                <div>
                                    <h3 class="font-heading font-bold text-base text-zinc-900">Perencanaan Rute (A ➔ B ➔ C)</h3>
                                    <p class="text-[11px] text-zinc-500">Pilih beberapa titik lokasi secara berurutan untuk melihat rute & waktu jalan kaki.</p>
                                </div>
                            </div>
                            <button @click="showMultiRouteModal = false" class="text-zinc-400 hover:text-zinc-600 p-1 rounded-lg hover:bg-zinc-100 cursor-pointer">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>

                        {{-- Form Tambah Waypoint --}}
                        <div class="flex items-center gap-2">
                            <select id="select-waypoint-dropdown" x-model="selectedMarkerToAdd" class="flex-1 bg-zinc-50 border border-zinc-200 rounded-xl px-3 py-2 text-xs font-medium focus:ring-emerald-500 focus:border-emerald-500">
                                <option value="">-- Pilih Titik Kunjungan --</option>
                                <option value="gps">📍 Lokasi Saya saat ini (GPS)</option>
                                @foreach($markers->filter(fn($m) => $m->geometry_type === 'point') as $m)
                                    <option value="{{ $m->id }}" data-name="{{ addslashes($m->name) }}" data-lat="{{ $m->latitude }}" data-lng="{{ $m->longitude }}">{{ $m->name }} ({{ Str::of($m->type)->replace('_', ' ')->title() }})</option>
                                @endforeach
                            </select>
                            <button @click="if (selectedMarkerToAdd) { window.addWaypointFromDropdown(selectedMarkerToAdd); selectedMarkerToAdd = ''; }"
                                    class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition-all shadow-sm shrink-0 cursor-pointer">
                                + Tambah
                            </button>
                        </div>

                        {{-- Waypoints List --}}
                        <div class="flex flex-col gap-2 my-1">
                            <template x-if="waypoints.length === 0">
                                <div class="text-center py-8 bg-zinc-50 border border-dashed border-zinc-200 rounded-2xl">
                                    <p class="text-xs text-zinc-400 font-medium">Belum ada titik kunjungan dipilih.</p>
                                    <p class="text-[10px] text-zinc-400 mt-1">Pilih titik dari dropdown di atas atau klik tombol <strong>"+ RUTE"</strong> pada marker di peta.</p>
                                </div>
                            </template>

                            <template x-for="(wp, index) in waypoints" :key="index">
                                <div class="flex items-center justify-between bg-zinc-50 border border-zinc-200/80 p-3 rounded-2xl">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <span class="w-6 h-6 rounded-full bg-emerald-600 text-white text-xs font-extrabold flex items-center justify-center shrink-0 shadow-xs" x-text="index + 1"></span>
                                        <span class="text-xs font-bold text-zinc-900 truncate" x-text="wp.name"></span>
                                    </div>
                                    <div class="flex items-center gap-1 shrink-0">
                                        <button @click="window.moveMultiWaypoint(index, -1)" :disabled="index === 0" class="p-1 text-zinc-400 hover:text-zinc-700 disabled:opacity-30 disabled:cursor-not-allowed">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 15l7-7 7 7"></path></svg>
                                        </button>
                                        <button @click="window.moveMultiWaypoint(index, 1)" :disabled="index === waypoints.length - 1" class="p-1 text-zinc-400 hover:text-zinc-700 disabled:opacity-30 disabled:cursor-not-allowed">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path></svg>
                                        </button>
                                        <button @click="window.removeMultiWaypoint(index)" class="p-1 text-red-400 hover:text-red-600">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        </button>
                                    </div>
                                </div>
                            </template>
                        </div>

                        {{-- Action Buttons --}}
                        <div x-show="waypoints.length > 0" class="flex items-center justify-between gap-2 border-t border-zinc-100 pt-3">
                            <button @click="window.reverseMultiWaypoints()" class="px-3 py-1.5 bg-zinc-100 hover:bg-zinc-200 text-zinc-700 text-[11px] font-bold rounded-xl transition-all cursor-pointer flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path></svg>
                                Balik Urutan Rute
                            </button>
                            <button @click="window.clearMultiRoute()" class="px-3 py-1.5 bg-red-50 hover:bg-red-100 text-red-600 text-[11px] font-bold rounded-xl transition-all cursor-pointer">
                                Hapus Semua
                            </button>
                        </div>

                        {{-- Statistics Card --}}
                        <div x-show="waypoints.length >= 2" class="bg-gradient-to-br from-emerald-600 to-teal-700 rounded-2xl p-4 text-white shadow-lg flex flex-col gap-3">
                            <div class="grid grid-cols-2 gap-3">
                                <div class="bg-white/10 backdrop-blur-xs rounded-xl p-2.5">
                                    <span class="text-[9px] uppercase tracking-wider text-emerald-100 font-bold">Total Jarak</span>
                                    <p class="text-base font-extrabold" x-text="totalDistanceStr">0 m</p>
                                </div>
                                <div class="bg-white/10 backdrop-blur-xs rounded-xl p-2.5">
                                    <span class="text-[9px] uppercase tracking-wider text-emerald-100 font-bold">Estimasi Jalan Kaki</span>
                                    <p class="text-base font-extrabold" x-text="totalTimeStr">0 mnt</p>
                                </div>
                            </div>

                            {{-- Leg Details --}}
                            <div class="border-t border-white/20 pt-2 flex flex-col gap-1.5 text-xs">
                                <span class="text-[10px] font-bold text-emerald-100 uppercase tracking-wider">Rincian Per Segmen:</span>
                                <template x-for="(leg, idx) in legsDetails" :key="idx">
                                    <div class="flex items-center justify-between text-[11px] bg-black/15 px-2.5 py-1.5 rounded-lg">
                                        <span class="truncate max-w-[220px]" x-text="(idx + 1) + '. ' + leg.from + ' ➔ ' + leg.to"></span>
                                        <span class="font-bold shrink-0 ml-2" x-text="leg.distanceStr + ' (' + leg.timeStr + ')'"></span>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
                </template>

                <template x-teleport="body">
                <div x-show="showSettingsModal"
                     class="fixed inset-0 z-[9999] flex items-center justify-center p-4 sm:p-6"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     x-cloak>

                    <div class="fixed inset-0 bg-black/60 backdrop-blur-xs" @click="showSettingsModal = false"></div>

                    <div class="relative w-full max-w-sm bg-white/95 backdrop-blur-md rounded-3xl shadow-2xl border border-zinc-200/50 p-6 flex flex-col gap-4 text-zinc-800 transform transition-all duration-300"
                         x-show="showSettingsModal"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                         x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">

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

                        <div class="flex flex-col gap-4 py-2">

                            <div class="flex flex-col gap-1.5">
                                <span class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest pl-0.5">Status GPS</span>
                                <div id="gps-status" class="bg-zinc-50 border border-zinc-100 rounded-2xl p-3.5 flex items-center justify-between">
                                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[9px] font-semibold bg-zinc-100 text-zinc-500">
                                        GPS Menghubungkan...
                                    </span>
                                </div>
                            </div>

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
                </template>

                <template x-teleport="body">
                <div x-show="showNotification"
                     class="fixed inset-0 z-[10005] flex items-center justify-center p-4"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     x-cloak>

                    <div class="fixed inset-0 bg-black/60 backdrop-blur-xs" @click="if (notificationType !== 'confirm') showNotification = false"></div>

                    <div class="relative w-full max-w-xs bg-white rounded-3xl shadow-2xl p-6 flex flex-col gap-4 text-center text-zinc-800"
                         x-show="showNotification"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95">

                        <div class="mx-auto w-12 h-12 rounded-full flex items-center justify-center shadow-inner"
                             :class="{
                                 'bg-emerald-50 text-emerald-600': notificationType === 'success',
                                 'bg-red-50 text-red-600': notificationType === 'error',
                                 'bg-amber-50 text-amber-600': notificationType === 'confirm' || notificationType === 'warning'
                             }">
                            <template x-if="notificationType === 'success'">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                            </template>
                            <template x-if="notificationType === 'error'">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </template>
                            <template x-if="notificationType === 'confirm' || notificationType === 'warning'">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            </template>
                        </div>

                        <div>
                            <h4 class="font-heading font-bold text-sm text-zinc-900" x-text="notificationTitle"></h4>
                            <p class="text-xs text-zinc-500 font-inter mt-1.5 leading-relaxed" x-text="notificationMessage"></p>
                        </div>

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
                </template>
            </div>

            <div class="map-controls" x-data="{ open: false, selected: 'road' }" @click.away="open = false" @close-layer-dropdown.window="open = false">
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
                        <button type="button" @click="selected = 'road'; switchLayer('road'); open = false" class="w-full flex items-center gap-3 px-4 py-2 text-xs font-bold text-zinc-700 hover:bg-emerald-50 hover:text-emerald-700 transition rounded-xl cursor-pointer">Lapisan Default</button>
                        <button type="button" @click="selected = 'satellite'; switchLayer('satellite'); open = false" class="w-full flex items-center gap-3 px-4 py-2 text-xs font-bold text-zinc-700 hover:bg-emerald-50 hover:text-emerald-700 transition rounded-xl cursor-pointer">Lapisan Satelit</button>
                        <button type="button" @click="selected = 'terrain'; switchLayer('terrain'); open = false" class="w-full flex items-center gap-3 px-4 py-2 text-xs font-bold text-zinc-700 hover:bg-emerald-50 hover:text-emerald-700 transition rounded-xl cursor-pointer">Lapisan Medan</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-12 bg-zinc-50 border border-zinc-200 rounded-3xl p-6 shadow-sm">
        <h4 class="font-heading text-lg font-bold text-zinc-900 mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg>
            Legenda Peta
        </h4>
        <div class="flex flex-wrap items-center gap-3">
            @php
                $markerTypes = $markers->groupBy('type');
            @endphp

            @foreach($markerTypes as $type => $markersOfType)
                @php
                    $color = $markersOfType->first()->color ?? '#3b82f6';
                    $firstGeomType = $markersOfType->first()->geometry_type ?? 'point';
                    $typeNormIdx = strtolower(str_replace([' ', '-', '_'], '', $type));
                    $isBatas = str_contains($typeNormIdx, 'batas');
                    $isJalanUtama = str_contains($typeNormIdx, 'jalanutama');
                    $isJalanLain  = str_contains($typeNormIdx, 'jalanlain');
                    $lineColor = $isJalanUtama ? '#b8b8b8' : ($isJalanLain ? '#c8c8c8' : $color);
                    $lineWidth = $isJalanUtama ? '5.5' : ($isJalanLain ? '2.5' : '3.0');
                @endphp
                <div class="inline-flex items-center gap-2.5 px-3.5 py-2 rounded-2xl text-xs font-bold bg-white border border-zinc-200/80 shadow-xs">
                    @if($firstGeomType === 'point')
                        {{-- Point: titik bundar penanda lokasi --}}
                        <span class="w-3.5 h-3.5 rounded-full shrink-0 shadow-xs" style="background-color: {{ $color }}; border: 2px solid #ffffff; box-shadow: 0 0 0 1px rgba(0,0,0,0.15), 0 2px 4px rgba(0,0,0,0.2);"></span>
                    @elseif($firstGeomType === 'polygon')
                        {{-- Polygon: persis dengan simbol Kelola Peta --}}
                        <svg class="w-8 h-6 shrink-0" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 42 C10 28, 16 10, 32 9 C48 8, 56 22, 54 38 C52 50, 40 54, 32 54 C22 54, 14 52, 12 42 Z"
                                  stroke="{{ $color }}" stroke-width="3.5"
                                  stroke-dasharray="{{ $isBatas ? '8 5' : 'none' }}" stroke-linecap="round" stroke-linejoin="round"
                                  fill="{{ $color }}" fill-opacity="{{ $isBatas ? '0.08' : '0.18' }}"/>
                        </svg>
                    @else
                        {{-- LineString / Polyline: persis dengan simbol Kelola Peta --}}
                        <svg class="w-8 h-6 shrink-0" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M8 50 C16 36, 20 18, 32 28 C42 37, 46 14, 56 12"
                                  stroke="{{ $lineColor }}"
                                  stroke-width="{{ $lineWidth }}"
                                  stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    @endif
                    <span class="text-zinc-800 font-space">{{ Str::of($type)->replace('_', ' ')->title() }}</span>
                    <span class="bg-zinc-100 text-zinc-500 rounded-full px-2 py-0.5 text-[10px] font-extrabold">{{ $markersOfType->count() }}</span>
                </div>
            @endforeach

        </div>
    </div>

    <div class="mt-10 mb-16">
        <h4 class="font-heading text-xl font-bold text-zinc-900 mb-6 flex items-center gap-2">
            <svg class="w-5.5 h-5.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            Daftar Detail Lokasi & Area
        </h4>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($markers->filter(fn($m) => $m->geometry_type === 'point') as $marker)
                @php
                    $color = $marker->color ?? '#3b82f6';
                    $typeLabel = Str::of($marker->type)->replace('_', ' ')->title();
                @endphp
                <div id="marker-detail-{{ $marker->id }}"
                     class="bg-white border border-zinc-200/80 rounded-2xl p-4 shadow-xs hover:shadow-md transition-all duration-300 flex gap-4 items-center scroll-mt-24">

                    @if($marker->photo)
                        <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-xl overflow-hidden shrink-0 bg-zinc-100">
                            <img src="{{ Storage::url($marker->photo) }}" alt="{{ $marker->name }}" class="w-full h-full object-cover">
                        </div>
                    @else
                        <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-xl shrink-0 bg-zinc-50 border border-zinc-200/50 flex items-center justify-center text-zinc-400">
                            <svg class="w-8 h-8 text-zinc-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                    @endif

                    <div class="flex-1 min-w-0 flex flex-col justify-between h-full">
                        <div>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-bold uppercase tracking-wider mb-1"
                                  style="color: {{ $color }}; border: 1px solid {{ $color }}40; background-color: {{ $color }}15;">
                                {{ $typeLabel }}
                            </span>
                            <h5 class="font-heading text-sm font-bold text-zinc-900 truncate mb-1" title="{{ $marker->name }}">{{ $marker->name }}</h5>
                            <p class="text-xs text-zinc-500 font-light font-inter line-clamp-2 leading-relaxed mb-2">
                                {{ $marker->description ?? 'Tidak ada deskripsi tambahan.' }}
                            </p>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <a href="{{ route('peta.show', $marker->id) }}"
                               class="px-2 py-1 bg-zinc-100 hover:bg-zinc-200 text-zinc-700 text-[9px] font-bold rounded-lg transition-colors">
                                DETAIL
                            </a>
                            <button onclick="focusOnMarker({{ $marker->id }})"
                                    class="px-2 py-1 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 text-[9px] font-bold rounded-lg transition-colors cursor-pointer">
                                PETA
                            </button>
                            <button onclick="window.addWaypointFromDropdown({{ $marker->id }})"
                                    class="px-2 py-1 bg-zinc-900 hover:bg-zinc-800 text-white text-[9px] font-bold rounded-lg transition-colors cursor-pointer"
                                    title="Tambah ke Rute Multi-Point">
                                + RUTE
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/localforage/1.10.0/localforage.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {

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

    var bounds = L.latLngBounds([[-3.0, 108.0], [2.5, 114.5]]);
    var map = L.map('map', {
        zoomControl: false,
        attributionControl: false,
        maxBounds: bounds,
        maxBoundsViscosity: 0.8,
        minZoom: 8
    }).setView([1.271885, 109.477339], 13.8);

    // --- Custom Modern Glassmorphic Zoom & Scale Control Widget ---
    var CustomMapControls = L.Control.extend({
        options: { position: 'bottomright' },
        onAdd: function(map) {
            var container = L.DomUtil.create('div', 'flex items-end gap-2 mb-3 mr-3 z-[1000]');
            container.innerHTML = `
                <div class="px-3.5 py-2 bg-white/90 backdrop-blur-md border border-zinc-200/60 rounded-2xl shadow-lg flex items-center gap-2 text-zinc-800 text-[10px] font-bold select-none cursor-default">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse shrink-0"></span>
                    <span class="text-zinc-400 uppercase tracking-widest text-[8px] font-bold">Skala</span>
                    <span id="custom-scale-text" class="text-zinc-900 font-mono font-extrabold text-xs">1 km</span>
                </div>
                <div class="bg-white/90 backdrop-blur-md border border-zinc-200/60 rounded-2xl shadow-lg flex flex-col overflow-hidden">
                    <button id="btn-zoom-in" type="button" class="w-8 h-8 flex items-center justify-center text-zinc-700 hover:bg-emerald-50 hover:text-emerald-600 transition-colors font-bold text-base border-b border-zinc-100/80 cursor-pointer" title="Perbesar Peta">+</button>
                    <button id="btn-zoom-out" type="button" class="w-8 h-8 flex items-center justify-center text-zinc-700 hover:bg-emerald-50 hover:text-emerald-600 transition-colors font-bold text-base cursor-pointer" title="Perkecil Peta">&minus;</button>
                </div>
            `;

            L.DomEvent.disableClickPropagation(container);
            L.DomEvent.disableScrollPropagation(container);

            setTimeout(function() {
                document.getElementById('btn-zoom-in')?.addEventListener('click', function() { map.zoomIn(); });
                document.getElementById('btn-zoom-out')?.addEventListener('click', function() { map.zoomOut(); });
            }, 100);

            return container;
        }
    });

    map.addControl(new CustomMapControls());

    function updateScaleDisplay() {
        var center = map.getCenter();
        var zoom = map.getZoom();
        var metersPerPx = 156543.03392 * Math.cos(center.lat * Math.PI / 180) / Math.pow(2, zoom);
        var rawMeters = metersPerPx * 90;

        var niceText = '1 km';
        if (rawMeters >= 2500) {
            niceText = Math.round(rawMeters / 1000) + ' km';
        } else if (rawMeters >= 750) {
            niceText = '1 km';
        } else if (rawMeters >= 350) {
            niceText = '500 m';
        } else if (rawMeters >= 180) {
            niceText = '250 m';
        } else if (rawMeters >= 90) {
            niceText = '100 m';
        } else if (rawMeters >= 40) {
            niceText = '50 m';
        } else if (rawMeters >= 15) {
            niceText = '20 m';
        } else {
            niceText = Math.max(5, Math.round(rawMeters)) + ' m';
        }

        var scaleEl = document.getElementById('custom-scale-text');
        if (scaleEl) scaleEl.textContent = niceText;
    }

    map.on('zoom zoomend move moveend viewreset zoomlevelschange', updateScaleDisplay);
    setTimeout(updateScaleDisplay, 100);

    var roadLayer = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', { maxNativeZoom: 19, maxZoom: 20, attribution: '&copy; OpenStreetMap', crossOrigin: true });
    var satelliteLayer = L.tileLayer.offline('https://mt1.google.com/vt/lyrs=s&x={x}&y={y}&z={z}', { maxNativeZoom: 20, maxZoom: 20, attribution: '&copy; Google Satellite', crossOrigin: true });
    var terrainLayer = L.tileLayer('https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png', {
        maxNativeZoom: 17,
        maxZoom: 17,
        attribution: 'Map data: &copy; OpenStreetMap contributors',
        subdomains: 'abc',
        crossOrigin: true
    });

    var currentLayer = roadLayer.addTo(map);

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
        color: '#3b82f6',
        weight: 4.5,
        opacity: 0.9,
        dashArray: '5, 8'
    }).addTo(map);

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

                userPathCoords.push(latlng);
                userPolyline.setLatLngs(userPathCoords);

                if (window.currentNavTarget) {
                    window.updateNavigationRouting();
                }

                if (firstLocationCheck) {
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

    map.on('click', function(e) {
        if (!userMarker) {
            var latlng = e.latlng;
            var userIcon = L.divIcon({
                className: 'user-location-marker',
                html: '<div class="pulse-ring"></div><div class="blue-dot"></div>',
                iconSize: [20, 20],
                iconAnchor: [10, 10]
            });
            userMarker = L.marker(latlng, { icon: userIcon }).addTo(map);
            userAccuracyCircle = L.circle(latlng, {
                radius: 15,
                color: '#3b82f6',
                fillColor: '#3b82f6',
                fillOpacity: 0.15,
                weight: 1
            }).addTo(map);

            var statusEl = document.getElementById('gps-status');
            if (statusEl) {
                statusEl.innerHTML = `
                    <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-md text-[9px] font-bold bg-blue-50 text-blue-700 border border-blue-100">
                        Lokasi Manual (Tap Peta)
                    </span>
                `;
            }
            if (window.multiWaypoints && window.multiWaypoints.length > 0) {
                window.updateMultiRoute();
            }
        }
    });

    window.downloadVisibleArea = function() {

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

    // --- Auto-download Kebun Raya Sambas tiles on page load (background) ---
    function autoDownloadKRS() {
        if (!navigator.onLine) return;

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

                        });
                }
            });
        });
    }

    setTimeout(autoDownloadKRS, 3000);

    function getPolygonVisualCenter(coords) {
        if (!coords || coords.length === 0) return null;
        var flat = (Array.isArray(coords[0]) && Array.isArray(coords[0][0])) ? coords[0] : coords;
        var area = 0, cx = 0, cy = 0;
        var n = flat.length;
        for (var i = 0; i < n; i++) {
            var j = (i + 1) % n;
            var p1 = flat[i], p2 = flat[j];
            var x1 = parseFloat(p1[0]), y1 = parseFloat(p1[1]);
            var x2 = parseFloat(p2[0]), y2 = parseFloat(p2[1]);
            var f = (x1 * y2 - x2 * y1);
            area += f;
            cx += (x1 + x2) * f;
            cy += (y1 + y2) * f;
        }
        area *= 0.5;
        if (Math.abs(area) < 1e-9) {
            var sumLat = 0, sumLng = 0;
            flat.forEach(function(p) { sumLat += parseFloat(p[0]); sumLng += parseFloat(p[1]); });
            return L.latLng(sumLat / flat.length, sumLng / flat.length);
        }
        cx /= (6 * area);
        cy /= (6 * area);
        return L.latLng(cx, cy);
    }

    var markers = @json($markers);
    var storageBase = "{{ \Illuminate\Support\Facades\Storage::url('') }}".replace(/\/$/, '');

    var roadPolylines = [];
    markers.forEach(function(marker) {
        if ((marker.geometry_type === 'polyline' || marker.geometry_type === 'linestring') && marker.geojson) {
            var nameLower = (marker.name || '').toLowerCase();
            var typeLower = (marker.type || '').toLowerCase().replace(/[\s\-]+/g, '_');

            if (nameLower.includes('batas') || typeLower.includes('batas')) {
                return;
            }

            if (typeLower !== 'jalan_utama' && typeLower !== 'jalan_lain') {
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
                        type: typeLower,
                        path: path
                    });
                }
            } catch(e) {
                console.error('Gagal memproses jalan kustom untuk rute:', e);
            }
        }
    });
    console.log('Road polylines extracted for routing:', roadPolylines.length, roadPolylines.map(function(r){ return r.type; }));

    // --- Logika Matematika Navigasi (Haversine & Vector Snapping) ---
    function getHaversineDistance(p1, p2) {
        var R = 6371000;
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

    function buildGraphAndRunDijkstra(startLatLng, endLatLng) {
        console.log("Dijkstra started. start:", startLatLng, "end:", endLatLng);
        console.log("Available roadPolylines:", roadPolylines);

        var snapS = findClosestSnapPoint(startLatLng);
        var snapE = findClosestSnapPoint(endLatLng);
        console.log("snapS:", snapS, "snapE:", snapE);

        if (!snapS.point || !snapE.point || roadPolylines.length === 0) {
            console.log("No snap points or roadPolylines empty. Snapping failed.");
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

        // Semua vertex (termasuk endpoint) hanya dihubungkan jika jarak < 30m.
        roadPolylines.forEach(function(polyline, i) {
            var pathLen = polyline.path.length;
            polyline.path.forEach(function(v, vIdx) {
                var closestDist = Infinity;
                var closestPoint = null;
                var closestSegment = null;

                roadPolylines.forEach(function(otherPolyline, j) {
                    if (i === j) return;
                    for (var k = 0; k < otherPolyline.path.length - 1; k++) {
                        var a = otherPolyline.path[k];
                        var b = otherPolyline.path[k+1];
                        var projection = projectPointOnSegment(v, a, b);
                        if (projection.distance < closestDist) {
                            closestDist = projection.distance;
                            closestPoint = projection.point;
                            closestSegment = { a: a, b: b };
                        }
                    }
                });

                // Hubungkan vertex (termasuk endpoint) hanya jika jarak ke jalan lain < 30m
                // CATATAN: Endpoint tidak boleh "selalu" terhubung tanpa batas jarak,

                var shouldConnect = closestPoint && closestDist < 30.0;
                if (shouldConnect) {
                    registerCoord(closestPoint);
                    addEdge(v, closestPoint);
                    addEdge(closestSegment.a, closestPoint);
                    addEdge(closestPoint, closestSegment.b);
                }
            });
        });

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

            console.log("Dijkstra path successfully found! nodes count:", pathCoords.length);
            return {
                path: pathCoords,
                distance: distances[endKey],
                snapStart: snapS.point,
                snapEnd: snapE.point,
                isStraightLine: false
            };
        } else {
            console.log("Dijkstra failed: target is unreachable in the graph. startKey:", startKey, "endKey:", endKey);
            return {
                path: [startLatLng, endLatLng],
                distance: getHaversineDistance(startLatLng, endLatLng),
                isStraightLine: true
            };
        }
    }

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

    window.currentNavTarget = null;
    window.arrivalTimeout = null;

    function playChime() {
        try {
            var AudioContext = window.AudioContext || window.webkitAudioContext;
            if (!AudioContext) return;
            var ctx = new AudioContext();
            var osc = ctx.createOscillator();
            var gain = ctx.createGain();

            osc.connect(gain);
            gain.connect(ctx.destination);

            osc.type = 'sine';
            osc.frequency.setValueAtTime(880, ctx.currentTime);
            osc.frequency.exponentialRampToValueAtTime(1200, ctx.currentTime + 0.1);

            gain.gain.setValueAtTime(0.5, ctx.currentTime);
            gain.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.8);

            osc.start(ctx.currentTime);
            osc.stop(ctx.currentTime + 0.8);
        } catch (e) {
            console.error("Gagal memutar chime:", e);
        }
    }

    window.startNavigation = function(id, name, lat, lng) {
        if (window.arrivalTimeout) {
            clearTimeout(window.arrivalTimeout);
            window.arrivalTimeout = null;
        }
        window.currentNavTarget = { id: id, name: name, lat: lat, lng: lng };
        map.closePopup();
        window.updateNavigationRouting();
    };

    window.stopNavigation = function() {
        window.currentNavTarget = null;
        clearNavigationLayers();
        window.dispatchEvent(new CustomEvent('stop-nav'));
        if (window.arrivalTimeout) {
            clearTimeout(window.arrivalTimeout);
            window.arrivalTimeout = null;
        }
    };

    function updateFloatingPanel(name, totalDistMeters) {
        var distStr = '';
        if (totalDistMeters < 1000) {
            distStr = Math.round(totalDistMeters) + ' m';
        } else {
            distStr = (totalDistMeters / 1000).toFixed(2) + ' km';
        }

        var timeMinutes = Math.round(totalDistMeters / (1.25 * 60));
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
                    distance: 'Menunggu GPS...',
                    time: 'Atau tap peta utk lokasi Anda'
                }
            }));
            window.showMapAlert("Informasi Navigasi", "Sistem sedang mencari lokasi GPS Anda. Jika Anda sedang offline atau GPS tidak tersedia, silakan TAP/KLIK di mana saja pada peta untuk menentukan posisi Anda saat ini secara manual.", "info");
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

        if (totalDistMeters <= 10.0 && !window.arrivalTimeout) {
            playChime();

            if ('speechSynthesis' in window) {
                var speechMsg = new SpeechSynthesisUtterance("Sudah sampai di lokasi");
                speechMsg.lang = 'id-ID';
                window.speechSynthesis.speak(speechMsg);
            }

            window.dispatchEvent(new CustomEvent('map-alert', {
                detail: {
                    title: "Sampai di Lokasi",
                    message: "Anda telah tiba di " + window.currentNavTarget.name + ". Navigasi akan dihentikan otomatis dalam 5 detik.",
                    type: "success"
                }
            }));

            window.arrivalTimeout = setTimeout(function() {
                window.stopNavigation();
            }, 5000);
        }
    };

    // --- Logika Navigasi Multi-Point (A ➔ B ➔ C...) ---
    window.multiWaypoints = [];
    window.multiRoutePolylines = [];
    window.multiRouteMarkers = [];

    window.addMultiWaypoint = function(wp) {
        if (!wp || !wp.name) return;

        // Prevent adding duplicate waypoints
        var isDuplicate = window.multiWaypoints.some(function(item) {
            return item.id == wp.id;
        });

        if (isDuplicate) {
            window.showMapAlert("Lokasi Sudah Ada", `Titik "${wp.name}" sudah ada dalam rute Anda.`, "warning");
            return;
        }

        window.multiWaypoints.push(wp);
        window.updateMultiRoute();
    };

    window.addWaypointFromDropdown = function(markerIdOrGps) {
        if (markerIdOrGps === 'gps') {
            if (!userMarker) {
                window.showMapAlert("GPS Belum Siap", "Lokasi Anda belum terdeteksi. Silakan aktifkan GPS atau TAP di mana saja pada peta untuk menentukan lokasi posisi Anda.", "warning");
                return;
            }
            var userLatLng = userMarker.getLatLng();
            window.addMultiWaypoint({
                id: 'gps',
                name: '📍 Lokasi Saya',
                lat: userLatLng.lat,
                lng: userLatLng.lng
            });
        } else {
            var markerObj = markers.find(function(m) { return m.id == markerIdOrGps; });
            if (markerObj) {
                var lat = markerObj.latitude;
                var lng = markerObj.longitude;
                if (!lat || !lng) {
                    if (markerObj.geojson) {
                        try {
                            var coords = JSON.parse(markerObj.geojson);
                            if (coords.length > 0) {
                                var flat = Array.isArray(coords[0][0]) ? coords[0] : coords;
                                var sumLat = 0, sumLng = 0;
                                flat.forEach(function(c) { sumLat += parseFloat(c[0]); sumLng += parseFloat(c[1]); });
                                lat = sumLat / flat.length;
                                lng = sumLng / flat.length;
                            }
                        } catch(e){}
                    }
                }
                if (lat && lng) {
                    window.addMultiWaypoint({
                        id: markerObj.id,
                        name: markerObj.name,
                        lat: parseFloat(lat),
                        lng: parseFloat(lng)
                    });
                } else {
                    window.showMapAlert("Koordinat Tidak Valid", "Lokasi ini tidak memiliki titik lokasi yang valid.", "error");
                }
            }
        }
    };

    window.removeMultiWaypoint = function(index) {
        window.multiWaypoints.splice(index, 1);
        window.updateMultiRoute();
    };

    window.moveMultiWaypoint = function(index, direction) {
        var newIndex = index + direction;
        if (newIndex < 0 || newIndex >= window.multiWaypoints.length) return;
        var temp = window.multiWaypoints[index];
        window.multiWaypoints[index] = window.multiWaypoints[newIndex];
        window.multiWaypoints[newIndex] = temp;
        window.updateMultiRoute();
    };

    window.reverseMultiWaypoints = function() {
        window.multiWaypoints.reverse();
        window.updateMultiRoute();
    };

    window.clearMultiRoute = function() {
        window.multiWaypoints = [];
        window.clearMultiRouteLayers();
        window.updateMultiRouteUI([], "0 m", "0 mnt", []);
    };

    window.clearMultiRouteLayers = function() {
        window.multiRoutePolylines.forEach(function(l) { map.removeLayer(l); });
        window.multiRouteMarkers.forEach(function(m) { map.removeLayer(m); });
        window.multiRoutePolylines = [];
        window.multiRouteMarkers = [];
    };

    window.updateMultiRouteUI = function(wps, distStr, timeStr, legs) {
        window.dispatchEvent(new CustomEvent('update-multi-route', {
            detail: {
                waypoints: wps,
                totalDistance: distStr,
                totalTime: timeStr,
                legs: legs
            }
        }));
    };

    function formatDist(meters) {
        if (meters < 1000) {
            return Math.round(meters) + ' m';
        }
        return (meters / 1000).toFixed(2) + ' km';
    }

    function formatTimeMin(meters) {
        var min = Math.round(meters / (1.25 * 60));
        if (min < 1) return '< 1 mnt';
        return min + ' mnt';
    }

    window.updateMultiRoute = function() {
        window.clearMultiRouteLayers();

        if (window.multiWaypoints.length < 2) {
            if (window.multiWaypoints.length === 1) {
                var wp = window.multiWaypoints[0];
                var latlng = getWaypointLatLng(wp);
                if (latlng) {
                    var m = L.marker(latlng, {
                        icon: L.divIcon({
                            className: 'multi-wp-marker',
                            html: '<div style="background:#10b981;color:#fff;width:28px;height:28px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:bold;font-size:12px;border:2.5px solid #fff;box-shadow:0 3px 10px rgba(0,0,0,0.35);cursor:pointer;">1</div>',
                            iconSize: [28, 28],
                            iconAnchor: [14, 14]
                        })
                    }).addTo(map);
                    m.bindTooltip(`<b>1. ${wp.name}</b>`, { permanent: false, direction: 'top' });
                    if (wp.id && wp.id !== 'gps' && window.mapLayers && window.mapLayers[wp.id]) {
                        m.on('click', function() {
                            window.mapLayers[wp.id].openPopup();
                        });
                    }
                    window.multiRouteMarkers.push(m);
                }
            }
            window.updateMultiRouteUI(window.multiWaypoints, "0 m", "0 mnt", []);
            return;
        }

        var totalDistMeters = 0;
        var legs = [];
        var legColors = ['#059669', '#2563eb', '#7c3aed', '#d97706', '#db2777', '#0891b2'];

        for (var i = 0; i < window.multiWaypoints.length - 1; i++) {
            var startWp = window.multiWaypoints[i];
            var endWp = window.multiWaypoints[i+1];

            var startLatLng = getWaypointLatLng(startWp);
            var endLatLng = getWaypointLatLng(endWp);

            if (!startLatLng || !endLatLng) continue;

            var result = buildGraphAndRunDijkstra(startLatLng, endLatLng);
            var color = legColors[i % legColors.length];

            var legDist = 0;
            if (result.isStraightLine) {
                legDist = result.distance;
                var poly = L.polyline([startLatLng, endLatLng], {
                    color: color,
                    weight: 5,
                    opacity: 0.85,
                    dashArray: '8, 8'
                }).addTo(map);
                window.multiRoutePolylines.push(poly);
            } else {
                var distUserToSnapS = getHaversineDistance(startLatLng, result.snapStart);
                var distSnapEToTarget = getHaversineDistance(result.snapEnd, endLatLng);
                legDist = distUserToSnapS + result.distance + distSnapEToTarget;

                var p1 = L.polyline([startLatLng, result.snapStart], {
                    color: color,
                    weight: 4,
                    opacity: 0.8,
                    dashArray: '4, 6'
                }).addTo(map);
                window.multiRoutePolylines.push(p1);

                var pathLatLngs = result.path.map(function(p) { return L.latLng(p.lat, p.lng); });
                var p2 = L.polyline(pathLatLngs, {
                    color: color,
                    weight: 6,
                    opacity: 0.95
                }).addTo(map);
                window.multiRoutePolylines.push(p2);

                var p3 = L.polyline([result.snapEnd, endLatLng], {
                    color: color,
                    weight: 4,
                    opacity: 0.8,
                    dashArray: '4, 6'
                }).addTo(map);
                window.multiRoutePolylines.push(p3);
            }

            totalDistMeters += legDist;
            legs.push({
                from: startWp.name,
                to: endWp.name,
                distanceStr: formatDist(legDist),
                timeStr: formatTimeMin(legDist)
            });
        }

        window.multiWaypoints.forEach(function(wp, idx) {
            var latlng = getWaypointLatLng(wp);
            if (latlng) {
                var label = (idx + 1).toString();
                var pinColor = idx === 0 ? '#10b981' : (idx === window.multiWaypoints.length - 1 ? '#ef4444' : '#2563eb');
                var m = L.marker(latlng, {
                    icon: L.divIcon({
                        className: 'multi-wp-marker',
                        html: `<div style="background:${pinColor};color:#fff;width:28px;height:28px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:900;font-size:12px;border:2.5px solid #fff;box-shadow:0 4px 12px rgba(0,0,0,0.4);cursor:pointer;">${label}</div>`,
                        iconSize: [28, 28],
                        iconAnchor: [14, 14]
                    })
                }).addTo(map);
                m.bindTooltip(`<b>${label}. ${wp.name}</b>`, { permanent: false, direction: 'top' });

                if (wp.id && wp.id !== 'gps' && window.mapLayers && window.mapLayers[wp.id]) {
                    m.on('click', function() {
                        window.mapLayers[wp.id].openPopup();
                    });
                }

                window.multiRouteMarkers.push(m);
            }
        });

        window.updateMultiRouteUI(window.multiWaypoints, formatDist(totalDistMeters), formatTimeMin(totalDistMeters), legs);
    };

    function getWaypointLatLng(wp) {
        if (wp.id === 'gps') {
            if (userMarker) return userMarker.getLatLng();
            return null;
        }
        if (wp.lat && wp.lng) {
            return L.latLng(wp.lat, wp.lng);
        }
        return null;
    }

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
            navButtonHtml = `<div class="flex items-center gap-1.5 shrink-0">
                <button onclick="window.startNavigation(${marker.id}, '${marker.name.replace(/'/g, "\\'")}', ${targetLat}, ${targetLng})" class="inline-flex items-center gap-1 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold px-2.5 py-1.5 rounded-lg text-[10px] transition-all duration-200 shadow-sm cursor-pointer">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    Navigasi
                </button>
                <button onclick="window.addWaypointFromDropdown(${marker.id})" class="inline-flex items-center gap-1 bg-zinc-900 hover:bg-zinc-800 text-white font-semibold px-2.5 py-1.5 rounded-lg text-[10px] transition-all duration-200 shadow-sm cursor-pointer" title="Tambah ke Rute Multi-Point">
                    + Rute
                </button>
            </div>`;
        }

        var badgeHtml = `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider mb-2" style="color: ${marker.color}; border: 1px solid ${marker.color}40; background-color: ${marker.color}15;">${typeLabel}</span>`;
        var popupContent = `<div class="flex flex-col font-sans text-left bg-white w-full">${imageHtml}<div class="p-5"><div>${badgeHtml}<h3 class="font-heading font-bold text-lg leading-tight text-zinc-900 m-0">${marker.name}</h3></div>${marker.description ? `<p class="text-sm text-zinc-500 leading-relaxed m-0 mt-1 line-clamp-3">${marker.description}</p>` : ''}<div class="mt-4 pt-4 border-t border-zinc-100 flex items-center justify-between gap-2"><a href="#marker-detail-${marker.id}" onclick="document.getElementById('marker-detail-${marker.id}')?.scrollIntoView({ behavior: 'smooth' }); return false;" class="inline-flex items-center text-xs font-bold text-emerald-600 hover:text-emerald-700 transition-colors">LIHAT DETAIL</a>${navButtonHtml}</div></div></div>`;

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
                        var markerTypeNorm = (marker.type || '').toLowerCase().replace(/[\s\-]+/g, '_');
                        var lineColor = marker.color;
                        var lineWidth = 4.5;

                        if (markerTypeNorm === 'jalan_utama') {
                            lineColor = '#b8b8b8';
                            lineWidth = 4;
                        } else if (markerTypeNorm === 'jalan_lain') {
                            lineColor = '#c8c8c8';
                            lineWidth = 2;
                        }

                        leafletLayer = L.polyline(coordinates, { color: lineColor, weight: lineWidth }).addTo(map);
                    } else if (geomType === 'polygon') {
                        var markerTypeNorm = (marker.type || '').toLowerCase().replace(/[\s\-_]+/g, ' ');
                        var markerNameNorm = (marker.name || '').toLowerCase();
                        var isBatas = markerTypeNorm.includes('batas');
                        var isVak = markerTypeNorm.includes('vak') || markerNameNorm.includes('vak');

                        leafletLayer = L.polygon(coordinates, {
                            color: marker.color || '#10b981',
                            fillColor: marker.color || '#10b981',
                            fillOpacity: isBatas ? 0.08 : 0.18,
                            weight: isBatas ? 3 : 2.5,
                            dashArray: isBatas ? '10, 8' : null
                        }).addTo(map);

                        if (isVak) {
                            var center = getPolygonVisualCenter(coordinates);
                            if (!center && leafletLayer && leafletLayer.getBounds) {
                                center = leafletLayer.getBounds().getCenter();
                            }
                            if (center) {
                                var labelText = (marker.name || '').replace(/^VAK\s*/i, '').trim();
                                if (!labelText) labelText = marker.name || 'VAK';
                                if (!labelText.endsWith('.')) labelText += '.';

                                var vakLabelIcon = L.divIcon({
                                    className: 'vak-polygon-label-marker',
                                    html: `<div style="display:flex; align-items:center; justify-content:center; width:100%; height:100%; text-align:center; color:#000000; font-family:ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; font-weight:800; font-size:13px; text-shadow: 0 0 3px #ffffff, 0 0 5px #ffffff, 0 0 8px #ffffff; pointer-events:none; white-space:nowrap; line-height:1;">${labelText}</div>`,
                                    iconSize: [60, 30],
                                    iconAnchor: [30, 15]
                                });
                                L.marker(center, { icon: vakLabelIcon, interactive: false }).addTo(map);
                            }
                        }
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

    window.focusOnMarker = function(id) {
        var layer = window.mapLayers[id];
        if (layer) {
            if (layer.getLatLng) {
                var latlng = layer.getLatLng();
                map.setView(latlng, 17, { animate: true });
                layer.openPopup();
            } else if (layer.getBounds) {
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

    var urlParams = new URLSearchParams(window.location.search);
    var focusId = urlParams.get('focus');
    if (focusId) {
        setTimeout(function() {
            window.focusOnMarker(focusId);
        }, 1200);
    }

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