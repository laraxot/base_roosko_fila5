---
title: Blueprint — Sixteen typography alignment
status: implemented
track: Quick Flow
updated: 2026-10-19
---

# Objective

Make Sixteen follow the Design Comuni typography contract without introducing
Bootstrap Italia runtime or changing the existing Tailwind + Alpine + Lit +
DaisyUI + Flowbite stack.

## Dependency graph

`research` → `font-pipeline` → `typography-contract` → `template-cleanup` → `verification`

## Steps and exit criteria

1. **Research** — record official families, responsive body rhythm and Vite
   asset rules. Exit: sources and decisions are recorded in the research note.
2. **Font pipeline** — use the existing local `woff2` files and remove remote
   font loading from CSS and Blade layouts. Exit: no external font URL remains
   in source theme assets or layouts.
3. **Typography contract** — add one shared CSS contract for family, body
   rhythm, heading line-height and monospace/serif utilities. Exit: no new
   component-specific typography fork is introduced.
4. **Template cleanup** — authentication and guest layouts consume the Vite
   bundle only. Exit: no duplicate font `<link>` tags remain.
5. **Verification** — run diff checks and Vite build; inspect the generated
   manifest and verify 375/768/1440 responsive behavior. Exit: build succeeds
   and local font assets are emitted.

## Verification findings

The missed defect was a pipeline failure, not a CSS-family declaration:
`app-test.css` was absent from the Vite input list and Vite's default
`/build/assets` base did not match the copied theme directory. The browser
silently fell back after font 404s, and the remote Inter link in the test
layout obscured the issue. The blueprint now treats manifest paths, font
network responses, and computed font loading as release gates.

## Invariants

- No Bootstrap Italia package, CSS, JavaScript or CDN is added.
- Existing reusable, JSON-driven blocks remain untouched.
- Existing page parity rules may set a deliberate component size, but may not
  replace the Design Comuni font family with a third-party web font.
- Rollback is file-level: remove the new contract/imports and restore layout
  font links only if an external-font dependency is intentionally re-approved.

## QA correction and deployment detail

The first build verification exposed a deployment-path defect: Vite emitted
CSS font URLs as `/build/assets/...`, while this theme is served from
`/themes/Sixteen`. The theme Vite config now sets `base: '/themes/Sixteen/'`,
so CSS-imported fonts resolve to the same public subtree as the manifest.

The Italian CMS header also pointed to the black `images/logo-fixcity.svg` and
the legacy header template inverted the embedded image. The Italian section now
uses the white Design Comuni `logo-comune.svg`, and the inversion filter is
removed. Direct Puppeteer verification reports HTTP 200 and no failed resource
requests for `/it` and `/it/tests/homepage` at 375, 768 and 1440 pixels.

The configured Playwright MCP could not start because `/opt/google/chrome/chrome`
is missing; the same checks were run with Puppeteer against the installed
Playwright Chromium binary. This is a tool-environment limitation, not a page
failure.

