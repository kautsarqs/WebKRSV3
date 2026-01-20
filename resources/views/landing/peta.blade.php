<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <title>Peta Kawasan - Kebun Raya Sambas</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Space+Grotesk:wght@500;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body { font-family: 'Inter', sans-serif; } 
        .font-heading { font-family: 'Space Grotesk', sans-serif; }
        
        #map { height: 600px; width: 100%; z-index: 10; border-radius: 1.5rem; }
        
        /* --- MAP CONTROLS STYLING (Tombol Ganti Layer) --- */
        .map-controls {
            position: absolute;
            top: 1rem;
            right: 1rem;
            z-index: 1000;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }
        
        .map-control-btn {
            background: white;
            border: 1px solid #e4e4e7;
            border-radius: 0.75rem;
            padding: 0.75rem 1rem;
            font-size: 0.875rem;
            font-weight: 500;
            color: #52525b;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            gap: 0.5rem;
            min-width: 120px;
        }
        
        .map-control-btn:hover {
            background: #fafafa;
            border-color: #d4d4d8;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        
        .map-control-btn.active {
            background: #3b82f6;
            color: white;
            border-color: #2563eb;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }
        
        .map-control-btn.active:hover {
            background: #2563eb;
        }
        
        .map-wrapper {
            position: relative;
        }

        /* --- MATERIAL DESIGN POPUP OVERRIDES --- */
        
        /* 1. Reset wrapper default Leaflet & buat rounded card */
        .leaflet-popup-content-wrapper {
            padding: 0;
            overflow: hidden;
            border-radius: 16px;
            border: none;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); /* Shadow-xl */
            background: white;
        }

        /* 2. Pastikan content mentok pinggir (margin 0) agar gambar full width */
        .leaflet-popup-content {
            margin: 0 !important;
            width: 300px !important;
            line-height: 1.5;
        }

        /* 3. Style segitiga bawah (tip) */
        .leaflet-popup-tip {
            background: white;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }

        /* 4. Tombol Close Floating yang Modern */
        .leaflet-container a.leaflet-popup-close-button {
            top: 12px;
            right: 12px;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.95);
            color: #3f3f46;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            text-decoration: none;
            transition: all 0.2s;
            font-weight: bold;
            padding-bottom: 2px; /* optical adjustment */
        }

        .leaflet-container a.leaflet-popup-close-button:hover {
            background: #ffffff;
            color: #ef4444; /* Merah saat hover */
            transform: scale(1.1);
        }
    </style>
</head>
<body class="bg-white text-zinc-900 antialiased">
    <x-landing.navbar />

    <main class="pt-32 pb-20">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-10">
                <h1 class="font-heading text-4xl font-bold mb-2">Peta Digital Kawasan</h1>
                <p class="text-zinc-500">Sistem Informasi Geografis (WebGIS) persebaran koleksi tumbuhan.</p>
            </div>

            <div class="p-2 bg-white border border-zinc-200 rounded-4xl shadow-xl shadow-zinc-200/50">
                <div class="map-wrapper">
                    <div id="map" class="bg-zinc-100"></div>
                    
                                        <div class="map-controls" x-data="{ open: false, selected: 'road' }" @click.away="open = false">
                    
                                            <div class="relative">
                    
                                                <button @click="open = !open" class="map-control-btn flex items-center justify-between w-48">
                    
                                                    <span class="flex items-center gap-2">
                    
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg>
                    
                                                        <span x-text="selected === 'road' ? 'Lapisan Default' : (selected === 'satellite' ? 'Lapisan Satelit' : 'Lapisan Medan')"></span>
                    
                                                    </span>
                    
                                                    <svg class="w-4 h-4 text-zinc-400 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    
                                                </button>
                    
                                                <div x-show="open" x-transition class="absolute top-full right-0 mt-2 w-48 bg-white border border-zinc-200 rounded-lg shadow-lg py-1">
                    
                                                    <a @click="selected = 'road'; switchLayer('road'); open = false" href="#" class="flex items-center gap-3 px-4 py-2 text-sm text-zinc-700 hover:bg-zinc-100">
                    
                                                        Lapisan Default
                    
                                                    </a>
                    
                                                    <a @click="selected = 'satellite'; switchLayer('satellite'); open = false" href="#" class="flex items-center gap-3 px-4 py-2 text-sm text-zinc-700 hover:bg-zinc-100">
                    
                                                        Lapisan Satelit
                    
                                                    </a>
                    
                                                    <a @click="selected = 'terrain'; switchLayer('terrain'); open = false" href="#" class="flex items-center gap-3 px-4 py-2 text-sm text-zinc-700 hover:bg-zinc-100">
                    
                                                        Lapisan Medan
                    
                                                    </a>
                    
                                                </div>
                    
                                            </div>
                    
                                        </div>
                    
                                    </div>
                    
                                </div>
                    
                    
                    
                                <div class="mt-8 flex flex-wrap items-center gap-3">
                    @php
                        $markerTypes = $markers->groupBy('type');
                    @endphp
                    
                    @foreach($markerTypes as $type => $markersOfType)
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-sm font-medium bg-zinc-100 text-zinc-700 ring-1 ring-inset ring-zinc-200">
                            <span class="w-2.5 h-2.5 rounded-full" style="background-color: {{ $markersOfType->first()->color }}"></span>
                            <span>{{ Str::of($type)->replace('_', ' ')->title() }}</span>
                        </div>
                    @endforeach
                </div>
                    
                            </div>
                    
                        </main>
                    
                    
                    
                        <x-landing.footer />
                    
                    
                    
                        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
                    
                        
                    
                        <script>
                    
                            var map = L.map('map', { zoomControl: false }).setView([1.2699364219071683, 109.48515704081744], 13); 
                    
                            L.control.zoom({ position: 'bottomright' }).addTo(map);
                    
                    
                    
                                                        var roadLayer = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', { maxNativeZoom: 19, maxZoom: 20, attribution: '&copy; OpenStreetMap' });
                    
                    
                    
                                                        var satelliteLayer = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', { maxNativeZoom: 18, maxZoom: 18, attribution: '&copy; Esri' });
                    
                    
                    
                                                        var terrainLayer = L.tileLayer('https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png', { 
                                maxNativeZoom: 17, 
                                maxZoom: 17, 
                                attribution: 'Map data: &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap contributors</a>, <a href="http://viewfinderpanoramas.org">SRTM</a> | Map style: &copy; <a href="https://opentopomap.org">OpenTopoMap</a> (<a href="https://creativecommons.org/licenses/by-sa/3.0/">CC-BY-SA</a>)',
                                subdomains: 'abc'
                            });
                    
                    
                    
                            var currentLayer = roadLayer.addTo(map);
                    
                    
                    
                            function switchLayer(mode) {
                    
                                map.removeLayer(currentLayer);
                    
                                if(mode === 'satellite') currentLayer = satelliteLayer;
                    
                                else if(mode === 'terrain') currentLayer = terrainLayer;
                    
                                else currentLayer = roadLayer;
                    
                                
                    
                                currentLayer.addTo(map);
                    
                            }
                    
                    
                    
                            var markers = @json($markers);
                    
                            var storageBase = "{{ \Illuminate\Support\Facades\Storage::url('') }}".replace(/\/$/, '');
                    
                            
                    
                            markers.forEach(function(marker) {
                    
                                var customIcon = L.divIcon({
                    
                                    className: 'custom-marker',
                    
                                    html: '<div style="background-color: ' + marker.color + '; width: 22px; height: 22px; border-radius: 50%; border: 3px solid white; box-shadow: 0 2px 5px rgba(0,0,0,0.3);"></div>',
                    
                                    iconSize: [22, 22],
                    
                                    iconAnchor: [11, 11],
                    
                                    popupAnchor: [0, -10]
                    
                                });
                    
                    
                    
                                var typeLabel = 'Lokasi';
                    
                                var badgeClass = 'bg-zinc-100 text-zinc-600';
                    
                                switch(marker.type) {
                    
                                    case 'area_koleksi': typeLabel = 'Area Koleksi'; badgeClass = 'bg-green-50 text-green-700 border border-green-100'; break;
                    
                                    case 'fasilitas_umum': typeLabel = 'Fasilitas Umum'; badgeClass = 'bg-blue-50 text-blue-700 border border-blue-100'; break;
                    
                                    case 'kantor_pengelola': typeLabel = 'Kantor'; badgeClass = 'bg-red-50 text-red-700 border border-red-100'; break;
                    
                                    case 'pos_keamanan': typeLabel = 'Keamanan'; badgeClass = 'bg-yellow-50 text-yellow-700 border border-yellow-100'; break;
                    
                                    default: typeLabel = marker.type;
                    
                                }
                    
                    
                    
                                var imageHtml = '';
                    
                                if (marker.photo) {
                    
                                    var photoUrl = storageBase + '/' + marker.photo;
                    
                                    imageHtml = `<div class="relative w-full h-40 bg-zinc-100 group overflow-hidden"><img src="${photoUrl}" alt="${marker.name}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"><div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent opacity-60"></div></div>`;
                    
                                } else {
                    
                                    imageHtml = `<div class="w-full h-24 bg-zinc-100 flex items-center justify-center border-b border-zinc-50"><svg class="w-8 h-8 text-zinc-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg></div>`;
                    
                                }
                    
                    
                    
                                var popupContent = `<div class="flex flex-col font-sans text-left bg-white w-full">${imageHtml}<div class="p-5"><div class="flex items-start justify-between gap-2 mb-2"><div><span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider ${badgeClass} mb-2">${typeLabel}</span><h3 class="font-heading font-bold text-lg leading-tight text-zinc-900 m-0">${marker.name}</h3></div></div>${marker.description ? `<p class="text-sm text-zinc-500 leading-relaxed m-0 mt-1 line-clamp-3">${marker.description}</p>` : ''}<div class="mt-4 pt-4 border-t border-zinc-100"><a href="#" class="inline-flex items-center text-xs font-bold text-blue-600 hover:text-blue-700 transition-colors">LIHAT DETAIL<svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg></a></div></div></div>`;
                    
                    
                    
                                L.marker([marker.latitude, marker.longitude], { icon: customIcon })
                    
                                    .addTo(map)
                    
                                    .bindPopup(popupContent, { closeButton: true, maxWidth: 320, minWidth: 300 });
                    
                            });
                    
                        </script>
                    
                    </body>
                    
                    </html>
                    
                    