---
title: "Mercati a più risposte con foto, risoluzione con vincite"
type: story
status: done
created: 2026-11-10
updated: 2026-11-10
related:
  - ../../../../Modules/Predict/docs/adr-004-multi-outcome-markets-and-resolution.md
  - ../../../../Modules/Predict/docs/manuale/README.md
---

# Story: mercati a più risposte

**Come** giocatore **voglio** scommettere su domande con molte risposte (cantanti di Sanremo,
candidati sindaco, liste, mete estive, squadre, fasce di temperatura), ognuna con la sua foto,
**così che** il gioco rifletta gli eventi veri e non solo Sì/No.

## Criteri di accettazione (verificati 2026-11-10)

- [x] Seeder `MultiOutcomePredictSeeder` con 7 mercati (6 aperti, 1 risolto), 3-7 risposte, immagini.
- [x] Factory `PredictFactory::withOutcomes(n|array)`, `binary()`, `closed()`; `RatingFactory::person()`.
- [x] Scheda: prime 4 risposte con foto, "+ N altre risposte"; pagina mercato: tutte, anche da visitatore.
- [x] Scommessa su mercato a 6 risposte: percentuali di tutte aggiornate (bet #84, 50 crediti).
- [x] Pannello: tabella "Risposte del mercato" con foto, colore, nota, ordine; ricalcolo quote.
- [x] Coppa → mercato risolto, vincente segnata, vincite pagate (prova su mercato di test, poi rimosso).
- [x] Filtro stato aperti/chiusi/risolti; home e /predicts con 3 mercati in evidenza coerenti.
- [x] Manuale capitoli 1-3, problemi noti, PDF front office.

## Da fare (fuori da questa story)

- Grafico dell'andamento delle percentuali per risposta (come Polymarket).
- Sostituire i dati dimostrativi con eventi veri (cast di Sanremo 2027 quando annunciato).
