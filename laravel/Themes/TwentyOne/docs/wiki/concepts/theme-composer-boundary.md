---
qmd: "theme composer boundary"
issues: []
discussions: []
title: "Theme Composer Boundary"
type: concept
theme: TwentyOne
tags: [composer, theme, boundary, nwidart, laravel-modules]
created: 2026-06-30
updated: 2026-06-30
related:
  - ../../raw/notes/theme-composer-boundary-2026-06-30.md
  - ../../../../Modules/Xot/docs/wiki/concepts/composer-root-skeleton-modular.md
  - ../../../../Modules/Xot/docs/wiki/concepts/theme-psr4-autoload-without-merge.md
status: draft
---

# Theme Composer Boundary

TwentyOne e' un tema bridge-only. Il suo `composer.json` puo' dichiarare dipendenze locali del tema, ma il root `laravel/composer.json` non deve includere `Themes/*/composer.json` nel `merge-plugin`.

## Regola

- Root Composer: skeleton Laravel + `nwidart/laravel-modules`.
- Moduli: dipendenze funzionali e autoload applicativo.
- Temi: layout, composizione, shell e dipendenze locali del tema.
- Nessun merge root dei temi in questo progetto.
- Nessun autoload PSR-4 dei temi nel root `laravel/composer.json`.

## Motivo

Il merge root dei temi confonde la boundary tra modulo e tema. In Predict/FixCity i moduli sono package Composer attivi; i temi devono restare portabili e non diventare il punto in cui si installano dipendenze di dominio.

Anche il mapping PSR-4 dei temi nel root confonde la boundary: il root resta skeleton e contiene solo `App\\` e `Tests\\`.

## Collegamento operativo

La regola core e' in Xot: [composer-root-skeleton-modular](../../../../Modules/Xot/docs/wiki/concepts/composer-root-skeleton-modular.md).
