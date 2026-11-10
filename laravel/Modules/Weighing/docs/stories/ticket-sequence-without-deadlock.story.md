---
type: story
tags: [weighing, database, concurrency]
epic: database-audit
story: R10
slug: ticket-sequence-without-deadlock
title: "Numerazione dei ticket di pesata senza deadlock"
status: review
phase: REVIEW
owner: Weighing
depends_on: []
related: []
created: 2026-11-06
updated: 2026-11-06
---

# Numerazione dei ticket di pesata senza deadlock

## Story

Come **operatore della pesa**
voglio **aprire una pesata anche quando un collega ne sta aprendo un'altra nello stesso istante**
così da **non vedere errori di database casuali nei momenti di coda al ponte bascula**.

## Perché / evidenze

- Audit database **R10**: `GenerateWeighingTicketNumberAction` faceva `insertOrIgnore` e poi `SELECT ... FOR UPDATE`.
  Su InnoDB l'`INSERT IGNORE` che trova la chiave duplicata prende un lock condiviso; il `FOR UPDATE` deve convertirlo
  in esclusivo. Due transazioni con lock condiviso in attesa dell'esclusivo → deadlock, una delle due annullata.
- Dentro `RunInPlantTransactionAction` (Roosko) non c'era retry.

## Soluzione

- Un'unica istruzione crea o incrementa il contatore: `upsert` su `weighing_sequences` (unique `year`) con
  `last_number = last_number + 1`. Il lock esclusivo si prende subito; poi si rilegge il valore.
- Se chiamata fuori da una transazione apre la propria con 3 tentativi sugli errori di concorrenza; dentro una
  transazione esterna partecipa a quella (nessun savepoint inutile).
- `OpenWeighingAction` e `RecordSingleWeighingAction` aprono la transazione con 3 tentativi (`ATTEMPTS`).

## Acceptance criteria

1. Il primo ticket di un anno nuovo è `W-{anno}-000001`, il secondo `000002`, una sola riga di contatore.
2. Un rollback della transazione del chiamante non lascia buchi.
3. Nessun lock condiviso seguito da conversione in esclusivo nel percorso di numerazione.

## Rischi residui

- Manca il test d'integrazione su MySQL con due processi in parallelo (100 iterazioni) chiesto dall'audit: la harness ha
  solo sqlite. Va eseguito in un ambiente MySQL.
- Dentro `RunInPlantTransactionAction` (Roosko) un deadlock residuo annulla l'intera transazione composta: il retry va
  aggiunto lì (fuori da questo modulo).

## Dev agent record

- File: `app/Actions/GenerateWeighingTicketNumberAction.php`, `app/Actions/OpenWeighingAction.php`,
  `app/Actions/RecordSingleWeighingAction.php`, `docs/README.md`.
- Test: `tests/Feature/WeighingActionsTest.php` (progressione, anno nuovo da 1 con una sola riga, rollback senza buchi).
