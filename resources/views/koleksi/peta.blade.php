<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <title>Peta Sebaran: {{ $koleksi->title }} - Kebun Raya Sambas</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Space+Grotesk:wght@500;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body { font-family: 'Inter', sans-serif; } 
        .font-heading { font-family: 'Space Grotesk', sans-serif; }
        
        #map { height: 600px; width: 100%; z-index: 10; border-radius: 1.5rem; }
        
        .map-controls {
            position: absolute;
            top: 1rem;
            right: 1rem;
            z-index: 401; /* Above map tiles */
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
        }
        
        .map-control-btn.active {
            background: #3b82f6;
            color: white;
            border-color: #2563eb;
        }
        
        .map-wrapper {
            position: relative;
        }

        .leaflet-popup-content-wrapper {
            padding: 0;
            overflow: hidden;
            border-radius: 16px;
            box-shadow: 0 10px 15px -3px rgba(0,0,0,.1), 0 4px 6px -4px rgba(0,0,0,.1);
        }
        .leaflet-popup-content {
            margin: 0 !important;
            width: 280px !important;
        }
        .leaflet-container a.leaflet-popup-close-button {
            top: 8px; right: 8px; width: 32px; height: 32px;
            background: rgba(255,255,255,0.8);
            backdrop-filter: blur(4px);
            color: #3f3f46;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 16px; text-decoration: none;
        }
        .leaflet-container a.leaflet-popup-close-button:hover {
             background: white; color: #ef4444;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.4); opacity: 0.7; }
        }
        .animate-pulse-custom {
            animation: pulse 2s infinite;
        }
    </style>
</head>
<body class="bg-white text-zinc-900 antialiased">
    <x-landing.navbar />

    <main class="pt-32 pb-20">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-10">
                <a href="{{ route('koleksi.show', $koleksi) }}" class="inline-flex items-center text-sm font-bold text-zinc-500 hover:text-zinc-900 transition-colors mb-6 font-space group">
                    <div class="w-8 h-8 rounded-full bg-white border border-zinc-200 flex items-center justify-center mr-2 shadow-sm group-hover:bg-zinc-100 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                    </div>
                    Kembali ke Detail Koleksi
                </a>
                <h1 class="font-heading text-4xl font-bold mb-2">Peta Sebaran: {{ $koleksi->title }}</h1>
                <p class="text-zinc-500 max-w-2xl mx-auto">{{ $koleksi->description }}</p>
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
                            <div x-show="open" x-transition class="absolute top-full right-0 mt-2 w-48 bg-white border border-zinc-200 rounded-lg shadow-lg py-1 z-10">
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
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-sm font-bold bg-violet-100 text-violet-800 ring-2 ring-violet-200">
                    <span class="w-2.5 h-2.5 rounded-full bg-violet-500"></span>
                    <span>Lokasi {{ $koleksi->title }}</span>
                </div>

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
        var map = L.map('map', { zoomControl: false }).setView([1.269936, 109.485157], 13);
        L.control.zoom({ position: 'bottomright' }).addTo(map);

        var roadLayer = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 20, attribution: '&copy; OpenStreetMap' });
        var satelliteLayer = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', { maxZoom: 18, attribution: '&copy; Esri' });
        var terrainLayer = L.tileLayer('https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png', { 
            maxZoom: 17, 
            attribution: 'Map data: &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap contributors</a>, <a href="http://viewfinderpanoramas.org">SRTM</a> | Map style: &copy; <a href="https://opentopomap.org">OpenTopoMap</a> (<a href="https://creativecommons.org/licenses/by-sa/3.0/">CC-BY-SA</a>)',
            subdomains: 'abc'
        });
        
        var currentLayer = roadLayer.addTo(map);

        function switchLayer(mode) {
            map.removeLayer(currentLayer);
            currentLayer = (mode === 'satellite') ? satelliteLayer : (mode === 'terrain') ? terrainLayer : roadLayer;
            currentLayer.addTo(map);
        }

        var storageBase = "{{ \Illuminate\Support\Facades\Storage::url('') }}".replace(/\/$/, '');
        var bounds = [];

        // Render General Markers
        var generalMarkers = @json($markers ?? []);
        generalMarkers.forEach(function(marker) {
            var iconHtml = `<div style="background-color: ${marker.color};" class="w-4 h-4 rounded-full border-2 border-white shadow"></div>`;
            var customIcon = L.divIcon({ className: 'custom-marker', html: iconHtml, iconSize: [16, 16], iconAnchor: [8, 8], popupAnchor: [0, -8] });
            
            var popupContent = `<div class="font-sans p-3"><h3 class="font-bold text-base m-0 mb-1">${marker.name}</h3><p class="text-sm text-zinc-600 m-0">${marker.description || ''}</p></div>`;

            L.marker([marker.latitude, marker.longitude], { icon: customIcon, zIndexOffset: 100 })
                .addTo(map)
                .bindPopup(popupContent, { minWidth: 250 });
        });

        // Render Highlighted Collection Locations
        var collectionLocations = @json($koleksi->locations);
        if (collectionLocations.length > 0) {
            collectionLocations.forEach(function(location) {
                var iconHtml = `<div class="w-6 h-6 rounded-full bg-violet-500 border-4 border-white shadow-lg animate-pulse-custom"></div>`;
                var highlightIcon = L.divIcon({ className: 'custom-marker', html: iconHtml, iconSize: [24, 24], iconAnchor: [12, 12], popupAnchor: [0, -12] });

                var popupContent = `<div class="font-sans p-3"><span class="text-xs font-bold uppercase text-violet-500">Lokasi</span><h3 class="font-bold text-base m-0">${location.name}</h3></div>`;
                
                var latLng = [location.latitude, location.longitude];
                bounds.push(latLng);
                L.marker(latLng, { icon: highlightIcon, zIndexOffset: 1000 })
                    .addTo(map)
                    .bindPopup(popupContent)
                    .openPopup();
            });

            if (bounds.length > 0) {
                map.fitBounds(bounds, { padding: [75, 75], maxZoom: 17 });
            }
        } else {
             var noMarkerDiv = document.createElement('div');
            noMarkerDiv.innerHTML = `<div class="absolute inset-0 flex items-center justify-center bg-zinc-100/50 backdrop-blur-sm z-[1001]"><div class="text-center p-8 bg-white rounded-2xl shadow-lg border"><h3 class="font-bold text-zinc-800 text-lg">Lokasi Tidak Ditemukan</h3><p class="text-zinc-500 mt-1">Koleksi ini belum memiliki data koordinat.</p></div></div>`;
            document.getElementById('map').parentNode.appendChild(noMarkerDiv);
        }
    </script>
</body>
</html>
