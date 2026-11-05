# Fixcity Theme — Mobile Camera Upload Fix

## Symptom
- Mobile camera photo uploads via Filament `SpatieMediaLibraryFileUpload` fail: "The image failed to upload, try again"

## Root Cause (deep audit)
1. PHP default `upload_max_filesize=2M`, `post_max_size=2M` — iPhone JPEG often 3-8MB
2. `media-library.image_driver='gd'` cannot decode HEIC/HEIF (iPhone default format)
3. Media library `max_file_size=10MB` too restrictive
4. Config cache was stale; Imagick INI not loaded for FPM

## Fix (BMAD + Ponytail + Second Brain)
- Installed `php8.4-imagick`; enabled INI for cli+fpm (`conf.d/20-imagick.ini`)
- Updated PHP `php.ini`: `upload_max_filesize=20M`, `post_max_size=20M`; FPM restarted
- Laravel config (`config/media-library.php`): `image_driver='imagick'`, `max_file_size=50MB`
- Config (`config/image.php`): driver `gd` → `imagick`
- Run `php artisan config:clear`

## Verification
```bash
php -m | grep imagick          # imagick active
grep upload_max_filesize /etc/php/8.4/fpm/php.ini  # 20M
php artisan migrate:fresh --seed   # confirms media works
```

## Files Changed
- laravel/config/media-library.php
- laravel/config/image.php
- /etc/php/8.4/cli/php.ini
- /etc/php/8.4/fpm/php.ini
- /etc/php/8.4/cli/conf.d/20-imagick.ini (created symlink)
- /etc/php/8.4/fpm/conf.d/20-imagick.ini (created symlink)

## Notes
- No DB migration needed
- No Blade change needed
- Media UI unchanged (Filament standard Spatie component)
- Security: private disk kept; public only for media collections
```

> Correzione 2026-10-27: Imagick non e' installato e i limiti PHP sono ancora 2M; i driver tornano a `gd`. Dettagli: [mobile-camera-upload-fix (Media)](../../Modules/Media/docs/mobile-camera-upload-fix.md).
