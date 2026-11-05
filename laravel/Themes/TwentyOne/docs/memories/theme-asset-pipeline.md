---
title: "theme asset pipeline"
type: note
tags: [documentation]
created: 2026-09-26
updated: 2026-09-26
qmd: "theme asset pipeline"
issues: []
discussions: []
status: draft
---

# Memory - TwentyOne Theme Asset Pipeline

- `@vite(..., 'themes/TwentyOne')` legge da `public_html/themes/TwentyOne/manifest.json`
- `npm run build` da solo non basta
- dopo ogni modifica frontend del tema servono `npm run build` e `npm run copy`
- se il CSS importa Filament, i path devono risalire fino a `laravel/vendor`, non a `Themes/TwentyOne/vendor`
- `x-section` del CMS cerca `pub_theme::components.sections.{slug}.v1`, quindi per `header` e `footer` devono esistere i wrapper `header/v1.blade.php` e `footer/v1.blade.php`
