---
title: "Decision log — Event"
type: decision-log
module: Event
status: active
created: 2026-10-16
updated: 2026-10-20
tags: [bmad, decision-log, event]
qmd: "decision log event"
related:
  - ../bmad-planning.md
  - ../bmad-brainstorming.md
  - ./project-context.md
---

# Decision log — Event

## 2026-10-16 — Kit BMAD owner

- **Decisione:** adottare `docs/bmad-planning.md` + `docs/bmad-brainstorming.md` + `docs/bmad/filament-blueprint.md` come SSoT di planning per questo owner.
- **Perche:** rimuovere stub copiati "Ticket Timeline" non pertinenti; allineare second brain e Filament blueprint allo scopo reale.
- **Track:** `quick-flow`

## 2026-10-20 — Relazioni `MorphMany` su `Event` (Comment)

- **Decisione:** su `app/Models/Event.php`, `comments()` e `notificationSubscriptions()` restano `@return MorphMany<Related, $this>` allineate al trait `HasComments` del modulo Comment, non al secondo template generico del solo contratto `Commentable` (`MorphMany<Comment, Model>`).
- **Perche':** PHPStan/Larastan richiede `$this` sul modello concreto; l'interfaccia `Commentable` usa `Model` nel template per **covarianza** con implementazioni diverse (`Ticket`, `Article`, `Event`). Il contratto descrive la capacita'; il modello Event dichiara la relazione precisa.
- **Verifica:** `./vendor/bin/phpstan analyse Modules/Event/app/Models/Event.php` (da `laravel/`). Pattern generico: [phpstan-relationship-fix.md](../../../User/docs/phpstan-relationship-fix.md) (modulo User, stesso schema MorphMany).
