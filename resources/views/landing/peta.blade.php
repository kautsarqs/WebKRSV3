@extends('layouts.landing')

@section('title', 'Peta Kawasan - Kebun Raya Sambas')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<style>
    #map { height: 600px; width: 100%; z-index: 10; border-radius: 1.5rem; }
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
        gap: 0.5rem;
        max-width: 200px;
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
    .map-wrapper { position: relative; }
    .leaflet-popup-content-wrapper { padding: 0; overflow: hidden; border-radius: 16px; border: none; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1); background: white; }
    .leaflet-popup-content { margin: 0 !important; width: 300px !important; line-height: 1.5; }
    .leaflet-popup-tip { background: white; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1); }
    .leaflet-container a.leaflet-popup-close-button { top: 12px; right: 12px; width: 32px; height: 32px; border-radius: 50%; background: rgba(255, 255, 255, 0.95); color: #3f3f46; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); display: flex; align-items: center; justify-content: center; font-size: 18px; text-decoration: none; transition: all 0.2s; font-weight: bold; padding-bottom: 2px; }
    .leaflet-container a.leaflet-popup-close-button:hover { background: #ffffff; color: #ef4444; transform: scale(1.1); }

    /* Custom GPS User location marker styles */
    .user-location-marker {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .blue-dot {
        width: 12px;
        height: 12px;
        background-color: #3b82f6;
        border-radius: 50%;
        border: 2px solid white;
        box-shadow: 0 1px 4px rgba(0, 0, 0, 0.4);
        z-index: 2;
    }
    .pulse-ring {
        position: absolute;
        width: 24px;
        height: 24px;
        border: 2.5px solid #3b82f6;
        border-radius: 50%;
        background-color: rgba(59, 130, 246, 0.15);
        animation: pulse-animation 1.6s infinite ease-out;
        z-index: 1;
    }
    @keyframes pulse-animation {
        0% {
            transform: scale(0.5);
            opacity: 1;
        }
        100% {
            transform: scale(2.0);
            opacity: 0;
        }
    }
</style>
@endpush

@section('content')
<div class="max-w-7xl mx-auto px-6">
    <div class="text-center mb-10">
        <h1 class="font-heading text-4xl font-bold mb-2">Peta Digital Kawasan</h1>
        <p class="text-zinc-500">Sistem Informasi Geografis (WebGIS) persebaran koleksi tumbuhan.</p>
    </div>

    <div class="p-2 bg-white border border-zinc-200 rounded-4xl shadow-xl shadow-zinc-200/50">
        <div class="map-wrapper">
            <div id="map" class="bg-zinc-100"></div>
            
            <!-- Left Controls: GPS & Offline Caching -->
            <div class="map-left-controls">
                <!-- GPS status -->
                <div class="bg-white/95 backdrop-blur-xs border border-zinc-200 rounded-xl p-2.5 shadow-sm flex flex-col gap-1">
                    <span class="text-[10px] font-bold text-zinc-400 uppercase tracking-wider">Status GPS</span>
                    <div id="gps-status">
                        <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[10px] font-medium bg-zinc-100 text-zinc-600">
                            GPS Menghubungkan...
                        </span>
                    </div>
                </div>

                <!-- Cache Map offline -->
                <div class="bg-white/95 backdrop-blur-xs border border-zinc-200 rounded-xl p-2.5 shadow-sm flex flex-col gap-1.5">
                    <span class="text-[10px] font-bold text-zinc-400 uppercase tracking-wider">Peta Offline</span>
                    <button id="download-btn" onclick="downloadVisibleArea()" class="w-full py-1 px-2 bg-zinc-900 hover:bg-zinc-800 text-white rounded-lg text-[10px] font-semibold transition">
                        Unduh Area Peta
                    </button>
                    <button id="clear-btn" onclick="clearCachedMap()" class="w-full py-1 px-2 bg-red-50 hover:bg-red-100 text-red-600 rounded-lg text-[10px] font-semibold transition">
                        Hapus Cache Peta
                    </button>
                    
                    <div id="download-progress" class="hidden">
                        <div class="w-full bg-zinc-100 rounded-full h-1 mt-1 overflow-hidden">
                            <div id="progress-bar" class="bg-green-500 h-1 w-0 transition-all duration-150"></div>
                        </div>
                        <p id="progress-text" class="text-[9px] text-zinc-500 mt-1 text-center font-medium"></p>
                    </div>
                </div>
            </div>
            
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
                        <a @click="selected = 'road'; switchLayer('road'); open = false" href="#" class="flex items-center gap-3 px-4 py-2 text-sm text-zinc-700 hover:bg-zinc-100">Lapisan Default</a>
                        <a @click="selected = 'satellite'; switchLayer('satellite'); open = false" href="#" class="flex items-center gap-3 px-4 py-2 text-sm text-zinc-700 hover:bg-zinc-100">Lapisan Satelit</a>
                        <a @click="selected = 'terrain'; switchLayer('terrain'); open = false" href="#" class="flex items-center gap-3 px-4 py-2 text-sm text-zinc-700 hover:bg-zinc-100">Lapisan Medan</a>
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
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<!-- LocalForage library for IndexedDB caching -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/localforage/1.10.0/localforage.min.js"></script>
<script>
    // Custom Tile Layer to cache map tiles in IndexedDB via localForage
    L.TileLayer.Offline = L.TileLayer.extend({
        getTileKey: function(coords) {
            // Bersihkan URL dari karakter non-alfanumerik untuk menjadikannya prefix unik per layer
            var cleanUrl = (this._url || "").replace(/[^a-zA-Z0-9]/g, "").substring(0, 20);
            return `${cleanUrl}_tile_${coords.z}_${coords.x}_${coords.y}`;
        },
        createTile: function(coords, done) {
            var tile = document.createElement('img');
            var url = this.getTileUrl(coords);
            var key = this.getTileKey(coords);

            // Cek data cache di IndexedDB
            localforage.getItem(key).then(function(blob) {
                if (blob) {
                    var objectUrl = URL.createObjectURL(blob);
                    tile.src = objectUrl;
                    tile.onload = function() {
                        URL.revokeObjectURL(objectUrl);
                        done(null, tile);
                    };
                    tile.onerror = function() {
                        done(new Error("Gagal render blob tile"), tile);
                    };
                } else {
                    // Coba unduh jika tidak ada di cache
                    fetch(url)
                        .then(function(res) {
                            if (!res.ok) throw new Error("Gagal mengambil tile");
                            return res.blob();
                        })
                        .then(function(blob) {
                            localforage.setItem(key, blob);
                            var objectUrl = URL.createObjectURL(blob);
                            tile.src = objectUrl;
                            tile.onload = function() {
                                URL.revokeObjectURL(objectUrl);
                                done(null, tile);
                            };
                            tile.onerror = function() {
                                done(new Error("Gagal render downloaded tile"), tile);
                            };
                        })
                        .catch(function(err) {
                            console.warn("Offline fallback ke normal image src:", url);
                            tile.src = url;
                            done(err, tile);
                        });
                }
            }).catch(function(err) {
                console.error(err);
                tile.src = url;
                done(err, tile);
            });

            return tile;
        }
    });

    L.tileLayer.offline = function(urlTemplate, options) {
        return new L.TileLayer.Offline(urlTemplate, options);
    };

    var map = L.map('map', { zoomControl: false }).setView([1.2706202914994014, 109.48517276551188], 14); 
    L.control.zoom({ position: 'bottomright' }).addTo(map);

    // Gunakan Offline Layer untuk melayani tile dengan offline support
    var roadLayer = L.tileLayer.offline('https://tile.openstreetmap.org/{z}/{x}/{y}.png', { maxNativeZoom: 19, maxZoom: 20, attribution: '&copy; OpenStreetMap', crossOrigin: true });
    var satelliteLayer = L.tileLayer.offline('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', { maxNativeZoom: 18, maxZoom: 18, attribution: '&copy; Esri', crossOrigin: true });
    var terrainLayer = L.tileLayer.offline('https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png', { 
        maxNativeZoom: 17, 
        maxZoom: 17, 
        attribution: 'Map data: &copy; OpenStreetMap contributors',
        subdomains: 'abc',
        crossOrigin: true
    });

    var currentLayer = roadLayer.addTo(map);

    function switchLayer(mode) {
        map.removeLayer(currentLayer);
        if(mode === 'satellite') currentLayer = satelliteLayer;
        else if(mode === 'terrain') currentLayer = terrainLayer;
        else currentLayer = roadLayer;
        currentLayer.addTo(map);
    }



    // --- Jalur Rekaman Perjalanan User (Linestring Navigasi Real-Time) ---
    var userPathCoords = [];
    var userPolyline = L.polyline(userPathCoords, {
        color: '#3b82f6', // Biru untuk navigasi aktif
        weight: 4.5,
        opacity: 0.9,
        dashArray: '5, 8'
    }).addTo(map);

    // --- Real-time User tracking logic ---
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

                // Tambahkan koordinat ke riwayat rute user dan perbarui linestring
                userPathCoords.push(latlng);
                userPolyline.setLatLngs(userPathCoords);

                if (firstLocationCheck) {
                    map.setView(latlng, 16);
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

    // --- Offline map seeding logic ---
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

    function downloadVisibleArea() {
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

        if (tiles.length > 250) {
            var confirmDl = confirm(`Anda akan mengunduh ${tiles.length} petak peta. Unduh sekarang?`);
            if (!confirmDl) return;
        }

        var downloaded = 0;
        var progressDiv = document.getElementById('download-progress');
        var bar = document.getElementById('progress-bar');
        var text = document.getElementById('progress-text');
        var btn = document.getElementById('download-btn');

        progressDiv.classList.remove('hidden');
        btn.disabled = true;
        btn.innerText = "Mengunduh...";

        function updateProgress() {
            var percentage = Math.round((downloaded / tiles.length) * 100);
            bar.style.width = percentage + '%';
            text.innerText = `Mengunduh: ${downloaded}/${tiles.length} (${percentage}%)`;

            if (downloaded === tiles.length) {
                setTimeout(function() {
                    progressDiv.classList.add('hidden');
                    btn.disabled = false;
                    btn.innerText = "Unduh Area Peta";
                    alert("Selesai! Peta berhasil disimpan secara offline.");
                }, 1000);
            }
        }

        tiles.forEach(function(tile) {
            var url = currentLayer.getTileUrl(tile);
            var key = currentLayer.getTileKey(tile);

            localforage.getItem(key).then(function(val) {
                if (val) {
                    downloaded++;
                    updateProgress();
                } else {
                    fetch(url)
                        .then(function(r) { return r.blob(); })
                        .then(function(blob) {
                            localforage.setItem(key, blob).then(function() {
                                downloaded++;
                                updateProgress();
                            });
                        })
                        .catch(function(e) {
                            downloaded++;
                            updateProgress();
                        });
                }
            });
        });
    }

    function clearCachedMap() {
        if (confirm("Hapus semua peta offline yang tersimpan di browser ini?")) {
            var btn = document.getElementById('clear-btn');
            btn.disabled = true;
            btn.innerText = "Menghapus...";

            localforage.clear().then(function() {
                alert("Cache dibersihkan.");
                btn.disabled = false;
                btn.innerText = "Hapus Cache Peta";
                currentLayer.redraw();
            }).catch(function(err) {
                btn.disabled = false;
                btn.innerText = "Hapus Cache Peta";
            });
        }
    }

    // --- Auto-download Kebun Raya Sambas tiles on page load (background) ---
    function autoDownloadKRS() {
        if (!navigator.onLine) return; // Jangan download jika offline
        
        var bounds = L.latLngBounds([1.2599, 109.4751], [1.2799, 109.4951]);
        var zooms = [13, 14, 15, 16, 17];
        var tiles = [];

        zooms.forEach(function(z) {
            tiles = tiles.concat(getTileCoordsForBounds(bounds, z));
        });

        tiles.forEach(function(tile) {
            var url = roadLayer.getTileUrl(tile);
            var key = roadLayer.getTileKey(tile);

            localforage.getItem(key).then(function(val) {
                if (!val) {
                    fetch(url)
                        .then(function(r) { return r.blob(); })
                        .then(function(blob) {
                            localforage.setItem(key, blob);
                        })
                        .catch(function(e) {
                            // Abaikan error
                        });
                }
            });
        });
    }

    // Jalankan otomatis 3 detik setelah halaman termuat
    setTimeout(autoDownloadKRS, 3000);

    var markers = @json($markers);
    var storageBase = "{{ \Illuminate\Support\Facades\Storage::url('') }}".replace(/\/$/, '');

    markers.forEach(function(marker) {
        var typeLabel = 'Lokasi';
        var badgeClass = 'bg-zinc-100 text-zinc-600';
        switch(marker.type) {
            case 'area_koleksi': typeLabel = 'Area Koleksi'; badgeClass = 'bg-green-50 text-green-700 border border-green-100'; break;
            case 'fasilitas_umum': typeLabel = 'Fasilitas Umum'; badgeClass = 'bg-blue-50 text-blue-700 border border-blue-100'; break;
            case 'kantor_pengelola': typeLabel = 'Kantor'; badgeClass = 'bg-red-50 text-red-700 border border-red-100'; break;
            case 'pos_keamanan': typeLabel = 'Keamanan'; badgeClass = 'bg-yellow-50 text-yellow-700 border border-yellow-100'; break;
        }

        var imageHtml = marker.photo 
            ? `<div class="relative w-full h-40 bg-zinc-100 group overflow-hidden"><img src="${storageBase + '/' + marker.photo}" alt="${marker.name}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"></div>`
            : `<div class="w-full h-24 bg-zinc-100 flex items-center justify-center border-b border-zinc-50"><svg class="w-8 h-8 text-zinc-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg></div>`;

        var popupContent = `<div class="flex flex-col font-sans text-left bg-white w-full">${imageHtml}<div class="p-5"><div><span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider ${badgeClass} mb-2">${typeLabel}</span><h3 class="font-heading font-bold text-lg leading-tight text-zinc-900 m-0">${marker.name}</h3></div>${marker.description ? `<p class="text-sm text-zinc-500 leading-relaxed m-0 mt-1 line-clamp-3">${marker.description}</p>` : ''}<div class="mt-4 pt-4 border-t border-zinc-100"><a href="#" class="inline-flex items-center text-xs font-bold text-blue-600 hover:text-blue-700 transition-colors">LIHAT DETAIL</a></div></div></div>`;

        // Render berdasarkan tipe geometri dari database
        var geomType = marker.geometry_type || 'point';

        if (geomType === 'point' && marker.latitude && marker.longitude) {
            var customIcon = L.divIcon({
                className: 'custom-marker',
                html: '<div style="background-color: ' + marker.color + '; width: 22px; height: 22px; border-radius: 50%; border: 3px solid white; box-shadow: 0 2px 5px rgba(0,0,0,0.3);"></div>',
                iconSize: [22, 22],
                iconAnchor: [11, 11],
                popupAnchor: [0, -10]
            });
            L.marker([marker.latitude, marker.longitude], { icon: customIcon })
                .addTo(map)
                .bindPopup(popupContent, { closeButton: true, maxWidth: 320, minWidth: 300 });
        } else if (marker.geojson) {
            try {
                var coordinates = JSON.parse(marker.geojson);
                if (coordinates.length > 0) {
                    if (geomType === 'polyline') {
                        L.polyline(coordinates, { 
                            color: marker.color, 
                            weight: 4.5 
                        }).addTo(map).bindPopup(popupContent, { closeButton: true, maxWidth: 320, minWidth: 300 });
                    } else if (geomType === 'polygon') {
                        L.polygon(coordinates, { 
                            color: marker.color, 
                            fillColor: marker.color, 
                            fillOpacity: 0.12, 
                            weight: 3 
                        }).addTo(map).bindPopup(popupContent, { closeButton: true, maxWidth: 320, minWidth: 300 });
                    }
                }
            } catch(e) {
                console.error("Gagal menggambar fitur spasial:", e);
            }
        }
    });
</script>
@endpush