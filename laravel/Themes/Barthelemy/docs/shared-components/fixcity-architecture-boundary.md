---
qmd: "fixcity architecture boundary 2026 09 26"
issues: []
discussions: []
title: "FixCity — boundary architetturale tema/modulo"
type: concept
theme: Barthelemy
created: 2026-09-26
updated: 2026-09-26
tags: [fixcity, theme, module, xotbase, second-brain]
status: draft
---

# Boundary FixCity tra tema e modulo

Questo documento evita di applicare al dominio FixCity pattern legacy del tema.

| Responsabilità | Owner |
|---|---|
| Ticket, status, policy, Action, query | `Modules/Fixcity` |
| wrapper Filament XotBase | `Modules/Xot` |
| layout, CSS, componenti e UX Frontoffice | `Themes/Sixteen` |
| blocchi CMS e composizione pagina | `Modules/Cms` + tema |

Le classi del dominio non devono finire nel tema. Il tema non deve introdurre Controller, Services, model Ticket o una propria autorizzazione. Le pagine Filament del modulo usano sempre le classi `XotBase*`; le pagine Frontoffice sono Folio/Volt.

Prima di proporre codice consultare il contratto canonico del modulo:
`Modules/Fixcity/docs/wiki/concepts/fixcity-architecture-contract-2026-09-26.md`.
