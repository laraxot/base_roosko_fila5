---
title: "Blocchi di base per la homepage CMS"
type: guide
created: 2026-11-17
updated: 2026-11-17
status: active
tags: [cms, homepage, blocchi]
issues: []
discussions: []
---

# Blocchi di base per la homepage CMS

Blocchi senza dipendenze da moduli, usabili da qualunque progetto nella pagina CMS
`config/local/<tenant>/database/content/pages/home.json` (`content_blocks.<lingua>`).
Usati per esempio dalla homepage di Passaparola.

| Blocco (`data.view`) | Dati richiesti |
|---|---|
| `pub_theme::components.blocks.hero.simple` | `title`, `subtitle`, `cta_text`, `cta_link` |
| `pub_theme::components.blocks.features.simple` | `items`: elenco di `{icon, title, description}`; `icon` è un nome Heroicon (es. `heroicon-o-chart-bar`) |
| `pub_theme::components.blocks.cta.simple` | `title`, `subtitle`, `button_text`, `button_link` |

Esempio:

```json
{"type": "hero", "data": {"view": "pub_theme::components.blocks.hero.simple",
  "title": "Ti restituiamo le serate", "subtitle": "…", "cta_text": "Crea il tuo account", "cta_link": "/it/auth/register"}}
```

I blocchi `hero/cinematic`, `markets`, `predict*`, `trading`, `wallet` sono di Predict.
