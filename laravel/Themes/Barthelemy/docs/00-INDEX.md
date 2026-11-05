---
title: "Barthelemy - indice docs"
type: index
theme: Barthelemy
updated: 2026-10-14
status: active
tags: [docs, barthelemy]
qmd: "barthelemy: indice docs barthelemy ex hub Themes/docs legacy guscio senza codice shared-components classificazione"
---

# Barthelemy: indice docs

**Stato (verificato 2026-10-14): il tema Barthelemy e' legacy e non ha codice**; questa cartella e' l'ex hub `laravel/Themes/docs` dei temi, rinominato qui dal commit `bc58be108` (2026-06-12, 1.988 rinomine `R100`). Fixcity usa `pub_theme = Sixteen`. Prove, contenuto reale del tema e tabella dei quattro temi in [README.md](README.md). Quindi i documenti sotto sono regole e storia dei temi in generale, non la descrizione di un tema Barthelemy.

Indice minimo dei documenti della cartella. Per l'inventario esteso vedi [DOCUMENTATION_INDEX.md](DOCUMENTATION_INDEX.md) (alcuni suoi link puntano a file inesistenti e sono annotati).

## Classificazione delle cartelle

| Cartella | Ruolo | Nota |
|---|---|---|
| radice (`*.md`) | canonica per le regole trasversali dei temi | `theme-selection-governance.md`, `directory-structure-rules.md`, `contracts-naming.md`, `THEME_PHILOSOPHY_COMPLETE.md`; `vhost-configuration.md` chiama "Bootstrap Italia compliant" il tema Sixteen, che non ha quella dipendenza |
| `wiki/` | locale ai temi | `index.md`, `log.md`, `concepts/`; `theme-architecture.md` e' `stale` (descrive un tema Barthelemy inesistente); `wiki/rules/` contiene solo `INDEX.md` |
| `shared-components/` | evidence / legacy, da triare (DOC-004, DOC-006) | 1.887 file in un solo livello: 1.753 `.md`, 96 `.html` (63 sono riferimenti di parity `*-reference-body.html` / `*-local-body.html` del tema Sixteen), script `.sh`/`.py`, JSON, immagini; 150 nomi finiscono in `-Modules.md` e 167 in `-N.md` (copie con suffisso), 18 iniziano con `-`; non e' documentazione di un tema |
| `bmad/`, `concepts/`, `rules/`, `llm-wiki/` | supporto | dossier BMAD e singole note |
| `raw/`, `outputs/` | evidence, immutabili | non si toccano |
| `root-md-files/` | legacy | changelog archiviato dalla radice |

Note `stale` datate 2026-10-14: [project-structure.md](project-structure.md) e [wiki/theme-architecture.md](wiki/theme-architecture.md). Story di questo giro: [stories/2026-10-14-docs-studio-stato-e-ingressi.story.md](stories/docs-studio-stato-e-ingressi.story.md).

## Documenti in radice

- [README.md](README.md)
- [THEME_PHILOSOPHY_COMPLETE.md](THEME_PHILOSOPHY_COMPLETE.md)
- [theme-selection-governance.md](theme-selection-governance.md)
- [vhost-configuration.md](vhost-configuration.md)
- [directory-structure-rules.md](directory-structure-rules.md)
- [project-structure.md](project-structure.md)
- [contracts-naming.md](contracts-naming.md)
- [on-demand-pattern.md](on-demand-pattern.md)
- [performance-optimization.md](performance-optimization.md)
- [qmd-setup.md](qmd-setup.md)
- [llm-wiki.md](llm-wiki.md)

## Igiene e ridondanze

- [REDUNDANCY_ANALYSIS.md](REDUNDANCY_ANALYSIS.md)
- [redundancy-audit.md](redundancy-audit.md)
- [ridondanze-documentazione-temi.md](ridondanze-documentazione-temi.md)
- [root-file-policy.md](root-file-policy.md)
- [root-files-hygiene.md](root-files-hygiene.md)
- [codex-error-fix.md](codex-error-fix.md)
- [merge-conflict-marker-task-list.md](merge-conflict-marker-task-list.md)
- [merge-conflict-task-list.md](merge-conflict-task-list.md)

## Cartelle

- [concepts/](concepts/): integrazioni concettuali (es. [comment-module-fo-integration.md](concepts/comment-module-fo-integration.md)).
- [rules/](rules/): regole del tema (es. [xotbasefield-view-override.md](rules/xotbasefield-view-override.md)).
- [wiki/](wiki/index.md): wiki locale del tema, architettura in [wiki/theme-architecture.md](wiki/theme-architecture.md).
- [shared-components/](shared-components/): documentazione dei componenti condivisi; `README.md.cleaned` e' una copia del README del modulo Geo, non del tema.
- [llm-wiki/](llm-wiki/), [raw/](raw/), [outputs/](outputs/), [root-md-files/](root-md-files/): materiale di supporto.
