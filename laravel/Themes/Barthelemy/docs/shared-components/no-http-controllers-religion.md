---
title: "no http controllers religion"
type: note
tags: [documentation]
created: 2026-09-26
updated: 2026-09-26
qmd: "no http controllers religion"
issues: []
discussions: []
status: draft
---

# No Http\Controllers — religione Laraxot/Fixcity

**Canon wiki:** [docs/wiki/rules/no-controllers-rule.md](../../../../../docs/wiki/rules/no-controllers-rule.md)

## Regola

```
MAI:  laravel/app/Http/Controllers/*.php
MAI:  Modules/{Nome}/app/Http/Controllers/*.php
```

## Nel tema Sixteen

- Endpoint HTTP → **non** nel tema; Folio nel modulo owner (`Modules/*/resources/views/pages/api/`).
- Esempio rating: [data-queableactions.md](data-queableactions.md) — sezione Folio/Volt, non Controller.
- Stack frontoffice: Folio + blocchi CMS + `map-lit` (modulo Geo), non route Controller.

## Riferimenti

- [STORY-107](../../../../../docs/stories/STORY-107-no-http-controllers-religion-standing-rule.md)
- [STORY-108](../../../../../docs/stories/STORY-108-cms-block-type-view-naming-convention.md)
- [cms-block-type-view-convention.md](../../../../../docs/wiki/rules/cms-block-type-view-convention.md)
- [folio-api-no-controllers.md](../../../../../docs/wiki/concepts/folio-api-no-controllers.md)
