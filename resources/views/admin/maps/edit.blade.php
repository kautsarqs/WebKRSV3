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

                <!-- Tipe Geometri -->
                <div class="space-y-2">
                    <label for="geometry_type" class="block text-sm font-bold text-zinc-700 font-space ml-1">Tipe Geometri</label>
                    <div class="relative">
                        <select name="geometry_type" id="geometry_type" onchange="changeGeometryType(this.value)" class="w-full px-4 py-3 bg-white/50 border border-zinc-300 rounded-xl focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all outline-none text-zinc-800 shadow-sm appearance-none cursor-pointer">
                            <option value="point" {{ old('geometry_type', $map->geometry_type) === 'point' ? 'selected' : '' }}>Point (Marker Lokasi Bangunan)</option>
                            <option value="polyline" {{ old('geometry_type', $map->geometry_type) === 'polyline' ? 'selected' : '' }}>LineString (Garis Jalan/Jalur Navigasi)</option>
                            <option value="polygon" {{ old('geometry_type', $map->geometry_type) === 'polygon' ? 'selected' : '' }}>Polygon (Batas Wilayah)</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-zinc-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                    @error('geometry_type') <span class="text-xs text-red-500 font-medium ml-1">{{ $message }}</span> @enderror
                </div>

                <!-- Interactive Drawing Map -->
                <div class="space-y-2">
                    <label class="block text-sm font-bold text-zinc-700 font-space ml-1">Gambar di Peta (Klik-Klik Seperti Pen Tool)</label>
                    <div class="border border-zinc-200 rounded-2xl overflow-hidden shadow-sm relative">
                        <div id="admin-map" style="height: 350px; width: 100%; z-index: 10;"></div>
                        
                        <!-- Layer Selector Overlay -->
                        <div class="absolute top-2 right-2 z-[1000]">
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

                <!-- Hidden Input untuk Koordinat Array Polyline/Polygon -->
                <input type="hidden" id="geojson" name="geojson" value="{{ old('geojson', $map->geojson) }}" />

                <!-- Container Input Koordinat Point (Marker) -->
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

                <div class="space-y-2">
                    <label for="type" class="block text-sm font-bold text-zinc-700 font-space ml-1">Tipe Marker</label>
                    <div class="relative">
                        <select name="type" id="type" class="w-full px-4 py-3 bg-white/50 border border-zinc-300 rounded-xl focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all outline-none text-zinc-800 shadow-sm appearance-none cursor-pointer">
                            <option value="area_koleksi" {{ old('type', $map->type) === 'area_koleksi' ? 'selected' : '' }}>Area Koleksi</option>
                            <option value="fasilitas_umum" {{ old('type', $map->type) === 'fasilitas_umum' ? 'selected' : '' }}>Fasilitas Umum</option>
                            <option value="kantor_pengelola" {{ old('type', $map->type) === 'kantor_pengelola' ? 'selected' : '' }}>Kantor Pengelola</option>
                            <option value="pos_keamanan" {{ old('type', $map->type) === 'pos_keamanan' ? 'selected' : '' }}>Pos Keamanan</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-zinc-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                    @error('type') <span class="text-xs text-red-500 font-medium ml-1">{{ $message }}</span> @enderror
                </div>

                <div class="space-y-2">
                    <label for="description" class="block text-sm font-bold text-zinc-700 font-space ml-1">Deskripsi (Opsional)</label>
                    <textarea id="description" name="description" rows="3" 
                        class="w-full px-4 py-3 bg-white/50 border border-zinc-300 rounded-xl focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all outline-none text-zinc-800 shadow-sm placeholder-zinc-400 resize-none">{{ old('description', $map->description) }}</textarea>
                    @error('description') <span class="text-xs text-red-500 font-medium ml-1">{{ $message }}</span> @enderror
                </div>

                <div class="space-y-2">
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

                <div class="space-y-2">
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

    <!-- Leaflet JS & CSS for drawing -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        // Sync color picker dengan text input
        document.getElementById('color').addEventListener('input', function(e) {
            document.getElementById('color-text').value = e.target.value;
        });

        // Auto-split coordinates
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

        // --- Leaflet Drawing Engine ---
        var mapCenter = [1.2706202914994014, 109.48517276551188]; // Center of KRS
        
        // Cek jika sudah ada koordinat yang terdefinisi pada marker
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

        // Definisikan layer untuk Admin Map
        var roadLayer = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19, attribution: '&copy; OpenStreetMap' });
        var satelliteLayer = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', { maxZoom: 18, attribution: '&copy; Esri' });
        var terrainLayer = L.tileLayer('https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png', { maxZoom: 17, attribution: 'Map data: &copy; OpenStreetMap' });

        var currentLayer = roadLayer.addTo(adminMap);

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

        // Undo & Redo History State Management
        var historyStack = [];
        var redoStack = [];

        function saveState() {
            var state = {
                geomType: currentGeomType,
                coords: [...clickedCoords],
                markerCoords: tempMarker ? [tempMarker.getLatLng().lat, tempMarker.getLatLng().lng] : null
            };
            historyStack.push(state);
            redoStack = []; // clear redo stack on new action
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
                
                var state = {
                    geomType: 'point',
                    coords: [],
                    markerCoords: [dragStartLatLng.lat, dragStartLatLng.lng]
                };
                historyStack.push(state);
                redoStack = [];

                updateCoordsInputs(newLatLng.lat, newLatLng.lng);
                updateUndoRedoButtons();
            });
        }

        // Load existing geometry
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

            if (type === 'point') {
                if(coordsInputContainer) coordsInputContainer.classList.remove('hidden');
                drawInfo.innerText = "Klik pada peta untuk menempatkan titik marker (seret pin untuk menyesuaikan).";
            } else {
                if(coordsInputContainer) coordsInputContainer.classList.add('hidden');
                if (type === 'polyline') {
                    drawInfo.innerText = "Klik peta berulang kali (seperti pen tool) untuk menggambar garis rute/jalan.";
                } else {
                    drawInfo.innerText = "Klik peta berulang kali (seperti pen tool) untuk menggambar poligon wilayah.";
                }
            }
        }

        // Tangani klik pada peta untuk menggambar
        adminMap.on('click', function(e) {
            var lat = e.latlng.lat;
            var lng = e.latlng.lng;

            if (currentGeomType === 'point') {
                saveState();
                createDraggableMarker(e.latlng);
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

            if (currentGeomType === 'polyline') {
                if (clickedCoords.length >= 2) {
                    tempPolyline = L.polyline(clickedCoords, { color: colorVal, weight: 4.5 }).addTo(drawnItems);
                }
                document.getElementById('geojson').value = JSON.stringify(clickedCoords);
            } else if (currentGeomType === 'polygon') {
                if (clickedCoords.length >= 3) {
                    tempPolygon = L.polygon(clickedCoords, { color: colorVal, fillColor: colorVal, fillOpacity: 0.15, weight: 3 }).addTo(drawnItems);
                }
                document.getElementById('geojson').value = JSON.stringify(clickedCoords);
            }
        }

        // Jalankan sinkronisasi warna live pada garis/polygon yang sedang digambar
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

        // Keyboard shortcut listener for Undo/Redo
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

        // Inisialisasi awal UI tanpa reset geometry bawaan DB
        changeGeometryType(document.getElementById('geometry_type').value, false);
    </script>

</x-dashboard-layout>

