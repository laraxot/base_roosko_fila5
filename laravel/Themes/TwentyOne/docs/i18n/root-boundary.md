---
title: "root boundary"
type: note
tags: [documentation]
created: 2026-09-26
updated: 2026-09-26
qmd: "root boundary"
issues: []
discussions: []
status: draft
---

# I18n Root Boundary

## Regola

I path di traduzione non devono avere livelli fantasma come `lang/lang/`.

Per i temi e per i moduli la root i18n deve essere unica e leggibile:

- `lang/<locale>/...`
- oppure `resources/lang/<locale>/...`

## Motivo

Una root duplicata rende piu` difficile tracciare quale file venga davvero caricato, rallenta debugging e manutenzione e aumenta il rischio di copie divergenti.
