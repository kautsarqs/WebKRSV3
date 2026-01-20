<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-white text-zinc-900 min-h-screen flex flex-col relative overflow-x-hidden selection:bg-indigo-500/30">

    <div class="fixed inset-0 z-0 pointer-events-none">
        <div class="absolute inset-0 bg-white"></div>

        <div class="absolute inset-0 bg-grid-black opacity-100"></div>

        <div class="absolute inset-0 bg-white mask-gradient"></div>
        
        <div class="absolute top-[-10%] left-1/2 -translate-x-1/2 w-125 h-125 bg-indigo-500/10 blur-[100px] rounded-full"></div>
    </div>

    <main class="relative z-10 flex-1 container mx-auto px-4 py-8 flex flex-col justify-center">
        {{ $slot }}
    </main>

    <footer class="relative z-10 py-6 text-center text-sm text-zinc-400 border-t border-zinc-100">
        &copy; {{ date('Y') }} Kebun Raya Sambas - Light Mode
    </footer>
</body>
</html>