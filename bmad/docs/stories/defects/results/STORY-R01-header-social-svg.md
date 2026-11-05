---
id: STORY-R01
type: story
status: todo
priority: critical
scope: risultati-ricerca
links:
  github_issue: https://github.com/italia/design-comuni-pagine-statiche/issues/01
  architecture: KP-4187CF0C013A4F3C955E016F4BE39DD1#section-arch-001
  brainstorming: BMAD-BRAINSTORMING-001
---
# STORY-R01 — Fix Header Social Icons (SVG vs Letters) — Results Page

## User Job + Success State
User sees visual-identical social icons in header on results page. Success: SVG icons render with proper aria-label.

## Screen Inventory
- `social_links` JSON array (current: wrong format with text labels)
- `partials/social-links.blade.php` component (shared with homepage)
- SVG sprite at `themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg`

## Token Constraints
Colors: `#ffffff` (icon-white), `#007a52` (accent), `#191919` (authority)
Typography: Titillium Web (heading), Lora (body)

## Required Interaction States
- Default: SVG icon rendered
- Hover: icon color shifts to accent
- Focus-visible: focus ring on link container

## Reference
screenshot: Design Comuni risultati-ricerca header — social icons as SVG with brand colors

## Acceptance Criteria
1. Social icons render as SVG `<use href="#it-facebook">` not text
2. Each icon has `aria-label="Facebook"` etc
3. WCAG 2.1 AA compliant contrast
4. Visual parity verified at 375/768/1440

## Technical Fix
1. Ensure JSON includes `icon` field: `{ "label": "Facebook", "url": "...", "icon": "it-facebook" }`
2. Shared `social-links.blade.php` partial already created
3. Run puppeteer visual comparison to verify

## Done Checklist
- [ ] JSON updated with icon field
- [ ] Blade partial uses shared component
- [ ] SVG sprite verified at correct path
- [ ] Visual parity test passed at 375/768/1440
- [ ] ux_audit passed
- [ ] Documentation updated in ARCH-001