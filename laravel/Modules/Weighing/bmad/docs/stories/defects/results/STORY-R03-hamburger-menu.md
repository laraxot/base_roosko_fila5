---
id: STORY-R03
type: story
status: todo
priority: critical
scope: risultati-ricerca
links:
  github_issue: https://github.com/italia/design-comuni-pagine-statiche/issues/01
  architecture: KP-4187CF0C013A4F3C955E016F4BE39DD1#section-arch-003
  brainstorming: BMAD-BRAINSTORMING-001
---
# STORY-R03 — Fix Hamburger Menu Visibility (Mobile) — Results Page

## User Job + Success State
User sees hamburger menu (three horizontal bars) visible only on mobile (< md), clickable to expand navigation. Success: conditional rendering with proper aria-expanded.

## Screen Inventory
- Header navigation in `partials/nav.blade.php` (shared with homepage)
- Alpine.js for toggle state

## Token Constraints
Colors: `#ffffff` (icon-white), `#007a52` (accent)

## Required Interaction States
- Default: hamburger icon visible on mobile, hidden on md+
- Hover: focus ring on hamburger
- Active: navigation expands (slide down)

## Reference
screenshot: Design Comuni risultati-ricerca hamburger menu on mobile

## Acceptance Criteria
1. Hamburger icon visible only on mobile (`< md`)
2. Proper `aria-expanded` attribute reflecting state
3. Navigation collapses/expanded on click
4. Visual parity verified at 375/768/1440

## Technical Fix
1. Add `hidden md:flex` to navigation container
2. Add hamburger button with `md:hidden` for mobile
3. Use Alpine.js for toggle: `x-data="{ open: false }"`
4. Add proper aria labels and roles

## Done Checklist
- [ ] Hamburger button created with proper visibility classes
- [ ] Alpine.js toggle functionality added
- [ ] Aria attributes set correctly
- [ ] Visual parity test passed
- [ ] ux_audit passed