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

<body class="bg-white text-zinc-900 antialiased selection:bg-zinc-900 selection:text-white">

    <div class="fixed inset-0 z-0 pointer-events-none">
        <div class="absolute inset-0 bg-white"></div>
        <div class="absolute inset-0 bg-grid-black/[0.03]"></div>
        <div class="absolute inset-0 bg-white [mask-image:radial-gradient(ellipse_at_center,transparent_20%,black)]"></div>
    </div>

    <x-landing.navbar />

    <main class="relative z-10 pt-32">
        @yield('content')
        <x-landing.footer />
    </main>
    

    @stack('scripts')
</body>
</html>
