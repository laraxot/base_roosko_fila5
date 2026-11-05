---
id: STORY-011
type: story
status: todo
priority: critical
scope: homepage
links:
  github_issue: https://github.com/italia/design-comuni-pagine-statiche/issues/01
  architecture: KP-4187CF0C013A4F3C955E016F4BE39DD1#section-arch-011
  brainstorming: BMAD-BRAINSTORMING-001
---
# STORY-011 — Fix Search Key Phrase: Missing Lens Icon + Submit Button

## User Job + Success State
User sees search input with lens icon inside and "Invia" submit button next to it. Success: search component fully functional and visually correct.

## Screen Inventory
- Search form component (`partials/search.blade.php`)
- Search input group

## Token Constraints
Colors: `#007a52` (accent), `#191919` (text), `#ffffff` (bg)
Typography: Lora body, 16px

## Required Interaction States
- Default: input + lens + submit button visible
- Hover: button bg-darkens, input ring shows
- Active: button press state

## Reference
screenshot: Design Comuni homepage search — lens + "Invia" button

## Acceptance Criteria
1. Lens icon (it-search) present inside input
2. Submit button with "Invia" text + lens icon present
3. Button positioned to right of input
4. Visual parity verified at 375/768/1440

## Technical Fix
1. Add `<svg>` lens inside input container
2. Add `<button type="submit" class="...">Invia</button>` with icon
3. Ensure proper spacing (`gap-2`) between input and button
4. Check responsive behavior (stack on mobile?)

## Done Checklist
- [ ] Lens icon added inside input
- [ ] Submit button with "Invia" text added
- [ ] Visual parity test passed
- [ ] ux_audit passed