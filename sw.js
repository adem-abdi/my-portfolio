
const CACHE_NAME = 'portfolio-cleared-v2';

self.addEventListener('install', (event) => {
    self.skipWaiting();
    event.waitUntil(caches.open(CACHE_NAME));
});

self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys()
            .then((cacheNames) => Promise.all(cacheNames.map((cacheName) => caches.delete(cacheName))))
            .then(() => self.clients.claim())
    );
});

self.addEventListener('fetch', () => {});
