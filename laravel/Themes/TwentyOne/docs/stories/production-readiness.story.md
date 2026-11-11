---
title: "Pronto per la produzione: sicurezza, regole, discussione, SEO, test"
type: story
status: done
created: 2026-11-10
updated: 2026-11-10
related:
  - ../../../../Modules/Predict/docs/produzione-checklist.md
---

# Story: pronto per la produzione

**Come** gestore del progetto **voglio** poter mettere online Predict senza falle e con una guida
chiara **così che** i giocatori possano usarlo in sicurezza.

## Fatto (verificato 2026-11-10)

- [x] Rimossi da `public_html` phpinfo/debug/test (`*.php.bak`, ignorati da git).
- [x] `PlaceBetPage` rotta archiviata; cluster Test e TransactionResource solo `app()->isLocal()`.
- [x] `BetHeaderAction` → `PlaceBetAction` (prima `ArticleAggregate::rating()` azzerava il saldo a 1000).
- [x] Riquadro Regole (resolution_source, fallback_source, cancellation_rule) + campi nel pannello.
- [x] Discussione pubblica (`predict.market-discussion`, modulo Comment, rate limit 5/10 min).
- [x] SEO/OG per mercato (title, description) e immagine social del tema; metatag tenant ripuliti.
- [x] `SeedAiPredictsAction` (colonna inesistente) e resolver rotte Folio (TypeError con model).
- [x] Test: `.env.testing` per connessioni ereditate; nuovo `MultiOutcomeMarketLifecycleTest` verde;
      Unit Predict verdi; RefreshDatabase tolto dai Feature (vietato).
- [x] `produzione-checklist.md`, manuale (2.8, 3.5, 5, 7, 10, 11) e PDF aggiornati.

## Da fare (per altri agenti)

- 69 Feature test vecchi di Predict da aggiornare o archiviare (elenco cause nella checklist §6).
- Cartella di configurazione del tenant per il dominio di produzione (dominio non noto).
- `FolioVoltServiceProvider` (modulo Cms) è condiviso: portare la correzione upstream.
