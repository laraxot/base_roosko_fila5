---
title: Sixteen typography research — Design Comuni + Vite
status: implemented
updated: 2026-10-19
stack: Tailwind + Alpine + Lit + DaisyUI + Flowbite
---

# Research findings

## Sources

- [Bootstrap Italia — Tipografia](https://italia.github.io/bootstrap-italia/docs/organizzare-i-contenuti/tipografia/)
- [Laravel — Vite, working with fonts](https://laravel.com/framework/docs/vite#working-with-fonts)
- [Vite — Static asset handling](https://vite.dev/guide/assets)
- [Design Comuni — homepage reference](https://italia.github.io/design-comuni-pagine-statiche/sito/homepage.html)
- [Design Comuni — source repository](https://github.com/italia/design-comuni-pagine-statiche)

## Decisions

1. Sixteen uses the Design Comuni type families: Titillium Web for UI/content,
   Lora only for explicitly editorial serif content, and Roboto Mono for code,
   numbers and technical values.
2. Fonts remain local under `resources/fonts/`. CSS references them with relative
   `url()` values so Vite fingerprints and publishes them with the CSS asset.
3. No Google Fonts, Bunny Fonts, Bootstrap Italia CSS or Bootstrap Italia runtime
   is required by the typography contract.
4. Body text follows the reference rhythm: 16px/24px on small screens and
   18px/28px from 576px upward. Heading line-height is 1.2 with responsive
   sizes left to the existing component-specific layout rules.
5. The implementation is a CSS contract consumed by Tailwind utilities and the
   existing Alpine/Lit/DaisyUI/Flowbite components; no new dependency is added.

## Verification contract

- Search source CSS and Blade layouts for external font URLs.
- Build the Sixteen Vite entry and inspect the generated manifest for local
  `woff2` assets.
- Check typography at 375px, 768px and 1440px on homepage, authentication,
  listing and detail pages.
- Keep page-specific sizes where they are part of visual parity; the global
  contract owns family, body rhythm, code family and responsive base size.

## Verification correction

The first pass missed `/it/tests/homepage` because `app-test.css` was not a
Vite input. Laravel therefore served a stale/incorrect manifest entry, while
font URLs were emitted under `/build/assets` even though the theme is served
from `/themes/Sixteen`. The test layout also loaded remote Inter. The fixed
pipeline explicitly builds `app-test.css`, uses `base: '/themes/Sixteen/'`,
and loads only the local font contract; Playwright and Puppeteer now report
zero font 404s at 375px, 768px and 1440px.

## Verified implementation finding

Vite's nested deployment base is part of the font contract. Without
`base: '/themes/Sixteen/'`, relative font imports were fingerprinted but the
compiled CSS pointed at `/build/assets`, producing browser 404s despite the
correct computed `font-family`. The generated theme CSS now points at
`/themes/Sixteen/assets` and direct browser checks load the requested weights.

The official homepage and local test page were measured at 375/768/1440:
Titillium Web body 16/24, h1 40/48 then 48/56, h2 32/40 then 40/48, h3
28/32 then 32/40, and Lora editorial paragraphs 16/24 then 18/28. The local
test page matches the heading contract and the browser reports no failed
resource requests.

