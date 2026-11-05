---
title: "documentation standards"
type: note
tags: [documentation]
created: 2026-09-26
updated: 2026-09-26
qmd: "documentation standards"
issues: []
discussions: []
status: draft
---

# Standard di Documentazione

## Convenzioni di Naming
- Tutti i file e le cartelle nella documentazione devono essere in minuscolo
- L'unica eccezione è il file `README.md`
- Utilizzare lo script [fix_docs_case](../../../../../bashscripts/docs/docs/fix_docs_case.md) per la standardizzazione automatica

## Organizzazione
- Ogni modulo ha la sua cartella `docs`
- La documentazione specifica va nel modulo pertinente
- Collegamenti bidirezionali tra documenti correlati
- Evitare duplicazione della documentazione

## Manutenzione
- Aggiornare la documentazione contestualmente alle modifiche del codice
- Verificare regolarmente i collegamenti
- Mantenere la coerenza dello stile e del formato
- Eseguire periodicamente gli script di manutenzione automatica
