---
id: STORY-006
type: story
status: todo
priority: high
scope: homepage
links:
  github_issue: https://github.com/italia/design-comuni-pagine-statiche/issues/01
  architecture: KP-4187CF0C013A4F3C955E016F4BE39DD1#section-arch-006
  brainstorming: BMAD-BRAINSTORMING-001
---
# STORY-006 — Fix "Estate in Città" Badge (Missing)

## User Job + Success State
User sees the "Estate in Città" badge in hero section. Success: badge renders with correct styling and position.

## Screen Inventory
- Hero section news card in `sections/homepage/hero.blade.php`
- News card component

## Token Constraints
Colors: `#007a52` (accent), `#ffffff` (text)
Typography: Titillium Web 700, badge style

## Required Interaction States
- Default: badge rendered
- Hover: badge slightly darker

## Reference
screenshot: Design Comuni homepage — "Estate in Città" badge

## Acceptance Criteria
1. Badge "Estate in Città" present
2. Badge style matches reference (green background, white text)
3. Position correct (top-right or bottom-left of news card)
4. Visual parity verified at 375/768/1440

## Technical Fix
1. Add `badge-estate` class to badge element
2. Style with `bg-[#007a52] text-white px-3 py-1 text-sm font-bold rounded`
3. Update JSON if needed for badge content
4. Check positioning in news card layout

## Done Checklist
- [ ] Badge content added to JSON/component
- [ ] Badge styling created
- [ ] Position verified
- [ ] Visual parity test passed
- [ ] ux_audit passed