---
id: STORY-R13
type: story
status: todo
priority: critical
scope: risultati-ricerca
links:
  github_issue: https://github.com/italia/design-comuni-pagine-statiche/issues/01
  architecture: KP-4187CF0C013A4F3C955E016F4BE39DD1#section-arch-013
  brainstorming: BMAD-BRAINSTORMING-001
---
# STORY-R13 — Fix Contacts: Section + Cards — Results Page

## User Job + Success State
User sees contact section with cards showing contact information (address, phone, email). Success: contact cards match reference exactly with proper layout and styling.

## Screen Inventory
- `sections/homepage/contact-section.blade.php` component
- Contact cards with address, phone, email

## Token Constraints
Colors: `#191919` (text), `#007a52` (accent/link), `#ffffff` (bg overlay)
Typography: Lora body, 16px

## Required Interaction States
- Default: contact cards with info
- Hover: card lifts + shadow

## Reference
screenshot: Design Comuni risultati-ricerca contact section

## Acceptance Criteria
1. Contact section present with heading
2. Each card has: address/phone/email
3. Correct layout (grid columns)
4. Visual parity verified at 375/768/1440

## Technical Fix
1. Add contact data to JSON results structure
2. In Blade, render contact cards with correct classes
3. Fix grid layout (responsive columns)
4. Ensure proper spacing and typography

## Done Checklist
- [ ] JSON updated with contact data
- [ ] Blade renders contact cards correctly
- [ ] Visual parity test passed
- [ ] ux_audit passed