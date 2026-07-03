<x-dashboard-layout title="Beranda">
    <x-slot name="sidebar">
        <x-ui.nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
            Beranda
        </x-ui.nav-link>
        
        <x-ui.nav-link :href="route('profile.show')" :active="request()->routeIs('profile.show') || request()->routeIs('profile.edit')">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            Profil Saya
        </x-ui.nav-link>

        <div class="mt-4 pt-4 border-t border-zinc-200/60">
            <a href="{{ route('home') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium text-zinc-600 hover:text-zinc-900 hover:bg-zinc-50 rounded-xl transition-all group">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4 group-hover:translate-x-[-2px] transition-transform">
                    <path d="m12 19-7-7 7-7"/><path d="M19 12H5"/>
                </svg>
                Kembali ke Home
            </a>
        </div>
    </x-slot>

    @php
        $user = Auth::user();
        $hour = now()->hour;
        $greeting = $hour < 11 ? 'Selamat Pagi' : ($hour < 15 ? 'Selamat Siang' : ($hour < 18 ? 'Selamat Sore' : 'Selamat Malam'));
    @endphp

    <div class="space-y-6">

        {{-- ── FLASH MESSAGES ──────────────────────────────────────────── --}}
        @if(session('success'))
            <div class="flex items-start gap-3 bg-emerald-50 border border-emerald-200 text-emerald-800 px-5 py-4 rounded-2xl">
                <svg class="w-5 h-5 mt-0.5 shrink-0 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <p class="text-sm font-medium">{{ session('success') }}</p>
            </div>
        @endif

        {{-- ── HERO GREETING ────────────────────────────────────────────── --}}
        <div class="relative overflow-hidden bg-gradient-to-br from-zinc-950 via-zinc-900 to-emerald-950 rounded-3xl p-8 text-white shadow-xl shadow-zinc-950/20">
            {{-- Decorative blobs --}}
            <div class="absolute -top-12 -right-12 w-56 h-56 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-8 -left-8 w-40 h-40 bg-blue-500/10 rounded-full blur-2xl pointer-events-none"></div>
            <div class="relative z-10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-5">
                <div>
                    <p class="text-emerald-400 text-sm font-semibold mb-1 tracking-wide">{{ $greeting }},</p>
                    <h1 class="text-3xl font-bold text-white leading-tight">{{ $user->name }} 👋</h1>
                    <p class="text-zinc-400 text-sm mt-2 max-w-sm">Selamat datang di portal pengguna Kebun Raya Sambas. Jelajahi, daftarkan kunjungan, atau ajukan penelitian Anda.</p>
                </div>
                <div class="shrink-0">
                    <div class="w-16 h-16 rounded-2xl bg-white/10 backdrop-blur-sm border border-white/10 flex items-center justify-center text-3xl">
                        🌿
                    </div>
                </div>
            </div>
        </div>

        {{-- ── QUICK ACTIONS ─────────────────────────────────────────────── --}}
        <div>
            <h2 class="text-sm font-bold text-zinc-500 uppercase tracking-widest mb-4">Aksi Cepat</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">

                <a href="{{ route('peta') }}" 
                   class="group flex items-center gap-4 p-5 bg-white border border-zinc-200/80 rounded-2xl hover:shadow-lg hover:border-emerald-250 transition-all duration-300 hover:-translate-y-0.5">
                    <div class="w-11 h-11 rounded-xl bg-emerald-100 flex items-center justify-center text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-colors shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                    </div>
                    <div>
                        <p class="font-bold text-zinc-900 text-sm group-hover:text-emerald-700 transition-colors">Jelajahi Peta</p>
                        <p class="text-xs text-zinc-500">Lihat kawasan & lokasi KRS</p>
                    </div>
                </a>

                <a href="{{ route('pendaftaran.pengunjung') }}" 
                   class="group flex items-center gap-4 p-5 bg-white border border-zinc-200/80 rounded-2xl hover:shadow-lg hover:border-blue-250 transition-all duration-300 hover:-translate-y-0.5">
                    <div class="w-11 h-11 rounded-xl bg-blue-100 flex items-center justify-center text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-colors shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <div>
                        <p class="font-bold text-zinc-900 text-sm group-hover:text-blue-700 transition-colors">Daftar Kunjungan</p>
                        <p class="text-xs text-zinc-500">Rencanakan kunjungan Anda</p>
                    </div>
                </a>

                <a href="{{ route('pendaftaran.peneliti') }}" 
                   class="group flex items-center gap-4 p-5 bg-white border border-zinc-200/80 rounded-2xl hover:shadow-lg hover:border-violet-250 transition-all duration-300 hover:-translate-y-0.5">
                    <div class="w-11 h-11 rounded-xl bg-violet-100 flex items-center justify-center text-violet-600 group-hover:bg-violet-600 group-hover:text-white transition-colors shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <div>
                        <p class="font-bold text-zinc-900 text-sm group-hover:text-violet-700 transition-colors">Daftar Penelitian</p>
                        <p class="text-xs text-zinc-500">Ajukan izin penelitian</p>
                    </div>
                </a>

                <a href="{{ route('koleksi') }}" 
                   class="group flex items-center gap-4 p-5 bg-white border border-zinc-200/80 rounded-2xl hover:shadow-lg hover:border-amber-250 transition-all duration-300 hover:-translate-y-0.5">
                    <div class="w-11 h-11 rounded-xl bg-amber-100 flex items-center justify-center text-amber-600 group-hover:bg-amber-600 group-hover:text-white transition-colors shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                    </div>
                    <div>
                        <p class="font-bold text-zinc-900 text-sm group-hover:text-amber-700 transition-colors">Koleksi Flora</p>
                        <p class="text-xs text-zinc-500">Lihat katalog tanaman KRS</p>
                    </div>
                </a>

                <a href="{{ route('profil') }}" 
                   class="group flex items-center gap-4 p-5 bg-white border border-zinc-200/80 rounded-2xl hover:shadow-lg hover:border-teal-250 transition-all duration-300 hover:-translate-y-0.5">
                    <div class="w-11 h-11 rounded-xl bg-teal-100 flex items-center justify-center text-teal-600 group-hover:bg-teal-600 group-hover:text-white transition-colors shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <p class="font-bold text-zinc-900 text-sm group-hover:text-teal-700 transition-colors">Tentang KRS</p>
                        <p class="text-xs text-zinc-500">Profil Kebun Raya Sambas</p>
                    </div>
                </a>

                <a href="{{ route('profile.edit') }}" 
                   class="group flex items-center gap-4 p-5 bg-white border border-zinc-200/80 rounded-2xl hover:shadow-lg hover:border-zinc-400 transition-all duration-300 hover:-translate-y-0.5">
                    <div class="w-11 h-11 rounded-xl bg-zinc-100 flex items-center justify-center text-zinc-600 group-hover:bg-zinc-700 group-hover:text-white transition-colors shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><circle cx="12" cy="12" r="3"/></svg>
                    </div>
                    <div>
                        <p class="font-bold text-zinc-900 text-sm group-hover:text-zinc-800 transition-colors">Edit Profil</p>
                        <p class="text-xs text-zinc-500">Perbarui data akun Anda</p>
                    </div>
                </a>

            </div>
        </div>

        {{-- ── RIWAYAT PENDAFTARAN SAYA ─────────────────────────────────── --}}
        <div class="bg-white border border-zinc-200/80 rounded-3xl p-6 shadow-sm">
            <h2 class="text-lg font-bold text-zinc-900 font-space tracking-tight mb-5 flex items-center gap-2">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                Riwayat Pendaftaran Saya
            </h2>

            @if($pengunjungRegistrations->isEmpty() && $penelitiRegistrations->isEmpty())
                <div class="text-center py-8 bg-zinc-50 rounded-2xl border border-dashed border-zinc-200">
                    <svg class="w-12 h-12 text-zinc-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <p class="text-zinc-500 text-sm">Belum ada riwayat pendaftaran.</p>
                    <p class="text-xs text-zinc-400 mt-1">Silakan gunakan menu Aksi Cepat di atas untuk melakukan pendaftaran pertama Anda.</p>
                </div>
            @else
                <div class="space-y-6">
                    {{-- Riwayat Kunjungan --}}
                    @if($pengunjungRegistrations->isNotEmpty())
                        <div>
                            <h3 class="text-xs font-bold text-zinc-400 uppercase tracking-wider mb-3">Pendaftaran Kunjungan</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($pengunjungRegistrations as $reg)
                                    <div class="bg-zinc-50/60 border border-zinc-200/60 rounded-2xl p-5 flex flex-col justify-between hover:border-zinc-300 transition-colors">
                                        <div class="flex items-start justify-between gap-3 mb-3">
                                            <div>
                                                <h4 class="font-bold text-zinc-900 text-sm">Kunjungan Rombongan</h4>
                                                <p class="text-xs text-zinc-500 mt-1">Tanggal: {{ \Carbon\Carbon::parse($reg->tanggal_kunjungan)->translatedFormat('d F Y') }}</p>
                                                <p class="text-xs text-zinc-500">Jumlah: {{ $reg->jumlah_rombongan }} orang</p>
                                            </div>
                                            <div>
                                                @if($reg->status === 'pending')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200 uppercase tracking-wide">
                                                        Pending
                                                    </span>
                                                @elseif($reg->status === 'disetujui')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-55/15 text-emerald-700 border border-emerald-200/40 uppercase tracking-wide">
                                                        Disetujui
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-red-55/15 text-red-700 border border-red-200/40 uppercase tracking-wide">
                                                        Ditolak
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        @if($reg->keperluan)
                                            <p class="text-xs text-zinc-600 bg-white/60 border border-zinc-150 p-2.5 rounded-xl italic">
                                                "{{ $reg->keperluan }}"
                                            </p>
                                        @endif

                                        @if($reg->status !== 'disetujui')
                                            <div class="mt-4 flex items-center justify-end gap-2 border-t border-zinc-200/60 pt-3">
                                                <a href="{{ route('dashboard.pengunjungs.edit', $reg->id) }}" 
                                                   class="px-3 py-1.5 bg-zinc-900 hover:bg-zinc-800 text-white rounded-lg text-xs font-bold transition-all">
                                                    Edit
                                                </a>
                                                <form action="{{ route('dashboard.pengunjungs.destroy', $reg->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pendaftaran kunjungan ini?');" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="px-3 py-1.5 bg-red-50 hover:bg-red-650 hover:text-white text-red-650 rounded-lg text-xs font-bold transition-all">
                                                        Batal
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Riwayat Penelitian --}}
                    @if($penelitiRegistrations->isNotEmpty())
                        <div>
                            <h3 class="text-xs font-bold text-zinc-400 uppercase tracking-wider mb-3 mt-2">Pendaftaran Penelitian</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($penelitiRegistrations as $reg)
                                    <div class="bg-zinc-50/60 border border-zinc-200/60 rounded-2xl p-5 flex flex-col justify-between hover:border-zinc-300 transition-colors">
                                        <div class="flex items-start justify-between gap-3 mb-3">
                                            <div class="min-w-0 flex-1">
                                                <h4 class="font-bold text-zinc-900 text-sm truncate" title="{{ $reg->judul_penelitian }}">{{ $reg->judul_penelitian }}</h4>
                                                <p class="text-xs text-zinc-500 mt-1">Institusi: {{ $reg->institusi }}</p>
                                                <p class="text-xs text-zinc-500">Mulai: {{ \Carbon\Carbon::parse($reg->tanggal_mulai)->translatedFormat('d F Y') }}</p>
                                                <p class="text-xs text-zinc-500">Selesai: {{ \Carbon\Carbon::parse($reg->tanggal_selesai)->translatedFormat('d F Y') }}</p>
                                            </div>
                                            <div class="shrink-0">
                                                @if($reg->status === 'pending')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200 uppercase tracking-wide">
                                                        Pending
                                                    </span>
                                                @elseif($reg->status === 'disetujui')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-55/15 text-emerald-700 border border-emerald-200/40 uppercase tracking-wide">
                                                        Disetujui
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-red-55/15 text-red-700 border border-red-200/40 uppercase tracking-wide">
                                                        Ditolak
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        @if($reg->catatan_admin)
                                            <div class="mt-2 text-xs text-red-800 bg-red-50 border border-red-150 p-2.5 rounded-xl">
                                                <p class="font-bold">Catatan Admin:</p>
                                                <p class="mt-0.5">{{ $reg->catatan_admin }}</p>
                                            </div>
                                        @endif

                                        @if($reg->status !== 'disetujui')
                                            <div class="mt-4 flex items-center justify-end gap-2 border-t border-zinc-200/60 pt-3">
                                                <a href="{{ route('dashboard.penelitis.edit', $reg->id) }}" 
                                                   class="px-3 py-1.5 bg-zinc-900 hover:bg-zinc-800 text-white rounded-lg text-xs font-bold transition-all">
                                                    Edit
                                                </a>
                                                <form action="{{ route('dashboard.penelitis.destroy', $reg->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan permohonan penelitian ini?');" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="px-3 py-1.5 bg-red-50 hover:bg-red-650 hover:text-white text-red-650 rounded-lg text-xs font-bold transition-all">
                                                        Batal
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            @endif
        </div>

        {{-- ── INFO CARD ─────────────────────────────────────────────────── --}}
        <div class="bg-gradient-to-r from-emerald-50 to-teal-50 border border-emerald-200/60 rounded-3xl p-6">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-2xl bg-emerald-600 flex items-center justify-center shrink-0 shadow-lg shadow-emerald-600/25">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <h3 class="font-bold text-emerald-900 mb-1">Tentang Portal ini</h3>
                    <p class="text-sm text-emerald-800/70 leading-relaxed">
                        Portal ini menyediakan akses digital ke kawasan <strong>Kebun Raya Sambas</strong> — mulai dari peta interaktif, katalog koleksi flora, hingga pendaftaran kunjungan dan penelitian. 
                        Gunakan menu di atas untuk memulai.
                    </p>
                </div>
            </div>
        </div>

    </div>
</x-dashboard-layout>