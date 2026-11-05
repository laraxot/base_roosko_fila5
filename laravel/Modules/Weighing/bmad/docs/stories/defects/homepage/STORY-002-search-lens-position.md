---
id: STORY-002
type: story
status: todo
priority: critical
scope: homepage
links:
  github_issue: https://github.com/italia/design-comuni-pagine-statiche/issues/01
  architecture: KP-4187CF0C013A4F3C955E016F4BE39DD1#section-arch-002
  brainstorming: BMAD-BRAINSTORMING-001
---
# STORY-002 — Fix Search Lens Position + "Cerca" Label

## User Job + Success State
User sees search input with lens icon positioned inside input (right-aligned) and "Cerca" label text. Success: input-group visually matches reference.

## Screen Inventory
- `partials/search.blade.php` component
- Search form in header

## Token Constraints
Colors: `#191919` text, `#007a52` accent, `#ffffff` background
Typography: Lora body, 16px

## Required Interaction States
- Default: lens icon visible, placeholder "Cerca una parola chiave"
- Focus: ring on input
- Hover: button "Invia" visible

## Reference
screenshot: Design Comuni homepage search — lens inside input, "Invia" button

## Acceptance Criteria
1. Lens icon (`it-search`) positioned absolutely right-aligned in input
2. "Cerca una parola chiave" placeholder text
3. "Invia" submit button with `it-search` icon + text
4. Visual parity verified at 375/768/1440

## Technical Fix
1. Wrap input in `relative` container
2. Add `absolute right-2 top-1/2 -translate-y-1/2` for lens SVG
3. Add submit button with `px-4 py-2 bg-primary-500 text-white`
4. Update JSON if needed for search config

## Done Checklist
- [ ] Search component updated with correct layout
- [ ] Lens icon positioned inside input
- [ ] Submit button added with "Invia" text
- [ ] Visual parity test passed
- [ ] ux_audit passed