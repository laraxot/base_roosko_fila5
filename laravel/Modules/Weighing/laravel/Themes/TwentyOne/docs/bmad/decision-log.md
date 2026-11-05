---
title: "Decision log — TwentyOne"
type: decision-log
theme: TwentyOne
status: active
created: 2026-10-16
updated: 2026-10-16
tags: [bmad, decision-log, twentyone]
qmd: "decision log twentyone"
related:
  - ../bmad-planning.md
  - ../bmad-brainstorming.md
  - ./project-context.md
---

# Decision log — TwentyOne

## 2026-10-20 — Hero senza PredictService

- **Decisione:** `blocks/hero/dynamic.blade.php` non risolve piu' `Modules\Predict\Services\PredictService` (modulo assente). I market trend arrivano gia' shaped dalle props CMS.
- **Perche':** religione no-services: la vista non e' un Service locator; use-case = Action se serve arricchimento, altrimenti props.
- **Track:** BMAD + services-to-actions (owner Xot) + filament/theme blueprint

## 2026-10-16 — Kit BMAD owner

- **Decisione:** adottare `docs/bmad-planning.md` + `docs/bmad-brainstorming.md` + `docs/bmad/filament-blueprint.md` come SSoT di planning per questo owner.
- **Perche:** rimuovere stub copiati "Ticket Timeline" non pertinenti; allineare second brain e Filament blueprint allo scopo reale.
- **Track:** `quick-flow`
