<x-dashboard-layout title="User Dashboard">
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

    <div class="rounded-lg border border-border bg-card text-card-foreground shadow-sm p-8 text-center">
        <h2 class="text-2xl font-bold">Selamat Datang, {{ Auth::user()->name }}!</h2>
        <p class="text-muted-foreground mt-2">Ini adalah halaman dashboard khusus User.</p>
    </div>
</x-dashboard-layout>