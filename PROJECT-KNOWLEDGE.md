# FixCity (base_fixcity_fila5) — Complete Project Knowledge Reference

> **Purpose**: Single source of truth for any agent/session working on this project.
> **Updated**: 2026-10-26
> **Source**: Ingested from AGENTS.md, CLAUDE.md, docs/wiki/, codebase, Hindsight memory, Graphify graph

---

## 1. Project Identity

- **Name**: FixCity (base_fixcity_fila5)
- **Domain**: Civic issue reporting / ticket management for Italian municipalities (PA)
- **Stack**: Laravel 13 + Filament 5 + Livewire 3 + Volt + Folio + TailwindCSS + DaisyUI + Alpine.js + Lit + PHP 8.4
- **Architecture**: Nwidart Laravel Modules (18 modules) + 2 Themes (Sixteen = Design Comuni/AGID, TwentyOne = cinematic)
- **Core pattern**: Laraxot — QueueableAction (no Services), Folio+Volt (no Controllers), Design Comuni CSS (no Bootstrap)

---

## 2. Architecture — Religion Rules (Never Violate)

| # | Rule | Forbidden | Required | Package |
|---|------|-----------|----------|---------|
| 1 | No Services | `class TicketService` | `use QueueableAction` + `handle()` | spatie/laravel-queueable-action |
| 2 | No Controllers | `app/Http/Controllers/*`, `Modules/*/app/Http/Controllers/*` | Filament + Folio+Volt | — |
| 3 | QueueableAction | Service classes | Single-action classes with QueueableAction trait | spatie/laravel-queueable-action |
| 4 | Design Comuni CSS | Bootstrap, domain-prefixed classes (`ticket-card`) | Standard Design Comuni classes (`cmp-card-teaser`) | Tailwind+DaisyUI |
| 5 | `<x-page>` data bag | Prop `containerN`, route segments in data | Only `side`, `slug`, `data` props | — |
| 6 | FO Detail = Widget + Infolist | Duplicate Blade detail pages | CMS `type: widget` + `ViewWidget` | Filament |
| 7 | FO PA tokens | Per-page hex colors (`#007A52 !important`) | `PaDesignColors` + `.fo-filament-form-shell` | — |
| 8 | CSS filenames English | Italian slugs (`segnalazione`, `comuni`) | English slugs (`civic-design-*`) | — |
| 9 | Widget → *Form schema | Fields inline in widget | Fields in `*Form` classes, widget orchestrates | `XotBaseSchemaWidget` |
| 10 | No validateForm | Validation in widget submit | Validation in `*Form`, submit = `$this->form->getState()` | — |
| 11 | Widget CRUD = Model::create | Action for trivial create | `getUserClass()::create($data)` | — |
| 12 | No Italian in Code | Italian in files/classes/methods/Blade/HTML attrs | English everywhere | — |
| 13 | BMAD Story Required | Code without Story | Story → Dev Story → Code | BMAD v6 |
| 14 | Data Sacred | `migrate --force`, `RefreshDatabase` | `php artisan migrate` forward-only | — |
| 15 | Frontmatter + GitHub | .md without YAML frontmatter | `qmd`, `issues`, `discussions` URL in frontmatter | — |
| 16 | Migration updateTimestamps only | `timestamps()`/`softDeletes()`/dup `created_by` | `updateTimestamps()` in `tableUpdate` | — |

---

## 3. Module Structure (18 modules)

All in `laravel/Modules/`. Each module has independent `composer.json`, `module.json`, tests, and git potential.

| Module | Responsibility |
|--------|---------------|
| Xot | Foundation: XotBaseModel, XotBaseResource, traits, helpers, Filament admin panel |
| Cms | Content blocks, Folio routing, `<x-page>` shell, multilingual pages |
| Fixcity | Core domain: tickets/segnalazioni, workflows, map integration |
| User | Auth, profiles, GDPR, roles/permissions (Spatie) |
| Geo | Geolocation, maps (OpenStreetMap), map-lit Web Component |
| Media | File management, S3 storage (Spatie MediaLibrary) |
| Blog | Posts, categories, comments, Filament resources |
| Event | Events, calendar, registrations |
| Comment | Polymorphic comments (ticket/blog/event) |
| Rating | Ratings/reviews |
| Notify | Email/SMS/WhatsApp/Telegram/FCM notifications |
| Activity | Activity logging (Spatie activitylog) |
| Seo | Meta tags, structured data, sitemap |
| Lang | Auto-discovery i18n, mcamara routing (LaravelLocalization) |
| UI | Design system, Blade components, Filament widgets |
| AI | AI integration, MCP governance, local-first Ollama |
| Job | Queue management, scheduled jobs |
| Tenant | Multi-tenancy |
| Gdpr | GDPR compliance, data export/deletion |
| Mobile | Mobile-specific features |

**Theme count**: 2 (Sixteen = primary/Design Comuni, TwentyOne = cinematic)

---

## 4. Key Classes & Patterns

### Xot Foundation (`laravel/Modules/Xot/`)
- `XotBaseModel` — base model with UUID, soft deletes, traits
- `XotBaseResource` — Filament resource base
- `XotBaseSchemaWidget` — base for schema widgets
- `XotBaseEditRecord` / `XotBaseCreateRecord` / `XotBaseViewRecord`
- `QueueableAction` trait — marks action as queueable
- `EnumTrait` — required for all backed enums in modules
- `Helper.php` — global helper functions

### Ticket / Segnalazione Domain
- Code uses "Ticket" (never "segnalazione")
- Workflow: Draft → Submitted → In Progress → Resolved → Closed
- Actions: `CreateTicketAction`, `ListPublicTicketsAction`, etc.
- Widgets: `TicketViewWidget` (FO detail via Infolist), `TicketForm` fields

### CMS Pages (`laravel/Modules/Cms/`)
- `<x-page side="primary" slug="home" data={[...]} />`
- Blocks: `components/blocks/*` — reusable, data-driven
- CMS JSON supplies page labels/URLs, config fallback `config('comune.*')`
- Header/footer via `<x-section slug="header/footer">`

---

## 5. Development Commands

```bash
# === PHP ===
cd laravel

# PHPStan Level 10 (ZERO errors across modules)
php -d memory_limit=2048M ./vendor/bin/phpstan analyse Modules

# PHPMD code quality
./vendor/bin/phpmd Modules text phpmd.ruleset.xml

# Pint formatting
./vendor/bin/pint --format agent

# Pest tests (narrow)
./vendor/bin/pest --filter=TestName
./vendor/bin/pest --compact

# === QMD ===
qmd search "<topic>"
bashscripts/docs/llm-wiki-qmd.sh search "<query>"

# === Graphify ===
graphify query "<question>"
graphify path "<A>" "<B>"
graphify explain "<concept>"
graphify update .          # after code changes, AST-only, no API cost

# === Git ===
git log --oneline          # study history (forward-only)
git show <commit>          # inspect past changes

# === Artisan ===
php artisan route:list
php artisan config:show app.name
php artisan make:test --pest FeatureName

# === Composer ===
composer show --direct     # verify package versions
```

---

## 6. Testing Rules

- **TestCase hierarchy**: Module → XotBaseTestCase → Laravel → PHPUnit
- Nwidart\Modules NOT available in test context
- Assertions: Pure PHPUnit `Assert::assert*()` OR pure Pest `expect()` — never mixed
- `refreshDatabase()` returns empty (no RefreshDatabase — religion rule)
- Migrations run once: `static::$migrationsRun` guard
- Use factories for test data; check custom states before manual setup

---

## 7. Documentation Rules

- **BMAD docs only in**: `laravel/Modules/*/docs/` and `laravel/Themes/*/docs/`
- **NEVER** in root `bmad-output/` (legacy/read-only)
- Every `.md` needs YAML frontmatter: `qmd`, `issues`, `discussions`, `created`, `updated`
- Filename: lowercase-kebab-case.md (no dates, no `-1`, no `-3`)
- Validate: `bashscripts/tools/validate-wiki-frontmatter.sh`
- Rename: `python3 -I bashscripts/tools/docs/md-filenames.py name <path>`
- Search before changing convention: `qmd search "<topic>"`

---

## 8. Hindsight Knowledge Pages (Key IDs)

| Page ID | Topic |
|---------|-------|
| kp-de2ce96b0ff5425bac62bf24abcbc303 | Component map |
| kp-bafeeafed8ce40cfab7478146a10e424 | Conventions and patterns |
| kp-88e65c66812a4863afc0da3b9c829a8b | Core concepts |
| kp-ce89423bd98d42699c88bbad02e97924 | FixCity DesignComuni Front Office Redesign |
| kp-1748411e3d434e46bb13c2b38a831813 | FixCity completion BMAD Filament Blueprint wave |
| kp-d46c1cb2ad974e24b2b10d116bee487c | Area personale tab navigation chrome |
| kp-174d15a58ff94901b2b0df0d86b8907a | Redesign execution (Sixteen) |
| kp-a17b3a970a394c1e8753da6982cce20c | Deep knowledge capture (this session) |

---

## 9. Open Objectives (from AGENTS.md)

1. **Homepage rebuild** — FO redesign parity with Design Comuni
2. **Segnalazione-dettaglio** — Ticket detail page completion
3. **All pages WCAG 2.1 AA** — Accessibility compliance
4. **Tab groups** — Area personale shared chrome (seguite/notifiche/impostazioni)
5. **Blog/Eventi completion** — Parity with Design Comuni Novità/Eventi
6. **Cookiebar GDPR** — Bootstrap Italia + GDPR compliance

---

## 10. Verification Context

- Sixteen asset checks: `cd laravel/Themes/Sixteen`
- Laravel/PHP tests: `cd laravel`
- `php artisan` inside theme loads Testbench bootstrap — NOT valid application-test context
- Report environment/bootstrap blockers as unverified
- Check parity at 375px, 768px, 1440px when changing front-office markup

---

## 11. Current Session Changes (Uncommitted)

| File | Change |
|------|--------|
| `laravel/composer.json` | Added `Database\\Factories\\` autoload-dev path |
| `laravel/database/factories/UserFactory.php` | Added `id` => `(string) Str::uuid()` |
| `laravel/tests/TestCase.php` | Simplified `refreshDatabase()` to empty body, reordered `parent::setUp()` |

---

## 12. Hints for Future Sessions

1. **Always start here**: Read this file + `docs/wiki/index.md` + `docs/wiki/ARCHITECTURE-INDEX.md`
2. **Before coding**: `qmd search "<topic>"` to check for existing decisions
3. **PHPStan**: Run `php -d memory_limit=2048M ./vendor/bin/phpstan analyse Modules` — ZERO errors required
4. **No controllers/services**: Use Filament + QueueableAction + Folio/Volt
5. **Git forward-only**: Never revert; study history then patch forward
6. **Boy Scout rule**: Leave every file better than you found it
7. **Memory**: Call `hindsight_capture_initiative` before starting new features; ingest corrections when memory is wrong
8. **Graphify**: Run `graphify update .` after code changes to keep knowledge graph current

---

## 13. Agent Tooling and Provider Integration

- Claude Code is installed as version 2.1.310 and launched through `/home/zorin/.local/bin/claude`.
- Claude Code uses the first-party Anthropic connection directly (`api.anthropic.com`). It is strictly decoupled from OmniRoute; `ANTHROPIC_BASE_URL` is unset in `/home/zorin/.local/bin/claude` and must never be configured.
- OmniRoute remains an independent local proxy server bound to `127.0.0.1:20128` used exclusively for non-Claude tools.
- Filament Blueprint is installed at `v2.4.0` as a planning and review aid; it does not replace the project's XotBase Filament runtime patterns.
- QMD is indexed and embedded with vector search across all 7 collections (`wiki`, `concepts`, `second-brain`, `sixteen-theme`, `chat`, `pi-memory`, `ptvx-domain-bmad`).
- Graphify AST knowledge graph is generated and updated (`graphify-out/graph.json`).

---

## 14. Gemini & Antigravity Setup (Verified 2026-10-26)

- **Root Agent Symlinks**: `.agents`, `.claude`, and `.cursor` are symlinked to `bashscripts/ai/.agents/` according to the canonical rule in `docs/wiki/rules/agent-tool-dirs-symlink-to-bashscripts-ai-agents.md`. Antigravity automatically discovers all 71 skills and workflows in `.agents/skills/`.
- **Global MCP Configuration**: `~/.gemini/config/mcp_config.json` configured with:
  1. `laravel-boost`: `php laravel/artisan boost:mcp` (Laravel model, route, schema inspection).
  2. `qmd`: `qmd mcp` (Fast BM25 keyword, hybrid search, and vector similarity).
  3. `graphify`: `graphify-mcp` (AST knowledge graph query over stdio).
- **QMD Vector Embedding**: 7 collections indexed. 9,968 orphaned chunks pruned, 470 vector chunks embedded (100% complete).
- **Git Merge Residue Cleaned**: Conflict marker in `bashscripts/ai/.agents/mcp_config.json` resolved.
- **Rule Engine**: `GEMINI.md` upgraded to comprehensive project instructions enforcing the 16 Religion Rules, theme parity, and verification context.
- **Static Analysis**: Verified `php -d memory_limit=2048M ./vendor/bin/phpstan analyse Modules/Fixcity` Level Max passes with 0 errors.

