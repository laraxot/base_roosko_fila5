---
id: "STORY-novita-design-comuni-visual-parity"
name: "Design Comuni Visual Parity — novita"
type: story
status: open
github_issue: "https://github.com/italia/design-comuni-pagine-statiche"
discussion: "https://github.com/italia/design-comuni-pagine-statiche/discussions"
priority: critical
module: Sixteen
pages_target:
  - "/it/tests/novita"
reference_url: "https://italia.github.io/design-comuni-pagine-statiche/sito/novita.html"
stack: "TailwindCSS + Alpine.js + Lit + DaisyUI + Flowbite"
no_bootstrap_italia: true
gate:
  html_parity_min_percent: 90
  visual_parity_min_percent: 95
---
## Objective
Achieve 90% HTML parity and 95% visual parity for page "novita" vs design-comuni-pagine-statiche reference.

## Block inventory
Blocks mapped to pub_theme::components.blocks.* (Tailwind, no Bootstrap Italia).

## Visual parity checks
- Colors: primary #007a52, neutral #191919
- Typography: Inter / modular scale
- States: :hover, :focus-visible, :active, :disabled
- Elevation: named shadow levels only
- Signature element: one per page (badge/stamp/glyph)
- Motion: prefers-reduced-motion fallback
