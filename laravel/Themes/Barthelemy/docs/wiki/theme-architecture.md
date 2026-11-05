---
created: 2026-09-26
updated: 2026-09-26
issues: []
discussions: []
title: "Barthelemy Theme - Architecture Guide"
type: "architecture"
tags: ["theme", "barthelemy", "frontend", "volt"]
date: "2026-06-13"
qmd: "barthelemy-architecture"
github_issue: ""
github_discussion: ""
status: stale
---

> Nota 2026-10-14 (docs-audit): descrive una struttura del tema Barthelemy che non esiste: nel tema ci sono solo package.json, .releaserc.json, scripts/ci e docs (nessun app/, config/, public/, resources/), e Barthelemy non e' il tema principale (pub_theme e' Sixteen); lo stato '70% completato' e' privo di prova. Contenuto non riscritto, verificare prima di usarlo.

# Barthelemy Theme - Architecture Guide

## Overview

Tema principale FixCity Fila5 - Tema Filament-based per backoffice e frontend.
Stato: 🔄 In sviluppo - 70% completato

## Struttura

```
Themes/Barthelemy/
├── app/
│   └── Providers/
│       └── ThemeServiceProvider.php
├── config/
│   └── theme.php
├── public/
│   ├── css/
│   ├── js/
│   └── images/
├── resources/
│   ├── css/
│   ├── js/
│   └── views/
│       ├── components/
│       ├── layouts/
│       └── pages/
└── docs/wiki/ (this directory)
```

## Pattern Componenti

### Volt Components

```php
<?php
// resources/views/components/segnalazione-card.blade.php

use function Livewire\Volt\state;
use Modules\Fixcity\Models\Segnalazione;

state(['segnalazione']);

?>

<div class="segnalazione-card">
    <h3>{{ $segnalazione->titolo }}</h3>
    <span class="status-{{ $segnalazione->status }}">
        {{ $segnalazione->status_label }}
    </span>
</div>
```

### Folio Pages

```php
<?php
// resources/views/pages/segnalazioni/index.blade.php

use function Laravel\Folio\name;
use Modules\Fixcity\Actions\ListSegnalazioniAction;

name('segnalazioni.index');

$action = app(ListSegnalazioniAction::class);
$segnalazioni = $action->execute(['per_page' => 20]);

?>

<x-layouts.app>
    @foreach($segnalazioni as $segnalazione)
        <x-segnalazione-card :segnalazione="$segnalazione" />
    @endforeach
</x-layouts.app>
```

## Integrazione Moduli

### Fixcity Module
- Resource: `SegnalazioneResource` (backoffice)
- Pages: `segnalazioni/*` (frontend)
- Components: `segnalazione-card`, `segnalazione-form`

### Geo Module
- Component: `map-component`
- Integration: geocoding in form segnalazioni

### UI Module
- Components: `ui-button`, `ui-modal`, `ui-table`
- Styling: Tailwind config esteso

## TODO Frontend

### Componenti Volt
- [ ] `resources/views/components/map-interactive.blade.php`
- [ ] `resources/views/components/segnalazione-form.blade.php`
- [ ] `resources/views/components/attachment-upload.blade.php`
- [ ] `resources/views/components/status-timeline.blade.php`

### Pages Folio
- [ ] `resources/views/pages/segnalazioni/create.blade.php`
- [ ] `resources/views/pages/segnalazioni/[id].blade.php`
- [ ] `resources/views/pages/dashboard.blade.php`
- [ ] `resources/views/pages/profile.blade.php`

### Styling
- [ ] Responsive mobile (currently desktop-only)
- [ ] Dark mode support
- [ ] Print styles for reports

## Best Practices

### 1. DRY Components
```php
// ❌ Duplicazione
<x-button class="bg-blue-500">Salva</x-button>
<x-button class="bg-blue-500">Invia</x-button>

// ✅ Componente riutilizzabile
<x-ui-button variant="primary">Salva</x-ui-button>
<x-ui-button variant="primary">Invia</x-ui-button>
```

### 2. Data Flow
```
Folio Page
  → Action (business logic)
    → Model (data)
      → Component (presentation)
```

### 3. Responsive
```blade
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3">
    <!-- Contenuto -->
</div>
```

## Collegamenti

- [Project Roadmap](../../../../Modules/Activity/docs/wiki/project-roadmap.md)
- [Xot Best Practices](../../../Modules/Xot/docs/wiki/PHPSTAN-BEST-PRACTICES.md)
