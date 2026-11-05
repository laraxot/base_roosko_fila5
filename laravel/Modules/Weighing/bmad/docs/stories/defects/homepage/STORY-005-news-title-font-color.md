---
id: STORY-005
type: story
status: todo
priority: critical
scope: homepage
links:
  github_issue: https://github.com/italia/design-comuni-pagine-statiche/issues/01
  architecture: KP-4187CF0C013A4F3C955E016F4BE39DD1#section-arch-005
  brainstorming: BMAD-BRAINSTORMING-001
---
# STORY-005 — Fix News Title Font/Color ("Notizie 18 mag 2022")

## User Job + Success State
User sees news title "Parte l'estate con oltre 300 eventi..." with correct Titillium Web font and correct color (#17324d). Success: typography matches reference exactly.

## Screen Inventory
- Hero section news card in `sections/homepage/hero.blade.php`
- News title component

## Token Constraints
Colors: `#17324d` (title), `#007a52` (accent), `#191919` (authority)
Typography: Titillium Web 700 (title), Lora 400 (body), 16px

## Required Interaction States
- Default: correct font/color
- Hover: title links get underline + accent color

## Reference
screenshot: Design Comuni homepage — "Notizie 18 mag 2022" + title

## Acceptance Criteria
1. "Notizie 18 mag 2022" label present with correct style
2. Title uses Titillium Web font at correct weight/size
3. Title color is #17324d (not default gray)
4. Visual parity verified at 375/768/1440

## Technical Fix
1. Add `font-titillium` class to title element
2. Set `text-[#17324d]` color
3. Ensure "Notizie 18 mag 2022" label is rendered
4. Check font loading (Google Fonts or local)

## Done Checklist
- [ ] Font imported (Titillium Web 700)
- [ ] Title element updated with correct classes
- [ ] Label "Notizie 18 mag 2022" added
- [ ] Visual parity test passed
- [ ] ux_audit passed