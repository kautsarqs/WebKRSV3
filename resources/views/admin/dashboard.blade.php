<x-dashboard-layout title="Admin Dashboard">
    <x-admin-sidebar />

    <div class="space-y-8 py-4">

        <div>
            <h2 class="text-3xl font-bold tracking-tight text-zinc-900 font-space">Overview</h2>
            <p class="text-zinc-500 text-sm mt-1 font-inter">Ringkasan aktivitas dan statistik website Kebun Raya Sambas.</p>
        </div>

        <div class="grid gap-6 grid-cols-1 sm:grid-cols-2 lg:grid-cols-5">

            <div class="relative bg-white/60 backdrop-blur-xl border border-zinc-200/60 rounded-3xl p-6 shadow-xl shadow-zinc-200/40 hover:scale-[1.02] transition-transform duration-300 group">
                <div class="flex flex-row items-center justify-between space-y-0 w-full mb-4">
                    <h3 class="tracking-tight text-[11px] font-bold text-zinc-500 font-space uppercase">Total User</h3>
                    <div class="p-2 bg-zinc-100 rounded-xl group-hover:bg-zinc-200 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4 text-zinc-650"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    </div>
                </div>
                <div>
                    <div class="text-2xl font-bold text-zinc-900 font-space">{{ $totalUsers }}</div>
                </div>
            </div>

            <div class="relative bg-white/60 backdrop-blur-xl border border-zinc-200/60 rounded-3xl p-6 shadow-xl shadow-zinc-200/40 hover:scale-[1.02] transition-transform duration-300 group">
                <div class="flex flex-row items-center justify-between space-y-0 w-full mb-4">
                    <h3 class="tracking-tight text-[11px] font-bold text-zinc-500 font-space uppercase">Total Koleksi</h3>
                    <div class="p-2 bg-zinc-100 rounded-xl group-hover:bg-zinc-200 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4 text-zinc-650"><path d="M7 20h10"/><path d="M10 20c5.5-2.5.8-6.4 3-10"/><path d="M9.5 9.4c1.1.8 1.8 2.2 1.8 3.7 0 .9-.5 1.6-1.3 2.1"/><path d="M19 9c-2.2.3-6 2-6 6"/><path d="M13 12c.9-.6 1.4-1.6 1.4-2.8 0-1.9-1.3-3.2-3.4-2.9"/></svg>
                    </div>
                </div>
                <div>
                    <div class="text-2xl font-bold text-zinc-900 font-space">{{ $totalKoleksi }}</div>
                </div>
            </div>

            <div class="relative bg-white/60 backdrop-blur-xl border border-zinc-200/60 rounded-3xl p-6 shadow-xl shadow-zinc-200/40 hover:scale-[1.02] transition-transform duration-300 group">
                <div class="flex flex-row items-center justify-between space-y-0 w-full mb-4">
                    <h3 class="tracking-tight text-[11px] font-bold text-zinc-500 font-space uppercase">Total Penanda</h3>
                    <div class="p-2 bg-zinc-100 rounded-xl group-hover:bg-zinc-200 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4 text-zinc-650"><path d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
                <div>
                    <div class="text-2xl font-bold text-zinc-900 font-space">{{ $totalMapMarkers }}</div>
                </div>
            </div>

            <div class="relative bg-white/60 backdrop-blur-xl border border-zinc-200/60 rounded-3xl p-6 shadow-xl shadow-zinc-200/40 hover:scale-[1.02] transition-transform duration-300 group">
                <div class="flex flex-row items-center justify-between space-y-0 w-full mb-4">
                    <h3 class="tracking-tight text-[11px] font-bold text-zinc-500 font-space uppercase">Pengunjung</h3>
                    <div class="p-2 bg-zinc-100 rounded-xl group-hover:bg-zinc-200 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4 text-zinc-650"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><polyline points="16 11 18 13 22 9"/></svg>
                    </div>
                </div>
                <div>
                    <div class="text-2xl font-bold text-zinc-900 font-space">{{ $totalPengunjung }} <span class="text-xs text-zinc-400 font-normal">Orang</span></div>
                </div>
            </div>

            <div class="relative bg-white/60 backdrop-blur-xl border border-zinc-200/60 rounded-3xl p-6 shadow-xl shadow-zinc-200/40 hover:scale-[1.02] transition-transform duration-300 group">
                <div class="flex flex-row items-center justify-between space-y-0 w-full mb-4">
                    <h3 class="tracking-tight text-[11px] font-bold text-zinc-500 font-space uppercase">Peneliti</h3>
                    <div class="p-2 bg-zinc-100 rounded-xl group-hover:bg-zinc-200 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4 text-zinc-650"><path d="M21.42 10.922a1 1 0 0 0-.019-1.838L12.83 5.18a2 2 0 0 0-1.66 0L2.6 9.08a1 1 0 0 0 0 1.832l8.57 3.908a2 2 0 0 0 1.66 0z"/><path d="M6 12v5c0 2 2 3 6 3s6-1 6-3v-5"/><path d="M21.5 12v6"/></svg>
                    </div>
                </div>
                <div>
                    <div class="text-2xl font-bold text-zinc-900 font-space">{{ $totalPeneliti }} <span class="text-xs text-zinc-400 font-normal">Orang</span></div>
                </div>
            </div>

        </div>

        <div class="grid gap-6 grid-cols-1 lg:grid-cols-2 mt-8">

            <div class="bg-white/60 backdrop-blur-xl border border-zinc-200/60 rounded-3xl p-6 shadow-xl shadow-zinc-200/40">
                <h4 class="text-xs font-bold text-zinc-400 font-space uppercase mb-6 tracking-wider">Status Registrasi Pengunjung</h4>

                <div class="space-y-5">
                    @php
                        $visitorTotal = $pengunjungPending + $pengunjungSetuju + $pengunjungTolak;
                        $visitorTotal = $visitorTotal > 0 ? $visitorTotal : 1; // avoid division by zero
                        $pendingPct = round(($pengunjungPending / $visitorTotal) * 100);
                        $setujuPct = round(($pengunjungSetuju / $visitorTotal) * 100);
                        $tolakPct = round(($pengunjungTolak / $visitorTotal) * 100);
                    @endphp

                    <div>
                        <div class="flex items-center justify-between text-xs font-bold text-zinc-700 mb-1.5">
                            <span class="flex items-center gap-1.5 font-space"><span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>Disetujui</span>
                            <span class="font-mono text-zinc-500">{{ $pengunjungSetuju }} Rombongan ({{ $setujuPct }}%)</span>
                        </div>
                        <div class="w-full bg-zinc-100 h-3 rounded-full overflow-hidden border border-zinc-200/40">
                            <div class="bg-emerald-500 h-full rounded-full transition-all duration-500" style="width: {{ $setujuPct }}%"></div>
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center justify-between text-xs font-bold text-zinc-700 mb-1.5">
                            <span class="flex items-center gap-1.5 font-space"><span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>Pending</span>
                            <span class="font-mono text-zinc-500">{{ $pengunjungPending }} Rombongan ({{ $pendingPct }}%)</span>
                        </div>
                        <div class="w-full bg-zinc-100 h-3 rounded-full overflow-hidden border border-zinc-200/40">
                            <div class="bg-amber-500 h-full rounded-full transition-all duration-500" style="width: {{ $pendingPct }}%"></div>
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center justify-between text-xs font-bold text-zinc-700 mb-1.5">
                            <span class="flex items-center gap-1.5 font-space"><span class="w-2.5 h-2.5 rounded-full bg-red-500"></span>Ditolak</span>
                            <span class="font-mono text-zinc-500">{{ $pengunjungTolak }} Rombongan ({{ $tolakPct }}%)</span>
                        </div>
                        <div class="w-full bg-zinc-100 h-3 rounded-full overflow-hidden border border-zinc-200/40">
                            <div class="bg-red-500 h-full rounded-full transition-all duration-500" style="width: {{ $tolakPct }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white/60 backdrop-blur-xl border border-zinc-200/60 rounded-3xl p-6 shadow-xl shadow-zinc-200/40">
                <h4 class="text-xs font-bold text-zinc-400 font-space uppercase mb-6 tracking-wider">Status & Aktivitas Peneliti</h4>

                <div class="space-y-5">
                    @php
                        $researchTotal = $penelitiPending + $penelitiSedang + $penelitiSelesai + $penelitiTolak;
                        $researchTotal = $researchTotal > 0 ? $researchTotal : 1; // avoid division by zero
                        $pendingRPct = round(($penelitiPending / $researchTotal) * 100);
                        $sedangRPct = round(($penelitiSedang / $researchTotal) * 100);
                        $selesaiRPct = round(($penelitiSelesai / $researchTotal) * 100);
                        $tolakRPct = round(($penelitiTolak / $researchTotal) * 100);
                    @endphp

                    <div>
                        <div class="flex items-center justify-between text-xs font-bold text-zinc-700 mb-1.5">
                            <span class="flex items-center gap-1.5 font-space"><span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>Sedang Penelitian (Aktif)</span>
                            <span class="font-mono text-zinc-500">{{ $penelitiSedang }} Orang ({{ $sedangRPct }}%)</span>
                        </div>
                        <div class="w-full bg-zinc-100 h-3 rounded-full overflow-hidden border border-zinc-200/40">
                            <div class="bg-emerald-500 h-full rounded-full transition-all duration-500" style="width: {{ $sedangRPct }}%"></div>
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center justify-between text-xs font-bold text-zinc-700 mb-1.5">
                            <span class="flex items-center gap-1.5 font-space"><span class="w-2.5 h-2.5 rounded-full bg-zinc-400"></span>Selesai Penelitian</span>
                            <span class="font-mono text-zinc-500">{{ $penelitiSelesai }} Orang ({{ $selesaiRPct }}%)</span>
                        </div>
                        <div class="w-full bg-zinc-100 h-3 rounded-full overflow-hidden border border-zinc-200/40">
                            <div class="bg-zinc-400 h-full rounded-full transition-all duration-500" style="width: {{ $selesaiRPct }}%"></div>
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center justify-between text-xs font-bold text-zinc-700 mb-1.5">
                            <span class="flex items-center gap-1.5 font-space"><span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>Pending</span>
                            <span class="font-mono text-zinc-500">{{ $penelitiPending }} Orang ({{ $pendingRPct }}%)</span>
                        </div>
                        <div class="w-full bg-zinc-100 h-3 rounded-full overflow-hidden border border-zinc-200/40">
                            <div class="bg-amber-500 h-full rounded-full transition-all duration-500" style="width: {{ $pendingRPct }}%"></div>
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center justify-between text-xs font-bold text-zinc-700 mb-1.5">
                            <span class="flex items-center gap-1.5 font-space"><span class="w-2.5 h-2.5 rounded-full bg-red-500"></span>Ditolak</span>
                            <span class="font-mono text-zinc-500">{{ $penelitiTolak }} Orang ({{ $tolakRPct }}%)</span>
                        </div>
                        <div class="w-full bg-zinc-100 h-3 rounded-full overflow-hidden border border-zinc-200/40">
                            <div class="bg-red-500 h-full rounded-full transition-all duration-500" style="width: {{ $tolakRPct }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</x-dashboard-layout>