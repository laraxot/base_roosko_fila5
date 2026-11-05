---
title: Sixteen typography and Design Comuni visual QA
status: verified-with-tooling-limitation
date: 2026-10-19
---

## Scope

Compared the official Design Comuni homepage with `/it` and
`/it/tests/homepage` using the same Chromium executable through Puppeteer.
Viewports: 375, 768 and 1440 CSS pixels. The configured Playwright MCP was
also attempted, but cannot initialize because `/opt/google/chrome/chrome` is
not installed.

## Results

| Check | Result |
|---|---|
| HTTP status | 200 on all six local checks |
| Failed network resources | 0 after the Vite base-path fix |
| Body family | Titillium Web |
| Test h1 | 40/48 mobile, 48/56 from 768px |
| Test h2 | 32/40 mobile, 40/48 from 768px |
| Titillium Web weights | loaded when requested: 300, 400, 600, 700 |
| Lora weights | loaded when requested: 400, 700 |
| Header logo | white `logo-comune.svg`, no inversion filter |

## Root causes found

1. Vite used its default `/build/` base even though the theme manifest and
   generated assets are deployed below `/themes/Sixteen`. Browser computed
   styles still showed the intended family, masking the WOFF2 404s.
2. The Italian CMS header selected the black `logo-fixcity.svg`; the legacy
   header template also inverted the embedded image, making the white Design
   Comuni asset black when it was selected.
3. Earlier QA checked computed `font-family` and a different route, but did not
   assert `document.fonts`, failed network responses, or the fingerprinted URL
   path. That is why the mismatch escaped detection.

## Artifacts

Temporary screenshots were written to `/tmp/fixcity-logo-final-375.png` and
the `/tmp/fixcity-final-{home,tests}-{375,768,1440}.png` set. They are not
committed baselines; therefore this report is a measured smoke/parity check,
not a pixel-diff PASS against committed images.
