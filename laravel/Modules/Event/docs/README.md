---
title: "Event module documentation"
type: readme
status: active
updated: 2026-10-25
tags: [documentation, module, event]
qmd: "event event"
---

# Event

The Event module currently owns the `Event` model and its migration. Its
domain documentation is still small; the two BMAD notes below are the active
planning material.

## Stato dello scheletro

`module.json` e `composer.json` esistono perche' la cartella sia un modulo per
nwidart, per il merge `Modules/*/composer.json` e per la guardia
`ModuleRootHygieneTest`. `providers` e' vuoto e il modulo **non** compare in
`laravel/modules_statuses.json`, quindi resta dormiente: nessun service
provider registrato, la migration non viene caricata, l'autoload PSR-4
`Modules\Event\` -> `app/` invece e' attivo. Attivarlo richiede tre classi e
una riga in `modules_statuses.json`: il provider del modulo (da chiamare
`EventModuleServiceProvider`, perche' `EventServiceProvider` e' gia' il nome
del provider eventi che `XotBaseServiceProvider::register()` cerca), piu'
`Providers\RouteServiceProvider` e `Providers\EventServiceProvider` che quella
base registra sempre — senza di loro il boot fallisce con class not found.

- [Canonical index](00-INDEX.md)
- [BMAD brainstorming](bmad-brainstorming.md)
- [BMAD planning](bmad-planning.md)

Keep event presentation in the active theme and editorial composition in the
CMS module. Add implementation details here when the module gains actions,
resources, or tests.

## PHPStan (modello Event)

Il modello `Event` usa tipi Eloquent nativi e PHPDoc generics corretti
(`MorphMany`, `BelongsTo`), import espliciti (`Category`, `Profile`, `Comment`),
`casts()` tipizzati e accessor che restringono le proprietà. La migration
`20260101000000_create_events_table` non chiama `after()` su `ForeignKeyDefinition`
(colonne già nell’ordine di `Schema::create`).

Verifica: da `laravel/`, `php -d memory_limit=2048M ./vendor/bin/phpstan analyse Modules/Event`.
