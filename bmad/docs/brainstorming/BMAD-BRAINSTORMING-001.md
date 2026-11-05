---
id: BMAD-BRAINSTORMING-001
type: brainstorming
status: open
priority: critical
scope: homepage
github_issue: https://github.com/italia/design-comuni-pagine-statiche/issues/01
---
# BMAD Brainstorming — Homepage Visual Parity (40% → 97%)

## Brainstorming Session: 2026-10-13
### Goal: Achieve 97% visual parity with Design Comuni homepage using TailwindCSS + Alpine.js + Lit + DaisyUI + Flowbite (NO Bootstrap Italia).

## Key Questions
1. **Social icons**: Why are they text-only? How to map SVG icons from reference?
2. **Search lens**: Why is it misplaced? How to position it correctly?
3. **Hamburger menu**: Why is it missing on mobile? How to implement conditional visibility?
4. **Link underlines**: Why are all links underlined? How to apply underline only on hover/focus?
5. **News title**: Why wrong font/color? How to apply Titillium/Lora with correct color?
6. **Estate badge**: Why missing? How to add badge with correct styling?
7. **Government boxes**: Why missing image/button? How to include assets in JSON/render?
8. **Calendar format**: Why wrong format? How to fix calendar component?
9. **Featured topics**: Why geometric background + wrong padding? How to fix styling?
10. **Thematic sites**: Why wrong colors/images? How to map tokens?
11. **Search key phrase**: Why missing lens icon + submit button? How to implement?
12. **Rating behavior**: Why wrong state behavior? How to implement interactive stars?
13. **Contact spacing**: Why wrong margins/padding? How to fix spacing system?

## Brainstorming Solutions
- **Social icons**: Extract SVG sprite from reference, add `icon` field to social_links JSON, update header component to render SVG with `use` directive.
- **Search lens**: Position search input in `flex items-center` with `absolute right-2` for lens icon; add "Cerca" label text.
- **Hamburger menu**: Add `hidden md:flex` to nav, use Alpine `x-show` with `aria-expanded` toggle.
- **Link underlines**: Remove universal `underline` class; use `hover:underline` + `focus-visible:ring`.
- **News title**: Apply `font-titillium` + `text-[#17324d]` for title, `#007a52` for accent; verify font availability.
- **Estate badge**: Add `badge-estate` class with `bg-[#007a52]` text white; update JSON with `estate` field.
- **Government boxes**: Add `img` and `a.btn` fields to JSON; render in Blade with proper alt text.
- **Calendar format**: Correct date format in calendar component; use `date-fns` or similar for consistency.
- **Featured topics**: Add `bg-gradient-to-tr` with geometric pattern; adjust padding to `p-4` / `m-2`.
- **Thematic sites**: Map site colors to tokens; add image assets to JSON.
- **Search key phrase**: Add `svg` lens icon + `button` "Invia" to search form.
- **Rating behavior**: Implement interactive star component with `hover`/`active` states; use Alpine for reactivity.
- **Contact spacing**: Fix margin/padding system; use `py-6 px-4` for cards; `gap-4` for grid.

## Action Plan
1. Create BMAD architecture documents for each defect (already done: ARCH-001..013)
2. Create BMAD stories for each defect (kp-4187cf0c013a4f3c955e016f4be39dd1)
3. Implement fixes in Blade/JSON files
4. Verify with Puppeteer screenshots at 375/768/1440
5. Update second brain initiative with before/after evidence
6. Re-run ux_audit to confirm 97% visual parity
7. Document final status in STATUS-ALL-PAGES.md

## Dependencies
- `pub_theme/components/blocks/header.slim.blade.php` (needs update)
- `laravel/Themes/Sixteen/resources/views/sections/header/nav-primary.blade.php` (needs update)
- `laravel/Themes/Sixteen/resources/views/sections/header/partials/social-links.blade.php` (needs creation)
- `laravel/Themes/Sixteen/resources/views/sections/header/partials/search.blade.php` (needs update)
- `laravel/Themes/Sixteen/resources/views/sections/homepage/calendar.blade.php` (needs update)
- `laravel/Themes/Sixteen/resources/views/sections/homepage/featured-topics.blade.php` (needs update)
- `laravel/Themes/Sixteen/resources/views/sections/homepage/thematic-sites.blade.php` (needs update)

## Success Metrics
- 97% visual parity (ux_audit)
- 90% HTML parity
- All 13 defects fixed and documented
- Zero Bootstrap Italia usage
- All pages pass WCAG accessibility audit