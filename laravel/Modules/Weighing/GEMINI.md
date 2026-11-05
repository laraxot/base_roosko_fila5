# Gemini & Antigravity — FixCity Project Confidence & Rule Engine

> **Purpose**: Authoritative project instructions and persistent memory for Gemini & Antigravity.
> **Single Source of Truth**: [PROJECT-KNOWLEDGE.md](file:///mnt/nas07/var/www/_bases/base_fixcity_fila5/PROJECT-KNOWLEDGE.md)
> **Recovery Map**: [docs/second-brain/project/project-confidence-recovery.md](file:///mnt/nas07/var/www/_bases/base_fixcity_fila5/docs/second-brain/project/project-confidence-recovery.md)
> **Updated**: October 2026

---

## 1. Project Identity & Stack

- **Name**: FixCity (`base_fixcity_fila5`)
- **Domain**: Civic issue reporting and ticket management for Italian Municipalities (PA / AGID)
- **Stack**: Laravel 13.38 + Filament 5.10 + Livewire 4.4 + Volt 1.11 + Folio 1.2 + PHP 8.4
- **Presentation**: Sixteen theme (TailwindCSS + DaisyUI + Alpine.js + Flowbite + Lit web components) — **NO Bootstrap**
- **Architecture**: Nwidart Laravel Modules (20 modules in `laravel/Modules/`) + 4 themes in `laravel/Themes/`
- **Pattern**: Laraxot — `QueueableAction` (no Services), Filament + Folio/Volt (no Controllers), CMS blocks data-driven

---

## 2. Standing Architecture Rules & Religions (Never Violate)

| # | Rule | Forbidden | Required | Reference |
|---|------|-----------|----------|-----------|
| 1 | **No Services** | `class TicketService` | `use QueueableAction` + `execute()` / `handle()` | [actions-over-services.md](file:///mnt/nas07/var/www/_bases/base_fixcity_fila5/docs/wiki/rules/actions-over-services.md) |
| 2 | **No Controllers** | `app/Http/Controllers/*` | Filament Panels + Folio/Volt pages | [folio-page-shell-pattern.md](file:///mnt/nas07/var/www/_bases/base_fixcity_fila5/laravel/Modules/Cms/docs/wiki/concepts/folio-page-shell-pattern.md) |
| 3 | **Design Comuni CSS** | Bootstrap classes, custom hex styling | Design Comuni tokens (`cmp-*`, Tailwind + DaisyUI) | [Sixteen docs](file:///mnt/nas07/var/www/_bases/base_fixcity_fila5/laravel/Themes/Sixteen/docs/README.md) |
| 4 | **`<x-page>` data bag** | Custom ad-hoc routing parameters | Props: `side`, `slug`, `data` bag only | [FolioVoltServiceProvider.php](file:///mnt/nas07/var/www/_bases/base_fixcity_fila5/laravel/Modules/Cms/app/Providers/FolioVoltServiceProvider.php) |
| 5 | **FO Detail = Widget + Infolist** | Duplicate Blade ticket detail views | CMS `type: widget` + `ViewWidget` | Filament Infolists |
| 6 | **CSS filenames English** | Italian filenames (`segnalazione.css`) | English kebab-case filenames | [laravel-naming-conventions.md](file:///mnt/nas07/var/www/_bases/base_fixcity_fila5/docs/wiki/rules/laravel-naming-conventions.md) |
| 7 | **Widget → `*Form` schema** | Field schemas inline in widgets | Fields in `*Form` schema classes | `XotBaseSchemaWidget` |
| 8 | **No validateForm** | Custom validation in submit action | Schema validation via `$this->form->getState()` | Filament Forms |
| 9 | **No Italian in Code** | Italian class, method, variable names | English everywhere (domain term is `Ticket`) | Standing rule |
| 10 | **Data Sacred** | `migrate:fresh`, `db:wipe`, `RefreshDatabase` | Forward-only additive migrations | [data-sacred-no-destructive-db.md](file:///mnt/nas07/var/www/_bases/base_fixcity_fila5/docs/wiki/rules/data-sacred-no-destructive-db.md) |
| 11 | **BMAD Story Required** | Coding without story/spec | Story → Dev Story → Code | [bmad-artifacts-live-in-module-docs.md](file:///mnt/nas07/var/www/_bases/base_fixcity_fila5/docs/wiki/memories/bmad-artifacts-live-in-module-docs.md) |
| 12 | **Markdown filenames** | Dates, timestamps, suffixes (`-1.md`) | Descriptive kebab-case (`topic-name.md`) | [markdown-filename-conventions.md](file:///mnt/nas07/var/www/_bases/base_fixcity_fila5/docs/wiki/rules/markdown-filename-conventions.md) |
| 13 | **BMAD docs placement** | Writing in root `bmad-output/` | Modulo docs: `Modules/*/docs/`, `Themes/*/docs/` | [bmad-docs-never-in-bmad-output.md](file:///mnt/nas07/var/www/_bases/base_fixcity_fila5/docs/wiki/memories/bmad-docs-never-in-bmad-output.md) |
| 14 | **Git forward-only** | Rollback, git reset, creating branches | Fix forward, preserve branch | [git-forward-only.md](file:///mnt/nas07/var/www/_bases/base_fixcity_fila5/docs/wiki/rules/git-forward-only.md) |
| 15 | **Type Narrowing** | `mixed` to bypass static analysis | Precise types, domain contracts, generics | [source-first-before-fix.md](file:///mnt/nas07/var/www/_bases/base_fixcity_fila5/docs/wiki/memories/source-first-before-fix.md) |
| 16 | **PHPStan Zero Errors** | Bypassing errors or `--level` flags | Level Max (`laravel/phpstan.neon`) | [post-edit-quality-gate.md](file:///mnt/nas07/var/www/_bases/base_fixcity_fila5/docs/wiki/rules/post-edit-quality-gate.md) |

---

## 3. Toolchain & Local Retrieval Workflow

Always retrieve before coding:
1. **Graphify**: When `graphify-out/graph.json` exists, run `graphify query "<question>"` before code search.
   After editing code, run `graphify update .` to keep knowledge graph synchronized.
2. **QMD Search**: Use `qmd query "<topic>"` or `qmd search "<keywords>"` to search across collections:
   - `wiki` (`docs/wiki/`)
   - `concepts` (`bashscripts/ai/wiki/concepts/`)
   - `second-brain` (`docs/second-brain/`)
   - `sixteen-theme` (`laravel/Themes/Sixteen/docs/`)
   - `chat` (`docs/chat/`, archivio in sola lettura: il coordinamento ora sta nelle story BMAD owner)
3. **MCP Servers**: Configured in `~/.gemini/config/mcp_config.json`:
   - `laravel-boost`: Direct access to Laravel models, routes, and configs.
   - `qmd`: Markdown hybrid search & vector embeddings over stdio.
   - `graphify`: AST knowledge graph query over stdio.
4. **Skills & Agents**: Located in `.agents/skills/` (symlinked from `bashscripts/ai/.agents/`).

---

## 4. Verification Context & Testing Discipline

- **Sixteen Asset Checks**: Run from `laravel/Themes/Sixteen` (`npm run build && npm run copy`).
- **Laravel / Pest Tests**: Run from `laravel` directory (`./vendor/bin/pest <path> --no-coverage`).
- **Do not run `php artisan` inside theme directory**: It loads Testbench bootstrap and is not the application test context.
- **PHPStan Analysis**:
  ```bash
  cd laravel
  php -d memory_limit=2048M ./vendor/bin/phpstan analyse Modules
  ```
- **Front Office Parity Checks**: Test responsive layouts at `375px`, `768px`, and `1440px`. Ensure WCAG 2.1 AA compliance (contrast >= 4.5:1, keyboard focusable, valid ARIA labels, reflow up to 400%).

---

## 5. File Locking Protocol

Before modifying any existing file:
```bash
bash bashscripts/lock/check.sh <path>
bash bashscripts/lock/lock.sh <path> <task-id> <agent-id>
# Perform edit
# Run quality gate (php -l, phpstan, phpmd)
bash bashscripts/lock/unlock.sh <path>
```

---

## 6. On-Demand Documentation Pointers

- **Trigger Map**: [docs/wiki/rules/00-TRIGGER_MAP.md](file:///mnt/nas07/var/www/_bases/base_fixcity_fila5/docs/wiki/rules/00-TRIGGER_MAP.md)
- **Skills Index**: [docs/wiki/skills/INDEX.md](file:///mnt/nas07/var/www/_bases/base_fixcity_fila5/docs/wiki/skills/INDEX.md)
- **Complete Project Reference**: [PROJECT-KNOWLEDGE.md](file:///mnt/nas07/var/www/_bases/base_fixcity_fila5/PROJECT-KNOWLEDGE.md)


- [Project identity and decision map](docs/second-brain/project/project-identity-and-decision-map.md) — purpose, politics, invariants, philosophy, vision and recovery of context.
