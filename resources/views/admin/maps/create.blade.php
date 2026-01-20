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

                <div class="space-y-2">
                    <label for="name" class="block text-sm font-bold text-zinc-700 font-space ml-1">Nama Marker</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus 
                        class="w-full px-4 py-3 bg-white/50 border border-zinc-300 rounded-xl focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all outline-none text-zinc-800 shadow-sm placeholder-zinc-400" placeholder="Contoh: Area Koleksi A" />
                    @error('name') <span class="text-xs text-red-500 font-medium ml-1">{{ $message }}</span> @enderror
                </div>

                <div class="space-y-2">
                    <label for="coordinates" class="block text-sm font-bold text-zinc-700 font-space ml-1">Koordinat (Latitude, Longitude)</label>
                    <input id="coordinates" type="text" value="{{ old('coordinates') }}" 
                        class="w-full px-4 py-3 bg-white/50 border border-zinc-300 rounded-xl focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all outline-none text-zinc-800 shadow-sm placeholder-zinc-400" 
                        placeholder="1.269936, 109.485157 atau pisahkan manual di bawah" />
                    <p class="text-xs text-zinc-400 ml-1">Masukkan koordinat dengan format: latitude, longitude (akan otomatis terpisah)</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="space-y-2">
                        <label for="latitude" class="block text-sm font-bold text-zinc-700 font-space ml-1">Latitude</label>
                        <input id="latitude" type="number" step="any" name="latitude" value="{{ old('latitude', '1.2699364219071683') }}" required 
                            class="w-full px-4 py-3 bg-white/50 border border-zinc-300 rounded-xl focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all outline-none text-zinc-800 shadow-sm placeholder-zinc-400" placeholder="1.269936" />
                        @error('latitude') <span class="text-xs text-red-500 font-medium ml-1">{{ $message }}</span> @enderror
                    </div>
                    <div class="space-y-2">
                        <label for="longitude" class="block text-sm font-bold text-zinc-700 font-space ml-1">Longitude</label>
                        <input id="longitude" type="number" step="any" name="longitude" value="{{ old('longitude', '109.48515704081744') }}" required 
                            class="w-full px-4 py-3 bg-white/50 border border-zinc-300 rounded-xl focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all outline-none text-zinc-800 shadow-sm placeholder-zinc-400" placeholder="109.485157" />
                        @error('longitude') <span class="text-xs text-red-500 font-medium ml-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="space-y-2">
                    <label for="type" class="block text-sm font-bold text-zinc-700 font-space ml-1">Tipe Marker</label>
                    <div class="relative">
                        <select name="type" id="type" class="w-full px-4 py-3 bg-white/50 border border-zinc-300 rounded-xl focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all outline-none text-zinc-800 shadow-sm appearance-none cursor-pointer">
                            <option value="area_koleksi" {{ old('type') === 'area_koleksi' ? 'selected' : '' }}>Area Koleksi</option>
                            <option value="fasilitas_umum" {{ old('type') === 'fasilitas_umum' ? 'selected' : '' }}>Fasilitas Umum</option>
                            <option value="kantor_pengelola" {{ old('type') === 'kantor_pengelola' ? 'selected' : '' }}>Kantor Pengelola</option>
                            <option value="pos_keamanan" {{ old('type') === 'pos_keamanan' ? 'selected' : '' }}>Pos Keamanan</option>
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
                        class="w-full px-4 py-3 bg-white/50 border border-zinc-300 rounded-xl focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all outline-none text-zinc-800 shadow-sm placeholder-zinc-400 resize-none">{{ old('description') }}</textarea>
                    @error('description') <span class="text-xs text-red-500 font-medium ml-1">{{ $message }}</span> @enderror
                </div>

                <div class="space-y-2">
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

                <div class="space-y-2">
                    <label for="photo" class="block text-sm font-bold text-zinc-700 font-space ml-1">Foto Marker (Opsional)</label>
                    <input id="photo" type="file" name="photo" accept="image/*" 
                        class="w-full px-4 py-3 bg-white/50 border border-zinc-300 rounded-xl focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all outline-none text-zinc-800 shadow-sm file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-zinc-100 file:text-zinc-700 hover:file:bg-zinc-200 cursor-pointer" />
                    @error('photo') <span class="text-xs text-red-500 font-medium ml-1">{{ $message }}</span> @enderror
                    <p class="text-xs text-zinc-400 ml-1">Format: JPEG, PNG, JPG, GIF (maks. 2MB)</p>
                </div>

                <div class="pt-6 flex justify-end">
                    <button type="submit" class="px-8 py-3.5 bg-zinc-900 hover:bg-zinc-800 text-white font-bold rounded-xl transition-all transform hover:scale-[1.02] active:scale-[0.98] font-space shadow-lg shadow-zinc-900/20">
                        Simpan Marker
                    </button>
                </div>
            </form>
        </div>
    </div>

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
    </script>

</x-dashboard-layout>

