---
title: "Manuale utente front office con schermate + percorso del giocatore funzionante"
type: story
status: done
tags: [front-office, manuale, screenshot, scommessa, predict, twentyone]
created: 2026-11-10
related:
  - ../manuale-uso-front-office.md
  - ../../../../Modules/Predict/docs/manuale/01-visitatore.md
  - ../../../../Modules/Predict/docs/manuale/02-giocatore.md
  - ../../../../Modules/Predict/docs/wiki/decisions/place-bet-server-side-validation.md
---

# Story: manuale d'uso del front office per chi parte da zero

## Obiettivo
Manuale per **visitatore** e **giocatore** con schermate vere e numerate, scritto per principianti.
Ogni passo è stato rifatto nel browser: quello che non funzionava è stato corretto prima di fotografarlo.

## Criteri di accettazione
- [x] Capitoli 1 (visitatore) e 2 (giocatore) con 24 schermate (desktop e telefono)
- [x] Scommessa completa dal sito pubblico: scelta, conferma, saldo scalato, movimento nel portafoglio
- [x] Pagine informative con testo reale (it/en), nessun *lorem ipsum* o numero inventato
- [x] Pagine inesistenti → 404; pagine personali → login
- [x] PHPStan livello max (Larastan) pulito sui file PHP toccati

## Debito residuo
- Sito `/en/...` vuoto; saldo nella barra non si aggiorna senza ricaricare; vendita non supportata
- Test Pest bloccati da `XotBaseTestCase::refreshDatabase(): void` incompatibile con `RefreshDatabase` (modulo Xot condiviso)
- Testi legali da completare con i dati del titolare
