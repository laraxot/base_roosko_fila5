---
id: STORY-012
type: story
status: todo
priority: high
scope: homepage
links:
  github_issue: https://github.com/italia/design-comuni-pagine-statiche/issues/01
  architecture: KP-4187CF0C013A4F3C955E016F4BE39DD1#section-arch-012
  brainstorming: BMAD-BRAINSTORMING-001
---
# STORY-012 — Fix Rating Behavior (Interactive Stars)

## User Job + Success State
User sees interactive rating stars (e.g., 4/5 stars) with hover/active states. Success: stars fill correctly on hover/click and show proper aria-label.

## Screen Inventory
- Rating component (may be in homepage or a dedicated page)
- Star icons with interactive state

## Token Constraints
Colors: `#fbbf24` (star filled - amber), `#e5e7eb` (star empty - gray), `#007a52` (accent)

## Required Interaction States
- Default: empty/half-filled stars
- Hover: stars fill up to hovered star
- Active: selected rating submitted
- Focus-visible: ring on focusable star

## Reference
screenshot: Design Comuni homepage rating section or dedicated rating page

## Acceptance Criteria
1. Stars render interactively (hover fills, click selects)
2. Selected rating persists (or submits)
3. Aria-label describes current rating
4. Visual parity verified at 375/768/1440

## Technical Fix
1. Create rating component with clickable stars
2. Use Alpine.js or Lit for state management
3. Style filled stars with `text-[#fbbf24]` (amber-500)
4. Style empty stars with `text-[#e5e7eb]` (gray-300)
5. Add hover/focus states
6. Add aria-label for accessibility

## Done Checklist
- [ ] Rating component created with interactive stars
- [ ] Hover state: fill stars up to cursor
- [ ] Click state: selected rating persists
- [ ] Focus-visible state added
- [ ] Visual parity test passed
- [ ] ux_audit passed