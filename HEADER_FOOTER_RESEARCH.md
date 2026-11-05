# Sixteen Theme — Header & Footer Implementation Research

**Path root:** `laravel/Themes/Sixteen/`
**Theme owner module:** Sixteen (not root `Modules/*`)
**Reference design:** Design Comuni (AGID) — `design-comuni-pagine-statiche` v2.4.0

---

## 1. Implementation Locations

| Component | File Path | Type |
|---|---|---|
| Main header (Design-Comuni exact) | `resources/views/components/header-comune.blade.php` (~276 lines) | Blade component |
| Main footer (Design-Comuni exact) | `resources/views/components/footer-comune.blade.php` (~190 lines) | Blade component |
| Section wrapper (dynamic CMS) | `resources/views/components/sections/header.blade.php` (+ tailwind partial via `pub_theme`) | Dynamic `<x-section slug="header">` |
| Section wrapper footer | `resources/views/components/sections/footer.blade.php` | Dynamic `<x-section slug="footer">` |
| Section templates | `resources/views/sections/footer-full.blade.php`, `footer-slim.blade.php`, `footer.blade.php` | `<x-section>` templates |
| Layout base | `resources/views/layouts/app.blade.php` / `base.blade.php` | Calls `<x-section slug="header" />` and `<x-section slug="footer" tpl="full" />` |
| Pub-theme references (duplicate namespace) | `pub_theme::components.sections.header.partials.*` included inside header-comune | Includes language-switcher, user-dropdown |

Public theme assets: `public/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg` (icons).

---

## 2. Blade Component Structure (`<x-section>`)

`resources/views/components/layouts/app.blade.php`:
```blade
<x-section slug="header" />
<x-section slug="footer" tpl="full" />
```

`x-section` resolves via `components/section.blade.php` (documented in `docs/layout-architecture.md`). The component reads `$data` (from CMS / JSON page content) and selects a partial (`tpl="full"` / `slim"` / `default`).

Header uses: `header-comune.blade.php` (hard-coded Design-Comuni structure) + `pub_theme::components.sections.header.partials.language-switcher` + user dropdown partial.
Footer uses: `footer-comune.blade.php` (hard-coded) or the dynamic `sections/footer.blade.php` which pulls `data['admin_title']`, `data['service_links']`, `data['contact']`, `data['social_links']`, etc.

---

## 3. Header Elements (from `header-comune.blade.php`)

- **Slim bar** (`it-header-slim-wrapper`): region link (`config('comune.regione')`), language-switcher, personal-area (guest/login button or user dropdown with avatar, display name, unread notifications, initial fallback).
- **Brand** (`it-brand-wrapper`): logo SVG (`logo-comune.svg`), site name (`config('app.name')`), tagline (`config('comune.sottotitolo')`).
- **Social icons** (`it-socials`): Twitter, Facebook, YouTube, Telegram, WhatsApp, RSS (SVG sprite references).
- **Search** (`it-search-wrapper`): button opening `#search-modal` (search-modal component exists in `components/sections/search-modal.blade.php`).
- **Navigation** (`navbar`): primary links (`amministrazione`, `novita`, `servizi`, `vivere-il-comune`), secondary (`iscrizioni`, `estate-in-citta`, `polizia-locale`, `argomenti`), mobile hamburger overlay with close-div.
- **Data flow**: uses `auth()->user()`, profile avatar URL (media library / gravatar fallback), `config()` values for names/regions. No direct CMS JSON injection in `header-comune` — it is static/template-level.

---

## 4. Footer Elements (from `footer-comune.blade.php` / `footer-full.blade.php`)

`footer-comune.blade.php`:
- EU logo (`logo-eu-inverted.svg`)
- Brand logo + name (`Nome del Comune` hard-coded)
- Four columns: Amministrazione links, Service categories (two sub-columns), Novità + Vivere il Comune, Contatti (address, CF/P.IVA, URP, phone/WhatsApp/email/PEC, buttons FAQ/Appuntamento/Segnalazione/Assistenza)
- Bottom bar: legal links (Amministrazione trasparente, Privacy, Note legali, Dichiarazione accessibilità), social icons, Media policy, Mappa sito
- Uses `<x-filament::icon>` components (`heroicon-o-phone`, `ui-brands.twitter`, etc.)

`sections/footer-full.blade.php` (CMS-driven):
- Receives `$data['home_url']`, `$data['site_name']`, `$data['admin_links']`, `$data['service_links']` (array sliced to two columns), `$data['contact']`, `$data['social_links']`, `$data['bottom_links']`, `$data['show_social']`.
- Uses `asset('themes/Sixteen/design-comuni/assets/images/logo-eu-inverted.svg')`.
- Uses Tailwind utility classes (`mt-8`, `text-underline`, `text-slate-300`, `bg-sky-800`).

---

## 5. Composition / Data Flow

- **Static path (`header-comune` / `footer-comune`)**: reads `auth()->user()`, profile media, gravatar fallback, `config('app.name/comune.regione/comune.sottotitolo')`. Hard-coded nav URLs (`/it/amministrazione`, `/it/novita`, `/it/servizi`, `/it/eventi`, `/it/iscrizioni`, etc.).
- **Dynamic CMS path (`x-section`)**: reads from JSON page content (`config/local/fixcity/database/content/pages/*.json`). Example `tests.index.json` uses `layout: "test"` and `content_blocks`. Footer data (`data`) can be injected from CMS / Folio actions.
- **Duplication**: `pub_theme::` namespace references inside `header-comune.blade.php` point to duplicate components (language switcher partial, user dropdown). `design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg` is the shared sprite; `public/design-comuni/assets/images/logo-comune.svg` and `logo-eu-inverted.svg` are design-system assets.

---

## 6. Redundancy / Duplication

- `header-comune.blade.php` includes `pub_theme::components.sections.header.partials.language-switcher` and `user-dropdown` — these duplicate functionality likely also present in `pub_theme` module (check `pub_theme` module if it exists in `laravel/Modules/`).
- `footer-comune.blade.php` duplicates content that `sections/footer-full.blade.php` also renders; there are two footer implementations: one hard-coded (Design-Comuni exact) and one data-driven (`x-section`).
- `design-comuni` assets folder (`public/design-comuni/assets/`) holds copies/references to Bootstrap Italia sprites and images; theme also has `resources/assets/css/` and `js/`.
- References to `design-comuni.blade.php` in docs suggest an earlier parallel layout (`docs/wiki/rules/no-parallel-design-comuni-layout.md` confirms no parallel layout should exist).
- `resources/views/components/sections/header.blade.php` and `components/header-comune.blade.php` co-exist — potential naming confusion.

---

## 7. Design Tokens (used in header/footer)

From `header-comune.blade.php` and `tailwind.config.js` / `css/app.css`:
- **Colors**: `primary` (blue from Bootstrap Italia), `white`, `text-muted`, `text-slate-300` (in dynamic footer), `bg-sky-800` (search button), `text-emerald-700` (pub_theme icon references), `text-slate-950`, `text-white`, `border-slate-300`.
- **Typography**: `font-bold`, `text-xl`, `text-2xl`, `text-sm`, `text-lg`, `h5`, `h4`, `h2`, `no_toc`; `uppercase`, `tracking-wide`; `leading-6`.
- **Spacing**: `py-4`, `py-lg-5`, `mb-3`, `mb-4`, `mt-3`, `mt-8`, `me-2`, `gap-3`, `p-3`, `px-4`, `px-6`, `rounded-lg`, `rounded-full`, `rounded-icon`.
- **Elevation**: `shadow` not heavily used; structure relies on Bootstrap Italia grid (`container`, `row`, `col-12`, `col-md-3`, `col-lg-3`, `col-md-6`).
- **States**: `hover:bg-sky-50`, `hover:text-white`, `focus:outline-none`, `focus:ring-4`, `focus:ring-white/30`, `focus:ring-sky-800/20`, `prefers-reduced-motion` handled globally via `vite.config.js` / `tailwind.config.js`.

---

## 8. Technical Implementation Stack

- **Blade**: Components, sections (`x-section`), partials (`@include`), props (`@props`), config helpers (`config()`), auth helpers (`auth()->user()`), route helpers (`route('login')`).
- **Alpine.js**: Used in mobile overlay (`data-bs-target`, `data-bs-toggle` via Bootstrap Italia JS; `x-data` / `x-cloak` used in theme base). Search modal uses `x-data` or Bootstrap `data-bs-toggle="modal"`.
- **TailwindCSS**: Primary styling layer (`tailwind.config.js`, `tailwind-optimized.config.js`). Replicates Bootstrap Italia spacing/colors.
- **DaisyUI / Flowbite**: Mentioned in docs (`docs/components.md`, `docs/design-system/`) but not clearly visible in header/footer blade; likely used via `pub_theme` components or theme-wide CSS variables.
- **Filament**: `<x-filament::icon>` components used in footer (`heroicon-o-*`, `ui-brands.*`). Theme has `resources/views/components/filament/` and `app/Providers`.
- **Vite**: `vite(['resources/css/app.css'], 'themes/Sixteen')` in base layout.
- **Lit / Alpine stores**: Not directly visible in header/footer blade; theme docs (`docs/design-system/`) mention Lit components and Alpine stores for interactivity.

---

## 9. Refactoring / Refactoring Outline + Required Docs

### 9.1 Immediate Issues (from inspection)
- Duplicate footer implementations (`footer-comune.blade.php` vs `sections/footer-full.blade.php`).
- `header-comune.blade.php` includes `pub_theme` partials (language-switcher, user-dropdown) — potential circular/module dependency; check `pub_theme` existence and contracts.
- Hard-coded nav links in header (`/it/amministrazione`, `/it/novita`, `/it/servizi`, `/it/eventi`, `/it/iscrizioni`, `/it/estate-in-citta`, `/it/polizia-locale`, `/it/argomenti`) — should come from CMS / `data` array in `<x-section>`.
- `footer-comune.blade.php` hard-codes `Nome del Comune`, EU logo path; dynamic `footer-full.blade.php` already supports `$data` injection.
- Design-system assets (`public/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg`) are shared; verify no duplicate sprite copies in `public/assets/`.
- `docs/FOOTER-CSS-FIX.md` indicates previous footer CSS fixes; review whether the fix is still needed or superseded by `tailwind.config.js`.

### 9.2 BMAD Documents Required (per `AGENTS.md` rules)
Per `docs/stories/STORY-*.md` conventions and `docs/wiki/rules/016-github-issue-discussion-in-every-story.md`:

1. **Story doc** (`docs/stories/sixteen-header-footer-refactor.story.md`):
   - Type: `epic-story`
   - Title: "Sixteen Header/Footer consolidation — remove duplication, align with Design Comuni"
   - Frontmatter: `links: {github_issue: '#XX', discussion: '...'}` (must include at least one GitHub Issue + Discussion link)
   - Sections: Problem, Scope (header-comune, footer-comune, x-section templates, pub_theme references), Success criteria (single source of truth for header/footer, CMS data injection, no `pub_theme` circular includes), Out of scope (redesign of design system tokens).

2. **Investigation doc** (`docs/investigation/header-footer-duplication.md`):
   - Type: `investigation`
   - Evidence: file paths, code snippets from `header-comune.blade.php` and `footer-comune.blade.php`, comparison with `footer-full.blade.php`, `pub_theme` references.
   - Findings: duplication count, design-token mismatches.

3. **Decision log entry** (`docs/decision-log.md` or `docs/decision-log/header-footer.md`):
   - Type: `decision-log`
   - Decision: consolidate on `x-section` dynamic components OR keep `header-comune` static for Design-Comuni parity.
   - Rationale: no parallel `design-comuni` layout allowed (`docs/wiki/rules/no-parallel-design-comuni-layout.md`).

4. **Quality gate script** (`bash bashscripts/quality-gates/verify-llm-wiki.sh`): run after any edit. Ensure no `.txt` / extra `.md` added to theme root; audit with `bashscripts/tools/audit-module-theme-root-files.sh`.

### 9.3 Second Brain Entries (per `docs/wiki/concepts/agent-bootstrap-compact.md`)

- Entry slug: `sixteen-header-footer-consolidation`
- Key facts:
  - `header-comune.blade.php` line 1: auth/user profile data flow (avatar, display name, initial, unread count).
  - `footer-comune.blade.php` line 1: EU logo, brand wrapper, 4-column layout.
  - Dynamic alternative: `resources/views/components/sections/header.blade.php` + `sections/footer.blade.php` (CMS data).
  - `public/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg` = icon sprite.
  - No `pub_theme` parallel layout allowed.
- Cross-reference: link to `docs/layout-architecture.md`, `docs/design-comuni/ARCHITECTURAL_DECISIONS.md`.

### 9.4 QMD Queries for Future Reference
Use `qmd search` with single-file results and append decisions to `docs/wiki/log.md`:

```bash
qmd search "header-comune footer-comune duplication" --limit 5
qmd search "x-section slug header footer data" --limit 5
qmd search "pub_theme language-switcher user-dropdown" --limit 5
qmd search "design-comuni header footer parity" --limit 5
qmd search "sixteen theme header footer CMS JSON" --limit 5
```

After each query, append reusable decision to `docs/wiki/log.md`:
```markdown
- [2026-...] QMD query result: `x-section` resolves via `components/section.blade.php` (see `docs/layout-architecture.md`); `pub_theme` partials must not duplicate `header-comune` components; design tokens must stay within `tailwind.config.js` tokens.
```

---

## References

- `laravel/Themes/Sixteen/resources/views/components/header-comune.blade.php`
- `laravel/Themes/Sixteen/resources/views/components/footer-comune.blade.php`
- `laravel/Themes/Sixteen/resources/views/components/sections/header.blade.php`
- `laravel/Themes/Sixteen/resources/views/sections/footer-full.blade.php`
- `laravel/Themes/Sixteen/resources/views/sections/footer-slim.blade.php`
- `laravel/Themes/Sixteen/resources/views/layouts/app.blade.php`
- `laravel/Themes/Sixteen/docs/layout-architecture.md`
- `laravel/Themes/Sixteen/docs/design-comuni/ARCHITECTURAL_DECISIONS.md`
- `laravel/Themes/Sixteen/docs/FOOTER-CSS-FIX.md`
- AGENTS.md (no controllers, use Actions/Folio; no services layer; module contracts naming; migration timestamp-only; wiki frontmatter; x-page data bag; quality gate after edit; git forward-only)
