---
id: STORY-001
type: story
status: todo
priority: critical
scope: homepage
links:
  github_issue: https://github.com/italia/design-comuni-pagine-statiche/issues/01
  architecture: KP-4187CF0C013A4F3C955E016F4BE39DD1#section-arch-001
  brainstorming: BMAD-BRAINSTORMING-001
---
# STORY-001 — Fix Header Social Icons (SVG vs Letters)

## User Job + Success State
User sees visual-identical social icons in header. Success: SVG icons render with proper aria-label.

## Screen Inventory
- `social_links` JSON array (current: wrong format with text labels)
- `partials/social-links.blade.php` component
- SVG sprite at `themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg`

## Token Constraints
Colors: `#ffffff` (icon-white), `#007a52` (accent), `#191919` (authority)
Typography: Titillium Web (heading), Lora (body)

## Required Interaction States
- Default: SVG icon rendered
- Hover: icon color shifts to accent
- Focus-visible: focus ring on link container

## Reference
screenshot: Design Comuni homepage header — social icons as SVG with brand colors

## Acceptance Criteria
1. Social icons render as SVG `<use href="#it-facebook">` not text
2. Each icon has `aria-label="Facebook"` etc
3. WCAG 2.1 AA compliant contrast
4. Visual parity verified at 375/768/1440

## Technical Fix
1. Update JSON to include `icon` field: `{ "label": "Facebook", "url": "...", "icon": "it-facebook" }`
2. Create `social-links.blade.php` partial that iterates and renders SVG
3. Position group correctly in header with `flex items-center` layout
4. Run puppeteer visual comparison to verify

## Done Checklist
- [ ] JSON updated with icon field
- [ ] Blade partial created/updated
- [ ] SVG sprite verified at correct path
- [ ] Visual parity test passed at 375/768/1440
- [ ] ux_audit passed
- [ ] Documentation updated in ARCH-001