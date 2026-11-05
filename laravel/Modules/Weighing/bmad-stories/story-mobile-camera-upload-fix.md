# BMAD Story — Mobile Camera Upload Fix

## Context
Ticket /t/2 (fixcity.eu): user uploaded photo from mobile camera (HEIC). After upload, page shows file name instead of image; photo section mispositioned.

## Analysis
- Root: PHP `upload_max_filesize=2M` rejected camera photo; `imagick` missing → HEIC not processable; storage symlink missing → media URL broken
- UI: photo section above meta, no map, image URL fails (`getFullUrl()` broken with missing symlink)
- Design: reference expects map + photos under it (layout parity at 375/768/1440)

## Decision (D13/D14)
- Fix server-side (imagick + PHP limits + storage symlink)
- Fix layout: add map (mappa-static component) + move images below map
- Fix image rendering: use reliable URL method + add error fallback
- Document in Modules/Media/docs, Modules/UI/docs, Themes/ docs

## Execution Status
- [x] Imagick installed + activated (cli/fpm)
- [x] PHP limits 20M
- [x] Laravel config: media-library imagick + 50MB
- [x] Storage symlink fixed
- [x] 4 docs written (Second Brain)
- [ ] Layout fix (map + image order)
- [ ] Image rendering fix (getUrl/fallback)

## Verification Rule
Run at 375px, 768px, 1440px: photo renders, map visible, photo under map.
