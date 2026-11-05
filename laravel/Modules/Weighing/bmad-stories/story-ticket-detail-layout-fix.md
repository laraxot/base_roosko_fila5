# BMAD Story — Ticket Detail Layout Fix (Layout + Image)

## Problem
- Photo section shows file name text instead of rendered image (media URL broken / missing storage symlink)
- Photos should be below map (design parity: map → images)
- No map shown on /it/tickets/2

## Root Cause Analysis (Second Brain)
- Image URL: `getFullUrl()` relies on `public/storage` symlink; missing → broken image
- Layout: meta card only; reference requires map + images under it
- Media file exists (`storage/app/public/10/grcp...`) but not served

## Fix Applied (Ponytail / reuse existing)
- Created storage symlink (`public/storage → storage/app/public`)
- Added `<x-blocks::geo.mappa-static>` after meta card (reuses existing block, no new dependency)
- Moved `$images` card below map card
- Fixed `<img>`: `getUrl()` primary, `getFullUrl()` for link, `onerror` fallback to placeholder, `alt` preserved, `File:` caption preserved for diagnostics
- Added `mba-3 mb-3` spacing for parity

## Verification
- File renders: `php -m imagick` OK; symlink OK; `getUrl()` uses `public` disk
- Layout at 375/768/1440: meta → map → images sequential

## Decisions
- D13: Keep `enable_temporary_uploads_session_affinity=true` (security)
- D14: Use existing `mappa-static` block instead of new Google Maps embed
- D15: Preserve `File:` label (diagnostic) but make image primary
