# Sixteen — mobile header whitespace fix

status: done
scope: single responsive defect
method: BMAD investigation + Filament Blueprint review + Second Brain capture

## Symptom

At 375px and tablet widths, the homepage showed a visible white strip between
the Design Comuni header and the first hero block. The expected geometry is a
direct transition from the green header to the homepage hero.

## Evidence and cause

- **A — Puppeteer before:** `#main-container` started 18px below the header.
- **A — DOM inspection:** the page was the homepage (`.fc-home`), while the
  global ticket spacing rule still applied `padding-top: 1.125rem`.
- **B — source tracing:** the spacing belongs to the shared shell and must not
  be removed from ticket/detail pages, where it protects page headings.

## Minimal change

`resources/css/app/13-final-runtime-overrides.css` now resets
`#main-container` padding only when `body:has(.fc-home)` and only below 992px.
`header-fix.css` keeps the navigation wrapper's desktop minimum height but
does not reserve the desktop height on mobile/tablet.

## Blueprint review gate

1. Shared caller impact checked: homepage, ticket pages, header/menu overlay.
2. No Blade/API/data contract changed; reusable block views remain untouched.
3. Rollback is limited to the two CSS rules.
4. Verification matrix: 375x812, 768x1024, 1440x900; HTTP, overflow, console,
   font family, logo and header-to-hero distance.

## Result

After `npm install --include=dev && npm run build`, the measured header-to-hero
distance is 0px at all three viewports and horizontal overflow is 0px.
