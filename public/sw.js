// Education Times Service Worker
// Strategy:
//  - Navigation requests: network-first, fall back to cache, then offline page
//  - Same-origin GET assets: cache-first, fall back to network
//  - Cross-origin / dev-server / extension requests: ignored (no SW interference)

const CACHE_NAME = 'edtimes-cache-v2';
const OFFLINE_URL = '/';
const PRECACHE_URLS = [OFFLINE_URL, '/favicon.ico', '/icon-192.png', '/icon-512.png'];

self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) =>
            // Use individual add() so a single 404 doesn't abort the whole precache
            Promise.all(PRECACHE_URLS.map((u) => cache.add(u).catch(() => null)))
        )
    );
    self.skipWaiting();
});

self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((cacheNames) =>
            Promise.all(
                cacheNames
                    .filter((name) => name !== CACHE_NAME)
                    .map((name) => caches.delete(name))
            )
        )
    );
    self.clients.claim();
});

function isHandlable(request) {
    if (request.method !== 'GET') return false;
    const url = new URL(request.url);
    // Only same-origin
    if (url.origin !== self.location.origin) return false;
    // Skip dev-server and other noisy protocols
    if (!['http:', 'https:'].includes(url.protocol)) return false;
    return true;
}

self.addEventListener('fetch', (event) => {
    const { request } = event;
    if (!isHandlable(request)) return;

    // Navigation: network-first, then cache, then offline
    if (request.mode === 'navigate') {
        event.respondWith(
            fetch(request)
                .then((response) => {
                    if (response && response.ok) {
                        const clone = response.clone();
                        caches.open(CACHE_NAME).then((c) => c.put(request, clone));
                    }
                    return response;
                })
                .catch(() =>
                    caches.match(request).then((cached) => cached || caches.match(OFFLINE_URL))
                )
        );
        return;
    }

    // Other assets: cache-first, then network (skip opaque / 3rd-party)
    event.respondWith(
        caches.match(request).then((cached) => {
            if (cached) return cached;
            return fetch(request)
                .then((response) => {
                    if (response && response.ok && response.type === 'basic') {
                        const clone = response.clone();
                        caches.open(CACHE_NAME).then((c) => c.put(request, clone));
                    }
                    return response;
                })
                .catch(() => cached);
        })
    );
});
