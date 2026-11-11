---
type: story
slug: asset-build-symlink
title: "Geo: build Vite nella cartella public del modulo, servita via symlink"
status: done
phase: DONE
owner: Geo
created: 2026-11-11
updated: 2026-11-11
---

# Geo: build Vite nella cartella public del modulo, servita via symlink

## Perché

`deploy.sh` di Roosko si fermava sulla build di Geo, lasciando l'applicazione in manutenzione. Le cause:

- `laravel-vite-plugin ^1.3` non è compatibile con vite 8, quindi `npm ci` falliva per conflitto di peer;
- gli alias puntavano a `laravel/node_modules`, che sul server non viene installato;
- `outDir` scriveva direttamente in `public_html/assets/geo`. Gli asset dei moduli invece si servono con il
  symlink `public_html/assets/geo` → `Modules/Geo/public` (`xot:module-link`), quindi `npm run copy` non serve più.

## Fatto

- `laravel-vite-plugin ^3.2.1`, via `laravel-mix` e lo spread di `refreshPaths`, alias su `node_modules` del modulo.
- `outDir` = `public/` del modulo, che è versionata: il server non compila. Lo script `copy` è stato rimosso.
- Bundle ricompilato (`public/manifest.json`, `public/assets/`).

## Verifica

`npm ci && npm run build` nel modulo, sia da solo sia dentro `laravel/Modules/Geo`.
