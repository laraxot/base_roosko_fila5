---
title: "Decision log: Mobile"
type: decision-log
module: Mobile
status: active
created: 2026-10-07
updated: 2026-10-14
tags: [mobile, decision-log, docs]
qmd: "mobile decision log decisioni docs indice canonico 00-index"
issues:
  - "https://github.com/laraxot/module_mobile_fila5/issues/1"
discussions:
  - "https://github.com/laraxot/module_mobile_fila5/discussions/2"
related:
  - ./00-INDEX.md
---

# Decision log: Mobile

## Decisions

| Data | Decisione | Prova |
|---|---|---|
| 2026-09-27 | Mobile non dipende dal modulo Restaurant: conosce solo il port `RestaurantGateway` e i DTO in `app/Data`; la dipendenza ammessa e' `Restaurant -> Mobile` | [story 1.11](stories/1.11.mobile-module-boundary.story.md); `grep "Modules.Restaurant"` su `app database tests config` senza risultati (2026-10-14) |
| 2026-10-07 | Nessun controller e nessuna rotta nel modulo: il front office e' Folio + Volt. `routes/api.php`, `mobile.php`, `web.php` restituiscono `[]` e `app/Http` non esiste | [story 1.12](stories/1.12.phpstan-level-max-mobile-cleanup.story.md), [prompt](prompts/01.md); i controller sono stati cancellati tre volte e riportati da merge con snapshot vecchi |
| 2026-10-14 | `00-INDEX.md` e' l'unico ingresso del `docs/`; `INDEX.md` e' superseded; il README e' `stale` (criteri di accettazione, non stato) | [00-INDEX](00-INDEX.md), [story](stories/docs-mobile-studio-e-ingressi.story.md) |

## Open Questions

1. Il dominio cameriere/ordini/KDS (modelli `OrderQueue` e `WaiterSession`, 6 Actions, 2 Notifications, risorse Filament, migration, 5 seeder) resta nel modulo, passa al modulo Restaurant o viene rimosso? Nessuna story lo decide.
2. `MobileGatewayContract` e `MobileDemoData` dichiarano il namespace `Modules\Mobile\App\...` e non sono caricabili: correggere il namespace o spostare i file?
3. Le pagine Folio `pages/mobile/index` e `map` danno 404 e usano le rotte `mobile.map` e `mobile.create`, che non esistono: cablarle, rimuoverle o lasciarle al tema?
4. Le schermate native usano dati simulati (`getDemoTickets()`, `markerCount = 27`): quando si collegano al contratto GeoJSON di Fixcity?
5. `config/bifrost.php` ha ancora `updater.repo = restaurant-fila5` e i permessi camera "QR di menu e tavoli".
6. La verifica runtime Android (emulatore, `/it/tickets`) non e' stata rieseguita il 2026-10-14; iOS richiede macOS e Xcode.

