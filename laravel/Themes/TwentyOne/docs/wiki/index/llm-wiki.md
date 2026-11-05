---
title: "llm wiki"
type: note
tags: [documentation]
created: 2026-09-26
updated: 2026-09-26
qmd: "llm wiki"
issues: []
discussions: []
status: draft
---

# LLM Wiki del Tema TwentyOne

Il tema TwentyOne usa `docs/wiki` come livello stabile per architettura, rendering CMS, blocchi e design system.

Prima di modificare il tema:

1. Cercare in QMD nella collezione `predict-themes-docs`.
2. Leggere gli indici canonici del tema.
3. Verificare se il tema deve restare bridge-only o se il dato appartiene a un modulo.
4. Non cancellare config tenant/locali: leggere [config-tenant-boundary](../concepts/config-tenant-boundary.md).
5. Per i controlli di accesso nel tema, consultare [authorization-boundary](../concepts/authorization-boundary.md): TwentyOne resta bridge-only e riusa Gate/Policy del modulo proprietario.
6. Aggiornare la wiki quando cambiano contratti dei blocchi, asset, Vite, layout o rendering CMS.

Comandi utili:

```bash
qmd search "TwentyOne x-page home.json" -c predict-themes-docs
qmd query "come funziona il rendering CMS del tema TwentyOne"
```
