---
title: "Mobile — Indice docs (canonico, sostituisce legacy INDEX.md)"
type: index
status: active
module: Mobile
updated: 2026-10-20
tags: [mobile, docs, module, index, bmad, nativephp, agnostic]
qmd: "mobile   indice docs mobile"
---
# Mobile — Indice docs

Modulo Mobile — host NativePHP agnostico (iOS/Android). **Boundary**: non contiene logica dominio FixCity, non importa `Modules\Restaurant`; consumer tramite contratti (`README.md`). Stato verificato 2026-10-14: `nativephp/mobile` 4.6.0; APK release verificato (`apksigner verify`, package `com.restaurant.waiter`, SHA-256 `787...`) via `build-apk.sh` (Java 17, Android SDK, keystore esterno).

## Panoramica / File chiave
- [`../README.md`](../README.md) — contratto canonico del modulo, boundary, consumer (FixCity, Geo, User) · [`README.md`](README.md) (docs, `status: stale`)
- [`00-INDEX.md`](00-INDEX.md) — indice operativo (superseded da questo file per l'ingresso canonico breve; contenuto operativo completo mantenuto)
- [`docs/bmad/bmad-dossier.md`](docs/bmad/bmad-dossier.md) — dossier BMAD (superseded da `docs/bmad/README.md`; conservato)
- [`docs/bmad/decision-log.md`](docs/bmad/decision-log.md) — log decisioni (ownership, adapter, deep link)
- [`bmad-planning.md`](bmad-planning.md) — piano implementazione
- [`bmad-brainstorming.md`](bmad-brainstorming.md) — brainstorming
- [`decision-log.md`](decision-log.md) — decision log consolidato (2026-10-16)

## Struttura
- `app/` — Actions / Adapters / Contracts (nessun controller FO proprio; web è Folio+Volt)
- `routes/` — rotte Folio/API per consumer
- `docs/stories/` — storie feature (`1.8.nativephp-integration.story.md`, `1.11.mobile-module-boundary.story.md`, `1.12.phpstan-level-max-mobile-cleanup.story.md`)
- `docs/bmad/` — dossier, planning, brainstorming, decision-log
- `docs/investigation/` — indagini aperte
- `docs/prompts/` — prompt engineering
- `docs/stories/` — cronologia storica (superseded, non cancellate)

## Stato
Indice breve creato 2026-10-20. `00-INDEX.md` resta ingresso operativo (13 KB) con stato verificato (APK, build, emulatore non verificato, iOS non disponibile su Linux). Nuovi `.md` usano nomi lowercase senza suffissi numerici sequenziali (`-1`, `-2`, `-3`). Controller cancellati e riportati da merge dopo `cd999d97e` — verifica con `rg` dopo ogni merge (vedi `1.12`).

## Relazioni
- **Fixcity** — parent / manager (ticket, segnalazioni, actor flows); contrato `fixcity-nativephp-mobile.md` (repo FixCity, non qui)
- **Geo** — localizzazione (AddressInput, mappe); consumer tramite `Geo/app/Filament/Forms/Components/`
- **User** — autenticazione / autorizzazione / profilo (login, RBAC, 2FA, OAuth)
- **Ticket** — calendario / timeline ticket (consumer tramite Actions)
- **Notify** — notifiche push / stato (adapters locali)
- **CMS / Sixteen** — componente `page` / adapter CMS per `/it/tickets` (pending)

## BMAD / Decisioni
- [Decision log Mobile](docs/bmad/decision-log.md) — boundary agnostico, adapter, deep link, iOS/macOS constraint
- `services-retirement.md` (Geo) — modello decisione Services→Actions non applicabile direttamente (Mobile non ha `app/Services` proprio; usa Actions esterni)
- Riferimento `google-oauth-redirect-fix.md` (User/docs/decisions/) — pattern redirect OAuth per auth mobile (se implementato via User OAuth)
- Stato dossier: `superseded` da `README.md` (stesso contenuto); `decision-log.md` attivo
