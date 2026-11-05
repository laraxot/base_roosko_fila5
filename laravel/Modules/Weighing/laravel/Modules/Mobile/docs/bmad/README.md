---
title: "Mobile — BMAD dossier"
type: bmad-module-dossier
module: Mobile
status: baseline
updated: 2026-10-07
tags: [bmad, mobile, nativephp, geojson]
qmd: "Mobile module purpose architecture PRD epics gaps release"
issues: ["https://github.com/laraxot/base_fixcity_fila5/issues/383"]
discussions: ["https://github.com/laraxot/base_fixcity_fila5/discussions/392"]
created: 2026-10-07
---
# Mobile BMAD dossier

## Kit BMAD owner (canonico)

- [BMAD Planning](../bmad-planning.md)
- [BMAD Brainstorming](../bmad-brainstorming.md)
- [Filament blueprint](./filament-blueprint.md)
- [Project context](./project-context.md)
- [Decision log](./decision-log.md)
**Purpose / product brief:** provide an agnostic NativePHP/mobile host; it consumes contracts and does not embed municipality or restaurant domain rules.
**Architecture:** mobile actions/adapters consume Fixcity/Geo/User contracts; canonical ticket semantics remain server/module-owned.
**PRD:** mobile discovery, location capture, report draft, upload retry, authentication and tracking parity.
**Epics:** host contract; mobile report slice; offline/error and release packaging.
**Discovered gaps:** establish supported platform matrix, offline draft policy, push token lifecycle, deep links and parity evidence.
**Release gate:** device smoke, denied permissions, offline recovery, secure storage and server-contract compatibility.