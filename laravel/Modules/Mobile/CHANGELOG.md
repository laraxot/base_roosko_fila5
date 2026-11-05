# Mobile Module Changelog

## 2026-10-07
- Removed again `Http/Controllers/Api/MobileController.php` and `app/Http/Controllers/MobileController.php`
  (Restaurant domain, no routes, no Restaurant module in this monorepo); resurrected by merge of snapshot d3cb4b9
- PHPStan `Modules/Mobile`: 23 errors -> 0; details in `docs/stories/1.12.phpstan-level-max-mobile-cleanup.story.md`

## 2026-09-28
- Added CHANGELOG.md for demo readiness
- MobileController deleted (Folio+Volt pattern)
- Routes/api.php and routes/web.php emptied
- NativePHP mobile integration verified
- BMAD stories: 1.1, 1.7, 1.8, 1.9, 1.11
