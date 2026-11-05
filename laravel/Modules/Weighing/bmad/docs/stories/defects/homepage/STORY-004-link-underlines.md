---
id: STORY-004
type: story
status: todo
priority: high
scope: homepage
links:
  github_issue: https://github.com/italia/design-comuni-pagine-statiche/issues/01
  architecture: KP-4187CF0C013A4F3C955E016F4BE39DD1#section-arch-004
  brainstorming: BMAD-BRAINSTORMING-001
---
# STORY-004 — Fix Link Underlines (CSS Over-Application)

## User Job + Success State
User sees links with underline only on hover/focus, not on default. Success: clean text links with subtle hover state.

## Screen Inventory
- Global stylesheet (Tailwind config)
- All `<a>` tags in page

## Token Constraints
Colors: `#007a52` (accent), `#191919` (authority)
Typography: Lora body, 16px

## Required Interaction States
- Default: no underline
- Hover: underline on `text-decoration-underline`
- Focus-visible: focus ring + underline

## Reference
screenshot: Design Comuni homepage links — no default underline

## Acceptance Criteria
1. No default underline on any link
2. Hover state shows underline
3. Focus-visible shows ring + underline
4. Visual parity verified at 375/768/1440

## Technical Fix
1. Add `a { text-decoration: none; }` to global CSS
2. Add `a:hover { text-decoration: underline; }`
3. Add `a:focus-visible { outline: 2px solid #007a52; outline-offset: 2px; }`
4. Run puppeteer visual comparison to verify

## Done Checklist
- [ ] Global CSS updated with link styles
- [ ] Hover state verified
- [ ] Focus-visible state verified
- [ ] Visual parity test passed
- [ ] ux_audit passed