<x-dashboard-layout title="Edit Koleksi">
    <x-admin-sidebar />

    <div class="max-w-2xl mx-auto py-6">
        
        <a href="{{ route('admin.koleksi.index') }}" class="inline-flex items-center text-sm font-bold text-zinc-500 hover:text-zinc-900 transition-colors mb-6 font-space group">
            <div class="w-8 h-8 rounded-full bg-white border border-zinc-200 flex items-center justify-center mr-2 shadow-sm group-hover:bg-zinc-100 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
            </div>
            Kembali ke Daftar
        </a>

        <div class="relative bg-white/60 backdrop-blur-xl border border-zinc-200/60 rounded-3xl shadow-xl shadow-zinc-200/40 p-8">
            
            <div class="space-y-1 mb-8">
                <h3 class="text-2xl font-bold font-space text-zinc-900 tracking-tight">Edit Koleksi</h3>
                <p class="text-zinc-500 text-sm font-inter">Perbarui informasi koleksi menggunakan formulir di bawah ini.</p>
            </div>
            
            <form method="POST" action="{{ route('admin.koleksi.update', $koleksi) }}" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="space-y-2">
                    <label for="title" class="block text-sm font-bold text-zinc-700 font-space ml-1">Judul Koleksi</label>
                    <input id="title" type="text" name="title" value="{{ old('title', $koleksi->title) }}" required autofocus 
                        class="w-full px-4 py-3 bg-white/50 border border-zinc-300 rounded-xl focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all outline-none text-zinc-800 shadow-sm placeholder-zinc-400" />
                    @error('title') <span class="text-xs text-red-500 font-medium ml-1">{{ $message }}</span> @enderror
                </div>

                <div class="space-y-2">
                    <label for="category_id" class="block text-sm font-bold text-zinc-700 font-space ml-1">Genus</label>
                    <select id="category_id" name="category_id" required
                        class="w-full px-4 py-3 bg-white/50 border border-zinc-300 rounded-xl focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all outline-none text-zinc-800 shadow-sm">
                        <option value="">Pilih Genus</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $koleksi->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id') <span class="text-xs text-red-500 font-medium ml-1">{{ $message }}</span> @enderror
                </div>

                <div class="space-y-2">
                    <label for="description" class="block text-sm font-bold text-zinc-700 font-space ml-1">Deskripsi</label>
                    <textarea id="description" name="description" rows="4" 
                        class="w-full px-4 py-3 bg-white/50 border border-zinc-300 rounded-xl focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all outline-none text-zinc-800 shadow-sm placeholder-zinc-400 resize-none" required>{{ old('description', $koleksi->description) }}</textarea>
                    @error('description') <span class="text-xs text-red-500 font-medium ml-1">{{ $message }}</span> @enderror
                </div>

                <div class="space-y-2">
                    <label for="photo" class="block text-sm font-bold text-zinc-700 font-space ml-1">Foto Koleksi</label>
                    @if ($koleksi->photo)
                        <div class="my-2">
                            <img src="{{ Storage::url($koleksi->photo) }}" alt="Current photo" class="w-32 h-32 object-cover rounded-xl border border-zinc-200 shadow-sm">
                            <p class="text-xs text-zinc-500 mt-1 ml-1">Foto saat ini</p>
                        </div>
                    @endif
                    <input id="photo" type="file" name="photo" accept="image/*" 
                        class="w-full px-4 py-3 bg-white/50 border border-zinc-300 rounded-xl focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all outline-none text-zinc-800 shadow-sm file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-zinc-100 file:text-zinc-700 hover:file:bg-zinc-200 cursor-pointer" />
                    @error('photo') <span class="text-xs text-red-500 font-medium ml-1">{{ $message }}</span> @enderror
                    <p class="text-xs text-zinc-400 ml-1">Kosongkan jika tidak ingin mengubah foto. Format: JPEG, PNG, JPG, GIF (maks. 10MB)</p>
                </div>

                                                                <div class="space-y-4" x-data='{ 

                                                                    locations: {{ old('locations') ? json_encode(old('locations')) : $koleksi->locations->toJson() }}.map(l => ({...l, coordinate: (l.latitude && l.longitude) ? l.latitude + ", " + l.longitude : ""})),

                                                                    addLocation() {

                                                                        this.locations.push({ name: "", latitude: "", longitude: "", coordinate: "" });

                                                                    },

                                                                    removeLocation(index) {

                                                                        this.locations.splice(index, 1);

                                                                    },

                                                                    updateCoordinates(index) {
                                                                        let coord = this.locations[index].coordinate;
                                                                        if (coord.includes(",")) {
                                                                            let parts = coord.split(",");
                                                                            this.locations[index].latitude = parts[0].trim();
                                                                            this.locations[index].longitude = parts[1].trim();
                                                                        }
                                                                    },

                                                                    formatId(index) {
                                                                        return "#" + (index + 1).toString().padStart(4, "0");
                                                                    }

                                                                }'>

                                                                    <label class="block text-sm font-bold text-zinc-700 font-space ml-1">Lokasi Peta (Opsional)</label>

                                                                    

                                                                    <div class="space-y-4">

                                                                        <template x-for="(location, index) in locations" :key="index">

                                                                            <div class="p-4 border border-zinc-200 rounded-xl bg-zinc-50/50 space-y-3 relative">

                                                                                <button type="button" @click="removeLocation(index)" class="absolute top-3 right-3 text-zinc-400 hover:text-red-500 transition-colors p-1 rounded-full hover:bg-red-50">

                                                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>

                                                                                </button>

                                                

                                                                                <div class="space-y-1">

                                                                                    <label :for="'location_name_' + index" class="text-xs font-bold text-zinc-600">Nama Lokasi</label>

                                                                                    <input :id="'location_name_' + index" type="text" x-model="location.name" :name="'locations[' + index + '][name]'" class="w-full px-3 py-2 bg-white border border-zinc-300 rounded-lg focus:ring-1 focus:ring-zinc-900 focus:border-zinc-900 transition-all outline-none text-zinc-800 text-sm" placeholder="Contoh: Area Anggrek">

                                                                                </div>

                                                                                <div class="grid grid-cols-2 gap-3">

                                                                                    <div class="space-y-1">

                                                                                        <label :for="'location_lat_' + index" class="text-xs font-bold text-zinc-600">Latitude</label>

                                                                                        <input :id="'location_lat_' + index" type="text" x-model="location.latitude" :name="'locations[' + index + '][latitude]'" class="w-full px-3 py-2 bg-white border border-zinc-300 rounded-lg focus:ring-1 focus:ring-zinc-900 focus:border-zinc-900 transition-all outline-none text-zinc-800 text-sm" placeholder="-0.123">

                                                                                    </div>

                                                                                    <div class="space-y-1">

                                                                                        <label :for="'location_lon_' + index" class="text-xs font-bold text-zinc-600">Longitude</label>

                                                                                        <input :id="'location_lon_' + index" type="text" x-model="location.longitude" :name="'locations[' + index + '][longitude]'" class="w-full px-3 py-2 bg-white border border-zinc-300 rounded-lg focus:ring-1 focus:ring-zinc-900 focus:border-zinc-900 transition-all outline-none text-zinc-800 text-sm" placeholder="109.123">

                                                                                    </div>

                                                                                </div>

                                                                            </div>

                                                                        </template>

                                                                    </div>

                                                

                                                                    <button type="button" @click="addLocation" class="inline-flex items-center justify-center px-4 py-2 border border-zinc-300 bg-white text-zinc-700 text-sm font-bold rounded-lg hover:bg-zinc-50 transition-all shadow-sm">

                                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>

                                                                        Tambah Lokasi

                                                                    </button>

                                                

                                                                    @error('locations') <span class="text-xs text-red-500 font-medium ml-1">{{ $message }}</span> @enderror

                                                                    @error('locations.*') <span class="text-xs text-red-500 font-medium ml-1">Pastikan semua field lokasi terisi dengan benar.</span> @enderror

                                                                </div>                <div class="pt-6 flex justify-end">
                    <button type="submit" class="px-8 py-3.5 bg-zinc-900 hover:bg-zinc-800 text-white font-bold rounded-xl transition-all transform hover:scale-[1.02] active:scale-[0.98] font-space shadow-lg shadow-zinc-900/20">
                        Update Koleksi
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-dashboard-layout>
