---
title: "ponytail audit over engineering"
type: note
tags: [documentation]
created: 2026-09-26
updated: 2026-09-26
qmd: "ponytail audit over engineering"
issues: []
discussions: []
status: draft
---

# Ponytail audit — TwentyOne

**Ultimo run:** 2026-06-30  
**Ruolo:** tema produzione Predict (bridge-only).  
**Hub temi:** [../../../../docs/project/ponytail-audit-themes.md](../../../../docs/project/ponytail-audit-themes.md)  
**Hub repo:** [../../../../docs/audit/ponytail-audit.md](../../../../docs/audit/ponytail-audit.md)  
**Remediation:** [../../../../docs/project/ponytail-audit-remediation.md](../../../../docs/project/ponytail-audit-remediation.md)  
**GitHub monorepo:** [Issue #221](https://github.com/laraxot/base_predict_fila5/issues/221) · [Discussion #222](https://github.com/laraxot/base_predict_fila5/discussions/222) · [Discussion #228](https://github.com/laraxot/base_predict_fila5/discussions/228)

**Repo upstream:** [theme_twentyone_fila5](https://github.com/laraxot/theme_twentyone_fila5) · [Issue #7](https://github.com/laraxot/theme_twentyone_fila5/issues/7)

## Findings

| # | Tag | Cosa | Sostituzione |
|---|-----|------|--------------|
| T21-1 | `delete`→`.bak` | `Main_files/` (~33 file, ~40k righe dump template) | Link repo upstream; non nel build Vite |

## Non tagliare

- Widget view in `resources/views/filament/widgets/` (puntano a moduli).
- Folio pages agnostiche `container0/slug0`.

## Collegamenti

- [00-INDEX.md](./00-INDEX.md)
- [PREDICT_DETAIL_AGNOSTIC_CONTRACT.md](./PREDICT_DETAIL_AGNOSTIC_CONTRACT.md)
- [Predict audit](../../../Modules/Predict/docs/ponytail-audit-over-engineering.md)
