---
title: "BMAD Planning — Event"
type: bmad-planning
status: active
module: Event
track: quick-flow
created: 2026-10-16
updated: 2026-10-16
tags: [bmad, planning, second-brain, filament-blueprint, event]
qmd: "bmad planning event scopo onde filament"
related:
  - ../../Blog/docs/bmad/content-type-contract.md
  - ../../Fixcity/docs/bmad/inbound-marketing/brainstorming-event-module-vs-article-type.md
  - ./bmad-brainstorming.md
  - ./bmad/filament-blueprint.md
---

# Obiettivo

Chiarire il confine del modulo Event rispetto ad Article content_type=event (Blog) e alla presentazione FO.

## Perché questo owner

- **Utenti:** redattore eventi, guest
- **Track BMAD:** `quick-flow`
- **Religione:** Actions + Folio/Volt/Filament `XotBase*`; mai Controllers; docs solo in questo `docs/` (mai `bmad-output/`).

## Scope

### In
- decisione ownership eventi
- contratto lettura pubblica
- eventuali adapter legacy

### Out
- duplicare ArticleResource
- timeline Ticket
- nuovo CMS eventi parallelo

## Onde di delivery

| Wave | Deliverable | Owner |
|---|---|---|
| 1 | ADR: Event module vs Blog content_type | Event + Blog + Fixcity |
| 2 | Deprecare o adattare residui legacy | Event |
| 3 | FO listing eventi owner Sixteen | Sixteen + Blog |

## Filament blueprint

Resource/widget di competenza:

- (nessuna Resource Filament in questo owner)

Dettaglio contratto: [filament-blueprint.md](./bmad/filament-blueprint.md).

## Second brain

- Costituzione sintetica: questo file + brainstorming gemello.
- Decisioni durature -> `docs/bmad/decision-log.md` (o dossier topic se gia esiste).
- QMD: indicizzare titoli/qmd; ownership resta nel modulo/tema.

## Definition of Done (planning)

- [ ] Scope in/out condiviso e non contraddetto dal codice owner
- [ ] Onde senza dipendenze circolari cross-owner
- [ ] Blueprint Filament aggiornato (o esplicitamente N/A)
- [ ] Link bidirezionali ai dossier/topic gia esistenti
- [ ] Nessun artefatto in `bmad-output/`
