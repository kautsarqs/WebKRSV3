<nav class="fixed top-0 w-full z-9999 border-b border-zinc-200 bg-white/70 backdrop-blur-xl">
    <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">

        <a href="/" class="flex items-center gap-3 font-heading font-bold text-xl tracking-wider text-zinc-900">
            <img src="{{ asset('storage/images/logoKRS.png') }}" alt="Logo" class="h-12 w-auto">
            <span class="pt-2">KEBUN RAYA SAMBAS</span>
        </a>

        <div class="hidden md:flex items-center gap-8">
            <a href="{{ route('home') }}"
                class="text-sm font-medium {{ request()->routeIs('home') ? 'text-zinc-900 font-bold' : 'text-zinc-600' }} hover:text-zinc-900 transition">Beranda</a>
            <a href="{{ route('profil') }}"
                class="text-sm font-medium {{ request()->routeIs('profil') ? 'text-zinc-900 font-bold' : 'text-zinc-500' }} hover:text-zinc-900 transition">Profil</a>
            <a href="{{ route('koleksi') }}"
                class="text-sm font-medium {{ request()->routeIs('koleksi') ? 'text-zinc-900 font-bold' : 'text-zinc-500' }} hover:text-zinc-900 transition">Koleksi</a>
            <a href="{{ route('peta') }}"
                class="text-sm font-medium {{ request()->routeIs('peta') ? 'text-zinc-900 font-bold' : 'text-zinc-500' }} hover:text-zinc-900 transition">Peta</a>
            <a href="{{ route('penelitian') }}"
                class="text-sm font-medium {{ request()->routeIs('penelitian') ? 'text-zinc-900 font-bold' : 'text-zinc-500' }} hover:text-zinc-900 transition">Penelitian</a>
            <a href="{{ route('kontak') }}"
                class="text-sm font-medium {{ request()->routeIs('kontak') ? 'text-zinc-900 font-bold' : 'text-zinc-500' }} hover:text-zinc-900 transition">Kontak</a>
        </div>


        <div class="flex items-center gap-6">
            @if (Route::has('login'))
                @auth
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center gap-2.5 transition-all hover:opacity-80 focus:outline-none">
                            <div class="text-right hidden sm:block">
                                <p class="text-sm font-bold text-zinc-900 font-space leading-none">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-zinc-500 font-medium">{{ Auth::user()->role=="admin"?"Administrator":"User" }}</p>
                            </div>
                            
                            @if (Auth::user()->avatar)
                                <img src="{{ Auth::user()->avatar }}" class="w-9 h-9 rounded-full border border-zinc-200 shadow-sm object-cover">
                            @else
                                <div class="w-9 h-9 rounded-full bg-zinc-100 border border-zinc-200 flex items-center justify-center text-zinc-600 font-bold font-space">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                            @endif
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
                    <a href="{{ route('login') }}" class="text-sm font-medium text-zinc-500 hover:text-zinc-900 transition">
                        Log in
                    </a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}"
                            class="px-5 py-2 rounded-full bg-zinc-900 text-white text-sm font-medium hover:bg-zinc-700 transition duration-300 shadow-lg shadow-zinc-200">
                            Register
                        </a>
                    @endif
                @endauth
            @endif
        </div>
    </div>
</nav>