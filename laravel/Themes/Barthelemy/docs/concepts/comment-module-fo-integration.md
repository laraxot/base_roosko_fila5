---
issues: []
discussions: []
title: "Tema FO — integrazione modulo Comment via Filament widgets"
type: concept
module: Comment
tags: [theme, comment, filament, widget, fo, xotbase]
created: 2026-06-10
updated: 2026-06-10
qmd: "theme comment filament CommentsWidget XotBase ticket article integration"
related:
  - ../../../../Modules/Comment/docs/wiki/concepts/fo-filament-widgets-no-livewire.md
  - ../../../Modules/Xot/docs/wiki/concepts/xotbase-filament-widget-hierarchy.md
status: draft
---

# Comment nel tema — Filament widgets

## Embed

```blade
@livewire(\Modules\Comment\Filament\Widgets\Commentable\CommentsWidget::class, [
    'model' => $record,
    'readOnly' => false,
])
```

- Fixcity: `filament/infolist/ticket-comments.blade.php`
- TwentyOne: `pages/articles/[slug].blade.php`
- No `<livewire:comments>` — FQCN obbligatorio

## Regole Laraxot (tema + modulo)

1. **No Livewire diretto** — niente `Http/Livewire`; solo widget in `Modules\Comment\Filament\Widgets\{Commentable|Comment|Mention}\`
2. **No Filament vendor** — mai `extends Filament\Widgets\Widget`; minimo `XotBaseWidget`, FO commenti su `XotBaseSchemaWidget`

## Riferimenti

- [FO commenti widget](../../../../Modules/Comment/docs/wiki/concepts/fo-filament-widgets-no-livewire.md)
- [Gerarchia XotBase*](../../../Modules/Xot/docs/wiki/concepts/xotbase-filament-widget-hierarchy.md)

## UI widget

Opzioni FO passate come `ui` array verso [CommentWidgetUiData](../../../../Modules/Comment/docs/concepts/widget-ui-spatie-data.md); legacy `readOnly`/`noReplies` ancora accettati su CommentsWidget mount.

## Profilo utente (main_module)

FO che chiama `XotData::getProfileModel()` richiede `profiles.uuid` su connessione Fixcity — vedi [profiles-uuid-contract](../../../../Modules/Fixcity/docs/wiki/concepts/profiles-uuid-contract.md).
