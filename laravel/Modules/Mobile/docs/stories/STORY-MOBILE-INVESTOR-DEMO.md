---
title: "Mobile Module — NativePHP iOS/Android Investor Demo"
type: story
status: in_progress
created: 2026-09-28
updated: 2026-09-28
tags: [bmad, mobile, nativephp, ios, android, geojson, investor-demo, offline, push]
module: Mobile
qmd: "Mobile NativePHP iOS Android FixCity investor demo offline push GeoJSON"
issues:
  - "https://github.com/laraxot/base_fixcity_fila5/issues"
discussions:
  - "https://github.com/laraxot/base_fixcity_fila5/discussions"
related:
  - ../../../../Modules/Fixcity/docs/bmad/fixcity-nativephp-mobile.md
  - ../../../../docs/COMPETITOR-ANALYSIS.md
---

# Mobile Module — NativePHP iOS/Android Investor Demo

## Decision BMAD

Il modulo `Mobile` è il contenitore della distribuzione NativePHP per iOS e Android per FixCity. Il prossimo slice deve esporre schermate native che consumano il contratto GeoJSON pubblico di FixCity (`/data/tickets.json` e API `/api/tickets/geojson`).

**Non duplicare**: la logica di dominio resta in FixCity (Actions, Models, Contracts). Il modulo Mobile espone solo la presentazione nativa.

## Stato Verificato (2026-09-28)

- `nativephp/mobile` installato: v4.5.2
- `php artisan native:version` ✅
- `php artisan native:debug` ✅ - PHP 8.4, Linux, Android shell, Java/Gradle presenti
- APK Android debug generato: `nativephp/android/app/build/outputs/apk/debug/app-debug.apk`
- GeoJSON endpoint: `/api/tickets/geojson` (Folio) e `/data/tickets.json` (statico)
- Web map view: `Modules/Mobile/resources/views/pages/mobile/map.blade.php` (Leaflet)

## Acceptance Criteria per Investor Demo (P0)

### 1. Native Screens (PHP Native - eseguite su device)
- [ ] **Map Screen** — Native map con marker GeoJSON (non WebView), clustering, filtro per tipo/stato
- [ ] **Ticket List Screen** — Lista nativa con pull-to-refresh, ricerca, filtri
- [ ] **Ticket Detail Screen** — Dettaglio completo con foto, timeline, stato, azioni
- [ ] **Create Ticket Screen** — Camera, location picker, categoria, descrizione (offline-first)

### 2. Offline-First Architecture
- [ ] SQLite locale per read model (tickets, categories, user)
- [ ] Coda invio create/update (idempotent replay)
- [ ] Sync automatica al ripristino connessione
- [ ] Conflict resolution (last-write-wins con timestamp server)

### 3. Push Notifications
- [ ] Configurazione FCM (Android) / APNs (iOS)
- [ ] Notifica cambio stato ticket
- [ ] Notifica risposta PA
- [ ] Deep link al ticket detail

### 4. Native Capabilities
- [ ] Camera (scatta foto allegato)
- [ ] Location GPS (prefill coordinate)
- [ ] Biometric auth (FaceID/TouchID)
- [ ] Background sync

### 5. Platform Builds
- [ ] Android debug APK (già generato)
- [ ] Android release AAB (Play Store ready)
- [ ] iOS Simulator build
- [ ] iOS TestFlight build (richiede macOS/Xcode)

## Technical Architecture

### NativePHP Screens (app/Native/)
```
Mobile/
├── app/
│   ├── Native/                    # PHP screens eseguibili su device
│   │   ├── Screens/
│   │   │   ├── MapScreen.php
│   │   │   ├── TicketListScreen.php
│   │   │   ├── TicketDetailScreen.php
│   │   │   └── CreateTicketScreen.php
│   │   ├── Components/
│   │   │   ├── TicketCard.php
│   │   │   ├── MapMarker.php
│   │   │   └── StatusBadge.php
│   │   └── Layouts/
│   │       └── MobileLayout.php
│   ├── Actions/Mobile/
│   │   ├── SyncTicketsAction.php
│   │   ├── CreateOfflineTicketAction.php
│   │   ├── FetchGeoJsonAction.php
│   │   └── RegisterPushTokenAction.php
│   └── Models/Mobile/
│       ├── OfflineTicket.php
│       ├── SyncQueue.php
│       └── PushToken.php
```

### Data Flow
```
┌─────────────┐     ┌─────────────┐     ┌─────────────┐
│   Server    │────▶│  GeoJSON    │────▶│  Mobile     │
│  (FixCity)  │     │  /data/     │     │  (SQLite)   │
└─────────────┘     │  tickets.json    └──────┬────────┘
                    └─────────────┘            │
                           ▲                   │
                           │                   ▼
                    ┌─────────────┐     ┌─────────────┐
                    │  API Live   │     │  Native     │
                    │ /api/tickets│     │  Screens    │
                    │  /geojson   │     │  (PHP)      │
                    └─────────────┘     └─────────────┘
```

## Implementation Plan

### Phase 1: Native Screens (Week 1)
1. Create `MapScreen.php` - Native map usando `MapView` bridge
2. Create `TicketListScreen.php` - Lista nativa con `ListView`
3. Create `TicketDetailScreen.php` - Dettaglio con `ScrollView`
4. Create `CreateTicketScreen.php` - Form nativo con camera/location

### Phase 2: Offline Sync (Week 1-2)
1. SQLite schema per tickets offline
2. `SyncTicketsAction` - Pull server → SQLite
3. `CreateOfflineTicketAction` - Push locale → queue → server
4. Background sync worker

### Phase 3: Push & Native (Week 2)
1. `RegisterPushTokenAction` - Registra token FCM/APNs
2. Bridge functions: Camera, Location, Biometric
3. Deep link handling

### Phase 4: iOS & Release (Week 3)
1. `php artisan native:install ios` su macOS
2. Xcode project configuration
3. TestFlight internal testing
4. Android release build (signing)

## Files to Create/Modify

### New Files
- `laravel/Modules/Mobile/app/Native/Screens/MapScreen.php`
- `laravel/Modules/Mobile/app/Native/Screens/TicketListScreen.php`
- `laravel/Modules/Mobile/app/Native/Screens/TicketDetailScreen.php`
- `laravel/Modules/Mobile/app/Native/Screens/CreateTicketScreen.php`
- `laravel/Modules/Mobile/app/Actions/Mobile/SyncTicketsAction.php`
- `laravel/Modules/Mobile/app/Actions/Mobile/CreateOfflineTicketAction.php`
- `laravel/Modules/Mobile/app/Actions/Mobile/FetchGeoJsonAction.php`
- `laravel/Modules/Mobile/app/Actions/Mobile/RegisterPushTokenAction.php`
- `laravel/Modules/Mobile/app/Models/Mobile/OfflineTicket.php`
- `laravel/Modules/Mobile/app/Models/Mobile/SyncQueue.php`
- `laravel/Modules/Mobile/app/Models/Mobile/PushToken.php`
- `laravel/Modules/Mobile/database/migrations/xxx_create_offline_tickets_table.php`
- `laravel/Modules/Mobile/database/migrations/xxx_create_sync_queue_table.php`
- `laravel/Modules/Mobile/database/migrations/xxx_create_push_tokens_table.php`

### Modify
- `laravel/config/nativephp.php` - Aggiungere native routes, push config
- `laravel/Modules/Mobile/app/Providers/MobileServiceProvider.php` - Register native screens
- `laravel/Modules/Mobile/composer.json` - Dipendenze native

## Testing Strategy

### Unit Tests (Pest)
- `SyncTicketsActionTest` - Pull/parse GeoJSON
- `CreateOfflineTicketActionTest` - Queue/idempotency
- `FetchGeoJsonActionTest` - Network/fallback

### Integration Tests
- `NativeScreenTest` - Render su device/simulator
- `OfflineSyncTest` - Full cycle offline→online

### E2E (Puppeteer/Playwright)
- Map load → markers visible
- Create ticket → appears in list
- Status change → push received

## Competitive Gaps Addressed (from COMPETITOR-ANALYSIS.md)

| Feature | FixMyStreet | SeeClickFix | OpenGov | FixCity Mobile |
|---------|-------------|-------------|---------|----------------|
| Native iOS/Android | ✅ | ✅ | ✅ | 🔄 **In Progress** |
| Offline create | ❌ | ❌ | ❌ | ✅ **Planned** |
| Push notifications | ✅ | ✅ | ✅ | 🔄 **Planned** |
| Open311 API | ✅ | ✅ | ✅ | ❌ Separate story |
| White-label | ✅ (Pro) | ✅ | ✅ | ⚠️ Theme-based |

## Success Metrics

| Metric | Target |
|--------|--------|
| App launch time | < 2s cold, < 500ms warm |
| Map render (500 markers) | < 1s |
| Offline create → sync | < 5s on reconnect |
| Push delivery | > 95% |
| Crash-free sessions | > 99.5% |
| Store rating (demo) | 4.5+ |

## Risks & Mitigations

| Risk | Level | Mitigation |
|------|-------|------------|
| iOS build requires macOS | 🔴 HIGH | CI/CD su macOS runner; documentazione per dev locale |
| Push provider secrets | 🟡 MEDIUM | Fail-closed senza provider; variabili d'ambiente |
| NativePHP bundle size | 🟡 MEDIUM | `cleanup_exclude_files` ottimizzato; lazy load schermate |
| Offline conflict resolution | 🟡 MEDIUM | Timestamp-based; UI per risoluzione manuale |

## Definition of Done

- [ ] Tutte le 4 schermate native funzionanti su Android debug
- [ ] Offline create + sync testato (modalità aereo → online)
- [ ] Push notification ricevuto su cambio stato
- [ ] Android release AAB firmato generato
- [ ] iOS Simulator build confermato (su macOS)
- [ ] Documentazione runbook in `docs/mobile-investor-demo.md`
- [ ] Screenshot/video demo registrati

## Next Actions

1. **Immediato**: Creare `MapScreen.php` e `TicketListScreen.php` come proof of concept
2. **Parallel**: Setup SQLite offline models + migrations
3. **Decisione BMAD**: Confermare routing native (Folio-style vs NativePHP router)
4. **Parallel**: Configurare FCM project per push test

---

**Story Owner**: Mobile Module Team  
**Reviewer**: FixCity Architect  
**Gate**: Android debug APK + 4 native screens + offline sync = **Investor Ready**