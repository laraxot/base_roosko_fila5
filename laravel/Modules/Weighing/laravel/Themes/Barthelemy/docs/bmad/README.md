---
title: "Barthelemy — BMAD dossier"
type: bmad-theme-dossier
theme: Barthelemy
status: support-decision-required
updated: 2026-10-07
tags: [bmad, theme, barthelemy, legacy]
qmd: "Barthelemy theme purpose architecture PRD epics gaps release"
issues: ["https://github.com/laraxot/base_fixcity_fila5/issues/383"]
discussions: ["https://github.com/laraxot/base_fixcity_fila5/discussions/392"]
---
# Barthelemy BMAD dossier

## Kit BMAD owner (canonico)

- [BMAD Planning](../bmad-planning.md)
- [BMAD Brainstorming](../bmad-brainstorming.md)
- [Filament blueprint](./filament-blueprint.md)
- [Project context](./project-context.md)
- [Decision log](./decision-log.md)
**Purpose:** alternate presentation/theme surface; it must not redefine Fixcity domain behavior.
**Architecture:** theme views/assets/components consume module contracts and shared UI tokens.
**PRD / epics:** classify supported routes, accessibility baseline and asset/build ownership before promising support.
**Discovered gaps:** legacy docs contain references to other projects and stale paths; active route ownership and production support are not evidenced.
**Release gate:** explicit support/archive decision, route smoke and asset build check.