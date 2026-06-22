<x-dashboard-layout title="Tambah Koleksi Baru">
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
                <h3 class="text-2xl font-bold font-space text-zinc-900 tracking-tight">Tambah Koleksi Baru</h3>
                <p class="text-zinc-500 text-sm font-inter">Isi formulir berikut untuk menambahkan koleksi baru.</p>
            </div>
            
            <form method="POST" action="{{ route('admin.koleksi.store') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <div class="space-y-2">
                    <label for="title" class="block text-sm font-bold text-zinc-700 font-space ml-1">Judul Koleksi</label>
                    <input id="title" type="text" name="title" value="{{ old('title') }}" required autofocus 
                        class="w-full px-4 py-3 bg-white/50 border border-zinc-300 rounded-xl focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all outline-none text-zinc-800 shadow-sm placeholder-zinc-400" placeholder="Contoh: Anggrek Bulan" />
                    @error('title') <span class="text-xs text-red-500 font-medium ml-1">{{ $message }}</span> @enderror
                </div>



                <!-- Klasifikasi Taksonomi -->
                <div class="p-6 border border-zinc-200 rounded-2xl bg-zinc-50/50 space-y-4">
                    <h4 class="text-sm font-bold text-zinc-800 font-space border-b border-zinc-200 pb-2">Klasifikasi Taksonomi Tumbuhan</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <label for="kerajaan" class="block text-xs font-bold text-zinc-600 font-space ml-1">Kerajaan (Kingdom)</label>
                            <input id="kerajaan" type="text" name="kerajaan" value="{{ old('kerajaan') }}" 
                                class="taxonomy-input w-full px-3 py-2.5 bg-white border border-zinc-300 rounded-xl focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all outline-none text-zinc-800 shadow-sm text-sm" placeholder="Contoh: Plantae" />
                            @error('kerajaan') <span class="text-[10px] text-red-500 font-medium ml-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="space-y-1">
                            <label for="divisi" class="block text-xs font-bold text-zinc-600 font-space ml-1">Divisi</label>
                            <input id="divisi" type="text" name="divisi" value="{{ old('divisi') }}" 
                                class="taxonomy-input w-full px-3 py-2.5 bg-white border border-zinc-300 rounded-xl focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all outline-none text-zinc-800 shadow-sm text-sm" placeholder="Contoh: Streptophyta" />
                            @error('divisi') <span class="text-[10px] text-red-500 font-medium ml-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="space-y-1">
                            <label for="kelas" class="block text-xs font-bold text-zinc-600 font-space ml-1">Kelas</label>
                            <input id="kelas" type="text" name="kelas" value="{{ old('kelas') }}" 
                                class="taxonomy-input w-full px-3 py-2.5 bg-white border border-zinc-300 rounded-xl focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all outline-none text-zinc-800 shadow-sm text-sm" placeholder="Contoh: Equisetopsida" />
                            @error('kelas') <span class="text-[10px] text-red-500 font-medium ml-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="space-y-1">
                            <label for="order" class="block text-xs font-bold text-zinc-600 font-space ml-1">Order / Ordo</label>
                            <input id="order" type="text" name="order" value="{{ old('order') }}" 
                                class="taxonomy-input w-full px-3 py-2.5 bg-white border border-zinc-300 rounded-xl focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all outline-none text-zinc-800 shadow-sm text-sm" placeholder="Contoh: Fagales" />
                            @error('order') <span class="text-[10px] text-red-500 font-medium ml-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="space-y-1">
                            <label for="famili" class="block text-xs font-bold text-zinc-600 font-space ml-1">Famili</label>
                            <input id="famili" type="text" name="famili" value="{{ old('famili') }}" 
                                class="taxonomy-input w-full px-3 py-2.5 bg-white border border-zinc-300 rounded-xl focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all outline-none text-zinc-800 shadow-sm text-sm" placeholder="Contoh: Casuarinaceae" />
                            @error('famili') <span class="text-[10px] text-red-500 font-medium ml-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="space-y-1">
                            <label for="genus" class="block text-xs font-bold text-zinc-600 font-space ml-1">Genus</label>
                            <input id="genus" type="text" name="genus" value="{{ old('genus') }}" 
                                class="taxonomy-input w-full px-3 py-2.5 bg-white border border-zinc-300 rounded-xl focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all outline-none text-zinc-800 shadow-sm text-sm" placeholder="Contoh: Gymnostoma" />
                            @error('genus') <span class="text-[10px] text-red-500 font-medium ml-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="space-y-1 md:col-span-2">
                            <label for="spesies" class="block text-xs font-bold text-zinc-600 font-space ml-1">Spesies</label>
                            <input id="spesies" type="text" name="spesies" value="{{ old('spesies') }}" 
                                class="taxonomy-input w-full px-3 py-2.5 bg-white border border-zinc-300 rounded-xl focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all outline-none text-zinc-800 shadow-sm text-sm" placeholder="Contoh: Gymnostoma Sumatranum" />
                            @error('spesies') <span class="text-[10px] text-red-500 font-medium ml-1">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <div class="space-y-2">
                    <label for="description" class="block text-sm font-bold text-zinc-700 font-space ml-1">Deskripsi</label>
                    <textarea id="description" name="description" rows="4" 
                        class="w-full px-4 py-3 bg-white/50 border border-zinc-300 rounded-xl focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all outline-none text-zinc-800 shadow-sm placeholder-zinc-400 resize-none" required>{{ old('description') }}</textarea>
                    @error('description') <span class="text-xs text-red-500 font-medium ml-1">{{ $message }}</span> @enderror
                </div>

                <div class="space-y-2">
                    <label for="photo" class="block text-sm font-bold text-zinc-700 font-space ml-1">Foto Koleksi</label>
                    <input id="photo" type="file" name="photo" accept="image/*" 
                        class="w-full px-4 py-3 bg-white/50 border border-zinc-300 rounded-xl focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all outline-none text-zinc-800 shadow-sm file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-zinc-100 file:text-zinc-700 hover:file:bg-zinc-200 cursor-pointer" />
                    @error('photo') <span class="text-xs text-red-500 font-medium ml-1">{{ $message }}</span> @enderror
                    <p class="text-xs text-zinc-400 ml-1">Format: JPEG, PNG, JPG, GIF (maks. 10MB)</p>
                </div>


                <div class="pt-6 flex justify-end">
                    <button type="submit" class="px-8 py-3.5 bg-zinc-900 hover:bg-zinc-800 text-white font-bold rounded-xl transition-all transform hover:scale-[1.02] active:scale-[0.98] font-space shadow-lg shadow-zinc-900/20">
                        Simpan Koleksi
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.querySelectorAll('.taxonomy-input').forEach(input => {
            input.addEventListener('input', function(e) {
                const start = this.selectionStart;
                const end = this.selectionEnd;
                let words = this.value.split(' ');
                let capitalized = words.map(w => w.charAt(0).toUpperCase() + w.slice(1));
                this.value = capitalized.join(' ');
                this.setSelectionRange(start, end);
            });
        });
    </script>
</x-dashboard-layout>
