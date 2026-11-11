---
type: story
slug: asset-symlink-no-copy
title: "TwentyOne: asset serviti dal symlink relativo, niente npm run copy"
status: done
phase: DONE
owner: TwentyOne
created: 2026-11-11
updated: 2026-11-11
---

# TwentyOne: asset serviti dal symlink relativo

## Regola

`public_html/themes/TwentyOne` è il symlink relativo `../../laravel/Themes/TwentyOne/public`, creato da
`php artisan xot:theme-link`. Vite compila in `public/` del tema e Laravel legge
`public_html/themes/TwentyOne/manifest.json` attraverso il link. Lo script `npm run copy` non serve più.

## Fatto

- Rimosso lo script `copy` da `package.json` e aggiunto in `vite.config.js` il commento sul symlink.
- Bundle ricompilato. Rimossi i 27 vecchi `app-*.css/js` che nessun manifest usava più.

## Note

La build legge il CSS di Filament da `laravel/vendor`, quindi va lanciata dal tema dentro `laravel/Themes/TwentyOne`
dopo `composer install`. I bundle sono versionati: il server di produzione non compila.
