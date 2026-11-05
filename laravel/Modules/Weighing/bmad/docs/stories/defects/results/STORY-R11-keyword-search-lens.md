---
id: STORY-R11
type: story
status: todo
priority: critical
scope: risultati-ricerca
links:
  github_issue: https://github.com/italia/design-comuni-pagine-statiche/issues/01
  architecture: KP-4187CF0C013A4F3C955E016F4BE39DD1#section-arch-011
  brainstorming: BMAD-BRAINSTORMING-001
---
# STORY-R11 — Fix Search: Keyword + Lens + Submit — Results Page

## User Job + Success State
User types keyword in search field, sees lens icon, clicks submit. Success: search form renders correctly with icon, input, and button matching reference exactly.

## Screen Inventory
- `sections/homepage/search-form.blade.php` or equivalent
- Search input field with lens icon
- Submit button

## Token Constraints
Colors: `#191919` (text), `#007a52` (accent), `#ffffff` (placeholder/bg)
Typography: Lora body, 16px

## Required Interaction States
- Default: input + lens + submit
- Focus: input border/focus ring
- Hover: lens/input subtle highlight
- Disabled: input muted, button inactive

## Reference
screenshot: Design Comuni risultati-ricerca search form

## Acceptance Criteria
1. Search input field present with correct styling
2. Lens icon visible inside/near input
3. Submit button present with correct label ("Cerca" or "Search")
4. Visual parity verified at 375/768/1440

## Technical Fix
1. Add search input to JSON data structure (`tests.risultati-ricerca.search`)
2. In Blade, render `<input class="form-input">` with proper placeholder
3. Add lens icon SVG (from Design Comuni sprite)
4. Create submit button with correct classes

## Done Checklist
- [ ] Search input added to JSON data
- [ ] Blade renders input + lens + submit
- [ ] Visual parity test passed
- [ ] ux_audit passed