---
id: ARCH-001-header-social-svg
type: architecture
title: Architecture — Header Social Icons (SVG vs Letters)
status: open
priority: critical
module: Sixteen
page: /it/tests/homepage
reference: https://italia.github.io/design-comuni-pagine-statiche/sito/homepage.html
stack: TailwindCSS + Alpine.js + Lit + DaisyUI + Flowbite (NO Bootstrap Italia)
---

# Architecture — Header Social Icons

## Root Cause
The header social section (`"Seguici su"`) renders **text labels** instead of SVG icons. The reference uses `<svg class="icon icon-white"><use href="...#it-facebook"></use></svg>` for each social platform. The local implementation probably uses `<span>Facebook</span>` or similar, losing the visual identity.

## Why This Happened
The component likely iterates over a data array but maps only the `label` field, ignoring the `icon` field. The SVG sprite path is either wrong or missing.

## Fix Plan
1. In `header.json`, ensure each social link has `icon` (e.g., `it-facebook`, `it-twitter`, `it-youtube`).
2. In the Blade component, render `<svg class="icon icon-white" aria-hidden="true"><use href="{{ asset('themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg#' . $social['icon']) }}"></use></svg>` with `aria-label="{{ $social['label'] }}"`.
3. Verify the sprite file exists at the expected path.
4. Test: `curl http://192.168.1.40:8000/it/tests/homepage | grep "it-facebook"` should return SVG markup.

## Verification
- Screenshot: `/tmp/fixcity-parity/current/homepage-local.png` vs `/tmp/fixcity-parity/current/homepage-ref.png`
- Check: social icons render as SVG, not text
- WCAG: `aria-label` on each icon link