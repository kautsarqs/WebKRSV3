<x-dashboard-layout title="Manajemen Kategori">
    <x-admin-sidebar />

    <div class="space-y-6">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <h2 class="text-3xl font-bold tracking-tight text-zinc-900 font-space">Daftar Kategori</h2>
                <p class="text-zinc-500 text-sm mt-1 font-inter">Kelola semua kategori koleksi.</p>
            </div>
            <a href="{{ route('admin.categories.create') }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-zinc-900 text-white text-sm font-bold font-space rounded-xl hover:bg-zinc-800 transition-all shadow-lg shadow-zinc-900/20 transform hover:-translate-y-0.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                Tambah Kategori
            </a>
        </div>

        @if (session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" class="bg-green-100/80 backdrop-blur-sm border border-green-200 text-green-800 px-4 py-3 rounded-xl text-sm font-bold flex items-center shadow-sm">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white/60 backdrop-blur-xl border border-zinc-200/60 rounded-3xl shadow-xl shadow-zinc-200/40 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-zinc-50/50 border-b border-zinc-200/60">
                        <tr>
                            <th class="px-6 py-4 font-bold text-zinc-900 font-space tracking-wide">Nama</th>
                            <th class="px-6 py-4 font-bold text-zinc-900 font-space tracking-wide">Jumlah Koleksi</th>
                            <th class="px-6 py-4 font-bold text-zinc-900 font-space tracking-wide text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-200/60">
                        @forelse ($categories as $category)
                            <tr class="hover:bg-white/40 transition-colors group">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-zinc-700 font-space group-hover:text-zinc-900 transition-colors">{{ $category->name }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-zinc-500">{{ $category->koleksis_count }}</span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.categories.edit', $category->id) }}" 
                                           class="p-2 text-zinc-500 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all" title="Edit">
                                           <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                            </svg>
                                        </a>

                                        <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" 
                                              onsubmit="return confirm('Yakin ingin menghapus kategori {{ $category->name }}? Ini tidak akan menghapus koleksi di dalamnya.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="p-2 text-zinc-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all" title="Hapus">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-12 text-center">
                                    <div class="text-zinc-400 text-sm font-medium">Belum ada kategori. <a href="{{ route('admin.categories.create') }}" class="text-zinc-900 font-bold hover:underline">Tambah kategori pertama</a></div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-dashboard-layout>
