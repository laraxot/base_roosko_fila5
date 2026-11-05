---
title: "filament admin"
type: note
tags: [documentation]
created: 2026-09-26
updated: 2026-09-26
qmd: "filament admin"
issues: []
discussions: []
status: draft
---

# Filament Admin Screenshot Analysis

**Files:** 
- `Themes/TwentyOne/docs/screenshots/filament-admin-light.png`
- `Themes/TwentyOne/docs/screenshots/filament-admin-dark.png`
**Date:** 2026-03-23
**URL:** http://predict.local/it/admin/predicts

## Image Info
- **Size:** 1280x720 pixels
- **Format:** PNG
- **File Size:** ~56KB each

## Color Analysis

### Light Mode

| Area | Color (RGB) | Tailwind Class |
|------|-------------|---------------|
| Top header | srgb(242,242,243) | bg-slate-50 |
| Table header | srgb(24,24,27) | bg-zinc-900 |
| Table row 1 | srgb(24,24,27) | bg-zinc-900 |
| Table row 2 | srgb(24,24,27) | bg-zinc-900 |

### Dark Mode

| Area | Color (RGB) | Tailwind Class |
|------|-------------|---------------|
| Top header | srgb(242,242,243) | bg-slate-50 |
| Table header | srgb(24,24,27) | bg-zinc-900 |
| Table row 1 | srgb(24,24,27) | bg-zinc-900 |
| Table row 2 | srgb(24,24,27) | bg-zinc-900 |

## Current Issues Found

### 1. Table Uses Zinc-900 (NOT Slate-700)
The Filament table widget currently uses:
- Background: `srgb(24,24,27)` which is zinc-900
- But our CSS file (`filament-widgets.css:24`) was incorrectly documented as slate-700

### 2. Hardcoded Dark Background
The table background is hardcoded to zinc-900 regardless of light/dark mode toggle.

### 3. CSS Override Applied
We modified `filament-widgets.css` to:
1. Make widget container transparent
2. Add glassmorphism effect with backdrop blur
3. Add rounded corners and border

## Recommended Fixes

### Current CSS (applied)
```css
.filament-table-widget,
.filament-widget,
.fi-wi-widget,
.fi-ta-content-grid,
.fi-ta-ctn,
.fi-widget,
[data-livewire-id],
.fi-ta,
[class*="fi-ta"],
[class*="filament-table"] {
    background-color: transparent !important;
    background: transparent !important;
    --tw-bg-opacity: 0 !important;
    border: none !important;
    box-shadow: none !important;
}

.fi-ta-container {
    @apply bg-slate-900/80;
    @apply dark:bg-slate-900/60;
    @apply backdrop-blur-md;
    @apply border border-white/5;
    @apply rounded-2xl;
    @apply overflow-hidden;
}
```

### Theme Switcher Configuration
Added `darkMode: 'class'` to TwentyOne `tailwind.config.js` to enable proper dark mode support.

## Navigation Elements Found

From OCR:
- PredictMarket (logo)
- Prediction market con crediti virtuali
- Navigation: Prodotto, Azienda, Legale, Classifica, Blog
- Admin action: Crea Mercato

## Next Steps

1. Verify the CSS changes take effect
2. Test dark mode toggle
3. Check if glassmorphism renders correctly
4. Consider matching homepage colors exactly
