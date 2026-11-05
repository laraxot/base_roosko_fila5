---
id: "STORY-wcag-a11y-footer-header-language-access-improvements"
name: "WCAG + a11y fixes — footer, header nav, language switcher, access button"
type: story
status: open
github_issue: "https://github.com/italia/design-comuni-pagine-statiche/issues/wcag-a11y"
discussion: "https://github.com/italia/design-comuni-pagine-statiche/discussions"
priority: critical
module: Sixteen
pages_target: ["/it/tests/", "/it/tests/homepage", "/it/tests/segnalazione-dettaglio"]
reference_url: "https://italia.github.io/design-comuni-pagine-statiche/"
stack: "TailwindCSS + Alpine.js + Lit + DaisyUI + Flowbite (NO Bootstrap Italia)"
no_bootstrap_italia: true
gate:
  html_parity_min_percent: 90
  visual_parity_min_percent: 95
---

## Issues found (WCAG / accessibility audit)

### Footer (footer-full.blade.php + footer-slim.blade.php)
- `h4.footer-heading-title` — dark text on dark footer bg (contrast failure, APCA Lc <30)
- `<a class="text-underline">` in footer links and social icons — text is dark/low contrast;
  missing `:focus-visible` state; underline alone is insufficient for links (needs underline + colordiff)
- `text-white/60` on links — insufficient contrast against dark footer (`#0B0B0B` / `#111`)
- `icon-white` links — white icons OK, but no `aria-label` on some links; `text-underline` class has no hover color shift for state visibility

### Header nav (header/components/partials/language-switcher.blade.php + header_bi5.blade.php)
- Language switcher `nav-item dropdown` — dropdown toggle has `aria-expanded` but missing `aria-controls` reference on some variants
- Icons (`it-arrow-right`, `it-search`) — sized with `h-5 w-5` / `h-4 w-4`; reference expects `icon-sm` (~16px) consistent; current mix of `h-5` (20px) and `h-4` (16px) causes size inconsistency in nav
- Underline on nav links (`hover:underline`) — no `focus-visible:outline-2` or `focus-visible:ring`; keyboard users lose position
- `language-switcher.blade.php`: `span` with `visually-hidden` OK, but dropdown `div` has `id="languages"`; link targets not always matching `aria-controls`

### Access button (layout-test.blade.php + design-comuni/pages/homepage.blade.php + footer links)
- `class="hover:underline"` on "Accedi all’area personale" (layout-test.blade.php:13) — underline decoration only; missing `:focus-visible:ring-2` + `focus-visible:ring-[#007a52]`; no `disabled` style for logged-in state
- Button should use `btn btn-primary` class with `focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2` for WCAG 2.4.7 (visible focus); current `hover:underline` hides focus indicator
- `text-blue-600 hover:text-blue-800 underline` (guest.blade.php:107/122/129/139) — blue accent only; missing `aria-label`; no `prefers-reduced-motion` fallback on underline transitions

### Plan to improve (must-do, non-blocking the gate but tracked)
- Fix footer `h4.footer-heading-title` to `text-white` or `text-neutral-200` with `font-semibold` and `mb-3` + add `focus-visible` to links (`focus-visible:outline-2 focus-visible:outline-[#007a52]`)
- Unify header icon sizes to `h-4 w-4` (16px = `icon-sm`) across all nav links; add `aria-label` to icon-only links
- Language switcher: verify `aria-controls="languages"` matches `id="languages"`; add `focus-visible` to toggle button
- Access button: replace `hover:underline` with `btn btn-primary` + `focus-visible:ring-2 focus-visible:ring-[#007a52]`; add `prefers-reduced-motion` to transition/animation; verify `aria-label="Accedi all'area personale"` on link
- Audit all interactive elements for `:focus-visible` + `:disabled`; add `prefers-reduced-motion` fallback to all transitions
- Use `ux_audit` (deterministic gate) after edits; block handoff on fail
