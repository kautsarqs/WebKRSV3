const CACHE_NAME = 'krs-cache-1783485551';
const ASSETS_TO_CACHE = [
  '/',
  '/build/assets/app-CiZ6hk-B.js',
  '/build/assets/app-x6z_NZ11.css',
  '/favicon.ico',
  '/manifest.json',
  '/peta',
  '/storage/images/logoKRS.png',
  '/storage/images/logoKRS_square.png',
  'https://cdnjs.cloudflare.com/ajax/libs/localforage/1.10.0/localforage.min.js',
  'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css',
  'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js'
];

// Self-install event
self.addEventListener('install', event => {
  event.waitUntil(
    caches.open(CACHE_NAME).then(cache => {
      console.log('Caching initial shell and map assets...');
      return cache.addAll(ASSETS_TO_CACHE).catch(err => {
        console.warn('Initial caching failed for some assets, caching individually:', err);
      });
    })
  );
  self.skipWaiting();
});

// Self-activation event
self.addEventListener('activate', event => {
  event.waitUntil(
    caches.keys().then(keys => {
      return Promise.all(
        keys.map(key => {
          if (key !== CACHE_NAME) {
            console.log('Clearing old cache:', key);
            return caches.delete(key);
          }
        })
      );
    })
  );
  self.clients.claim();
});

// Fetch event listener with strategy switching
self.addEventListener('fetch', event => {
  // Only cache GET requests
  if (event.request.method !== 'GET') return;

  const url = new URL(event.request.url);

  // 1. Skip caching untuk halaman admin, API, Livewire, dan auth
  //    PENGECUALIAN: /admin/maps/create di-cache karena admin bisa draft marker secara offline
  //    (data disimpan ke IndexedDB via OfflineSync, lalu disync saat online kembali)
  //    Semua endpoint POST/sync tetap butuh internet (SW tidak intercept non-GET)
  const isAdminMapsCreate = url.pathname === '/admin/maps/create';
  if ((url.pathname.startsWith('/admin') && !isAdminMapsCreate) ||
      url.pathname.startsWith('/api') || 
      url.pathname.includes('/livewire') || 
      url.pathname.startsWith('/profile') ||
      url.pathname.startsWith('/dashboard') ||
      url.pathname.startsWith('/login') ||
      url.pathname.startsWith('/logout') ||
      url.pathname.startsWith('/register') ||
      url.pathname.startsWith('/forgot-password') ||
      url.pathname.startsWith('/reset-password') ||
      url.pathname.startsWith('/email') ||
      url.pathname.startsWith('/storage')) {
    return;
  }

  // 2. JANGAN cache petak peta (map tiles) di Service Worker Cache Storage,
  // karena Leaflet sudah menyimpannya sendiri di IndexedDB (localforage).
  // Ini menghindari penyimpanan ganda (double-storage) di perangkat user.
  if (url.hostname.includes('tile.openstreetmap.org') || 
      url.hostname.includes('arcgisonline.com') || 
      url.hostname.includes('tile.opentopomap.org')) {
    return;
  }

  // 3. For HTML navigation requests, use Network-First strategy (preventing stale dynamic lists)
  if (event.request.headers.get('accept') && event.request.headers.get('accept').includes('text/html')) {
    event.respondWith(
      fetch(event.request).then(networkResponse => {
        if (networkResponse && networkResponse.status === 200) {
          const responseToCache = networkResponse.clone();
          caches.open(CACHE_NAME).then(cache => {
            cache.put(event.request, responseToCache);
          });
        }
        return networkResponse;
      }).catch(() => {
        return caches.match(event.request);
      })
    );
    return;
  }

  // 4. Default: Stale-While-Revalidate caching strategy for static assets
  event.respondWith(
    caches.match(event.request).then(cachedResponse => {
      // Return cached response if found, and fetch update in the background
      const fetchPromise = fetch(event.request).then(networkResponse => {
        if (networkResponse && networkResponse.status === 200) {
          const responseToCache = networkResponse.clone();
          caches.open(CACHE_NAME).then(cache => {
            cache.put(event.request, responseToCache);
          });
        }
        return networkResponse;
      }).catch(() => {
        console.log('Network request failed, serving from cache or offline fallback.');
      });

      return cachedResponse || fetchPromise;
    })
  );
});

// Listen to message events from the client (clear cache on login/logout)
self.addEventListener('message', event => {
  if (event.data && event.data.type === 'CLEAR_PAGE_CACHE') {
    caches.open(CACHE_NAME).then(cache => {
      cache.keys().then(requests => {
        requests.forEach(request => {
          const url = new URL(request.url);
          // Delete cached HTML page routes (which typically don't have a file extension)
          if (url.pathname === '/' || url.pathname === '/peta' || !url.pathname.includes('.')) {
            cache.delete(request);
            console.log('[Service Worker] Cleared cached page:', url.pathname);
          }
        });
      });
    });
  }
});
