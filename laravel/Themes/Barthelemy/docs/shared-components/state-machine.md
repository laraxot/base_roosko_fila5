---
title: "state machine 1"
type: note
tags: [documentation]
created: 2026-09-26
updated: 2026-09-26
qmd: "state machine 1"
issues: []
discussions: []
status: draft
---

# Regole Generali sulle State Machine

## Transizioni
- Ogni classe di transizione deve accettare solo parametri che siano compatibili con la chiamata automatica di Spatie Model States.
- Se si aggiungono parametri custom al costruttore, questi devono essere opzionali oppure bisogna aggiornare tutte le chiamate a `transitionTo`.
- Documentare ogni modifica di signature nelle docs del modulo coinvolto.

## Documentazione
- Ogni errore e soluzione va documentato sia nella docs del modulo sia qui, con link bidirezionali.

## Collegamenti
- [../../<nome progetto>/docs/state-machine.md](../../<nome progetto>/docs/state-machine.md)
- [../../.windsurf/rules/filament-state-transitions.mdc](../../.windsurf/rules/filament-state-transitions.mdc)
- [../../.cursor/rules/filament-state-transitions.mdc](../../.cursor/rules/filament-state-transitions.mdc)
