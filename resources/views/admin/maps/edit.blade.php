<x-dashboard-layout title="Edit Marker Peta">
    <x-admin-sidebar />

    <div class="max-w-2xl mx-auto py-6">

        <a href="{{ route('admin.maps.index') }}" class="inline-flex items-center text-sm font-bold text-zinc-500 hover:text-zinc-900 transition-colors mb-6 font-space group">
            <div class="w-8 h-8 rounded-full bg-white border border-zinc-200 flex items-center justify-center mr-2 shadow-sm group-hover:bg-zinc-100 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
            </div>
            Kembali ke Daftar
        </a>

        <div class="relative bg-white/60 backdrop-blur-xl border border-zinc-200/60 rounded-3xl shadow-xl shadow-zinc-200/40 p-8">

            <div class="space-y-1 mb-8">
                <h3 class="text-2xl font-bold font-space text-zinc-900 tracking-tight">Edit Marker Peta</h3>
                <p class="text-zinc-500 text-sm font-inter">Perbarui informasi marker peta.</p>
            </div>

            <form method="POST" action="{{ route('admin.maps.update', $map) }}" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="space-y-2">
                    <label for="name" class="block text-sm font-bold text-zinc-700 font-space ml-1">Nama Marker/Fitur</label>
                    <input id="name" type="text" name="name" value="{{ old('name', $map->name) }}" required
                        class="w-full px-4 py-3 bg-white/50 border border-zinc-300 rounded-xl focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all outline-none text-zinc-800 shadow-sm placeholder-zinc-400" />
                    @error('name') <span class="text-xs text-red-500 font-medium ml-1">{{ $message }}</span> @enderror
                </div>

                <div class="space-y-2">
                    <label for="geometry_type" class="block text-sm font-bold text-zinc-700 font-space ml-1">Tipe Geometri</label>
                    <div class="relative">
                        <select name="geometry_type" id="geometry_type" onchange="changeGeometryType(this.value)" class="w-full px-4 py-3 bg-white/50 border border-zinc-300 rounded-xl focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all outline-none text-zinc-800 shadow-sm appearance-none cursor-pointer">
                            <option value="point" {{ old('geometry_type', $map->geometry_type) === 'point' ? 'selected' : '' }}>Point (Marker Lokasi Bangunan)</option>
                            <option value="linestring" {{ old('geometry_type', $map->geometry_type) === 'linestring' || old('geometry_type', $map->geometry_type) === 'polyline' ? 'selected' : '' }}>LineString (Garis Jalan/Jalur Navigasi)</option>
                            <option value="polygon" {{ old('geometry_type', $map->geometry_type) === 'polygon' ? 'selected' : '' }}>Polygon (Batas Wilayah)</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-zinc-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                    @error('geometry_type') <span class="text-xs text-red-500 font-medium ml-1">{{ $message }}</span> @enderror
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-bold text-zinc-700 font-space ml-1">Gambar di Peta (Klik-Klik Seperti Pen Tool)</label>
                    <div class="border border-zinc-200 rounded-2xl overflow-hidden shadow-sm relative">
                        <div id="admin-map" style="height: 350px; width: 100%; z-index: 10;"></div>

                        <div class="absolute top-2 right-2 z-[1000] flex items-center gap-2">
                            <button type="button" id="toggle-existing-btn" onclick="toggleExistingMarkers()" class="px-3 py-1.5 bg-white/95 border border-zinc-200 rounded-lg text-xs font-semibold shadow-md outline-none cursor-pointer text-zinc-700 hover:bg-zinc-50 transition-all flex items-center gap-1.5">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-zinc-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <span>Sembunyikan Marker Lain</span>
                            </button>

                            <select onchange="switchAdminLayer(this.value)" class="px-3 py-1.5 bg-white/95 border border-zinc-200 rounded-lg text-xs font-semibold shadow-md outline-none cursor-pointer text-zinc-700">
                                <option value="road">Lapisan Default</option>
                                <option value="satellite">Lapisan Satelit</option>
                                <option value="terrain">Lapisan Medan</option>
                            </select>
                        </div>

                        <div class="absolute bottom-2 left-2 z-[1000] bg-white/95 backdrop-blur-xs border border-zinc-200 rounded-xl p-2 shadow-md flex flex-col gap-1.5 max-w-[280px]">
                            <div class="flex items-center gap-1.5 border-b border-zinc-100 pb-1.5">
                                <button type="button" id="btn-undo" onclick="undo()" class="p-1.5 bg-zinc-100 hover:bg-zinc-200 text-zinc-700 rounded transition flex items-center justify-center disabled:opacity-40 disabled:cursor-not-allowed" title="Undo (Ctrl+Z)" disabled>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 7v6h6"/><path d="M21 17a9 9 0 0 0-9-9 9 9 0 0 0-6 2.3L3 13"/></svg>
                                </button>
                                <button type="button" id="btn-redo" onclick="redo()" class="p-1.5 bg-zinc-100 hover:bg-zinc-200 text-zinc-700 rounded transition flex items-center justify-center disabled:opacity-40 disabled:cursor-not-allowed" title="Redo (Ctrl+Y)" disabled>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m21 7-6 6h6"/><path d="M3 17a9 9 0 0 1 9-9 9 9 0 0 1 6 2.3l3 2.7"/></svg>
                                </button>
                                <div class="w-px h-3.5 bg-zinc-200 mx-0.5"></div>
                                <button type="button" onclick="resetDrawingWithHistory()" class="px-2.5 py-0.5 bg-red-50 hover:bg-red-100 text-red-600 rounded text-[10px] font-bold transition" title="Reset Gambar">
                                    Reset
                                </button>
                            </div>
                            <span id="draw-info" class="text-[10px] text-zinc-500 font-medium leading-tight">Klik pada peta untuk menempatkan titik marker.</span>
                        </div>
                    </div>
                </div>

                <input type="hidden" id="geojson" name="geojson" value="{{ old('geojson', $map->geojson) }}" />

                <div id="coords-input-container" class="space-y-6">
                    <div class="space-y-2">
                        <label for="coordinates" class="block text-sm font-bold text-zinc-700 font-space ml-1">Koordinat Manual (Latitude, Longitude)</label>
                        <input id="coordinates" type="text" value="{{ old('coordinates', $map->latitude . ', ' . $map->longitude) }}"
                            class="w-full px-4 py-3 bg-white/50 border border-zinc-300 rounded-xl focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all outline-none text-zinc-800 shadow-sm placeholder-zinc-400"
                            placeholder="1.269936, 109.485157 atau klik peta di atas" />
                        <p class="text-xs text-zinc-400 ml-1">Otomatis terisi ketika Anda mengklik peta di atas.</p>
                    </div>

                    <div id="latlng-container" class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="space-y-2">
                            <label for="latitude" class="block text-sm font-bold text-zinc-700 font-space ml-1">Latitude</label>
                            <input id="latitude" type="number" step="any" name="latitude" value="{{ old('latitude', $map->latitude) }}"
                                class="w-full px-4 py-3 bg-white/50 border border-zinc-300 rounded-xl focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all outline-none text-zinc-800 shadow-sm placeholder-zinc-400" placeholder="1.269936" />
                            @error('latitude') <span class="text-xs text-red-500 font-medium ml-1">{{ $message }}</span> @enderror
                        </div>
                        <div class="space-y-2">
                            <label for="longitude" class="block text-sm font-bold text-zinc-700 font-space ml-1">Longitude</label>
                            <input id="longitude" type="number" step="any" name="longitude" value="{{ old('longitude', $map->longitude) }}"
                                class="w-full px-4 py-3 bg-white/50 border border-zinc-300 rounded-xl focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all outline-none text-zinc-800 shadow-sm placeholder-zinc-400" placeholder="109.485157" />
                            @error('longitude') <span class="text-xs text-red-500 font-medium ml-1">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <div class="space-y-2" id="type-wrapper">
                    <label for="type" class="block text-sm font-bold text-zinc-700 font-space ml-1">Kategori/Tipe Marker</label>
                    <input id="type" type="text" name="type" list="types-list" value="{{ old('type', $map->type) }}" required
                        class="w-full px-4 py-3 bg-white/50 border border-zinc-300 rounded-xl focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all outline-none text-zinc-800 shadow-sm placeholder-zinc-400" placeholder="Ketik atau pilih kategori (misal: Area Koleksi, Spot Foto, dll.)" />
                    <datalist id="types-list">
                        @foreach($existingTypes ?? ['area_koleksi', 'fasilitas_umum', 'kantor_pengelola', 'pos_keamanan'] as $t)
                            <option value="{{ $t }}"></option>
                        @endforeach
                    </datalist>
                    @error('type') <span class="text-xs text-red-500 font-medium ml-1">{{ $message }}</span> @enderror
                </div>

                <div class="space-y-2 hidden" id="jenis-jalan-wrapper">
                    <label for="jenis_jalan" class="block text-sm font-bold text-zinc-700 font-space ml-1">Jenis Jalan</label>
                    <select id="jenis_jalan" class="w-full px-4 py-3 bg-white/50 border border-zinc-300 rounded-xl focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all outline-none text-zinc-800 shadow-sm cursor-pointer">
                        <option value="jalan_utama" {{ old('type', $map->type) === 'jalan_utama' ? 'selected' : '' }}>Jalan Utama (Sinkronisasi Navigasi - Kuning/Oranye)</option>
                        <option value="jalan_lain" {{ old('type', $map->type) === 'jalan_lain' ? 'selected' : '' }}>Jalan Lain (Penghubung Tipis - Abu-abu)</option>
                    </select>
                </div>

                <div class="space-y-2" id="desc-wrapper">
                    <label for="description" class="block text-sm font-bold text-zinc-700 font-space ml-1">Deskripsi (Opsional)</label>
                    <textarea id="description" name="description" rows="3"
                        class="w-full px-4 py-3 bg-white/50 border border-zinc-300 rounded-xl focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all outline-none text-zinc-800 shadow-sm placeholder-zinc-400 resize-none">{{ old('description', $map->description) }}</textarea>
                    @error('description') <span class="text-xs text-red-500 font-medium ml-1">{{ $message }}</span> @enderror
                </div>

                <div class="space-y-2" id="color-wrapper">
                    <label for="color" class="block text-sm font-bold text-zinc-700 font-space ml-1">Warna Marker</label>
                    <div class="flex items-center gap-3">
                        <input id="color" type="color" name="color" value="{{ old('color', $map->color) }}"
                            class="w-16 h-12 bg-white/50 border border-zinc-300 rounded-xl cursor-pointer" />
                        <input id="color-text" type="text" value="{{ old('color', $map->color) }}"
                            class="flex-1 px-4 py-3 bg-white/50 border border-zinc-300 rounded-xl focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all outline-none text-zinc-800 shadow-sm placeholder-zinc-400 font-mono text-sm"
                            placeholder="#3b82f6" pattern="^#[0-9A-Fa-f]{6}$" readonly />
                    </div>
                    @error('color') <span class="text-xs text-red-500 font-medium ml-1">{{ $message }}</span> @enderror
                </div>

                <div class="space-y-2" id="photo-wrapper">
                    <label for="photo" class="block text-sm font-bold text-zinc-700 font-space ml-1">Foto Marker (Opsional)</label>
                    @if($map->photo)
                        <div class="mb-3">
                            <img src="{{ \Illuminate\Support\Facades\Storage::url($map->photo) }}" alt="{{ $map->name }}" class="w-full h-48 object-cover rounded-xl border border-zinc-200 shadow-sm">
                            <p class="text-xs text-zinc-500 mt-2">Foto saat ini</p>
                        </div>
                    @endif
                    <input id="photo" type="file" name="photo" accept="image/*"
                        class="w-full px-4 py-3 bg-white/50 border border-zinc-300 rounded-xl focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all outline-none text-zinc-800 shadow-sm file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-zinc-100 file:text-zinc-700 hover:file:bg-zinc-200 cursor-pointer" />
                    @error('photo') <span class="text-xs text-red-500 font-medium ml-1">{{ $message }}</span> @enderror
                    <p class="text-xs text-zinc-400 ml-1">Format: JPEG, PNG, JPG, GIF (maks. 2MB). Kosongkan jika tidak ingin mengubah foto.</p>
                </div>

                <div class="pt-6 flex justify-end gap-3">
                    <a href="{{ route('admin.maps.index') }}" class="px-6 py-3 bg-white border-2 border-zinc-200 text-zinc-700 font-bold rounded-xl hover:bg-zinc-50 hover:border-zinc-300 transition-all font-space">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-3 bg-zinc-900 hover:bg-zinc-800 text-white font-bold rounded-xl transition-all transform hover:scale-[1.02] active:scale-[0.98] font-space shadow-lg shadow-zinc-900/20">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/localforage/1.10.0/localforage.min.js"></script>
    <script src="{{ asset('js/offline-maps.js') }}"></script>
    <script>

        document.getElementById('color').addEventListener('input', function(e) {
            document.getElementById('color-text').value = e.target.value;
        });

        document.getElementById('coordinates').addEventListener('input', function(e) {
            const value = e.target.value.trim();
            if (value.includes(',')) {
                const parts = value.split(',').map(p => p.trim());
                if (parts.length >= 2) {
                    document.getElementById('latitude').value = parts[0] || '';
                    document.getElementById('longitude').value = parts[1] || '';
                }
            }
        });

        var mapCenter = [1.2706202914994014, 109.48517276551188];

        var initialLat = "{{ $map->latitude }}";
        var initialLng = "{{ $map->longitude }}";
        var initialGeomType = "{{ $map->geometry_type }}";
        var initialGeojson = `{!! $map->geojson !!}`;

        if (initialGeomType === 'point' && initialLat && initialLng) {
            mapCenter = [parseFloat(initialLat), parseFloat(initialLng)];
        } else if (initialGeojson) {
            try {
                var coords = JSON.parse(initialGeojson);
                if (coords.length > 0) mapCenter = coords[0];
            } catch(e) {}
        }

        var adminMap = L.map('admin-map', { zoomControl: false }).setView(mapCenter, 15);
        L.control.zoom({ position: 'bottomright' }).addTo(adminMap);

        var roadLayer = L.tileLayer.offline('https://tile.openstreetmap.org/{z}/{x}/{y}.png', { minZoom: 8, maxNativeZoom: 19, maxZoom: 20, attribution: '&copy; OpenStreetMap', crossOrigin: true });
        var satelliteLayer = L.tileLayer.offline('https://mt1.google.com/vt/lyrs=s&x={x}&y={y}&z={z}', { minZoom: 8, maxNativeZoom: 20, maxZoom: 20, attribution: '&copy; Google Satellite', crossOrigin: true });
        var terrainLayer = L.tileLayer.offline('https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png', { minZoom: 8, maxNativeZoom: 17, maxZoom: 17, attribution: 'Map data: &copy; OpenStreetMap', subdomains: 'abc', crossOrigin: true });

        var currentLayer = roadLayer.addTo(adminMap);

        var existingLayerGroup = L.layerGroup().addTo(adminMap);
        var existingMarkersData = @json($existingMarkers ?? []);

        existingMarkersData.forEach(function(m) {
            var layer = null;
            if (m.geometry_type === 'point' && m.latitude && m.longitude) {
                var latlng = [parseFloat(m.latitude), parseFloat(m.longitude)];
                layer = L.marker(latlng, {
                    icon: L.divIcon({
                        className: 'custom-div-icon',
                        html: `<div style="background-color: ${m.color || '#3b82f6'}; width: 12px; height: 12px; border-radius: 50%; border: 2px solid white; box-shadow: 0 0 4px rgba(0,0,0,0.4);"></div>`,
                        iconSize: [12, 12],
                        iconAnchor: [6, 6]
                    })
                }).bindTooltip(m.name, { direction: 'top' });
            } else if (m.geojson) {
                try {
                    var coords = JSON.parse(m.geojson);
                    if (coords.length > 0) {
                        if (m.geometry_type === 'polygon') {
                            layer = L.polygon(coords, {
                                color: m.color || '#3b82f6',
                                fillColor: m.color || '#3b82f6',
                                fillOpacity: 0.1,
                                weight: 2
                            }).bindTooltip(m.name, { sticky: true });
                        } else {
                            layer = L.polyline(coords, {
                                color: m.color || '#3b82f6',
                                weight: 3
                            }).bindTooltip(m.name, { sticky: true });
                        }
                    }
                } catch(e) {
                    console.error("Gagal menggambar existing marker:", e);
                }
            }
            if (layer) {
                existingLayerGroup.addLayer(layer);
            }
        });

        var showExisting = true;
        function toggleExistingMarkers() {
            showExisting = !showExisting;
            var btn = document.getElementById('toggle-existing-btn');

            if (showExisting) {
                adminMap.addLayer(existingLayerGroup);
                btn.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-zinc-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <span>Sembunyikan Marker Lain</span>
                `;
            } else {
                adminMap.removeLayer(existingLayerGroup);
                btn.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-zinc-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                    </svg>
                    <span>Tampilkan Marker Lain</span>
                `;
            }
        }

        function downloadAdminMap() {
            if (typeof window.downloadOfflineMaps === 'undefined') {
                alert('Pustaka Offline Maps belum dimuat!');
                return;
            }
            var btn = document.getElementById('btn-download-map');
            var text = document.getElementById('download-map-text');
            btn.disabled = true;
            text.innerText = "Loading...";

            var currentZ = adminMap.getZoom();
            var zooms = [currentZ - 2, currentZ - 1, currentZ, currentZ + 1, currentZ + 2];
            window.downloadOfflineMaps(
                adminMap,
                [roadLayer, satelliteLayer, terrainLayer],
                zooms,
                {
                    onProgress: function(d, f, t, p) { text.innerText = `${p}%`; },
                    onSuccess: function() {
                        text.innerText = "Unduh Area";
                        btn.disabled = false;
                        alert("Semua lapisan peta berhasil diunduh untuk area ini!");
                    },
                    onError: function(msg) {
                        text.innerText = "Unduh Area";
                        btn.disabled = false;
                        alert(msg);
                    }
                }
            );
        }

        function switchAdminLayer(mode) {
            adminMap.removeLayer(currentLayer);
            if(mode === 'satellite') currentLayer = satelliteLayer;
            else if(mode === 'terrain') currentLayer = terrainLayer;
            else currentLayer = roadLayer;
            currentLayer.addTo(adminMap);
        }

        var currentGeomType = initialGeomType || 'point';
        var drawnItems = L.featureGroup().addTo(adminMap);
        var clickedCoords = [];

        var tempMarker = null;
        var tempPolyline = null;
        var tempPolygon = null;

        var historyStack = [];
        var redoStack = [];

        function saveState() {
            var state = {
                geomType: currentGeomType,
                coords: [...clickedCoords],
                markerCoords: tempMarker ? [tempMarker.getLatLng().lat, tempMarker.getLatLng().lng] : null
            };
            historyStack.push(state);
            redoStack = [];
            updateUndoRedoButtons();
        }

        function undo() {
            if (historyStack.length === 0) return;

            var currentState = {
                geomType: currentGeomType,
                coords: [...clickedCoords],
                markerCoords: tempMarker ? [tempMarker.getLatLng().lat, tempMarker.getLatLng().lng] : null
            };
            redoStack.push(currentState);

            var prevState = historyStack.pop();
            applyState(prevState);
        }

        function redo() {
            if (redoStack.length === 0) return;

            var currentState = {
                geomType: currentGeomType,
                coords: [...clickedCoords],
                markerCoords: tempMarker ? [tempMarker.getLatLng().lat, tempMarker.getLatLng().lng] : null
            };
            historyStack.push(currentState);

            var nextState = redoStack.pop();
            applyState(nextState);
        }

        function applyState(state) {
            if (state.geomType !== currentGeomType) {
                currentGeomType = state.geomType;
                document.getElementById('geometry_type').value = currentGeomType;
                changeGeometryType(currentGeomType, false);
            }

            if (currentGeomType === 'point') {
                drawnItems.clearLayers();
                if (state.markerCoords) {
                    var latlng = L.latLng(state.markerCoords[0], state.markerCoords[1]);
                    createDraggableMarker(latlng);
                    updateCoordsInputs(latlng.lat, latlng.lng);
                } else {
                    tempMarker = null;
                    updateCoordsInputs(null, null);
                }
            } else {
                clickedCoords = [...state.coords];
                renderShape();
            }
            updateUndoRedoButtons();
        }

        function updateUndoRedoButtons() {
            var btnUndo = document.getElementById('btn-undo');
            var btnRedo = document.getElementById('btn-redo');
            if (btnUndo) btnUndo.disabled = historyStack.length === 0;
            if (btnRedo) btnRedo.disabled = redoStack.length === 0;
        }

        function updateCoordsInputs(lat, lng) {
            if (lat !== null && lng !== null) {
                document.getElementById('latitude').value = lat.toFixed(8);
                document.getElementById('longitude').value = lng.toFixed(8);
                document.getElementById('coordinates').value = `${lat.toFixed(8)}, ${lng.toFixed(8)}`;
            } else {
                document.getElementById('latitude').value = '';
                document.getElementById('longitude').value = '';
                document.getElementById('coordinates').value = '';
            }
        }

        function createDraggableMarker(latlng) {
            drawnItems.clearLayers();
            tempMarker = L.marker(latlng, { draggable: true }).addTo(drawnItems);

            var dragStartLatLng = null;
            tempMarker.on('dragstart', function() {
                dragStartLatLng = tempMarker.getLatLng();
            });

            tempMarker.on('dragend', function() {
                var newLatLng = tempMarker.getLatLng();
                var snapped = getSnappedLatLng(newLatLng);
                tempMarker.setLatLng(snapped);

                var state = {
                    geomType: 'point',
                    coords: [],
                    markerCoords: [dragStartLatLng.lat, dragStartLatLng.lng]
                };
                historyStack.push(state);
                redoStack = [];

                updateCoordsInputs(snapped.lat, snapped.lng);
                updateUndoRedoButtons();
            });
        }

        if (currentGeomType === 'point' && initialLat && initialLng) {
            createDraggableMarker([parseFloat(initialLat), parseFloat(initialLng)]);
        } else if (initialGeojson) {
            try {
                clickedCoords = JSON.parse(initialGeojson);
                renderShape();
            } catch(e) {
                console.error("Gagal parse geojson:", e);
            }
        }

        function changeGeometryType(type, clearHistory = true) {
            currentGeomType = type;

            if (clearHistory) {
                resetDrawing();
                historyStack = [];
                redoStack = [];
                updateUndoRedoButtons();
            }

            var coordsInputContainer = document.getElementById('coords-input-container');
            var drawInfo = document.getElementById('draw-info');

            var typeWrapper = document.getElementById('type-wrapper');
            var jenisJalanWrapper = document.getElementById('jenis-jalan-wrapper');
            var descWrapper = document.getElementById('desc-wrapper');
            var colorWrapper = document.getElementById('color-wrapper');
            var photoWrapper = document.getElementById('photo-wrapper');

            var typeInput = document.getElementById('type');
            var jenisJalanInput = document.getElementById('jenis_jalan');

            if (type === 'point') {
                if(coordsInputContainer) coordsInputContainer.classList.remove('hidden');
                drawInfo.innerText = "Klik pada peta untuk menempatkan titik marker (seret pin untuk menyesuaikan).";

                if (descWrapper) descWrapper.classList.remove('hidden');
                if (photoWrapper) photoWrapper.classList.remove('hidden');
                if (typeWrapper) typeWrapper.classList.remove('hidden');
                if (colorWrapper) colorWrapper.classList.remove('hidden');
                if (jenisJalanWrapper) jenisJalanWrapper.classList.add('hidden');

                if (typeInput.value === 'jalan_utama' || typeInput.value === 'jalan_lain') {
                    typeInput.value = '';
                }

                document.getElementById('description').removeAttribute('disabled');
                document.getElementById('photo').removeAttribute('disabled');
                document.getElementById('coordinates').removeAttribute('disabled');
                document.getElementById('latitude').removeAttribute('disabled');
                document.getElementById('longitude').removeAttribute('disabled');
            } else {
                if(coordsInputContainer) coordsInputContainer.classList.add('hidden');

                if (descWrapper) descWrapper.classList.add('hidden');
                if (photoWrapper) photoWrapper.classList.add('hidden');

                document.getElementById('description').setAttribute('disabled', 'true');
                document.getElementById('photo').setAttribute('disabled', 'true');
                document.getElementById('coordinates').setAttribute('disabled', 'true');
                document.getElementById('latitude').setAttribute('disabled', 'true');
                document.getElementById('longitude').setAttribute('disabled', 'true');

                if (type === 'polyline' || type === 'linestring') {
                    drawInfo.innerText = "Klik peta berulang kali (seperti pen tool) untuk menggambar garis rute/jalan.";
                    if (typeWrapper) typeWrapper.classList.add('hidden');
                    if (colorWrapper) colorWrapper.classList.add('hidden');
                    if (jenisJalanWrapper) {
                        jenisJalanWrapper.classList.remove('hidden');
                        typeInput.value = jenisJalanInput.value;
                    }
                } else {
                    drawInfo.innerText = "Klik peta berulang kali (seperti pen tool) untuk menggambar poligon wilayah.";
                    if (typeWrapper) typeWrapper.classList.remove('hidden');
                    if (colorWrapper) colorWrapper.classList.remove('hidden');
                    if (jenisJalanWrapper) jenisJalanWrapper.classList.add('hidden');

                    if (typeInput.value === 'jalan_utama' || typeInput.value === 'jalan_lain') {
                        typeInput.value = '';
                    }
                }
            }
        }

        document.getElementById('jenis_jalan').addEventListener('change', function(e) {
            document.getElementById('type').value = e.target.value;
            renderShape();
        });

        var snapTolerance = 15;
        function getSnappedLatLng(latlng) {
            var minDistance = Infinity;
            var snappedLatLng = latlng;
            var clickPoint = adminMap.latLngToContainerPoint(latlng);

            if (typeof existingLayerGroup !== 'undefined') {
                existingLayerGroup.eachLayer(function(layer) {
                    if (layer instanceof L.Marker) {
                        var p = adminMap.latLngToContainerPoint(layer.getLatLng());
                        var dist = clickPoint.distanceTo(p);
                        if (dist < snapTolerance && dist < minDistance) {
                            minDistance = dist;
                            snappedLatLng = layer.getLatLng();
                        }
                    } else if (layer instanceof L.Polyline || layer instanceof L.Polygon) {
                        var latlngs = layer.getLatLngs();
                        var flatLatLngs = latlngs.flat(Infinity);
                        flatLatLngs.forEach(function(ll) {
                            var p = adminMap.latLngToContainerPoint(ll);
                            var dist = clickPoint.distanceTo(p);
                            if (dist < snapTolerance && dist < minDistance) {
                                minDistance = dist;
                                snappedLatLng = ll;
                            }
                        });
                    }
                });
            }

            clickedCoords.forEach(function(coord) {
                var ll = L.latLng(coord[0], coord[1]);
                var p = adminMap.latLngToContainerPoint(ll);
                var dist = clickPoint.distanceTo(p);
                if (dist < snapTolerance && dist < minDistance) {
                    minDistance = dist;
                    snappedLatLng = ll;
                }
            });

            return snappedLatLng;
        }

        adminMap.on('click', function(e) {
            var snapped = getSnappedLatLng(e.latlng);
            var lat = snapped.lat;
            var lng = snapped.lng;

            if (currentGeomType === 'point') {
                saveState();
                createDraggableMarker(snapped);
                updateCoordsInputs(lat, lng);
            } else {
                saveState();
                clickedCoords.push([lat, lng]);
                renderShape();
            }
        });

        function renderShape() {
            drawnItems.clearLayers();

            if (clickedCoords.length === 0) return;

            // Gambar titik anchor (vertex)
            clickedCoords.forEach(function(coord, idx) {
                L.circleMarker(coord, {
                    radius: 5,
                    color: '#1e293b',
                    fillColor: '#3b82f6',
                    fillOpacity: 1,
                    weight: 2
                }).addTo(drawnItems);
            });

            var colorVal = document.getElementById('color').value || '#3b82f6';

            if (currentGeomType === 'polyline' || currentGeomType === 'linestring') {
                if (clickedCoords.length >= 2) {
                    var roadType = document.getElementById('jenis_jalan').value;
                    var roadColor = '#808080';
                    var roadWeight = 4.5;
                    if (roadType === 'jalan_utama') {
                        roadColor = '#808080';
                        roadWeight = 6;
                    } else if (roadType === 'jalan_lain') {
                        roadColor = '#808080';
                        roadWeight = 3;
                    }
                    tempPolyline = L.polyline(clickedCoords, { color: roadColor, weight: roadWeight }).addTo(drawnItems);
                }
                document.getElementById('geojson').value = JSON.stringify(clickedCoords);
            } else if (currentGeomType === 'polygon') {
                if (clickedCoords.length >= 3) {
                    tempPolygon = L.polygon(clickedCoords, { color: colorVal, fillColor: colorVal, fillOpacity: 0.15, weight: 3 }).addTo(drawnItems);
                }
                document.getElementById('geojson').value = JSON.stringify(clickedCoords);
            }
        }

        document.getElementById('color').addEventListener('input', function() {
            renderShape();
        });

        function resetDrawing() {
            clickedCoords = [];
            drawnItems.clearLayers();
            tempMarker = null;
            document.getElementById('geojson').value = '';
            if (document.getElementById('latitude')) document.getElementById('latitude').value = '';
            if (document.getElementById('longitude')) document.getElementById('longitude').value = '';
            if (document.getElementById('coordinates')) document.getElementById('coordinates').value = '';
        }

        function resetDrawingWithHistory() {
            saveState();
            resetDrawing();
        }

        document.addEventListener('keydown', function(e) {
            if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'z') {
                e.preventDefault();
                undo();
            }
            if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'y') {
                e.preventDefault();
                redo();
            }
        });

        changeGeometryType(document.getElementById('geometry_type').value, false);

        document.querySelector('form').addEventListener('submit', function(e) {
            if (currentGeomType === 'polyline' || currentGeomType === 'linestring') {
                var roadType = document.getElementById('jenis_jalan').value;
                document.getElementById('type').value = roadType;
                document.getElementById('color').value = '#808080';
            }
        });

        // Auto-download Kebun Raya Sambas tiles on page load (background)
        setTimeout(function() {
            if (window.autoDownloadKRS) {
                window.autoDownloadKRS(adminMap, [roadLayer, satelliteLayer, terrainLayer]);
            }
        }, 3000);
    </script>

</x-dashboard-layout>

