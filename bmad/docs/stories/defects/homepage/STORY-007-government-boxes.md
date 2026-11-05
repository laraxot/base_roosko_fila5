---
id: STORY-007
type: story
status: todo
priority: critical
scope: homepage
links:
  github_issue: https://github.com/italia/design-comuni-pagine-statiche/issues/01
  architecture: KP-4187CF0C013A4F3C955E016F4BE39DD1#section-arch-007
  brainstorming: BMAD-BRAINSTORMING-001
---
# STORY-007 — Fix Government Boxes: Image + Button (Missing)

## User Job + Success State
User sees government/enti boxes with image on left and "Vai alla pagina" button on right. Success: boxes render with proper image asset and action button.

## Screen Inventory
- `segnalazioni-elenco` or government entities section
- Box component with image + CTA button

## Token Constraints
Colors: `#007a52` (accent text), `#ffffff` (badge), `#191919` (authority text)
Typography: Lora body, 16px

## Required Interaction States
- Default: image + "Vai alla pagina" button
- Hover: button bg-darkens, image lifts slightly

## Reference
screenshot: Design Comuni homepage government boxes

## Acceptance Criteria
1. Each government box has image rendered
2. "Vai alla pagina" button present
3. Image alt text descriptive
4. Button action links to correct URL
5. Visual parity verified at 375/768/1440

## Technical Fix
1. Add `img` field to JSON box data
2. Add `link` and `button_text` fields
3. Render `<img>` with proper alt text
4. Create `<a class="btn">Vai alla pagina</a>` with correct href
5. Check positioning (grid layout)

## Done Checklist
- [ ] JSON updated with image/link/button fields
- [ ] Blade component renders image + button
- [ ] Visual parity test passed
- [ ] ux_audit passed