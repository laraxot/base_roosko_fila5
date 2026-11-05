---
title: "Mobile wiki log"
type: log
status: active
created: 2026-10-14
updated: 2026-10-14
module: Mobile
tags: [mobile, log, docs]
qmd: "mobile wiki log docs studio ingressi"
---

# Mobile wiki log

Voci in ordine cronologico inverso. Le decisioni stanno in [decision-log](../decision-log.md).

## 2026-10-14

Studio del modulo contro i docs (story [2026-10-14-docs-mobile-studio-e-ingressi](../stories/docs-mobile-studio-e-ingressi.story.md)).

- `README.md` contraddiceva il codice in 11 punti (versione, rotte, struttura, comandi di build, facade, checklist): marcato `stale` con la
  lista delle correzioni in testa; il testo originale non e' stato cancellato.
- `00-INDEX.md` e' ora l'ingresso unico; `INDEX.md` e' `superseded`.
- Nuova nota [nativephp-build-and-run](nativephp-build-and-run.md): comandi reali, prove (APK, log di build, PHPStan, Pest) e limiti.
- PHPStan `Modules/Mobile` exit 0; Pest 1 test passa. Il modulo contiene ancora il dominio cameriere/ordini/KDS: la decisione se
  rimuoverlo e' aperta nel decision log.
