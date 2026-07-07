const CACHE_NAME = 'krs-cache-1783394000';
const ASSETS_TO_CACHE = [
  '/',
  '/build/assets/app-C-XiN5r0.css',
  '/build/assets/app-CiZ6hk-B.js',
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

// Fetch event listener with Stale-While-Revalidate caching strategy
self.addEventListener('fetch', event => {
  // Only cache GET requests
  if (event.request.method !== 'GET') return;

  const url = new URL(event.request.url);

  // 1. Skip caching for administrative panel, API routes, livewire, or POST/profile operations
  if (url.pathname.startsWith('/admin') || 
      url.pathname.startsWith('/api') || 
      url.pathname.includes('/livewire') || 
      url.pathname.startsWith('/profile')) {
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
