<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Security-Policy" content="default-src 'self' 'unsafe-inline' 'unsafe-eval' https: http: data: blob:; img-src 'self' data: blob: https: http:;">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Kebun Raya Sambas') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logoKRS_square.png') }}" />

    <meta name="theme-color" content="#064e3b" />
    <link rel="manifest" href="{{ asset('manifest.json') }}" />
    <link rel="apple-touch-icon" href="{{ asset('images/logoKRS_square.png') }}" />
    <meta name="mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />
    <meta name="apple-mobile-web-app-title" content="KRS" />

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700|space-grotesk:500,700" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-zinc-50 text-zinc-900 font-inter antialiased overflow-x-hidden"
      x-data="{ isLoading: true, sidebarOpen: false }"
      x-init="if (document.readyState === 'complete') { setTimeout(() => { isLoading = false }, 400); } else { window.addEventListener('load', () => { setTimeout(() => { isLoading = false }, 400); }); }">

    <template x-if="isLoading">
        <x-dashboard.skeleton-shell />
    </template>

    <div class="fixed inset-0 z-0 pointer-events-none overflow-hidden">
        <div class="absolute top-[-10%] left-[-10%] w-150 h-150 bg-green-200/40 rounded-full blur-[120px] mix-blend-multiply opacity-70"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-150 h-150 bg-zinc-300/40 rounded-full blur-[120px] mix-blend-multiply opacity-70"></div>
    </div>

    <div class="relative z-10 flex h-screen overflow-hidden"
         x-show="!isLoading"
         x-cloak
         x-transition:enter="transition ease-out duration-500"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100">

        <div x-show="sidebarOpen"
             @click="sidebarOpen = false"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-30 bg-zinc-900/40 backdrop-blur-sm lg:hidden"
             style="display: none;"></div>

        <aside class="hidden lg:flex w-64 flex-col border-r border-zinc-200/80 bg-white/70 backdrop-blur-xl h-full shadow-sm relative z-20 shrink-0">
            <div class="h-16 flex items-center px-5 border-b border-zinc-200/80 gap-2.5 shrink-0">
                <div class="flex items-center justify-center p-1 bg-zinc-100 border border-zinc-200/60 rounded-xl">
                    <img src="{{ asset('images/logoKRS.png') }}" alt="Logo" class="h-6 w-auto object-contain">
                </div>
                <div class="flex flex-col">
                    <span class="font-heading font-extrabold tracking-wider text-[10px] sm:text-xs text-zinc-900 leading-none">KEBUN RAYA</span>
                    <span class="text-[8px] sm:text-[9px] tracking-widest font-bold text-emerald-600 uppercase leading-none mt-0.5">SAMBAS</span>
                </div>
            </div>
            <div class="flex-1 overflow-y-auto py-4 px-3 space-y-1">
                {{ $sidebar ?? '' }}
                @if(!isset($sidebar))
                    <a href="#" class="flex items-center px-3 py-2.5 bg-zinc-900 text-white rounded-xl text-sm font-medium transition-all shadow-lg shadow-zinc-900/20">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                        Dashboard
                    </a>
                @endif
            </div>
        </aside>

        <aside x-show="sidebarOpen"
               x-transition:enter="transition ease-out duration-300"
               x-transition:enter-start="-translate-x-full opacity-0"
               x-transition:enter-end="translate-x-0 opacity-100"
               x-transition:leave="transition ease-in duration-200"
               x-transition:leave-start="translate-x-0 opacity-100"
               x-transition:leave-end="-translate-x-full opacity-0"
               @keydown.escape.window="sidebarOpen = false"
               class="fixed inset-y-0 left-0 z-40 flex w-72 flex-col border-r border-zinc-200/80 bg-white/95 backdrop-blur-xl shadow-2xl lg:hidden"
               style="display: none;">

            <div class="h-16 flex items-center justify-between px-5 border-b border-zinc-200/80 shrink-0">
                <div class="flex items-center gap-2.5">
                    <div class="flex items-center justify-center p-1 bg-zinc-100 border border-zinc-200/60 rounded-xl">
                        <img src="{{ asset('images/logoKRS.png') }}" alt="Logo" class="h-6 w-auto object-contain">
                    </div>
                    <div class="flex flex-col">
                        <span class="font-heading font-extrabold tracking-wider text-[10px] sm:text-xs text-zinc-900 leading-none">KEBUN RAYA</span>
                        <span class="text-[8px] sm:text-[9px] tracking-widest font-bold text-emerald-600 uppercase leading-none mt-0.5">SAMBAS</span>
                    </div>
                </div>
                <button @click="sidebarOpen = false"
                        class="p-1.5 rounded-lg text-zinc-500 hover:text-zinc-900 hover:bg-zinc-100 transition-all focus:outline-none"
                        aria-label="Tutup Sidebar">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div class="flex-1 overflow-y-auto py-4 px-3 space-y-1">
                {{ $sidebar ?? '' }}
                @if(!isset($sidebar))
                    <a href="#" class="flex items-center px-3 py-2.5 bg-zinc-900 text-white rounded-xl text-sm font-medium transition-all shadow-lg shadow-zinc-900/20">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                        Dashboard
                    </a>
                @endif
            </div>
        </aside>

        <div class="flex-1 flex flex-col h-full relative overflow-hidden min-w-0">

            <header class="h-16 flex items-center justify-between gap-4 border-b border-zinc-200/80 bg-white/70 backdrop-blur-xl px-4 sm:px-6 sticky top-0 z-20 shadow-sm shrink-0">

                <div class="flex items-center gap-3 min-w-0">

                    <button @click="sidebarOpen = !sidebarOpen"
                            class="p-2 rounded-xl text-zinc-600 hover:text-zinc-900 hover:bg-zinc-100 transition-all focus:outline-none shrink-0 lg:hidden"
                            aria-label="Toggle Sidebar">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>

                    <div class="font-medium text-sm text-zinc-500 truncate">
                        Dashboard / <span class="text-zinc-900 font-bold font-space text-base ml-1">{{ $title ?? 'Overview' }}</span>
                    </div>
                </div>

                <div class="flex items-center gap-3 shrink-0 mr-2 border-r border-zinc-200/80 pr-4">
                    <div id="offline-sync-badge" class="hidden flex items-center gap-2">
                        <span id="offline-sync-count" class="bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">0</span>
                        <span id="offline-status-text" class="text-xs font-bold text-zinc-500 hidden sm:inline">Offline</span>
                        <button id="offline-sync-btn" class="p-1.5 bg-emerald-50 text-emerald-600 hover:bg-emerald-100 rounded-lg transition-colors border border-emerald-200" title="Sinkronkan Data Offline">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="flex items-center gap-3 shrink-0">
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center gap-2.5 transition-all hover:opacity-80 focus:outline-none">
                            <div class="text-right hidden sm:block">
                                <p class="text-sm font-bold text-zinc-900 font-space leading-none">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-zinc-500 font-medium">{{ Auth::user()->role=="admin"?"Administrator":"User" }}</p>
                            </div>

                            <img src="{{ Auth::user()->avatar_url }}" class="w-9 h-9 rounded-full border border-zinc-200 shadow-sm object-cover">
                        </button>

                        <div x-show="open" @click.away="open = false"
                             class="absolute right-0 mt-3 w-48 bg-white border border-zinc-200 rounded-xl shadow-xl py-1 z-50 overflow-hidden"
                             style="display: none;">
                            <a href="{{ route('profile.show') }}" class="block px-4 py-2 text-sm text-zinc-700 hover:bg-zinc-50 transition-colors">Profile</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors font-medium">
                                    Log Out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto p-4 sm:p-6 scroll-smooth">
                {{ $slot }}
            </main>
        </div>
    </div>

    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .then(reg => console.log('Service Worker registered successfully!', reg))
                    .catch(err => console.error('Service Worker registration failed:', err));
            });
        }
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/localforage/1.10.0/localforage.min.js"></script>
    <script src="{{ asset('js/offline-sync.js') }}"></script>

    @if(session('pendaftaran_sukses'))
        <div x-data="{ showSuccessRegModal: true }" x-show="showSuccessRegModal" class="fixed inset-0 z-[99999] flex items-center justify-center bg-zinc-950/60 backdrop-blur-sm transition-opacity" x-cloak>
            <div class="bg-white rounded-3xl p-8 max-w-md w-[90%] border border-zinc-100 shadow-2xl transform transition-all text-center space-y-6 animate-fade-in"
                 @click.away="showSuccessRegModal = false">

                <div class="w-16 h-16 bg-emerald-50 text-emerald-600 rounded-full flex items-center justify-center mx-auto border border-emerald-100 shadow-inner">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>

                <div class="space-y-2">
                    <h3 class="text-xl font-bold text-zinc-900 font-space">Pendaftaran Dikirim!</h3>
                    <p class="text-sm text-zinc-500 leading-relaxed">
                        {{ session('pendaftaran_sukses') }}
                    </p>
                    <div class="mt-4 p-4 bg-zinc-50 border border-zinc-200/60 rounded-2xl text-left">
                        <p class="text-xs font-semibold text-zinc-700 flex items-center gap-1.5">
                            💡 Info Verifikasi:
                        </p>
                        <p class="text-[11px] text-zinc-500 mt-1 leading-relaxed">
                            Mohon tunggu persetujuan dari admin. Anda dapat memantau apakah pendaftaran Anda disetujui atau ditolak secara berkala pada tabel riwayat pendaftaran pada dashboard.
                        </p>
                    </div>
                </div>

                <button @click="showSuccessRegModal = false"
                        class="w-full py-3.5 bg-zinc-900 hover:bg-zinc-800 text-white font-bold rounded-2xl transition-all font-space text-sm">
                    Mengerti
                </button>
            </div>
        </div>
    @endif
</body>
</html>