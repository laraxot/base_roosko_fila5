---
title: "bmad-output — VIETATO per documenti"
type: notice
status: deprecated
updated: 2026-10-15
tags: [bmad, deprecated, do-not-use]
qmd: "bmad-output forbidden use module theme docs"
---

# Questa cartella non ospita documenti

**Ordine utente 2026-10-15:** nessun documento va in `bmad-output/`.

Scrivi solo in:

- `laravel/Modules/<Modulo>/docs/…`
- `laravel/Themes/<Tema>/docs/…`

## Perché

Ownership, second brain, QMD e parallelismo BMAD richiedono la memoria
**accanto al codice owner**. Una cartella root orfana crea una seconda verità.

## Dove sono finiti i contenuti migrati

Vedi [migration-from-bmad-output.md](../laravel/Modules/Fixcity/docs/bmad/project/migration-from-bmad-output.md).

## Regole da caricare sempre

- [bmad-docs-never-in-bmad-output.md](../docs/wiki/memories/bmad-docs-never-in-bmad-output.md)
- [bmad-docs-in-module.md](../docs/wiki/rules/bmad-docs-in-module.md)
- skill [bmad-docs-placement.md](../docs/wiki/skills/bmad-docs-placement.md)

Non aggiungere altri file qui.

## Enforcement automatico

Hook PreToolUse `bmad-output-docs-guard.sh`: rifiuta Write/Edit sotto questa
cartella (eccetto questo README). Cursor rule always-on:
`.cursor/rules/bmad-docs-in-module.mdc`.

