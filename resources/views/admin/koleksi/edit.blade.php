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



                <!-- Klasifikasi Taksonomi -->
                <div class="p-6 border border-zinc-200 rounded-2xl bg-zinc-50/50 space-y-4">
                    <h4 class="text-sm font-bold text-zinc-800 font-space border-b border-zinc-200 pb-2">Klasifikasi Taksonomi Tumbuhan</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <label for="kerajaan" class="block text-xs font-bold text-zinc-600 font-space ml-1">Kerajaan (Kingdom)</label>
                            <input id="kerajaan" type="text" name="kerajaan" value="{{ old('kerajaan', $koleksi->kerajaan) }}" 
                                class="taxonomy-input w-full px-3 py-2.5 bg-white border border-zinc-300 rounded-xl focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all outline-none text-zinc-800 shadow-sm text-sm" placeholder="Contoh: Plantae" />
                            @error('kerajaan') <span class="text-[10px] text-red-500 font-medium ml-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="space-y-1">
                            <label for="divisi" class="block text-xs font-bold text-zinc-600 font-space ml-1">Divisi</label>
                            <input id="divisi" type="text" name="divisi" value="{{ old('divisi', $koleksi->divisi) }}" 
                                class="taxonomy-input w-full px-3 py-2.5 bg-white border border-zinc-300 rounded-xl focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all outline-none text-zinc-800 shadow-sm text-sm" placeholder="Contoh: Streptophyta" />
                            @error('divisi') <span class="text-[10px] text-red-500 font-medium ml-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="space-y-1">
                            <label for="kelas" class="block text-xs font-bold text-zinc-600 font-space ml-1">Kelas</label>
                            <input id="kelas" type="text" name="kelas" value="{{ old('kelas', $koleksi->kelas) }}" 
                                class="taxonomy-input w-full px-3 py-2.5 bg-white border border-zinc-300 rounded-xl focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all outline-none text-zinc-800 shadow-sm text-sm" placeholder="Contoh: Equisetopsida" />
                            @error('kelas') <span class="text-[10px] text-red-500 font-medium ml-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="space-y-1">
                            <label for="order" class="block text-xs font-bold text-zinc-600 font-space ml-1">Order / Ordo</label>
                            <input id="order" type="text" name="order" value="{{ old('order', $koleksi->order) }}" 
                                class="taxonomy-input w-full px-3 py-2.5 bg-white border border-zinc-300 rounded-xl focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all outline-none text-zinc-800 shadow-sm text-sm" placeholder="Contoh: Fagales" />
                            @error('order') <span class="text-[10px] text-red-500 font-medium ml-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="space-y-1">
                            <label for="famili" class="block text-xs font-bold text-zinc-600 font-space ml-1">Famili</label>
                            <input id="famili" type="text" name="famili" value="{{ old('famili', $koleksi->famili) }}" 
                                class="taxonomy-input w-full px-3 py-2.5 bg-white border border-zinc-300 rounded-xl focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all outline-none text-zinc-800 shadow-sm text-sm" placeholder="Contoh: Casuarinaceae" />
                            @error('famili') <span class="text-[10px] text-red-500 font-medium ml-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="space-y-1">
                            <label for="genus" class="block text-xs font-bold text-zinc-600 font-space ml-1">Genus</label>
                            <input id="genus" type="text" name="genus" value="{{ old('genus', $koleksi->genus) }}" 
                                class="taxonomy-input w-full px-3 py-2.5 bg-white border border-zinc-300 rounded-xl focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all outline-none text-zinc-800 shadow-sm text-sm" placeholder="Contoh: Gymnostoma" />
                            @error('genus') <span class="text-[10px] text-red-500 font-medium ml-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="space-y-1">
                            <label for="spesies" class="block text-xs font-bold text-zinc-600 font-space ml-1">Spesies (Penunjuk Spesies Saja)</label>
                            <input id="spesies" type="text" name="spesies" value="{{ old('spesies', $koleksi->spesies) }}" 
                                class="w-full px-3 py-2.5 bg-white border border-zinc-300 rounded-xl focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all outline-none text-zinc-800 shadow-sm text-sm" placeholder="Contoh: sumatranum" />
                            <p class="text-[10px] text-zinc-400 ml-1">Tulis penunjuk spesies dalam huruf kecil (epithet saja, e.g. sumatranum).</p>
                            @error('spesies') <span class="text-[10px] text-red-500 font-medium ml-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="space-y-1">
                            <label for="otoritas_1" class="block text-xs font-bold text-zinc-600 font-space ml-1">Otoritas 1 (Dalam Kurung)</label>
                            <input id="otoritas_1" type="text" name="otoritas_1" value="{{ old('otoritas_1', $koleksi->otoritas_1) }}" 
                                class="w-full px-3 py-2.5 bg-white border border-zinc-300 rounded-xl focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all outline-none text-zinc-800 shadow-sm text-sm" placeholder="Contoh: Jungh. ex de Vriese" />
                            <p class="text-[10px] text-zinc-400 ml-1">Otoritas pertama (dalam tanda kurung).</p>
                            @error('otoritas_1') <span class="text-[10px] text-red-500 font-medium ml-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="space-y-1">
                            <label for="otoritas_2" class="block text-xs font-bold text-zinc-600 font-space ml-1">Otoritas 2 (Di Luar Kurung)</label>
                            <input id="otoritas_2" type="text" name="otoritas_2" value="{{ old('otoritas_2', $koleksi->otoritas_2) }}" 
                                class="w-full px-3 py-2.5 bg-white border border-zinc-300 rounded-xl focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all outline-none text-zinc-800 shadow-sm text-sm" placeholder="Contoh: L.A.S. Johnson" />
                            <p class="text-[10px] text-zinc-400 ml-1">Otoritas pengubah (di luar tanda kurung).</p>
                            @error('otoritas_2') <span class="text-[10px] text-red-500 font-medium ml-1">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    {{-- Live Preview Nama Ilmiah / Botani --}}
                    <div class="mt-5 p-4 bg-zinc-50 border border-zinc-200 rounded-2xl">
                        <span class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest font-space block mb-1">Preview Nama Ilmiah / Botani</span>
                        <div id="scientific-preview" class="text-sm font-medium text-emerald-800 font-serif leading-relaxed">
                            <span class="text-zinc-400 italic font-sans font-normal">Belum ada input genus/spesies</span>
                        </div>
                    </div>
                </div>
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
                <div class="pt-6 flex justify-end">
                    <button type="submit" class="px-8 py-3.5 bg-zinc-900 hover:bg-zinc-800 text-white font-bold rounded-xl transition-all transform hover:scale-[1.02] active:scale-[0.98] font-space shadow-lg shadow-zinc-900/20">
                        Update Koleksi
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

        function updateScientificNamePreview() {
            let genusVal = document.getElementById('genus').value.trim();
            let speciesVal = document.getElementById('spesies').value.trim();
            let aut1Val = document.getElementById('otoritas_1').value.trim();
            let aut2Val = document.getElementById('otoritas_2').value.trim();

            if (genusVal) {
                genusVal = genusVal.charAt(0).toUpperCase() + genusVal.slice(1);
            }

            let cleanedSpecies = speciesVal;
            if (genusVal && speciesVal) {
                let regex = new RegExp('^' + genusVal + '\\s+', 'i');
                cleanedSpecies = speciesVal.replace(regex, '');
            }
            if (cleanedSpecies) {
                cleanedSpecies = cleanedSpecies.toLowerCase();
            }

            let html = '';
            if (genusVal || cleanedSpecies) {
                html += '<i class="italic">' + [genusVal, cleanedSpecies].filter(Boolean).join(' ') + '</i>';
            }
            if (aut1Val) {
                html += ' (' + aut1Val + ')';
            }
            if (aut2Val) {
                html += ' ' + aut2Val;
            }

            document.getElementById('scientific-preview').innerHTML = html || '<span class="text-zinc-400 italic font-sans font-normal">Belum ada input genus/spesies</span>';
        }

        document.querySelectorAll('#genus, #spesies, #otoritas_1, #otoritas_2').forEach(input => {
            input.addEventListener('input', updateScientificNamePreview);
        });

        // Initialize preview on page load
        document.addEventListener('DOMContentLoaded', updateScientificNamePreview);
    </script>
</x-dashboard-layout>
