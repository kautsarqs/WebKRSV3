<x-slot name="sidebar">
    @if(Auth::user()->role === 'admin')
        <x-ui.nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
            Dashboard
        </x-ui.nav-link>

        <x-ui.nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            Kelola User
        </x-ui.nav-link>

        <x-ui.nav-link :href="route('admin.maps.index')" :active="request()->routeIs('admin.maps.*')">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4"><path d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            Kelola Peta
        </x-ui.nav-link>

        <x-ui.nav-link :href="route('admin.koleksi.index')" :active="request()->routeIs('admin.koleksi.*')">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4"><path d="M7 20h10"/><path d="M10 20c5.5-2.5.8-6.4 3-10"/><path d="M9.5 9.4c1.1.8 1.8 2.2 1.8 3.7 0 .9-.5 1.6-1.3 2.1"/><path d="M19 9c-2.2.3-6 2-6 6"/><path d="M13 12c.9-.6 1.4-1.6 1.4-2.8 0-1.9-1.3-3.2-3.4-2.9"/></svg>
            Kelola Koleksi
        </x-ui.nav-link>

        <x-ui.nav-link :href="route('admin.pengunjung.index')" :active="request()->routeIs('admin.pengunjung.*')">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><polyline points="16 11 18 13 22 9"/></svg>
            Daftar Pengunjung
        </x-ui.nav-link>

        <x-ui.nav-link :href="route('admin.peneliti.index')" :active="request()->routeIs('admin.peneliti.*')">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4"><path d="M21.42 10.922a1 1 0 0 0-.019-1.838L12.83 5.18a2 2 0 0 0-1.66 0L2.6 9.08a1 1 0 0 0 0 1.832l8.57 3.908a2 2 0 0 0 1.66 0z"/><path d="M6 12v5c0 2 2 3 6 3s6-1 6-3v-5"/><path d="M21.5 12v6"/></svg>
            Daftar Peneliti
        </x-ui.nav-link>
    @else
        <x-ui.nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
            Dashboard
        </x-ui.nav-link>
    @endif

    <x-ui.nav-link :href="route('profile.show')" :active="request()->routeIs('profile.show') || request()->routeIs('profile.edit')">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
        Profil Saya
    </x-ui.nav-link>

    <div class="mt-4 pt-4 border-t border-zinc-200/60">
        <a href="{{ route('home') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium text-zinc-600 hover:text-zinc-900 hover:bg-zinc-50 rounded-xl transition-all group">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4 group-hover:-translate-x-0.5 transition-transform">
                <path d="m12 19-7-7 7-7"/><path d="M19 12H5"/>
            </svg>
            Kembali ke Home
        </a>
    </div>
</x-slot>