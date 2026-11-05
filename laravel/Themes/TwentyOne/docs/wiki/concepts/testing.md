---
title: "Testing in TwentyOne"
type: concept
tags: [twentyone, theme, testing, pest, playwright]
created: 2026-06-05
updated: 2026-06-13
qmd: "TwentyOne theme testing Pest Playwright secondary theme"
issues:
  - "https://github.com/laraxot/base_fixcity_fila5/issues/52"
discussions:
  - "https://github.com/laraxot/module_fixcity_fila5/discussions/53"
related:
  - ./visual-testing-playwright-puppeteer.md
  - ../../../../Sixteen/docs/wiki/overviews/completion-roadmap.md
  - ../../../../Modules/Xot/docs/wiki/concepts/phpstan-pest-bridge-discipline.md
status: draft
---

# Testing in TwentyOne

Tema **secondario** rispetto a Sixteen (FO Fixcity owner). Stessi pattern Pest + PHPStan dei moduli.

## Pest PHP

```bash
cd laravel
./vendor/bin/pest Themes/TwentyOne/tests
```

## Visual / E2E

Riferimento: [visual-testing-playwright-puppeteer.md](./visual-testing-playwright-puppeteer.md)

## Completamento

- [ ] Allineare quality gate a level **max** (come `phpstan.neon` root)
- [ ] Evitare duplicazione componenti già in Sixteen — [ridondanze-hub](./ridondanze-hub-twentyone-xot.md)
- [ ] Quando Themes entrerà in scope PHPStan: tipizzare classi sotto `Themes/TwentyOne/app/`

FO produzione Fixcity → priorità [Sixteen completion-roadmap](../../../../Sixteen/docs/wiki/overviews/completion-roadmap.md).
