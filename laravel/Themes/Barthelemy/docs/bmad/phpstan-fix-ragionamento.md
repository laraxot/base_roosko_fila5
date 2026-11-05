---
title: "PHPStan Fix — Ragionamento BMAD (scopo > utilità > funzionalità)"
type: note
status: draft
updated: 2026-10-17
tags: [docs, barthelemy, bmad]
qmd: "phpstan fix   ragionamento bmad (scopo > utilità > funzionalità) barthelemy"
---
# PHPStan Fix — Ragionamento BMAD (scopo > utilità > funzionalità)

## Origine errore
`Tests/` (maiuscola) duplicato di `tests/` (minuscola) → namespace `Modules\Xot\Tests` non corrisponde al percorso `tests/Fixtures/Stubs`.

## Scopo
Assicurare coerenza PSR-4 (namespace ↔ percorso file) e eliminare `class.nameCase`.

## Utilità
Senza coerenza, PHPStan non trova i file; con coerenza, il codice è valido e leggibile.

## Funzionalità
Rimozione `Tests/`; namespace → `tests`; stub ricostruiti (`XotCovRelationHost`, `ExporterStub`); docs `.md` senza timestamp; `MEMORY.md` aggiornato.

## Promemoria permanente
- `laravel/Modules/*/Tests` NON ESISTE (mai).
- `.md`: nessun timestamp nel nome.
- Git: solo avanti (add/commit).
- BMAD + second brain + ponytail sempre.
