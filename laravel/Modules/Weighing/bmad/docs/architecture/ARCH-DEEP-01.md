---
id: ARCH-DEEP-01
type: architecture
status: open
priority: critical
scope: homepage
github_issue: https://github.com/italia/design-comuni-pagine-statiche/issues/01
---
# Deep Fix Architecture — 40% → 97% Visual Parity (13 Defects)

## Philosophy (Zen / Design-Comuni)
Industrial design = every element serves public function. Zero decorative noise. Typography = identity (Titillium Web heading, Lora body). Color = semantic (#007a52 = action/trust, #191919 = authority, white = transparency). Margin/padding = breath, not waste. Geometry in featured sections = structured, not decorative.

## Philosophy Failure Analysis (Why 40%)
The previous state violated this: decorative underline on all links = noise; missing SVG icons = loss of identity; wrong font/color = broken identity; missing geometric background = lost structure; missing images = broken content contract; wrong calendar = broken time contract; wrong spacing = broken breath.

## Fix Architecture (Per Defect — linked to ARCH-001..013)
- Defect 01 (social SVG): fix icon mapping in Blade + JSON `social_links[].icon`
- Defect 02 (search lens): position `absolute` right-aligned with `input-group`; label text "Cerca"
- Defect 03 (hamburger): conditional `hidden md:block` + `aria-expanded`
- Defect 04 (underlines): remove universal underline; use `hover:underline` + `focus-visible:ring`
- Defect 05 (news font/color): apply `font-titillium` / `text-[#17324d]` (title) + `#007a52` accent
- Defect 06 (badge): add `badge-estate` with `bg-[#007a52]` text white
- Defect 07 (government boxes): add `img` + `a.btn` in JSON; render in Blade
- Defect 08 (calendar): correct `calendario` component with `grid` of event cards
- Defect 09 (topics geometry): `bg-gradient-to-tr` geometric pattern + padding-4 / margin-2
- Defect 10 (thematic sites): `bg-*` per site + `img` asset
- Defect 11 (search): `input-group` with `svg` lens + `button` "Invia"
- Defect 12 (rating): interactive star component with `hover`/`active` state + `aria-label`
- Defect 13 (contact spacing): `py-6 px-4` card spacing; `gap-4` grid

## Verification
Each fix must have: (1) before/after screenshot at 375/768/1440; (2) `git diff --check` clean; (3) `audit-prompts.sh` clean; (4) `ux_audit` contrast/states/motion pass; (5) BMAD story updated; (6) second-brain initiative `kp-4187...` updated.
