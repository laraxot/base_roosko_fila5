---
title: "Filament Blueprint — Theme TwentyOne"
type: filament-blueprint
status: active
theme: TwentyOne
created: 2026-10-16
updated: 2026-10-16
tags: [bmad, filament, blueprint, theme, twentyone]
qmd: "filament blueprint theme twentyone widget mount no resource"
related:
  - ../bmad-planning.md
  - ../bmad-brainstorming.md
---

# Scopo

Il tema **TwentyOne** non possiede Resource Filament. Compone pagine Folio/Volt e puo
montare widget Filament **dei moduli owner** con props canoniche.

## Consentito

- Mount `@livewire` di widget modulo con props tipizzate
- View Blade del tema che passano solo dati gia autorizzati dal modulo
- Stack FO: Tailwind + DaisyUI + Flowbite + Lit + Alpine (no Bootstrap target)

## Vietato

- `Themes/TwentyOne/app/Filament/Resources/*`
- Duplicare policy/Actions nel tema
- Nascondere sezioni FO quando il contratto owner dice "sempre montato"

## Widget tipici consumati

- nessuna Resource; solo eventuale mount widget owner

## Related

- Planning: [bmad-planning.md](../bmad-planning.md)
- Brainstorming: [bmad-brainstorming.md](../bmad-brainstorming.md)
