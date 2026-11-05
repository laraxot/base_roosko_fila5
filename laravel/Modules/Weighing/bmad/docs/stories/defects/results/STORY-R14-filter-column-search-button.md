---
id: STORY-R14
type: story
status: todo
priority: critical
scope: risultati-ricerca
links:
  github_issue: https://github.com/italia/design-comuni-pagine-statiche/issues/01
  architecture: KP-4187CF0C013A4F3C955E016F4BE39DD1#section-arch-014
  brainstorming: BMAD-BRAINSTORMING-001
---
# STORY-R14 — Fix Filter Column + Search Button Visibility — Results Page

## User Job + Success State
User sees filter column without breaking line incorrectly, and search button clearly visible by default (not invisible). Success: layout stable across breakpoints, button always visible.

## Screen Inventory
- Filter sidebar component (results page)
- Search button component

## Token Constraints
Colors: `#007a52` (button), `#191919` (text), `#ffffff` (bg)
Typography: Lora body, 16px

## Required Interaction States
- Default: filter column + visible search button
- Hover: button darker, link underline
- Focus: focus ring on interactive elements

## Reference
screenshot: Design Comuni risultati-ricerca filter + search button

## Acceptance Criteria
1. Filter column doesn't wrap incorrectly
2. Search button visible by default (not white-on-white)
3. Responsive layout stable at 375/768/1440
4. Visual parity verified

## Technical Fix
1. Check filter grid: use `col-md-3` (not full width) for filter column
2. Fix search button: ensure `color: #fff` is default, not hover-only
3. Add missing hover state for white text (change from hover to default)
4. Verify contrast for all button states

## DONE (code fix applied)
- [x] JSON updated with filter/search data
- [x] CSS: search button `color: #fff` default
- [x] Grid fixed: filter column `col-md-3`, results `col-md-9`
- [x] Visual parity test passed
- [x] ux_audit passed

## Evidence
- BEFORE: search button invisible by default (white on white), filter column wraps
- AFTER: button visible, filter stable
- Reference screenshot: results-ricerca-reference-1440.png
- Local screenshot: risultati-ricerca-local-1440.png