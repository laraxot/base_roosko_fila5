---
title: "ci — semantic release tema TwentyOne"
type: concept
tags: [ci, github-actions, semantic-release, twentyone, contributor-analytics]
created: 2026-06-12
updated: 2026-06-12
qmd: "TwentyOne theme github actions semantic release changelog contributor analytics git-fame"
issues:
  - "https://github.com/laraxot/base_fixcity_fila5/issues/356"
discussions:
  - "https://github.com/laraxot/base_fixcity_fila5/discussions/357"
related:
  - ../../../../../../docs/wiki/ci/semantic-release-monorepo.md
  - ../../../../Sixteen/docs/wiki/concepts/ci-semantic-release.md
  - ../../../../Modules/Xot/docs/ci/github-actions-modules.md
status: draft
---

# ci — semantic release tema TwentyOne

## Repo owner

`theme_twentyone_fila5` — i workflow in `.github/workflows/` **partono** su push al repo tema.

## File CI (scaffold STORY-355)

| File | Ruolo |
|------|--------|
| `.github/workflows/semantic-release.yml` | `npx semantic-release` (Conventional Commits) |
| `.github/workflows/update-changelog.yml` | Aggiorna `CHANGELOG.md` su GitHub Release |
| `.github/workflows/semantic-versioning.yml` | Tag semver opzionale |
| `.github/workflows/contributor-analytics.yml` | Report git-fame: LOC per contributor × estensione file |
| `.releaserc.json` | `tagFormat: theme-twentyone-v${version}` |
| `CHANGELOG.md` | Generato da semantic-release |

## Monorepo base

Su `base_fixcity_fila5` il release è orchestrato da `semantic-release-monorepo.yml` (matrix include `TwentyOne`).

I workflow locali servono al **push sul repo tema** standalone.

## Contributor analytics

Output: `docs/outputs/contributor-analytics/` (README + `by-extension.md` + `charts/contributors.svg`).

Schedule: lunedì 05:00 UTC; anche su `release` e `workflow_dispatch`.

## Scaffold

```bash
./bashscripts/ci/scaffold-module-github-workflows.sh laravel/Themes/TwentyOne theme-twentyone
```
