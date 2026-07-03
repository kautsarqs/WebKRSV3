<x-dashboard-layout title="Admin Dashboard">
    <x-admin-sidebar />

    <div class="space-y-8 py-4">
        
        <div>
            <h2 class="text-3xl font-bold tracking-tight text-zinc-900 font-space">Overview</h2>
            <p class="text-zinc-500 text-sm mt-1 font-inter">Ringkasan aktivitas dan statistik website Kebun Raya Sambas.</p>
        </div>

        <div class="grid gap-6 grid-cols-1 sm:grid-cols-2 lg:grid-cols-5">

            {{-- Total User Widget --}}
            <div class="relative bg-white/60 backdrop-blur-xl border border-zinc-200/60 rounded-3xl p-6 shadow-xl shadow-zinc-200/40 hover:scale-[1.02] transition-transform duration-300 group">
                <div class="flex flex-row items-center justify-between space-y-0 w-full mb-4">
                    <h3 class="tracking-tight text-[11px] font-bold text-zinc-500 font-space uppercase">Total User</h3>
                    <div class="p-2 bg-zinc-100 rounded-xl group-hover:bg-zinc-200 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-zinc-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                </div>
                <div>
                    <div class="text-2xl font-bold text-zinc-900 font-space">{{ $totalUsers }}</div>
                </div>
            </div>

            {{-- Total Koleksi Widget --}}
            <div class="relative bg-white/60 backdrop-blur-xl border border-zinc-200/60 rounded-3xl p-6 shadow-xl shadow-zinc-200/40 hover:scale-[1.02] transition-transform duration-300 group">
                <div class="flex flex-row items-center justify-between space-y-0 w-full mb-4">
                    <h3 class="tracking-tight text-[11px] font-bold text-zinc-500 font-space uppercase">Total Koleksi</h3>
                    <div class="p-2 bg-zinc-100 rounded-xl group-hover:bg-zinc-200 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-zinc-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                        </svg>
                    </div>
                </div>
                <div>
                    <div class="text-2xl font-bold text-zinc-900 font-space">{{ $totalKoleksi }}</div>
                </div>
            </div>

            {{-- Total Penanda Peta Widget --}}
            <div class="relative bg-white/60 backdrop-blur-xl border border-zinc-200/60 rounded-3xl p-6 shadow-xl shadow-zinc-200/40 hover:scale-[1.02] transition-transform duration-300 group">
                <div class="flex flex-row items-center justify-between space-y-0 w-full mb-4">
                    <h3 class="tracking-tight text-[11px] font-bold text-zinc-500 font-space uppercase">Total Penanda</h3>
                    <div class="p-2 bg-zinc-100 rounded-xl group-hover:bg-zinc-200 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-zinc-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                </div>
                <div>
                    <div class="text-2xl font-bold text-zinc-900 font-space">{{ $totalMapMarkers }}</div>
                </div>
            </div>

            {{-- Total Pengunjung Disetujui Widget --}}
            <div class="relative bg-white/60 backdrop-blur-xl border border-zinc-200/60 rounded-3xl p-6 shadow-xl shadow-zinc-200/40 hover:scale-[1.02] transition-transform duration-300 group">
                <div class="flex flex-row items-center justify-between space-y-0 w-full mb-4">
                    <h3 class="tracking-tight text-[11px] font-bold text-zinc-500 font-space uppercase">Pengunjung</h3>
                    <div class="p-2 bg-blue-50 rounded-xl group-hover:bg-blue-100 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                </div>
                <div>
                    <div class="text-2xl font-bold text-zinc-900 font-space">{{ $totalPengunjung }} <span class="text-xs text-zinc-400 font-normal">Orang</span></div>
                </div>
            </div>

            {{-- Total Peneliti Disetujui Widget --}}
            <div class="relative bg-white/60 backdrop-blur-xl border border-zinc-200/60 rounded-3xl p-6 shadow-xl shadow-zinc-200/40 hover:scale-[1.02] transition-transform duration-300 group">
                <div class="flex flex-row items-center justify-between space-y-0 w-full mb-4">
                    <h3 class="tracking-tight text-[11px] font-bold text-zinc-500 font-space uppercase">Peneliti</h3>
                    <div class="p-2 bg-violet-50 rounded-xl group-hover:bg-violet-100 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-violet-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364.364l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 113.536 0V21h2v-5.464" />
                        </svg>
                    </div>
                </div>
                <div>
                    <div class="text-2xl font-bold text-zinc-900 font-space">{{ $totalPeneliti }} <span class="text-xs text-zinc-400 font-normal">Aktif</span></div>
                </div>
            </div>

        </div>
    </div>
</x-dashboard-layout>