<x-dashboard-layout title="Tambah Marker Peta">
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
                <h3 class="text-2xl font-bold font-space text-zinc-900 tracking-tight">Tambah Marker Peta</h3>
                <p class="text-zinc-500 text-sm font-inter">Isi formulir berikut untuk menambahkan marker baru di peta digital.</p>
            </div>

            <form method="POST" action="{{ route('admin.maps.store') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <!-- 1. Tipe Geometri (Atas) -->
                <div class="space-y-2">
                    <label for="geometry_type" class="block text-sm font-bold text-zinc-700 font-space ml-1">Tipe Geometri</label>
                    <div class="relative">
                        <select name="geometry_type" id="geometry_type" onchange="changeGeometryType(this.value)" class="w-full px-4 py-3 bg-white/50 border border-zinc-300 rounded-xl focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all outline-none text-zinc-800 shadow-sm appearance-none cursor-pointer">
                            <option value="point" {{ old('geometry_type') === 'point' ? 'selected' : '' }}>Point (Marker Lokasi Bangunan/Tanaman)</option>
                            <option value="linestring" {{ old('geometry_type') === 'linestring' || old('geometry_type') === 'polyline' ? 'selected' : '' }}>LineString (Garis Jalan/Jalur Navigasi)</option>
                            <option value="polygon" {{ old('geometry_type') === 'polygon' ? 'selected' : '' }}>Polygon (Batas Wilayah / VAK)</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-zinc-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                    @error('geometry_type') <span class="text-xs text-red-500 font-medium ml-1">{{ $message }}</span> @enderror
                </div>

                <!-- 2. Jenis / Kategori Marker (Tengah) -->
                <div class="space-y-2" id="type-wrapper">
                    <label for="type" class="block text-sm font-bold text-zinc-700 font-space ml-1">Kategori/Tipe Marker</label>
                    <input id="type" type="text" name="type" list="types-list" value="{{ old('type', 'VAK') }}" required oninput="handleTypeChange()"
                        class="w-full px-4 py-3 bg-white/50 border border-zinc-300 rounded-xl focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all outline-none text-zinc-800 shadow-sm placeholder-zinc-400" placeholder="Ketik atau pilih kategori (misal: Batas Wilayah, VAK, dll.)" />
                    <datalist id="types-list">
                        <option value="Batas Wilayah">Batas Wilayah (Garis Putus-putus)</option>
                        <option value="VAK">VAK (Garis Solid / Utuh)</option>
                        <option value="Jalan Utama">Jalan Utama (Jalur Navigasi)</option>
                        <option value="Jalan Lain">Jalan Lain (Jalur Penghubung)</option>
                        @foreach($existingTypes ?? ['Area Koleksi', 'Fasilitas Umum', 'Kantor Pengelola', 'Pos Keamanan'] as $t)
                            <option value="{{ Str::title(str_replace('_', ' ', $t)) }}"></option>
                        @endforeach
                    </datalist>
                    @error('type') <span class="text-xs text-red-500 font-medium ml-1">{{ $message }}</span> @enderror
                </div>

                <div class="space-y-2 hidden" id="jenis-polygon-wrapper">
                    <label for="jenis_polygon" class="block text-sm font-bold text-zinc-700 font-space ml-1">Jenis Tampilan Polygon Area</label>
                    <select id="jenis_polygon" onchange="document.getElementById('type').value = this.value; handleTypeChange();" class="w-full px-4 py-3 bg-white/50 border border-zinc-300 rounded-xl focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all outline-none text-zinc-800 shadow-sm cursor-pointer">
                        <option value="Batas Wilayah" {{ old('type') === 'Batas Wilayah' || old('type') === 'batas_wilayah' ? 'selected' : '' }}>Batas Wilayah (Garis Putus-putus / Dashed)</option>
                        <option value="VAK" {{ old('type') === 'VAK' || old('type') === 'vak_zona' || old('type') === 'vak' || !old('type') ? 'selected' : '' }}>VAK (Garis Utuh / Solid)</option>
                    </select>
                </div>

                <div class="space-y-2 hidden" id="jenis-jalan-wrapper">
                    <label for="jenis_jalan" class="block text-sm font-bold text-zinc-700 font-space ml-1">Jenis Jalan</label>
                    <select id="jenis_jalan" onchange="document.getElementById('type').value = this.value; handleTypeChange();" class="w-full px-4 py-3 bg-white/50 border border-zinc-300 rounded-xl focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all outline-none text-zinc-800 shadow-sm cursor-pointer">
                        <option value="Jalan Utama" {{ old('type') === 'Jalan Utama' || old('type') === 'jalan_utama' ? 'selected' : '' }}>Jalan Utama (Sinkronisasi Navigasi - Abu-abu)</option>
                        <option value="Jalan Lain" {{ old('type') === 'Jalan Lain' || old('type') === 'jalan_lain' ? 'selected' : '' }}>Jalan Lain (Penghubung Tipis - Abu-abu Muda)</option>
                    </select>
                </div>

                <!-- 3. Nama Marker (Di Bawah Jenis) -->
                <div class="space-y-2">
                    <label for="name" class="block text-sm font-bold text-zinc-700 font-space ml-1">Nama Marker</label>
                    <input type="hidden" id="name" name="name" value="{{ old('name') }}" />

                    <!-- Input biasa untuk Non-VAK -->
                    <div id="single-name-container">
                        <input id="name_single" type="text" value="{{ old('name') }}" oninput="updateCombinedName()"
                            class="w-full px-4 py-3 bg-white/50 border border-zinc-300 rounded-xl focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all outline-none text-zinc-800 shadow-sm placeholder-zinc-400" placeholder="Contoh: Area Koleksi A, Batas Wilayah KRS, Pos Utama" />
                    </div>

                    <!-- Input 2 bagian terpisah khusus VAK Polygon -->
                    <div id="vak-name-container" class="hidden flex items-center gap-3">
                        <div class="w-28 shrink-0">
                            <input type="text" value="VAK" readonly disabled
                                class="w-full px-4 py-3 bg-zinc-100 border border-zinc-300 rounded-xl text-zinc-700 font-extrabold font-space text-center select-none cursor-not-allowed" />
                        </div>
                        <div class="flex-1">
                            <input id="vak_number_input" type="text" value="{{ old('vak_number') }}" oninput="updateCombinedName()"
                                class="w-full px-4 py-3 bg-white/50 border border-zinc-300 rounded-xl focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all outline-none text-zinc-800 shadow-sm placeholder-zinc-400 font-bold" placeholder="Ketik penomoran VAK (misal: II, I, A1)" />
                        </div>
                    </div>
                    @error('name') <span class="text-xs text-red-500 font-medium ml-1">{{ $message }}</span> @enderror
                </div>

                <!-- 4. Peta Drawing Pen Tool -->
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

                <input type="hidden" id="geojson" name="geojson" value="{{ old('geojson') }}" />

                <!-- 5. Koordinat Manual -->
                <div id="coords-input-container" class="space-y-6">
                    <div class="space-y-2">
                        <label for="coordinates" class="block text-sm font-bold text-zinc-700 font-space ml-1">Koordinat Manual (Latitude, Longitude)</label>
                        <input id="coordinates" type="text" value="{{ old('coordinates') }}"
                            class="w-full px-4 py-3 bg-white/50 border border-zinc-300 rounded-xl focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all outline-none text-zinc-800 shadow-sm placeholder-zinc-400"
                            placeholder="1.269936, 109.485157 atau klik peta di atas" />
                        <p class="text-xs text-zinc-400 ml-1">Otomatis terisi ketika Anda mengklik peta di atas.</p>
                    </div>

                    <div id="latlng-container" class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="space-y-2">
                            <label for="latitude" class="block text-sm font-bold text-zinc-700 font-space ml-1">Latitude</label>
                            <input id="latitude" type="number" step="any" name="latitude" value="{{ old('latitude') }}"
                                class="w-full px-4 py-3 bg-white/50 border border-zinc-300 rounded-xl focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all outline-none text-zinc-800 shadow-sm placeholder-zinc-400" placeholder="1.269936" />
                            @error('latitude') <span class="text-xs text-red-500 font-medium ml-1">{{ $message }}</span> @enderror
                        </div>
                        <div class="space-y-2">
                            <label for="longitude" class="block text-sm font-bold text-zinc-700 font-space ml-1">Longitude</label>
                            <input id="longitude" type="number" step="any" name="longitude" value="{{ old('longitude') }}"
                                class="w-full px-4 py-3 bg-white/50 border border-zinc-300 rounded-xl focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all outline-none text-zinc-800 shadow-sm placeholder-zinc-400" placeholder="109.485157" />
                            @error('longitude') <span class="text-xs text-red-500 font-medium ml-1">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <!-- 6. Deskripsi (Opsional) -->
                <div class="space-y-2" id="desc-wrapper">
                    <label for="description" class="block text-sm font-bold text-zinc-700 font-space ml-1">Deskripsi (Opsional)</label>
                    <textarea id="description" name="description" rows="3"
                        class="w-full px-4 py-3 bg-white/50 border border-zinc-300 rounded-xl focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all outline-none text-zinc-800 shadow-sm placeholder-zinc-400 resize-none">{{ old('description') }}</textarea>
                    @error('description') <span class="text-xs text-red-500 font-medium ml-1">{{ $message }}</span> @enderror
                </div>

                <!-- 7. Warna Marker -->
                <div class="space-y-2" id="color-wrapper">
                    <label for="color" class="block text-sm font-bold text-zinc-700 font-space ml-1">Warna Marker</label>
                    <div class="flex items-center gap-3">
                        <input id="color" type="color" name="color" value="{{ old('color', '#3b82f6') }}"
                            class="w-16 h-12 bg-white/50 border border-zinc-300 rounded-xl cursor-pointer" />
                        <input id="color-text" type="text" value="{{ old('color', '#3b82f6') }}"
                            class="flex-1 px-4 py-3 bg-white/50 border border-zinc-300 rounded-xl focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all outline-none text-zinc-800 shadow-sm placeholder-zinc-400 font-mono text-sm"
                            placeholder="#3b82f6" pattern="^#[0-9A-Fa-f]{6}$" readonly />
                    </div>
                    @error('color') <span class="text-xs text-red-500 font-medium ml-1">{{ $message }}</span> @enderror
                    <p class="text-xs text-zinc-400 ml-1">Pilih warna untuk marker (format hex: #3b82f6)</p>
                </div>

                <!-- 8. Foto Marker (Opsional) -->
                <div class="space-y-2" id="photo-wrapper">
                    <label for="photo" class="block text-sm font-bold text-zinc-700 font-space ml-1">Foto Marker (Opsional)</label>
                    <input id="photo" type="file" name="photo" accept="image/*"
                        class="w-full px-4 py-3 bg-white/50 border border-zinc-300 rounded-xl focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all outline-none text-zinc-800 shadow-sm file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-zinc-100 file:text-zinc-700 hover:file:bg-zinc-200 cursor-pointer" />
                    @error('photo') <span class="text-xs text-red-500 font-medium ml-1">{{ $message }}</span> @enderror
                    <p class="text-xs text-zinc-400 ml-1">Format: JPEG, PNG, JPG, GIF, WEBP, AVIF (maks. 10MB)</p>
                </div>

                <div class="pt-6 flex justify-end">
                    <button type="submit" class="px-8 py-3.5 bg-zinc-900 hover:bg-zinc-800 text-white font-bold rounded-xl transition-all transform hover:scale-[1.02] active:scale-[0.98] font-space shadow-lg shadow-zinc-900/20">
                        Simpan Marker
                    </button>
                </div>
            </form>
        </div>
    </div>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        .leaflet-container:focus,
        .leaflet-container *:focus,
        .leaflet-interactive:focus,
        path.leaflet-interactive:focus,
        svg.leaflet-zoom-animated path:focus {
            outline: none !important;
            box-shadow: none !important;
        }
    </style>
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
                const parts = value.split(',');
                const lat = parseFloat(parts[0].trim());
                const lng = parseFloat(parts[1].trim());

                if (!isNaN(lat) && !isNaN(lng)) {
                    document.getElementById('latitude').value = lat;
                    document.getElementById('longitude').value = lng;
                    if (drawingLayer) {
                        adminMap.removeLayer(drawingLayer);
                    }
                    drawingLayer = L.marker([lat, lng]).addTo(adminMap);
                    adminMap.panTo([lat, lng]);
                    updateGeoJSONInput();
                }
            }
        });

        function isVakPolygon() {
            var geomType = document.getElementById('geometry_type').value;
            var typeVal = (document.getElementById('type').value || '').trim();
            var normType = typeVal.toLowerCase().replace(/[\s\-_]+/g, ' ');

            return geomType === 'polygon' && (normType === 'vak' || normType.includes('vak'));
        }

        function handleTypeChange() {
            var singleContainer = document.getElementById('single-name-container');
            var vakContainer = document.getElementById('vak-name-container');

            if (isVakPolygon()) {
                if (singleContainer) singleContainer.classList.add('hidden');
                if (vakContainer) vakContainer.classList.remove('hidden');
            } else {
                if (singleContainer) singleContainer.classList.remove('hidden');
                if (vakContainer) vakContainer.classList.add('hidden');
            }
            updateCombinedName();
        }

        function updateCombinedName() {
            var hiddenNameInput = document.getElementById('name');
            if (isVakPolygon()) {
                var vakVal = document.getElementById('vak_number_input').value.trim();
                hiddenNameInput.value = vakVal ? 'VAK ' + vakVal : 'VAK';
            } else {
                var singleVal = document.getElementById('name_single').value.trim();
                hiddenNameInput.value = singleVal;
            }
        }

        var adminBounds = L.latLngBounds([[-3.0, 108.0], [2.5, 114.5]]);
        var adminMap = L.map('admin-map', {
            zoomControl: false,
            attributionControl: false,
            maxBounds: adminBounds,
            maxBoundsViscosity: 0.8,
            minZoom: 8
        }).setView([1.271885, 109.477339], 14);

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
                    document.getElementById('btn-zoom-in')?.addEventListener('click', function() { adminMap.zoomIn(); });
                    document.getElementById('btn-zoom-out')?.addEventListener('click', function() { adminMap.zoomOut(); });
                }, 100);

                return container;
            }
        });

        adminMap.addControl(new CustomMapControls());

        function updateScaleDisplay() {
            var center = adminMap.getCenter();
            var zoom = adminMap.getZoom();
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

        adminMap.on('zoom zoomend move moveend viewreset zoomlevelschange', updateScaleDisplay);
        setTimeout(updateScaleDisplay, 100);

        var adminRoadLayer = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', { maxNativeZoom: 19, maxZoom: 20, attribution: '&copy; OpenStreetMap', crossOrigin: true });
        var adminSatelliteLayer = L.tileLayer.offline('https://mt1.google.com/vt/lyrs=s&x={x}&y={y}&z={z}', { maxNativeZoom: 20, maxZoom: 20, attribution: '&copy; Google Satellite', crossOrigin: true });
        var adminTerrainLayer = L.tileLayer('https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png', {
            maxNativeZoom: 17, maxZoom: 17, attribution: 'Map data: &copy; OpenStreetMap', subdomains: 'abc', crossOrigin: true
        });

        var currentAdminLayer = adminRoadLayer.addTo(adminMap);

        window.switchAdminLayer = function(mode) {
            adminMap.removeLayer(currentAdminLayer);
            if(mode === 'satellite') currentAdminLayer = adminSatelliteLayer;
            else if(mode === 'terrain') currentAdminLayer = adminTerrainLayer;
            else currentAdminLayer = adminRoadLayer;
            currentAdminLayer.addTo(adminMap);
        };

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
                            var normType = (m.type || '').toLowerCase().replace(/[\s\-_]+/g, ' ');
                            var mNameNorm = (m.name || '').toLowerCase();
                            var isBatas = normType.includes('batas');
                            var isVak = normType.includes('vak') || mNameNorm.includes('vak');

                            layer = L.polygon(coords, {
                                color: m.color || '#3b82f6',
                                fillColor: m.color || '#3b82f6',
                                fillOpacity: isBatas ? 0.08 : 0.15,
                                weight: isBatas ? 3 : 2.5,
                                dashArray: isBatas ? '10, 8' : null
                            }).bindTooltip(m.name, { sticky: true });

                            if (isVak) {
                                var center = getPolygonVisualCenter(coords);
                                if (!center && layer.getBounds) center = layer.getBounds().getCenter();
                                if (center) {
                                    var labelText = (m.name || '').replace(/^VAK\s*/i, '').trim();
                                    if (!labelText) labelText = m.name || 'VAK';
                                    if (!labelText.endsWith('.')) labelText += '.';

                                    var vakLabelIcon = L.divIcon({
                                        className: 'vak-polygon-label-marker',
                                        html: `<div style="display:flex; align-items:center; justify-content:center; width:100%; height:100%; text-align:center; color:#000000; font-family:ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; font-weight:800; font-size:13px; text-shadow: 0 0 3px #ffffff, 0 0 5px #ffffff, 0 0 8px #ffffff; pointer-events:none; white-space:nowrap; line-height:1;">${labelText}</div>`,
                                        iconSize: [60, 30],
                                        iconAnchor: [30, 15]
                                    });
                                    var lblMarker = L.marker(center, { icon: vakLabelIcon, interactive: false });
                                    existingLayerGroup.addLayer(lblMarker);
                                }
                            }
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

        var currentPoints = [];
        var drawingLayer = null;
        var undoHistory = [];
        var redoHistory = [];

        function updateHistoryButtons() {
            document.getElementById('btn-undo').disabled = undoHistory.length === 0;
            document.getElementById('btn-redo').disabled = redoHistory.length === 0;
        }

        function pushStateToHistory() {
            undoHistory.push(JSON.parse(JSON.stringify(currentPoints)));
            redoHistory = [];
            updateHistoryButtons();
        }

        function undo() {
            if (undoHistory.length === 0) return;
            redoHistory.push(JSON.parse(JSON.stringify(currentPoints)));
            currentPoints = undoHistory.pop();
            redrawShape();
            updateHistoryButtons();
        }

        function redo() {
            if (redoHistory.length === 0) return;
            undoHistory.push(JSON.parse(JSON.stringify(currentPoints)));
            currentPoints = redoHistory.pop();
            redrawShape();
            updateHistoryButtons();
        }

        function resetDrawingWithHistory() {
            if (currentPoints.length > 0) {
                pushStateToHistory();
                currentPoints = [];
                redrawShape();
            }
        }

        function changeGeometryType(val) {
            var coordsContainer = document.getElementById('coords-input-container');
            var jenisJalan = document.getElementById('jenis-jalan-wrapper');
            var jenisPolygon = document.getElementById('jenis-polygon-wrapper');
            var descWrapper = document.getElementById('desc-wrapper');
            var colorWrapper = document.getElementById('color-wrapper');
            var photoWrapper = document.getElementById('photo-wrapper');

            // 1. Point: Show coords, desc, color, photo
            if (val === 'point') {
                if (coordsContainer) coordsContainer.classList.remove('hidden');
                if (descWrapper) descWrapper.classList.remove('hidden');
                if (colorWrapper) colorWrapper.classList.remove('hidden');
                if (photoWrapper) photoWrapper.classList.remove('hidden');

                if (jenisJalan) jenisJalan.classList.add('hidden');
                if (jenisPolygon) jenisPolygon.classList.add('hidden');
            }
            // 2. LineString: Show jenis_jalan. Hide desc, color, photo, coords, polygon.
            else if (val === 'linestring' || val === 'polyline') {
                if (jenisJalan) {
                    jenisJalan.classList.remove('hidden');
                    var typeInput = document.getElementById('type');
                    if (typeInput && (!typeInput.value || typeInput.value === 'Point' || typeInput.value.includes('VAK') || typeInput.value.includes('Batas'))) {
                        typeInput.value = document.getElementById('jenis_jalan').value;
                    }
                }
                if (coordsContainer) coordsContainer.classList.add('hidden');
                if (descWrapper) descWrapper.classList.add('hidden');
                if (colorWrapper) colorWrapper.classList.add('hidden');
                if (photoWrapper) photoWrapper.classList.add('hidden');
                if (jenisPolygon) jenisPolygon.classList.add('hidden');
            }
            // 3. Polygon: Show jenis_polygon, color. Hide desc, photo, coords, jalan.
            else if (val === 'polygon') {
                if (jenisPolygon) {
                    jenisPolygon.classList.remove('hidden');
                    var typeInput = document.getElementById('type');
                    if (typeInput && (!typeInput.value || typeInput.value === 'Point' || typeInput.value.includes('Jalan'))) {
                        typeInput.value = document.getElementById('jenis_polygon').value;
                    }
                }
                if (colorWrapper) colorWrapper.classList.remove('hidden');

                if (coordsContainer) coordsContainer.classList.add('hidden');
                if (descWrapper) descWrapper.classList.add('hidden');
                if (photoWrapper) photoWrapper.classList.add('hidden');
                if (jenisJalan) jenisJalan.classList.add('hidden');
            }

            handleTypeChange();

            var info = document.getElementById('draw-info');
            if (info) {
                if (val === 'point') info.innerText = "Klik pada peta untuk menempatkan titik marker.";
                else if (val === 'linestring' || val === 'polyline') info.innerText = "Klik beberapa kali pada peta untuk membuat garis jalan.";
                else if (val === 'polygon') info.innerText = "Klik beberapa kali pada peta untuk menggambar area polygon.";
            }
        }

        function updateGeoJSONInput() {
            const geojsonInput = document.getElementById('geojson');
            const geomType = document.getElementById('geometry_type').value;

            if (geomType === 'point' && currentPoints.length > 0) {
                const pt = currentPoints[0];
                document.getElementById('latitude').value = pt[0];
                document.getElementById('longitude').value = pt[1];
                document.getElementById('coordinates').value = `${pt[0]}, ${pt[1]}`;
                geojsonInput.value = '';
            } else if (currentPoints.length > 0) {
                geojsonInput.value = JSON.stringify(currentPoints);
            } else {
                geojsonInput.value = '';
            }
        }

        var drawingLabelMarker = null;
        function redrawShape() {
            if (drawingLayer) {
                adminMap.removeLayer(drawingLayer);
                drawingLayer = null;
            }
            if (drawingLabelMarker) {
                adminMap.removeLayer(drawingLabelMarker);
                drawingLabelMarker = null;
            }

            const geomType = document.getElementById('geometry_type').value;
            const currentColor = document.getElementById('color').value || '#3b82f6';
            const currentType = (document.getElementById('type').value || '').toLowerCase().replace(/[\s\-_]+/g, ' ');

            if (currentPoints.length === 0) {
                updateGeoJSONInput();
                return;
            }

            if (geomType === 'point') {
                const pt = currentPoints[0];
                drawingLayer = L.marker([pt[0], pt[1]]).addTo(adminMap);
            } else if (geomType === 'linestring' || geomType === 'polyline') {
                drawingLayer = L.polyline(currentPoints, {
                    color: currentType.includes('lain') ? '#c8c8c8' : '#b8b8b8',
                    weight: currentType.includes('lain') ? 2 : 4
                }).addTo(adminMap);
            } else if (geomType === 'polygon') {
                var isBatas = currentType.includes('batas');
                drawingLayer = L.polygon(currentPoints, {
                    color: currentColor,
                    fillColor: currentColor,
                    fillOpacity: isBatas ? 0.08 : 0.15,
                    weight: isBatas ? 3 : 2.5,
                    dashArray: isBatas ? '10, 8' : null
                }).addTo(adminMap);

                if (isVakPolygon() && drawingLayer && currentPoints.length >= 3) {
                    var center = getPolygonVisualCenter(currentPoints);
                    if (!center && drawingLayer.getBounds) center = drawingLayer.getBounds().getCenter();
                    if (center) {
                        var rawVal = document.getElementById('vak_number_input') ? document.getElementById('vak_number_input').value.trim() : '';
                        var labelText = rawVal || 'VAK';
                        if (!labelText.endsWith('.')) labelText += '.';

                        var vakLabelIcon = L.divIcon({
                            className: 'vak-polygon-label-marker',
                            html: `<div style="display:flex; align-items:center; justify-content:center; width:100%; height:100%; text-align:center; color:#000000; font-family:ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; font-weight:800; font-size:13px; text-shadow: 0 0 3px #ffffff, 0 0 5px #ffffff, 0 0 8px #ffffff; pointer-events:none; white-space:nowrap; line-height:1;">${labelText}</div>`,
                            iconSize: [60, 30],
                            iconAnchor: [30, 15]
                        });
                        drawingLabelMarker = L.marker(center, { icon: vakLabelIcon, interactive: false }).addTo(adminMap);
                    }
                }
            }

            updateGeoJSONInput();
        }

        adminMap.on('click', function(e) {
            const geomType = document.getElementById('geometry_type').value;
            const lat = parseFloat(e.latlng.lat.toFixed(6));
            const lng = parseFloat(e.latlng.lng.toFixed(6));

            pushStateToHistory();

            if (geomType === 'point') {
                currentPoints = [[lat, lng]];
            } else {
                currentPoints.push([lat, lng]);
            }

            redrawShape();
        });

        document.addEventListener('keydown', function(e) {
            if ((e.ctrlKey || e.metaKey) && e.key === 'z') {
                e.preventDefault();
                undo();
            } else if ((e.ctrlKey || e.metaKey) && e.key === 'y') {
                e.preventDefault();
                redo();
            }
        });

        // Initialize field visibility on page load
        changeGeometryType(document.getElementById('geometry_type').value);
    </script>
</x-dashboard-layout>
