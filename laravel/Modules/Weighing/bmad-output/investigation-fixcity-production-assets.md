---
title: FixCity production asset investigation
status: resolved-in-source
---

## Evidence

- A: `GET https://fixcity.eu/assets/xot/header-actions-wrap.css` returned
  `404` with `Content-Type: text/html`, so the browser's MIME error is a
  consequence of the HTML 404 page, not malformed CSS.
- A: `GET https://fixcity.eu/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg`
  returned `404` HTML.
- A: `GET https://fixcity.eu/api/tickets/geojson` returned valid JSON with
  `total: 0` and an empty feature collection.
- A: the local theme output contained the sprite, but the source directory
  configured as Vite `publicDir` was absent. A generated output file is not a
  valid deployment source.
- B: the Filament Xot provider registered the module CSS directly by path;
  production could therefore emit a URL before the module asset was
  published.

## Root cause and resolution

The stable Design Comuni sprite is now versioned under
`Themes/Sixteen/resources/static`, which Vite copies to `public/` during the
normal build. The Xot Filament provider now publishes its CSS through the
existing `AssetAction` before registering the Filament asset URL.

The empty GeoJSON response is expected for the current production dataset and
is not an asset or frontend failure. The report-only CSP worker messages are
diagnostics and do not block execution. `UNSUPPORTED_OS` is external browser/
subsystem telemetry and has no matching application source in this repository.
