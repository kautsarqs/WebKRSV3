<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Kebun Raya Sambas')</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logoKRS_square.png') }}" />
    <meta name="description" content="Kebun Raya Sambas - Kawasan Konservasi & Pusat Penelitian Flora di Sambas, Kalimantan Barat. Pelestarian keanekaragaman hayati tumbuhan lokal Kalimantan." />
    <link rel="canonical" href="{{ request()->url() }}" />
    <meta name="robots" content="index, follow" />

    <meta name="theme-color" content="#064e3b" />
    <link rel="manifest" href="{{ asset('manifest.json') }}" />
    <link rel="apple-touch-icon" href="{{ asset('images/logoKRS_square.png') }}" />
    <meta name="mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />
    <meta name="apple-mobile-web-app-title" content="KRS" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            scroll-behavior: smooth;
        }
        h1, h2, h3, .font-heading {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            font-weight: 800;
        }
        .img-zoom { transition: transform 0.7s ease; }
        .group:hover .img-zoom { transform: scale(1.05); }
    </style>
    @stack('styles')
</head>

@php
    $isHome = request()->routeIs('home') || request()->is('/');
@endphp

<body x-data="{ isLoading: true }"
      x-init="if (document.readyState === 'complete') { setTimeout(() => { isLoading = false }, 500); } else { window.addEventListener('load', () => { setTimeout(() => { isLoading = false }, 500); }); }"
      class="bg-white text-zinc-900 antialiased selection:bg-zinc-900 selection:text-white overflow-x-hidden"
      :class="{ 'overflow-hidden': isLoading }">

    <x-landing.skeleton-shell />

    <div class="fixed inset-0 z-0 pointer-events-none">
        <div class="absolute inset-0 bg-white"></div>
        <div class="absolute inset-0 bg-grid-black/[0.03]"></div>
        <div class="absolute inset-0 bg-white mask-[radial-gradient(ellipse_at_center,transparent_20%,black)]"></div>
    </div>

    <div x-show="!isLoading" x-transition:enter="transition ease-out duration-700" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
        <x-landing.navbar />

        <main class="relative z-10 {{ $isHome ? '' : 'pt-28' }}">
            @yield('content')
            <x-landing.footer />
        </main>
    </div>

    @stack('scripts')
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .then(reg => {
                        console.log('Service Worker registered successfully!', reg);

                        const currentUserId = @json(Auth::id() ?? 'guest');
                        const cachedUserId = localStorage.getItem('auth_user_id');

                        if (cachedUserId && cachedUserId !== String(currentUserId)) {
                            localStorage.setItem('auth_user_id', currentUserId);

                            if (navigator.serviceWorker.controller) {
                                navigator.serviceWorker.controller.postMessage({ type: 'CLEAR_PAGE_CACHE' });
                                console.log('[Auth Sync] Sent CLEAR_PAGE_CACHE to Service Worker');

                                setTimeout(() => {
                                    window.location.reload();
                                }, 50);
                            }
                        } else if (!cachedUserId) {
                            localStorage.setItem('auth_user_id', currentUserId);
                        }
                    })
                    .catch(err => console.error('Service Worker registration failed:', err));
            });
        }
    </script>
</body>
</html>
