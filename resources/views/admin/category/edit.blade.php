<x-dashboard-layout title="Edit Kategori">
    <x-admin-sidebar />

    <div class="max-w-2xl mx-auto py-6">
        
        <a href="{{ route('admin.categories.index') }}" class="inline-flex items-center text-sm font-bold text-zinc-500 hover:text-zinc-900 transition-colors mb-6 font-space group">
            <div class="w-8 h-8 rounded-full bg-white border border-zinc-200 flex items-center justify-center mr-2 shadow-sm group-hover:bg-zinc-100 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
            </div>
            Kembali ke Daftar
        </a>

        <div class="relative bg-white/60 backdrop-blur-xl border border-zinc-200/60 rounded-3xl shadow-xl shadow-zinc-200/40 p-8">
            
            <div class="space-y-1 mb-8">
                <h3 class="text-2xl font-bold font-space text-zinc-900 tracking-tight">Edit Kategori</h3>
                <p class="text-zinc-500 text-sm font-inter">Perbarui nama kategori.</p>
            </div>
            
            <form method="POST" action="{{ route('admin.categories.update', $category->id) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="space-y-2">
                    <label for="name" class="block text-sm font-bold text-zinc-700 font-space ml-1">Nama Kategori</label>
                    <input id="name" type="text" name="name" value="{{ old('name', $category->name) }}" required autofocus 
                        class="w-full px-4 py-3 bg-white/50 border border-zinc-300 rounded-xl focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all outline-none text-zinc-800 shadow-sm placeholder-zinc-400" />
                    @error('name') <span class="text-xs text-red-500 font-medium ml-1">{{ $message }}</span> @enderror
                </div>

                <div class="pt-6 flex justify-end">
                    <button type="submit" class="px-8 py-3.5 bg-zinc-900 hover:bg-zinc-800 text-white font-bold rounded-xl transition-all transform hover:scale-[1.02] active:scale-[0.98] font-space shadow-lg shadow-zinc-900/20">
                        Update Kategori
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-dashboard-layout>
