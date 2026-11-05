---
title: "README"
type: readme
status: active
tags: [documentation]
created: 2026-09-26
updated: 2026-10-27
qmd: "barthelemy themes hub docs ex Themes/docs bc58be108 guscio senza codice legacy pub_theme sixteen"
issues: []
discussions: []
---

# Laravel Themes Documentation

This directory routes to the active documentation roots for the Laravel themes in this repository.

## Cosa e' questa cartella (verificato 2026-10-14)

Il tema **Barthelemy non ha codice**: questa cartella non ne documenta uno. E' l'ex hub `laravel/Themes/docs`, rinominato in blocco in `laravel/Themes/Barthelemy/docs` dal commit `bc58be108` (2026-06-12, 1.988 rinomine `R100` verso questa cartella, tra cui questo `README.md` e `DOCUMENTATION_INDEX.md`). Per questo i temi fratelli puntano ancora a `../../docs/...` (il vecchio hub) e i file qui parlano dei temi in generale. Lo stato del tema e l'elenco delle cartelle sono in [00-INDEX.md](00-INDEX.md).

Prove dello stato legacy:

- il tema contiene solo `package.json` (nome `laraxot/theme-barthelemy-fila5`, versione `0.0.0`, privato), `.releaserc.json`, `.gitignore`, `scripts/ci/contributor-lines-report.mjs` e `docs/`; mancano `composer.json`, `theme.json`, `app/`, `resources/`, `lang/`, `tests/` e la configurazione Vite;
- non e' in `gitmodules.ini`, non ha un `.git` proprio (lo traccia la radice) e non e' tra i namespace di `RegisterRuntimePsr4NamespacesAction` (`TwentyOne`, `Sixteen`, `Two`);
- `pub_theme` vale `Sixteen` in tutti e 4 i `laravel/config/**/xra.php` e `public_html/themes/` contiene solo il link `Sixteen`;
- nessun file PHP o di configurazione cita il tema: `rg Barthelemy` sul codice trova solo la localita' "Saint Barthelemy" nel dump `Modules/Geo/database/_dump/geonames_cap.sql`.

## Current Themes

Stato verificato il 2026-10-14 (le righe sono prove, non intenzioni):

| Theme | Role | Status | Docs |
| --- | --- | --- | --- |
| `Sixteen` | tema pubblico di Fixcity (`pub_theme`), Tailwind 4 + DaisyUI + Alpine + Lit, resa Design Comuni; Bootstrap Italia non e' una dipendenza (e' assente da `package.json`) | vivo, repo git separato (submodule) | [README](../../Sixteen/docs/README.md) |
| `TwentyOne` | tema importato dal progetto Predict (mercati di previsione): 30 riferimenti a `Modules\Predict`, modulo che qui non esiste | non usato da Fixcity, repo git separato (submodule) | [README](../../TwentyOne/docs/README.md) |
| `Barthelemy` | guscio senza codice (vedi sopra) | legacy | questa cartella |
| `Meetup` | guscio senza codice, solo `package.json`, CI e una mail di prova | legacy | [README](../../Meetup/docs/README.md) |

## Theme Rules

- The active documentation home for a theme is `laravel/Themes/<Theme>/docs/README.md`.
- Never create `lang/lang/` or `_docs/` in a theme; see [directory-structure-rules.md](./directory-structure-rules.md).
- Keep root theme indexes short; they should route to the theme, not duplicate the theme docs.
- Do not create `docs/archive/` in any module or theme; follow the shared [Second Brain rule](../../../../bashscripts/ai/wiki/concepts/no-docs-archive-rule.md).
- Treat generated screenshots, vendor docs, and `node_modules/**/docs` as supporting material, not as canonical repository guidance.
- Use project-level rules from [DOCUMENTATION_GOVERNANCE.md](../../../../docs/DOCUMENTATION_GOVERNANCE.md).

## Runtime Context

- Web document root: `public_html/`
- Application code: `laravel/`
- Theme assets are published under `public_html/themes/<Theme>/`

## Fast Paths

- Active theme docs: [Sixteen](../../Sixteen/docs/README.md)
- Alternative theme docs: [TwentyOne](../../TwentyOne/docs/README.md)
- Module index: [laravel/Modules/docs/README.md](../../Modules/docs/README.md) _(target mancante, verificato 2026-10-14)_
- LLM wiki workflow: [docs/wiki/README.md](../../../../docs/wiki/README.md)
- Themes note: [llm-wiki.md](./llm-wiki.md)
- Shared project docs: [docs/README.md](../../../../docs/README.md)
- Agent rules: [AGENTS.md](../../../AGENTS.md)
- Ridondanze documentazione temi: [ridondanze-documentazione-temi.md](./ridondanze-documentazione-temi.md)

## Documentation (hub — non duplicare nei temi)

- [On-Demand Pattern](./on-demand-pattern.md) — Pattern per caricamento efficiente
- [QMD Setup](./qmd-setup.md) — Configurazione ricerca locale
- [Performance](./performance-optimization.md) — Metriche e best practice
- [Project Structure](./project-structure.md) — Directory layout
