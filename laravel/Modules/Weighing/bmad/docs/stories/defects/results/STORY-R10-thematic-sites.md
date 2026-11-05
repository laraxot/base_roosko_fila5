---
id: STORY-R10
type: story
status: todo
priority: critical
scope: risultati-ricerca
links:
  github_issue: https://github.com/italia/design-comuni-pagine-statiche/issues/01
  architecture: KP-4187CF0C013A4F3C955E016F4BE39DD1#section-arch-010
  brainstorming: BMAD-BRAINSTORMING-001
---
# STORY-R10 — Fix Thematic Sites: Colors + Images — Results Page

## User Job + Success State
User sees thematic sites section with correct background colors and images for each site. Success: each site card matches reference exactly.

## Screen Inventory
- `sections/homepage/thematic-sites.blade.php` component (shared with homepage)
- Thematic site cards with color backgrounds and images

## Token Constraints
Colors: per site (e.g., `#ff6b6b`, `#4ecdc4`, etc.), `#191919` (text), `#ffffff` (bg)
Typography: Lora body, 16px

## Required Interaction States
- Default: site card with correct color/image
- Hover: card lifts, color intensifies slightly

## Reference
screenshot: Design Comuni risultati-ricerca thematic sites section

## Acceptance Criteria
1. Each thematic site card has correct background color (from reference)
2. Each site card has correct image (logo or representative image)
3. Text label matches reference
4. Visual parity verified at 375/768/1440

## Technical Fix
1. Update JSON to include `color` and `image` fields for each site
2. In Blade, apply `bg-[color]` and render `<img>` with alt text
3. Ensure text label is present and styled correctly

## Done Checklist
- [ ] JSON updated with color/image for each site
- [ ] Blade component renders correct background and image
- [ ] Visual parity test passed
- [ ] ux_audit passed