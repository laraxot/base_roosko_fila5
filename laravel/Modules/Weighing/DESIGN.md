# Design System

## Overview

Fixcity's public frontend follows Design Comuni and Bootstrap Italia conventions through the Sixteen theme. The visual system is restrained and task-focused: it should feel like a trustworthy municipal service rather than a generic commercial application.

## Color

- Primary civic green: `#007A52`
- Dark civic green: `#00402B`
- Primary text: `#191919`
- Surfaces: white and neutral backgrounds from the existing theme
- Status colors: semantic values must come from the canonical domain enum or exported data contract

Color is never the only carrier of meaning. Pair status colors with text, icons, patterns or accessible names.

## Typography

- Primary family: Titillium Web
- Body baseline: 16px
- Use the existing Design Comuni scale and weights
- Keep labels, buttons and navigation familiar and readable

## Components

- Prefer Filament components where the Filament-first rule applies.
- Public page structure and visual parity belong to the Sixteen theme.
- Reusable map behavior belongs to the Geo module.
- Preserve familiar controls, visible focus states and touch targets of at least 44px.

## Layout

- Responsive behavior is structural: sidebars collapse, map overlays remain compact and controls do not overlap.
- Use the existing spacing, radius and z-index vocabulary.
- Avoid decorative cards or overlays that reduce available task space.

## Caution: CSS specificity debt

`civic-design-global.css` and `civic-design-visual-fix.css` carry ~300 combined
`!important` declarations — they are specificity patches over the base theme, not
clean tokens. Prefer extending `civic-design-theme.css` custom properties over
adding another `!important` override; if a new style needs `!important` to win,
that's a signal the underlying selector specificity should be fixed instead.

## Motion

- Motion communicates state changes only.
- Keep transitions short and respect `prefers-reduced-motion`.
- Do not move marker or cluster anchors on hover.

## References

- `laravel/Themes/Sixteen/resources/css/civic-design-global.css`
- `laravel/Themes/Sixteen/resources/css/civic-design-theme.css`
- `laravel/Themes/Sixteen/resources/css/civic-design-global-fixes.css`
- `laravel/Themes/Sixteen/resources/css/civic-design-visual-fix.css`
- `laravel/Themes/Sixteen/resources/css/thematic/civic-design-components-bundle.css`
- `laravel/Themes/Sixteen/resources/css/style-apply.css`
- `laravel/Themes/Sixteen/docs/wiki/index.md`
- `laravel/Themes/Sixteen/docs/wiki/concepts/design-comuni-font-tokens.md`
- `laravel/Themes/Sixteen/docs/wiki/concepts/fo-pa-tokens-uniformity.md`
- https://italia.github.io/design-comuni-pagine-statiche/
