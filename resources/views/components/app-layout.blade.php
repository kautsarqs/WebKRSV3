<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Kebun Raya Sambas') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logoKRS.png') }}" />

    <meta name="theme-color" content="#064e3b" />
    <link rel="manifest" href="{{ asset('manifest.json') }}" />
    <link rel="apple-touch-icon" href="{{ asset('images/logoKRS_square.png') }}" />
    <meta name="mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />
    <meta name="apple-mobile-web-app-title" content="KRS" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-white text-zinc-900 min-h-screen flex flex-col relative overflow-x-hidden selection:bg-indigo-500/30"
      x-data="{ isLoading: true }"
      x-init="if (document.readyState === 'complete') { setTimeout(() => { isLoading = false }, 400); } else { window.addEventListener('load', () => { setTimeout(() => { isLoading = false }, 400); }); }">

    <template x-if="isLoading">
        <x-auth.skeleton-shell />
    </template>

    <div class="fixed inset-0 z-0 pointer-events-none">
        <div class="absolute inset-0 bg-white"></div>

        <div class="absolute inset-0 bg-grid-black opacity-100"></div>

        <div class="absolute inset-0 bg-white mask-gradient"></div>

        <div class="absolute top-[-10%] left-1/2 -translate-x-1/2 w-125 h-125 bg-indigo-500/10 blur-[100px] rounded-full"></div>
    </div>

    <main class="relative z-10 flex-1 container mx-auto px-4 py-8 flex flex-col justify-center"
          x-show="!isLoading"
          x-cloak
          x-transition:enter="transition ease-out duration-500"
          x-transition:enter-start="opacity-0"
          x-transition:enter-end="opacity-100">
        {{ $slot }}
    </main>

    <footer class="relative z-10 py-6 text-center text-sm text-zinc-400 border-t border-zinc-100">
        &copy; {{ date('Y') }} Kebun Raya Sambas
    </footer>
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