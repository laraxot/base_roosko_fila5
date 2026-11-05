---
title: "Event — Indice docs"
type: index
status: active
module: Event
updated: 2026-10-20
tags: [event, docs, module, index, bmad, blog, comment]
qmd: "event   indice docs event"
---
# Event — Indice docs

Modulo Event (eventi comunali: pagine FO `/it/eventi`, `/it/evento/{slug}`). Proprietà: Blog (content_type=event) / Comment / Fixcity.

## Panoramica / File chiave
- [`README.md`](README.md) — descrizione modulo, contratto
- [`00-INDEX.md`](00-INDEX.md) — indice storico (superseded da questo file)
- [`docs/bmad/bmad-dossier.md`](docs/bmad/bmad-dossier.md) — dossier BMAD (ownership, article vs event)
- [`docs/bmad/decision-log.md`](docs/bmad/decision-log.md) — log decisioni
- [`bmad-planning.md`](bmad-planning.md) — piano implementazione BMAD
- [`bmad-brainstorming.md`](bmad-brainstorming.md) — brainstorming architettura
- [`docs/bmad/filament-blueprint.md`](docs/bmad/filament-blueprint.md) — blueprint Filament

## Struttura
- `app/Models/Event.php` — modello (`HasComments` trait)
- `routes/web.php` / `routes/api.php` — rotte Folio
- `docs/stories/` — storie BMAD (in arrivo / da consolidare)
- `docs/bmad/` — dossier BMAD consolidato

## Stato
Docs minimali presenti (indice creato 2026-10-20); storie BMAD da consolidare. Dipende da Blog (tipo evento content_type), Comment (commenti), Fixcity (ticket correlati). Nessun file `.old`/`.bak` rimasto dopo audit.

## Relazioni
- **Blog** — evento come `Article(content_type=event)`; contatto `docs/bmad/` con `Blog/docs/bmad/content-type-contract.md`
- **Comment** — `HasComments`; timeline commenti
- **Fixcity** — ticket/eventi correlati (segnalazioni calendarizzate)
- **Geo** — localizzazione evento (indirizzo, mappa)
- **Notify** — notifiche stato evento

## BMAD / Decisioni
- [Decision log Event](docs/bmad/decision-log.md) — ownership articolo vs evento, content_type, relazioni `MorphMany` Comment (2026-10-20)
- Riferimento: `google-oauth-redirect-fix.md` (User/docs/decisions/) — pattern redirect OAuth non applicabile direttamente, ma modello decisione BMAD analogo per auth/event visibility
- Dossier BMAD attivo (`status: active`, `updated: 2026-10-16`); nessun `services-retirement` (non applicabile: Event non usa `app/Services`)
