---
title: "BMAD Planning — Mobile"
type: bmad-planning
status: active
module: Mobile
track: bmad-method
created: 2026-10-16
updated: 2026-10-16
tags: [bmad, planning, second-brain, filament-blueprint, mobile]
qmd: "bmad planning mobile scopo onde filament"
related:
  - ./bmad/README.md
  - ./bmad/bmad-dossier.md
  - ./bmad-brainstorming.md
  - ./bmad/filament-blueprint.md
---

# Obiettivo

Offrire un host mobile agnostico per i workflow civici senza duplicare la logica Ticket.

## Perché questo owner

- **Utenti:** cittadino mobile, operatore field
- **Track BMAD:** `bmad-method`
- **Religione:** Actions + Folio/Volt/Filament `XotBase*`; mai Controllers; docs solo in questo `docs/` (mai `bmad-output/`).

## Scope

### In
- adapter/deep link
- permessi device
- parity report/tracking via contratti Fixcity/Geo/User

### Out
- copia regole Ticket
- nuovo backend segnalazioni

## Onde di delivery

| Wave | Deliverable | Owner |
|---|---|---|
| 1 | Host contract + auth | Mobile + User |
| 2 | Parity creazione/tracking | Mobile + Fixcity |
| 3 | Offline/push lifecycle | Mobile + Notify |

## Filament blueprint

Resource/widget di competenza:

- OrderResource
- TableResource

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
