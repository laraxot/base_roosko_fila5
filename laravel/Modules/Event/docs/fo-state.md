---
title: "Frontoffice — testo non completo / DB vuoto"
type: decision
status: active
tags: [frontoffice, event, ponytail, bmad]
created: 2026-10-26
updated: 2026-10-27
---

# Stato FO — /it/events

- Componente tema: `events-calendar` (esiste, completo).
- CMS JSON: `events/index.json` (completo, ma `.lock` DCP-289 aperto — non editare senza coordinamento).
- Dati: `/it/events` e' servita dagli articoli del modulo Blog (tipo evento), non dalla tabella `events` di questo modulo.
- Seeder: `EventDemoSeeder.php` e' stato **rimosso** il 2026-10-27 (DCP-385). Chiamava `Event::factory()`, che non esiste (PHPStan lo segnalava), e nessun codice lo richiamava; seminare questa tabella non avrebbe popolato `/it/events`. Gli eventi demo vengono da `Modules/Blog/database/seeders/DemoContentSeeder.php` (STI Parental, `type` = post | event).
- Non inventare Blade nuovi: `no-module-folio-page-directories.md` (mai `pages/events.blade.php`).

Prossimo: se serve un seeder per la tabella `events`, prima creare la factory del modello (`Modules\Event\Models\Event`) e un test che lo esercita; non reintrodurre il seeder senza.
