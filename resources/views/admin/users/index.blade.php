<x-dashboard-layout title="Kelola User">
    <x-admin-sidebar />

    <!-- 2. KONTEN UTAMA -->
    <div class="space-y-6">
        
        <!-- Header & Action -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <h2 class="text-3xl font-bold tracking-tight text-zinc-900 font-space">Daftar Pengguna</h2>
                <p class="text-zinc-500 text-sm mt-1 font-inter">Kelola semua akun yang terdaftar di sistem.</p>
            </div>
            
            <div class="flex items-center gap-3">
                <!-- Search Form -->
                <form action="{{ route('admin.users.index') }}" method="GET" class="relative group">
                    <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-3 top-3 h-5 w-5 text-zinc-400 group-hover:text-zinc-600 transition-colors" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                    
                    <input 
                        type="search" 
                        name="search" 
                        placeholder="Cari nama atau email..." 
                        value="{{ request('search') }}"
                        class="pl-10 pr-4 py-2.5 w-full md:w-[280px] bg-white/60 backdrop-blur-sm border border-zinc-200 rounded-xl focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 outline-none text-zinc-800 text-sm shadow-sm transition-all"
                    />
                </form>

                <!-- Tombol Tambah -->
                <a href="{{ route('admin.users.create') }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-zinc-900 text-white text-sm font-bold font-space rounded-xl hover:bg-zinc-800 transition-all shadow-lg shadow-zinc-900/20 transform hover:-translate-y-0.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    Tambah User
                </a>
            </div>
        </div>

        <!-- Alert Success/Error -->
        @if (session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" class="bg-green-100/80 backdrop-blur-sm border border-green-200 text-green-800 px-4 py-3 rounded-xl text-sm font-bold flex items-center shadow-sm">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" class="bg-red-100/80 backdrop-blur-sm border border-red-200 text-red-800 px-4 py-3 rounded-xl text-sm font-bold flex items-center shadow-sm">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                {{ session('error') }}
            </div>
        @endif

        <!-- GLASS TABLE CONTAINER -->
        <div class="bg-white/60 backdrop-blur-xl border border-zinc-200/60 rounded-3xl shadow-xl shadow-zinc-200/40 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-zinc-50/50 border-b border-zinc-200/60">
                        <tr>
                            <th class="px-6 py-4 font-bold text-zinc-900 font-space tracking-wide">Pengguna</th>
                            <th class="px-6 py-4 font-bold text-zinc-900 font-space tracking-wide">Email</th>
                            <th class="px-6 py-4 font-bold text-zinc-900 font-space tracking-wide">Role</th>
                            <th class="px-6 py-4 font-bold text-zinc-900 font-space tracking-wide">Verifikasi</th>
                            <th class="px-6 py-4 font-bold text-zinc-900 font-space tracking-wide">Tanggal Daftar</th>
                            <th class="px-6 py-4 font-bold text-zinc-900 font-space tracking-wide text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-200/60">
                        @foreach ($users as $user)
                            <tr class="hover:bg-white/40 transition-colors group">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                         <img src="{{ $user->avatar_url }}" 
                                              class="h-9 w-9 rounded-full border border-zinc-200 shadow-sm object-cover" alt="">
                                        <span class="font-bold text-zinc-700 font-space group-hover:text-zinc-900 transition-colors">{{ $user->name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 font-medium text-zinc-500">{{ $user->email }}</td>
                                <td class="px-6 py-4">
                                    @if($user->role === 'admin')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-zinc-900 text-white border border-zinc-700 shadow-sm font-space">
                                            Admin
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-zinc-100 text-zinc-600 border border-zinc-200 font-space">
                                            User
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($user->email_verified_at)
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700 border border-green-200 font-space">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                            Terverifikasi
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-700 border border-amber-200 font-space">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            Pending
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-zinc-500 font-medium">{{ $user->created_at->format('d M Y') }}</td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <!-- Edit Button -->
                                        <a href="{{ route('admin.users.edit', $user) }}" 
                                           class="p-2 text-zinc-500 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all" title="Edit">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                            </svg>
                                        </a>

                                        <!-- Delete Form -->
                                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" 
                                              onsubmit="return confirm('Yakin ingin menghapus {{ $user->name }}? Data tidak bisa dikembalikan.');">
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
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination (Jika ada) -->
            @if($users->hasPages())
                <div class="px-6 py-4 border-t border-zinc-200/60 bg-zinc-50/30">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>

</x-dashboard-layout>