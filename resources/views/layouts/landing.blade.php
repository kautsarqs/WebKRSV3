<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Kebun Raya Sambas')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Space+Grotesk:wght@500;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Inter', sans-serif; scroll-behavior: smooth; }
        h1, h2, h3, .font-heading { font-family: 'Space Grotesk', sans-serif; }
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

    {{-- Skeleton Loading Overlay --}}
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
</body>
</html>
