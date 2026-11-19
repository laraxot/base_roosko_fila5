---
title: "Componenti di modulo facoltativi nel layout"
type: guide
created: 2026-11-17
updated: 2026-11-17
status: active
tags: [layout, blade, componenti, agnostico]
issues: []
discussions: []
---

# Componenti di modulo facoltativi nel layout

TwentyOne è usato da più progetti (Predict, Passaparola, ...), ognuno con moduli diversi.

## Il problema

Blade risolve i tag `<x-namespace::nome>` **in compilazione**. Se il layout contiene
`<x-predict::daily-bonus />` e il modulo Predict non c'è, la vista non compila e **ogni pagina**
va in errore, anche quando il componente è dentro `@auth`.

## La regola

Nel layout e nei blocchi condivisi non si scrivono componenti di modulo direttamente. Si passa da
`Themes\TwentyOne\Actions\Layout\ResolveAvailableComponentsAction`, che tiene solo i componenti
registrati (vista anonima `namespace::components.nome` o classe nel namespace di componenti del
modulo), e li si mostra con `<x-dynamic-component>`:

```blade
@foreach (app(\Themes\TwentyOne\Actions\Layout\ResolveAvailableComponentsAction::class)->execute(['predict::daily-bonus']) as $component)
    <x-dynamic-component :component="$component" />
@endforeach
```

Dove Predict c'è, il comportamento non cambia. Dove manca, il componente semplicemente non compare.

Controllo automatico: `tests/Unit/LayoutOptionalModuleComponentsTest.php`.

## Ancora da rendere agnostico

- `components/blocks/hero/cinematic.blade.php` e `filament/widgets/predict-table/item.blade.php`
  usano `<x-predict::...>`: sono blocchi specifici di Predict, da usare solo dove Predict c'è.
- `layouts/mobile_tabs.blade.php` ha voci pensate per Predict (mercati, classifica): andrebbero
  prese dal CMS come header e footer.
