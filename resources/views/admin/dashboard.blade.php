<x-dashboard-layout title="Admin Dashboard">
    <x-admin-sidebar />

    <div class="space-y-8 py-4">
        
        <div>
            <h2 class="text-3xl font-bold tracking-tight text-zinc-900 font-space">Overview</h2>
            <p class="text-zinc-500 text-sm mt-1 font-inter">Ringkasan aktivitas dan statistik website Kebun Raya Sambas.</p>
        </div>

        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-4">

            {{-- Total User Widget --}}
            <div class="relative bg-white/60 backdrop-blur-xl border border-zinc-200/60 rounded-3xl p-6 shadow-xl shadow-zinc-200/40 hover:scale-[1.02] transition-transform duration-300 group">
                <div class="flex flex-row items-center justify-between space-y-0 w-full mb-4">
                    <h3 class="tracking-tight text-sm font-bold text-zinc-500 font-space uppercase">Total User</h3>
                    <div class="p-2 bg-zinc-100 rounded-xl group-hover:bg-zinc-200 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-zinc-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                </div>
                <div>
                    <div class="text-3xl font-bold text-zinc-900 font-space">{{ $totalUsers }}</div>
                </div>
            </div>

            {{-- Total Koleksi Widget --}}
            <div class="relative bg-white/60 backdrop-blur-xl border border-zinc-200/60 rounded-3xl p-6 shadow-xl shadow-zinc-200/40 hover:scale-[1.02] transition-transform duration-300 group">
                <div class="flex flex-row items-center justify-between space-y-0 w-full mb-4">
                    <h3 class="tracking-tight text-sm font-bold text-zinc-500 font-space uppercase">Total Koleksi</h3>
                    <div class="p-2 bg-zinc-100 rounded-xl group-hover:bg-zinc-200 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-zinc-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                        </svg>
                    </div>
                </div>
                <div>
                    <div class="text-3xl font-bold text-zinc-900 font-space">{{ $totalKoleksi }}</div>
                </div>
            </div>

            {{-- Total Kategori Widget --}}
            <div class="relative bg-white/60 backdrop-blur-xl border border-zinc-200/60 rounded-3xl p-6 shadow-xl shadow-zinc-200/40 hover:scale-[1.02] transition-transform duration-300 group">
                <div class="flex flex-row items-center justify-between space-y-0 w-full mb-4">
                    <h3 class="tracking-tight text-sm font-bold text-zinc-500 font-space uppercase">Total Kategori</h3>
                    <div class="p-2 bg-zinc-100 rounded-xl group-hover:bg-zinc-200 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-zinc-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                    </div>
                </div>
                <div>
                    <div class="text-3xl font-bold text-zinc-900 font-space">{{ $totalCategories }}</div>
                </div>
            </div>

            {{-- Total Penanda Peta Widget --}}
            <div class="relative bg-white/60 backdrop-blur-xl border border-zinc-200/60 rounded-3xl p-6 shadow-xl shadow-zinc-200/40 hover:scale-[1.02] transition-transform duration-300 group">
                <div class="flex flex-row items-center justify-between space-y-0 w-full mb-4">
                    <h3 class="tracking-tight text-sm font-bold text-zinc-500 font-space uppercase">Total Penanda Peta</h3>
                    <div class="p-2 bg-zinc-100 rounded-xl group-hover:bg-zinc-200 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-zinc-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                </div>
                <div>
                    <div class="text-3xl font-bold text-zinc-900 font-space">{{ $totalMapMarkers }}</div>
                </div>
            </div>

        </div>
    </div>
</x-dashboard-layout>