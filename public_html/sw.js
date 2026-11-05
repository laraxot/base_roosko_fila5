/**
 * Service Worker per FixCity PWA
 * Gestisce cache, offline functionality e push notifications
 */

const CACHE_NAME = 'fixcity-v1.0.0';
const CACHE_ASSETS = [
  '/',
  '/css/app.css',
  '/js/app.js',
  '/images/logo.svg',
  '/offline.html'
];

// Install event - cache assets
self.addEventListener('install', (event) => {
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then((cache) => {
        return cache.addAll(CACHE_ASSETS);
      })
      .then(() => self.skipWaiting())
  );
});

// Activate event - clean old caches
self.addEventListener('activate', (event) => {
  event.waitUntil(
    caches.keys().then((cacheNames) => {
      return Promise.all(
        cacheNames.map((cache) => {
          if (cache !== CACHE_NAME) {
            return caches.delete(cache);
          }
        })
      );
    })
  );
});

// Fetch event - serve from cache, fallback to network
self.addEventListener('fetch', (event) => {
  event.respondWith(
    caches.match(event.request)
      .then((response) => {
        // Return cached version or fetch from network
        return response || fetch(event.request)
          .then((fetchResponse) => {
            // Clone response as it can only be consumed once
            const responseToCache = fetchResponse.clone();
            
            // Don't cache API requests
            if (!event.request.url.includes('/api/')) {
              caches.open(CACHE_NAME)
                .then((cache) => {
                  cache.put(event.request, responseToCache);
                });
            }
            
            return fetchResponse;
          })
          .catch(() => {
            // Offline fallback
            if (event.request.destination === 'document') {
              return caches.match('/offline.html');
            }
          });
      })
  );
});

// Push notification event
self.addEventListener('push', (event) => {
  const data = event.data ? event.data.json() : {};
  const title = data.title || 'FixCity';
  const options = {
    body: data.body || 'Nuova notifica',
    icon: '/images/icons/icon-192x192.png',
    badge: '/images/icons/badge-72x72.png',
    vibrate: [200, 100, 200],
    tag: data.tag || 'fixcity-notification',
    requireInteraction: false,
    actions: [
      {
        action: 'view',
        title: 'Visualizza'
      },
      {
        action: 'close',
        title: 'Chiudi'
      }
    ],
    data: {
      url: data.url || '/'
    }
  };
  
  event.waitUntil(
    self.registration.showNotification(title, options)
  );
});

// Notification click event
self.addEventListener('notificationclick', (event) => {
  event.notification.close();
  
  if (event.action === 'view') {
    const urlToOpen = event.notification.data.url;
    
    event.waitUntil(
      clients.matchAll({ type: 'window', includeUncontrolled: true })
        .then((windowClients) => {
          // Check if there is already a window/tab open with the target URL
          for (let i = 0; i < windowClients.length; i++) {
            const client = windowClients[i];
            if (client.url === urlToOpen && 'focus' in client) {
              return client.focus();
            }
          }
          // If not, open a new window/tab
          if (clients.openWindow) {
            return clients.openWindow(urlToOpen);
          }
        })
    );
  }
});

// Background sync event (for offline form submissions)
self.addEventListener('sync', (event) => {
  if (event.tag === 'sync-tickets') {
    event.waitUntil(syncTickets());
  }
});

/**
 * Sync tickets from IndexedDB to server when online
 */
async function syncTickets() {
  try {
    // Get pending tickets from IndexedDB
    const db = await openDB();
    const tickets = await getAllPendingTickets(db);
    
    // Send each ticket to server
    for (const ticket of tickets) {
      try {
        const response = await fetch('/api/fixcity/tickets', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${ticket.token}`
          },
          body: JSON.stringify(ticket.data)
        });
        
        if (response.ok) {
          // Remove from IndexedDB after successful sync
          await removePendingTicket(db, ticket.id);
        }
      } catch (error) {
        // Silently fail for offline sync
      }
    }
  } catch (error) {
    // Handle sync error silently
  }
}

/**
 * Helper functions for IndexedDB operations
 */
function openDB() {
  return new Promise((resolve, reject) => {
    const request = indexedDB.open('FixCityDB', 1);
    
    request.onerror = () => reject(request.error);
    request.onsuccess = () => resolve(request.result);
    
    request.onupgradeneeded = (event) => {
      const db = event.target.result;
      if (!db.objectStoreNames.contains('pending_tickets')) {
        db.createObjectStore('pending_tickets', { keyPath: 'id', autoIncrement: true });
      }
    };
  });
}

function getAllPendingTickets(db) {
  return new Promise((resolve, reject) => {
    const transaction = db.transaction(['pending_tickets'], 'readonly');
    const store = transaction.objectStore('pending_tickets');
    const request = store.getAll();
    
    request.onerror = () => reject(request.error);
    request.onsuccess = () => resolve(request.result);
  });
}

function removePendingTicket(db, id) {
  return new Promise((resolve, reject) => {
    const transaction = db.transaction(['pending_tickets'], 'readwrite');
    const store = transaction.objectStore('pending_tickets');
    const request = store.delete(id);
    
    request.onerror = () => reject(request.error);
    request.onsuccess = () => resolve();
  });
}
