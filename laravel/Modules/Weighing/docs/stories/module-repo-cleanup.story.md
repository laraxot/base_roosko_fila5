---
type: story
slug: module-repo-cleanup
title: "Il repo del modulo contiene solo il modulo"
status: done
owner: Weighing
created: 2026-11-10
updated: 2026-11-10
---

# Il repo del modulo contiene solo il modulo

## Problema

Il commit `5552bcaa` ("let's start !") ha copiato nel repo del modulo l'intera app base (31.629 file, ~4,2 milioni di righe):
`laravel/` con tutti i moduli e i temi, `public_html/`, `bmad*`, i bootstrap degli agenti, `package.json` di un altro progetto,
workflow GitHub dell'app. Nell'app base la stessa copia finiva in `laravel/Modules/Weighing/laravel/` (oltre 30.000 file),
tanto che `phpstan.neon` la escludeva a mano.

Effetti: clone e sync lenti, autoload e strumenti che vedono due copie di ogni modulo, Dependabot che apre PR sul modulo per
dipendenze dell'app, e un `.env.testing` dell'app dentro il repo del modulo.

## Correzione

Rimosso tutto cio' che non e' il modulo. Restano: `app/`, `composer.json`, `config/`, `database/`, `docs/`, `lang/`,
`module.json`, `routes/`, `tests/` e `.gitattributes` come negli altri moduli. La storia git resta intatta (patch in avanti).

## Acceptance criteria

1. `git ls-tree HEAD` mostra solo le cartelle del modulo.
2. L'app base copia il modulo senza `laravel/` annidato e `phpstan.neon` non ha piu' bisogno dell'esclusione.
3. Suite Pest del modulo invariata.

## Per chi sincronizza

Chi copia l'app base in un repo di modulo deve copiare solo `laravel/Modules/Weighing/`. Se nel prossimo sync ricompaiono
`laravel/` o `public_html/` alla radice, l'origine e' lo script di sincronizzazione, non il modulo.
