---
id: STORY-009
type: story
status: todo
priority: critical
scope: homepage
links:
  github_issue: https://github.com/italia/design-comuni-pagine-statiche/issues/01
  architecture: KP-4187CF0C013A4F3C955E016F4BE39DD1#section-arch-009
  brainstorming: BMAD-BRAINSTORMING-001
---
# STORY-009 — Fix Featured Topics: Geometric Background + Margin/Padding

## User Job + Success State
User sees featured topics section with geometric background pattern and properly spaced text inside boxes. Success: background + spacing matches reference exactly.

## Screen Inventory
- `sections/homepage/featured-topics.blade.php` component
- Topic cards with geometric background

## Token Constraints
Colors: `#007a52` (accent), `#191919` (text), `#ffffff` (bg overlay)
Typography: Lora body, 16px

## Required Interaction States
- Default: geometric background + cards
- Hover: card lifts slightly, accent highlight

## Reference
screenshot: Design Comuni homepage featured topics section

## Acceptance Criteria
1. Geometric background pattern visible behind cards
2. Cards have correct padding (not too tight, not too loose)
3. Text margin inside cards correct
4. Visual parity verified at 375/768/1440

## Technical Fix
1. Add `bg-gradient-to-tr` or pattern class to section container
2. Adjust card padding: `p-4` for inner, `m-2` between cards
3. Fix text margin inside cards (`mb-2` / `mb-4`)
4. Ensure geometric pattern aligns with design system tokens

## Done Checklist
- [ ] Background class added to section
- [ ] Card padding adjusted
- [ ] Text margin fixed
- [ ] Visual parity test passed
- [ ] ux_audit passed