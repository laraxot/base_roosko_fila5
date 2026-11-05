---
id: STORY-008
type: story
status: todo
priority: high
scope: homepage
links:
  github_issue: https://github.com/italia/design-comuni-pagine-statiche/issues/01
  architecture: KP-4187CF0C013A4F3C955E016F4BE39DD1#section-arch-008
  brainstorming: BMAD-BRAINSTORMING-001
---
# STORY-008 — Fix Calendar of Events Format

## User Job + Success State
User sees events calendar with correct dates (e.g., "Giovedì 21 maggio 2020") and event cards. Success: calendar component renders events in correct format.

## Screen Inventory
- `sections/homepage/calendar.blade.php` component
- Event card component

## Token Constraints
Colors: `#007a52` (accent), `#191919` (text), `#ffffff` (bg)
Typography: Lora body, 16px

## Required Interaction States
- Default: correct date format + event cards
- Hover: cards lift + shadow

## Reference
screenshot: Design Comuni homepage calendar section

## Acceptance Criteria
1. Date format correct (e.g., "Giovedì 21 maggio 2020")
2. Event cards with proper image/label
3. Calendar grid layout matches reference
4. Visual parity verified at 375/768/1440

## Technical Fix
1. Update date formatting in calendar component (locale it-IT)
2. Fix grid layout (columns)
3. Add image/label rendering
4. Check positioning and spacing

## Done Checklist
- [ ] Date format fixed (locale)
- [ ] Event cards rendered correctly
- [ ] Visual parity test passed
- [ ] ux_audit passed