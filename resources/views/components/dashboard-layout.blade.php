<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Kebun Raya Sambas') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700|space-grotesk:500,700" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-zinc-50 text-zinc-900 font-inter antialiased overflow-x-hidden"
      x-data="{ isLoading: true, sidebarOpen: false }"
      x-init="if (document.readyState === 'complete') { setTimeout(() => { isLoading = false }, 400); } else { window.addEventListener('load', () => { setTimeout(() => { isLoading = false }, 400); }); }">

    <!-- Global Skeleton Loading Overlay -->
    <template x-if="isLoading">
        <x-dashboard.skeleton-shell />
    </template>

    <!-- Ambient background blobs -->
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

        <!-- ===== BACKDROP OVERLAY (hanya mobile/tablet, <lg) ===== -->
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

        <!-- ===== SIDEBAR DRAWER (mobile/tablet: toggle drawer; lg+: static) ===== -->

        {{-- Static sidebar for lg+ (always visible) --}}
        <aside class="hidden lg:flex w-64 flex-col border-r border-zinc-200/80 bg-white/70 backdrop-blur-xl h-full shadow-sm relative z-20 shrink-0">
            <div class="h-16 flex items-center px-6 border-b border-zinc-200/80">
                <span class="font-bold text-xl tracking-tight font-space text-zinc-900">Kebun Raya Sambas</span>
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

        {{-- Mobile/tablet drawer sidebar (<lg) --}}
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
                <span class="font-bold text-lg tracking-tight font-space text-zinc-900">Kebun Raya Sambas</span>
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

        <!-- ===== MAIN CONTENT AREA ===== -->
        <div class="flex-1 flex flex-col h-full relative overflow-hidden min-w-0">

            <!-- Top Header -->
            <header class="h-16 flex items-center justify-between gap-4 border-b border-zinc-200/80 bg-white/70 backdrop-blur-xl px-4 sm:px-6 sticky top-0 z-20 shadow-sm shrink-0">

                <div class="flex items-center gap-3 min-w-0">
                    <!-- Hamburger Button (hanya tampil di bawah lg) -->
                    <button @click="sidebarOpen = !sidebarOpen"
                            class="p-2 rounded-xl text-zinc-600 hover:text-zinc-900 hover:bg-zinc-100 transition-all focus:outline-none shrink-0 lg:hidden"
                            aria-label="Toggle Sidebar">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>

                    <!-- Page Title Breadcrumb -->
                    <div class="font-medium text-sm text-zinc-500 truncate">
                        Dashboard / <span class="text-zinc-900 font-bold font-space text-base ml-1">{{ $title ?? 'Overview' }}</span>
                    </div>
                </div>

                <!-- User Menu -->
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

            <!-- Main Page Content -->
            <main class="flex-1 overflow-y-auto p-4 sm:p-6 scroll-smooth">
                {{ $slot }}
            </main>
        </div>
    </div>


</body>
</html>