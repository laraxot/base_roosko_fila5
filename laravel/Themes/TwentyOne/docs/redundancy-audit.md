---
title: "TwentyOne redundancy audit"
type: audit
theme: TwentyOne
tags: [redundancy, blade, theme-boundary]
created: 2026-05-21
updated: 2026-10-28
related:
  - https://github.com/laraxot/base_fixcity_fila5/issues/89
---

# TwentyOne redundancy audit

Audit eseguito il 2026-05-21 (ex `redundancy-audit.md`); addendum Copilot CLI del 2026-05-25 unito in fondo (ex `copilot-redundancy-audit.md`).

High-risk findings:
- Header/footer sections are duplicated between `layouts/...` and `components/sections/...`: auth, language, links, links_dropdown, notifications, search, socials, crypto.
- Several block fragments are duplicated with `Modules/Blog`, especially `article_list/play_money_markets/...`.
- UI primitives are duplicated with Sixteen: `button`, `input`, `checkbox`, `badge`, `modal`, `logo`, `text-link`, `light-dark-switch`, `placeholder`.
- `pages/[slug].blade.php` and `pages/categories/[slug]/[slug].blade.php` are byte-identical.

Risk:
- Theme layout and component section directories compete as source of truth.
- Blog module fragments copied into theme can drift from domain behavior.

Suggested cleanup order:
1. Decide whether header/footer canonical paths are `layouts` or `components/sections`.
2. Keep module-provided Blog fragments in Blog unless TwentyOne has intentional visual differences.
3. Replace identical page files with one route/view contract after checking Folio/page resolver behavior.

## Addendum Copilot CLI (2026-05-25)

Sintesi: componenti layout e header/nav duplicati in piu' percorsi (stesso finding del punto 1 sopra).

Raccomandazioni:
- Spostare header/nav canonici in una cartella condivisa di componenti del tema e aggiornare gli include.
- Standardizzare i nomi dei componenti e documentarli nell'indice dei docs del tema.
