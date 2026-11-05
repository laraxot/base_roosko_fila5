---
id: STORY-013
type: story
status: todo
priority: medium
scope: homepage
links:
  github_issue: https://github.com/italia/design-comuni-pagine-statiche/issues/01
  architecture: KP-4187CF0C013A4F3C955E016F4BE39DD1#section-arch-013
  brainstorming: BMAD-BRAINSTORMING-001
---
# STORY-013 — Fix Contact Spacing (Margin/Padding Errors)

## User Job + Success State
User sees correct spacing between contact cards/items. Success: consistent margin/padding system matches reference.

## Screen Inventory
- Contact section in homepage
- Cards/items with spacing

## Token Constraints
Colors: `#191919` (text), `#ffffff` (bg), `#d1d5db` (border-gray-200)
Typography: Lora body, 16px

## Required Interaction States
- Default: correct spacing
- Hover: slight lift with shadow

## Reference
screenshot: Design Comuni homepage contact section

## Acceptance Criteria
1. Consistent margin between cards (`gap-4` or equivalent)
2. Consistent padding inside cards (`p-6` or equivalent)
3. No random/magic pixel values
4. Visual parity verified at 375/768/1440

## Technical Fix
1. Audit current margin/padding values in contact section
2. Map to design tokens: `py-6 px-4` for card, `gap-4` between cards
3. Remove any hardcoded margin/padding
4. Ensure grid/flex layout respects spacing system

## Done Checklist
- [ ] Current spacing values audited
- [ ] Tokens mapped (py-6 px-4, gap-4)
- [ ] Hardcoded values removed
- [ ] Visual parity test passed
- [ ] ux_audit passed