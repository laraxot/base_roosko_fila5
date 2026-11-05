---
id: STORY-R12
type: story
status: todo
priority: critical
scope: risultati-ricerca
links:
  github_issue: https://github.com/italia/design-comuni-pagine-statiche/issues/01
  architecture: KP-4187CF0C013A4F3C955E016F4BE39DD1#section-arch-012
  brainstorming: BMAD-BRAINSTORMING-001
---
# STORY-R12 — Fix Rating: Star Display + Interaction — Results Page

## User Job + Success State
User sees rating for each result (e.g., 4/5 stars) with interactive star selection. Success: rating component matches reference exactly, including hover/active states.

## Screen Inventory
- Result card component with rating display
- Star icons interactive

## Token Constraints
Colors: `#fbbf24` (star filled), `#e5e7eb` (star empty), `#191919` (text/label)
Typography: Lora body, 16px

## Required Interaction States
- Default: stars displayed (filled/empty based on rating)
- Hover: star hover state
- Active: selected rating highlighted

## Reference
screenshot: Design Comuni risultati-ricerca rating component

## Acceptance Criteria
1. Rating stars displayed correctly per result
2. Interactive: click/tap to select rating
3. Hover state visible
4. Visual parity verified at 375/768/1440

## Technical Fix
1. Add `rating` field to JSON result data structure
2. In Blade, render star icons with correct classes (DaisyUI rating or custom)
3. Add hover/active CSS states
4. Link rating to result ID

## Done Checklist
- [ ] JSON updated with rating data per result
- [ ] Blade renders star icons correctly
- [ ] Hover/active states styled
- [ ] Visual parity test passed
- [ ] ux_audit passed