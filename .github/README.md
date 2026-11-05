---
title: ".github/ canonical structure"
type: how-to
tags: [github, ci, copilot, contributing, design-system, audit, bmad]
created: 2026-06-05
updated: 2026-06-05
qmd: "github canonical structure issue content discussion content nonstandard directories cleanup bmad"
issues:
  - "https://github.com/laraxot/base_fixcity_fila5/issues/275"
discussions:
  - "https://github.com/laraxot/base_fixcity_fila5/discussions/276"
related:
  - ../docs/stories/STORY-145-github-directory-canonical-structure.md
  - ../../docs/architecture-ai-assisted-coding-2026-06-05.md
---

# .github/ — canonical structure

> **Created 2026-06-05** (opencode - MiniMax-M3) · **Cleanup rationale:** vedi §"Storia" sotto.

## Path GitHub-recognized (KEEP)

| Path | Contenuto | GitHub-recognized |
|------|-----------|-------------------|
| `CONTRIBUTING.md` | Guida per contributor (PR, issue, discussion) | ✅ [docs.github.com](https://docs.github.com/en/communities/setting-up-your-project-for-healthy-contributions/setting-guidelines-for-repository-contributors) |
| `FUNDING.yml` | Pulsante "Sponsor" | ✅ [docs.github.com](https://docs.github.com/en/repositories/managing-your-repositorys-settings-and-features/customizing-your-repository/displaying-a-sponsor-button-in-your-repository) |
| `SECURITY.md` | Policy sicurezza, CVE reporting | ✅ [docs.github.com](https://docs.github.com/en/code-security/getting-started/adding-a-security-policy-to-your-repository) |
| `pull_request_template.md` | Template PR (default) | ✅ [docs.github.com](https://docs.github.com/en/communities/using-templates-to-encourage-useful-issues-and-pull-requests/creating-a-pull-request-template-for-your-repository) |
| `ISSUE_TEMPLATE/` | Form per nuove issue (9 file) | ✅ [docs.github.com](https://docs.github.com/en/communities/using-templates-to-encourage-useful-issues-and-pull-requests/creating-an-issue-template-for-your-repository) |
| `DISCUSSION_TEMPLATE/` | Form per nuove discussion (1 file) | ✅ [docs.github.com](https://docs.github.com/en/communities/using-templates-to-encourage-useful-issues-and-pull-requests/creating-discussion-templates-for-your-repository) |
| `workflows/` | GitHub Actions CI/CD + module-release.yml | ✅ [docs.github.com](https://docs.github.com/en/actions/using-workflows) |
| `dependabot.yml` | Config Dependabot | ✅ [docs.github.com](https://docs.github.com/en/code-security/dependabot) |
| `release-drafter-config.yml` | Config release-drafter | ✅ [release-drafter](https://github.com/release-drafter/release-drafter) |
| `copilot-instructions.md` | GitHub Copilot agent | ✅ [docs.github.com](https://docs.github.com/en/copilot) |
| `prompts/` | Symlink → `bashscripts/ai/.agents/prompts` (design/UI skill trees) | ✅ junction — vedi [agent-config-junctions-ssot.md](../docs/wiki/concepts/agent-config-junctions-ssot.md) |

## Path custom FixCity (KEEP con giustificazione)

| Path | Contenuto | Purpose |
|------|-----------|---------|
| `agents/` | agent files `gsd-*` | Referenziati da `bashscripts/ai/skills/gsd-*` e dai workflow GSD |
| `get-shit-done/` | workflow, references, templates GSD | Referenziato da `.github/agents` e dalle skill GSD |

Nota audit 2026-06-05: `get-shit-done/bin/gsd-tools.cjs` e `get-shit-done/bin/lib/*` sono dichiarati nel manifest ma assenti dal tree; non cancellare il pacchetto senza prima decidere in #275/#276 se ripararlo o spostarlo fuori da `.github/`.

## Path RIMOSSI (2026-06-05)

| Path rimosso | Contenuto spostato in | Motivo |
|--------------|------------------------|--------|
| `ISSUE_CONTENT/` (31 file, 132K) | ❌ ELIMINATO | **Non GitHub-recognized** (GitHub usa `ISSUE_TEMPLATE/`). Nomi italiani (R12 violation: `amministrazione.md`, `appuntamento-01-ufficio.md`, `ticket-01-privacy.md`...). Duplicava contenuto di `ISSUE_TEMPLATE/`. |
| `DISCUSSION_CONTENT/` (30 file, 128K) | ❌ ELIMINATO | **Non GitHub-recognized** (GitHub usa `DISCUSSION_TEMPLATE/`). Nomi italiani (R12 violation). Duplicava contenuto di `DISCUSSION_TEMPLATE/`. |
| `ISSUES/` (3 file, 24K) | `docs/stories/EPIC-DESIGN-COMUNI-REPLICATION.md`, `docs/stories/STORY-SIXTEEN-CRITICAL-CORRECTION-2026-04.md`, `docs/chat/sync-multi-agent-test.md` | **Non GitHub-recognized**. SSoT stories = `docs/stories/`, chat = `docs/chat/`. |
| `DISCUSSIONS/` (4 file, 40K) | `docs/wiki/decisions/adr-001-tailwind-apply.md`, `docs/wiki/decisions/adr-002-tailwind-apply-design-comuni.md`, `docs/architecture/design-comuni-architecture-2026-04.md`, `docs/chat/sync-test-001-cli-success.md` | **Non GitHub-recognized**. ADRs in `docs/wiki/decisions/`, arch in `docs/architecture/`, chat in `docs/chat/`. |
| `templates/module-release.yml` | `.github/workflows/module-release.yml` | **Non GitHub-recognized**. Workflow GitHub Actions va in `workflows/`. |
| `contributing.md` (lowercase) | ❌ ELIMINATO | Duplicato di `CONTRIBUTING.md` (case-sensitive Linux, ma GitHub canonical = uppercase). |
| `security.md` (lowercase) | ❌ ELIMINATO | Duplicato di `SECURITY.md`. |
| `funding.yml` (lowercase) | ❌ ELIMINATO | Duplicato di `FUNDING.yml`. |
| `ISSUES_GUIDE.md` (9.3K) | ❌ ELIMINATO | Riferiva al path `ISSUES/` (ora rimosso), link rotti. |
| `README.md` (9.3K) | ❌ ELIMINATO | Riferiva a path inesistenti (`../_bmad-output/...`, `../.planning/ROADMAP.md`). |
| `DESIGN_COMUNI_CONVERSION.md` (5.8K) | `docs/architecture/design-comuni-pages-conversion-2026-04.md` | Planning doc, va in SSoT architettura. |
| `context-mode/` | ELIMINATO | Cache/sessioni runtime MCP: non e GitHub-native e non va versionata sotto `.github/`. |

## Risultato finale (12 path, 0 ridondanza)

```
.github/
├── CONTRIBUTING.md              (GitHub-recognized, uppercase canonical)
├── FUNDING.yml                  (GitHub-recognized, uppercase canonical)
├── SECURITY.md                  (GitHub-recognized, uppercase canonical)
├── pull_request_template.md     (GitHub-recognized)
├── ISSUE_TEMPLATE/              (GitHub-recognized, 9 form)
├── DISCUSSION_TEMPLATE/         (GitHub-recognized, 1 form)
├── workflows/                   (GitHub Actions + module-release.yml)
├── dependabot.yml               (Dependabot config)
├── release-drafter-config.yml   (release-drafter)
├── copilot-instructions.md      (Copilot agent)
├── prompts/                     (symlink → bashscripts/ai/.agents/prompts)
├── agents/                      (opencode integration, 11 file gsd-*)
└── get-shit-done/               (GSD methodology, 24+ workflow)
```

**Stats:** nessun `ISSUE_CONTENT/`, `DISCUSSION_CONTENT/`, `ISSUES/`, `DISCUSSIONS/` o `context-mode/` residuo nella root `.github`.

## Mirror `bashscripts/ai/.github`

Copia operativa per agenti (rsync da root). **Stesso divieto**: niente `ISSUES/`, `DISCUSSIONS/`, `ISSUE_CONTENT/`.  
Governance: [github-mirroring-governance.md](../bashscripts/docs/ai/github-mirroring-governance.md)

## Storia del cleanup
- **2026-06-05** (Codex - GPT-5): corretti link YAML verso issue/discussion dedicate (#275/#276), rimosso `context-mode/` dalla lista KEEP e marcato GSD come path custom da riparare/spostare solo dopo decisione multi-agente.

- **2026-06-05** (opencode - MiniMax-M3): audit + cleanup root `.github/`. Rimossi `ISSUE_CONTENT`, `DISCUSSION_CONTENT`, `ISSUES`, `DISCUSSIONS`, `templates/` + duplicati lowercase.
- **2026-06-05** (Cursor): allineato mirror `bashscripts/ai/.github` — eliminati `ISSUES/`, `DISCUSSIONS/`, `templates/` residui (10 file legacy Quaeris/sync-test).
- **2026-03-31 → 2026-04-21**: setup iniziale con ridondanze accumulate (case-insensitive duplicates, `ISSUE_CONTENT` vs `ISSUE_TEMPLATE` confusione).

## Riferimenti canonici

- [GitHub Community Health Files](https://docs.github.com/en/communities/setting-up-your-project-for-healthy-contributions/about-community-profile-for-repositories)
- [GitHub Actions](https://docs.github.com/en/actions)
- [Architecture doc FixCity](../../docs/architecture-ai-assisted-coding-2026-06-05.md)

---
*opencode (MiniMax-M3) · 2026-06-05 · R12 (English-only filenames) + R13 (harness install) applied*
