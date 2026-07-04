@php
    $isHome = request()->routeIs('home') || request()->is('/');
    $activeHome = $isHome;
    $activeProfil = request()->routeIs('profil');
    $activeKoleksi = request()->routeIs('koleksi') || request()->is('koleksi*');
    $activePeta = request()->routeIs('peta');
    $activePendaftaran = request()->routeIs('pendaftaran.*') || request()->is('pendaftaran*');
@endphp

<nav x-data="{ 
    mobileMenuOpen: false, 
    scrolled: @if($isHome) false @else true @endif
}" 
@if($isHome)
    x-init="scrolled = window.scrollY > 20"
    @scroll.window="scrolled = window.scrollY > 20"
@endif
class="fixed top-0 w-full z-50 transition-all duration-500 border-b {{ $isHome ? 'bg-transparent border-transparent' : 'bg-white border-zinc-200 shadow-md' }}"
:class="(scrolled || mobileMenuOpen) ? 'bg-white border-zinc-200 shadow-md' : 'bg-transparent border-transparent'">
    <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">

        <a href="/" 
           class="flex items-center gap-2 sm:gap-3 font-heading font-bold text-sm sm:text-base lg:text-xl tracking-wider shrink-0 transition-colors duration-300 {{ $isHome ? 'text-white' : 'text-zinc-900' }}"
           :class="(scrolled || mobileMenuOpen) ? 'text-zinc-900' : 'text-white'">
            <img src="{{ asset('storage/images/logoKRS.png') }}" alt="Logo" class="h-8 sm:h-10 lg:h-12 w-auto">
            <span class="pt-1 lg:pt-2">KEBUN RAYA SAMBAS</span>
        </a>

        {{-- Tampilkan menu desktop hanya di ukuran Large (lg) ke atas agar di tablet tidak berantakan --}}
        <div class="hidden lg:flex items-center gap-8">
            <a href="{{ route('home') }}"
                class="text-sm font-medium transition duration-300"
                :class="{ 
                    '{{ $activeHome ? 'text-white font-bold' : 'text-zinc-200 hover:text-white' }}': !(scrolled || mobileMenuOpen),
                    '{{ $activeHome ? 'text-emerald-800 font-bold' : 'text-zinc-600 hover:text-zinc-900' }}': (scrolled || mobileMenuOpen)
                }">Beranda</a>
            <a href="{{ route('profil') }}"
                class="text-sm font-medium transition duration-300"
                :class="{ 
                    '{{ $activeProfil ? 'text-white font-bold' : 'text-zinc-200 hover:text-white' }}': !(scrolled || mobileMenuOpen),
                    '{{ $activeProfil ? 'text-emerald-800 font-bold' : 'text-zinc-600 hover:text-zinc-900' }}': (scrolled || mobileMenuOpen)
                }">Tentang Kami</a>
            <a href="{{ route('koleksi') }}"
                class="text-sm font-medium transition duration-300"
                :class="{ 
                    '{{ $activeKoleksi ? 'text-white font-bold' : 'text-zinc-200 hover:text-white' }}': !(scrolled || mobileMenuOpen),
                    '{{ $activeKoleksi ? 'text-emerald-800 font-bold' : 'text-zinc-600 hover:text-zinc-900' }}': (scrolled || mobileMenuOpen)
                }">Koleksi</a>
            <a href="{{ route('peta') }}"
                class="text-sm font-medium transition duration-300"
                :class="{ 
                    '{{ $activePeta ? 'text-white font-bold' : 'text-zinc-200 hover:text-white' }}': !(scrolled || mobileMenuOpen),
                    '{{ $activePeta ? 'text-emerald-800 font-bold' : 'text-zinc-600 hover:text-zinc-900' }}': (scrolled || mobileMenuOpen)
                }">Peta</a>
            
            <div class="relative group">
                <button class="flex items-center gap-1 text-sm font-medium transition duration-300 focus:outline-none"
                    :class="{ 
                        '{{ $activePendaftaran ? 'text-white font-bold' : 'text-zinc-200 hover:text-white' }}': !(scrolled || mobileMenuOpen),
                        '{{ $activePendaftaran ? 'text-emerald-800 font-bold' : 'text-zinc-600 hover:text-zinc-900' }}': (scrolled || mobileMenuOpen)
                    }">
                    Pendaftaran
                    <svg class="w-4 h-4 transition-transform duration-200 group-hover:rotate-180 {{ $isHome ? 'text-zinc-300 group-hover:text-white' : 'text-zinc-400 group-hover:text-zinc-900' }}" 
                        :class="(scrolled || mobileMenuOpen) ? 'text-zinc-400 group-hover:text-zinc-900' : 'text-zinc-300 group-hover:text-white'"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div class="absolute top-full left-1/2 -translate-x-1/2 pt-4 w-56 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 transform translate-y-2 group-hover:translate-y-0">
                    <div class="bg-white rounded-xl shadow-xl border border-zinc-100 p-1.5 ring-1 ring-zinc-900/5">
                        <a href="{{ route('pendaftaran.pengunjung') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-sm {{ request()->routeIs('pendaftaran.pengunjung') ? 'text-emerald-800 font-bold bg-emerald-50/50' : 'text-zinc-600 hover:bg-zinc-50 hover:text-zinc-900' }} rounded-lg transition-colors">
                            <span class="w-6 h-6 rounded-lg bg-blue-100 flex items-center justify-center">
                                <svg class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </span>
                            Pengunjung
                        </a>
                        <a href="{{ route('pendaftaran.peneliti') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-sm {{ request()->routeIs('pendaftaran.peneliti') ? 'text-emerald-800 font-bold bg-emerald-50/50' : 'text-zinc-600 hover:bg-zinc-50 hover:text-zinc-900' }} rounded-lg transition-colors">
                            <span class="w-6 h-6 rounded-lg bg-violet-100 flex items-center justify-center">
                                <svg class="w-3.5 h-3.5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </span>
                            Peneliti
                        </a>
                    </div>
                </div>
            </div>
            </div>


        <div class="flex items-center gap-4 sm:gap-6">
            @if (Route::has('login'))
                @auth
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center gap-2.5 transition-all hover:opacity-80 focus:outline-none">
                            <div class="text-right hidden sm:block">
                                <p class="text-sm font-bold font-space leading-none transition-colors duration-300 {{ $isHome ? 'text-white' : 'text-zinc-900' }}"
                                   :class="(scrolled || mobileMenuOpen) ? 'text-zinc-900' : 'text-white'">{{ Auth::user()->name }}</p>
                                <p class="text-xs font-medium transition-colors duration-300 {{ $isHome ? 'text-zinc-200' : 'text-zinc-600' }}"
                                   :class="(scrolled || mobileMenuOpen) ? 'text-zinc-600' : 'text-zinc-200'">{{ Auth::user()->role=="admin"?"Administrator":"User" }}</p>
                            </div>
                            
                            <img src="{{ Auth::user()->avatar_url }}" class="w-9 h-9 rounded-full border border-zinc-200 shadow-sm object-cover">
                        </button>

                        <div x-show="open" @click.away="open = false" 
                             class="absolute right-0 mt-3 w-48 bg-white border border-zinc-200 rounded-xl shadow-xl py-1 z-50 overflow-hidden"
                             style="display: none;">
                             @if(Auth::user()->role === 'admin')
                                <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-sm text-zinc-700 hover:bg-zinc-50 transition-colors">Dashboard</a>
                             @else
                                <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm text-zinc-700 hover:bg-zinc-50 transition-colors">Dashboard</a>
                             @endif
                            <a href="{{ route('profile.show') }}" class="block px-4 py-2 text-sm text-zinc-700 hover:bg-zinc-50 transition-colors">Profile</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors font-medium">
                                    Log Out
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" 
                       class="hidden lg:block text-sm font-medium transition duration-300 {{ $isHome ? 'text-zinc-200 hover:text-white' : 'text-zinc-600 hover:text-zinc-900' }}"
                       :class="(scrolled || mobileMenuOpen) ? 'text-zinc-600 hover:text-zinc-900' : 'text-zinc-200 hover:text-white'">
                        Log in
                    </a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}"
                            class="hidden lg:block px-5 py-2 rounded-full bg-zinc-900 text-white text-sm font-medium hover:bg-zinc-700 transition duration-300 ">
                            Register
                        </a>
                    @endif
                @endauth
            @endif
            
            {{-- Tombol Menu Hamburger (Khusus Mobile) --}}
            <div class="lg:hidden flex items-center">
                <button @click="mobileMenuOpen = !mobileMenuOpen" type="button" class="transition-colors duration-300 focus:outline-none {{ $isHome ? 'text-white hover:text-zinc-200' : 'text-zinc-900 hover:text-zinc-600' }}" 
                    :class="(scrolled || mobileMenuOpen) ? 'text-zinc-900 hover:text-zinc-600' : 'text-white hover:text-zinc-200'"
                    aria-controls="mobile-menu" aria-expanded="false">
                    <span class="sr-only">Buka menu utama</span>
                    {{-- Ikon Hamburger --}}
                    <svg x-show="!mobileMenuOpen" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    {{-- Ikon Close (X) --}}
                    <svg x-show="mobileMenuOpen" style="display: none;" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Kontainer Dropdown Menu Mobile --}}
    <div x-show="mobileMenuOpen" 
         style="display: none;"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-2"
         class="lg:hidden absolute top-20 left-0 w-full bg-white border-b border-zinc-200 shadow-lg" 
         id="mobile-menu">
        <div class="px-6 pt-2 pb-6 flex flex-col gap-2">
            <a href="{{ route('home') }}" class="block px-3 py-2 rounded-md text-base font-medium {{ $activeHome ? 'text-emerald-800 font-bold bg-emerald-50/50' : 'text-zinc-600 hover:text-zinc-900 hover:bg-zinc-50' }}">Beranda</a>
            <a href="{{ route('profil') }}" class="block px-3 py-2 rounded-md text-base font-medium {{ $activeProfil ? 'text-emerald-800 font-bold bg-emerald-50/50' : 'text-zinc-600 hover:text-zinc-900 hover:bg-zinc-50' }}">Tentang Kami</a>
            <a href="{{ route('koleksi') }}" class="block px-3 py-2 rounded-md text-base font-medium {{ $activeKoleksi ? 'text-emerald-800 font-bold bg-emerald-50/50' : 'text-zinc-600 hover:text-zinc-900 hover:bg-zinc-50' }}">Koleksi</a>
            <a href="{{ route('peta') }}" class="block px-3 py-2 rounded-md text-base font-medium {{ $activePeta ? 'text-emerald-800 font-bold bg-emerald-50/50' : 'text-zinc-600 hover:text-zinc-900 hover:bg-zinc-50' }}">Peta</a>
            
            <div x-data="{ mobilePendaftaranOpen: {{ $activePendaftaran ? 'true' : 'false' }} }" class="block">
                <button @click="mobilePendaftaranOpen = !mobilePendaftaranOpen" class="w-full text-left flex items-center justify-between px-3 py-2 rounded-md text-base font-medium {{ $activePendaftaran ? 'text-emerald-800 font-bold bg-emerald-50/50' : 'text-zinc-600 hover:text-zinc-900 hover:bg-zinc-50' }} focus:outline-none">
                    Pendaftaran
                    <svg :class="{'rotate-180': mobilePendaftaranOpen}" class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                <div x-show="mobilePendaftaranOpen" style="display: none;" class="pl-4 mt-1 flex flex-col gap-1">
                    <a href="{{ route('pendaftaran.pengunjung') }}" class="block px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('pendaftaran.pengunjung') ? 'text-emerald-800 font-bold bg-emerald-50/50' : 'text-zinc-500 hover:text-zinc-900 hover:bg-zinc-50' }}">Pengunjung</a>
                    <a href="{{ route('pendaftaran.peneliti') }}" class="block px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('pendaftaran.peneliti') ? 'text-emerald-800 font-bold bg-emerald-50/50' : 'text-zinc-500 hover:text-zinc-900 hover:bg-zinc-50' }}">Peneliti</a>
                </div>
            </div>

            {{-- Tombol Login/Register untuk Mobile --}}
            @guest
                <div class="border-t border-zinc-200 mt-4 pt-4 flex flex-col gap-3">
                    <a href="{{ route('login') }}" class="block text-center px-3 py-2 rounded-md text-base font-medium text-zinc-600 hover:text-zinc-900 hover:bg-zinc-50">
                        Log in
                    </a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}"
                            class="block text-center px-3 py-2.5 rounded-full bg-zinc-900 text-white text-base font-medium hover:bg-zinc-700 transition duration-300">
                            Register
                        </a>
                    @endif
                </div>
            @endguest
        </div>
    </div>
</nav>