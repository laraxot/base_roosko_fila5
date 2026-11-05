---
title: "TwentyOne: studio del tema reale e correzione degli ingressi docs"
type: story
status: done-with-open-decisions
created: 2026-10-14
updated: 2026-10-14
theme: TwentyOne
owner: theme:TwentyOne
related:
  - ../00-INDEX.md
  - ../README.md
  - ../../../Sixteen/docs/bmad/stories/docs-studio-stato-e-ingressi.story.md
  - ../../../../../bmad-output/stories/docs-organization/DOC-010.module-theme-quality.story.md
tags: [docs, twentyone, bmad, ingressi, verifica, predict, doc-010, doc-002]
qmd: "twentyone docs studio stato ingressi non usato fixcity importato progetto predict Modules/Predict submodule pub_theme sixteen"
---

# TwentyOne: studio del tema reale e correzione degli ingressi docs

Fase BMAD: manutenzione documentale (DOC-010, DOC-002). Cartella `stories/` creata solo per questa story: il tema non ne aveva.

## Richiesta
Stabilire se TwentyOne e' un tema vivo o legacy con prove, e correggere `README.md` e `00-INDEX.md` solo con fatti verificati, senza cancellare, spostare o rinominare nulla.

## Analisi
TwentyOne ha codice (a differenza di Barthelemy e Meetup) ma **non e' il tema di Fixcity**:

- `'pub_theme' => 'Sixteen'` in tutti e 4 i `laravel/config/**/xra.php`; `public_html/themes/` contiene solo il link `Sixteen`.
- E' un submodule (`gitmodules.ini`, `git@github.com:laraxot/theme_twentyone_fila5.git`) con `.git` proprio; ultimo commit 2026-10-08 ("Add initial workspace configuration").
- E' un tema del progetto Predict (mercati di previsione): `app/Actions/Markets/ResolveFeaturedMarketsGridDataAction.php` e `app/Actions/Hero/ResolveCinematicHeroDataAction.php` importano `Modules\Predict\...`; `Modules\Predict` ha 30 occorrenze in `app/` e `resources/views/`, ma `laravel/Modules/` non ha `Predict`. `resources/views/components/predict/` ha 21 Blade. Dei 286 `.md` in `docs/`, 154 citano Predict e 16 citano Fixcity.
- `Themes\TwentyOne` compare nel codice del progetto solo in `Modules/Xot/app/Actions/Composer/RegisterRuntimePsr4NamespacesAction.php` (elenco PSR-4); `composer.json` ha `extra.laravel.providers` vuoto e `app/Providers/` non esiste.
- Contenuto reale: `app/` 2 Actions; `resources/views` 264 file (layouts, components, pages Folio, 17 pagine con Volt); `resources/js` con script GSAP (ma `gsap` non e' in `package.json`) e `tradingview-chart.js`; `lang/` en, it; `tests/Unit` 2 test sul contratto di mount Folio; `public/` con asset compilati e `manifest.json`. Build: `npm run build` (`vite build`, uscita `./public`) e `npm run copy` (verso `public_html/themes/TwentyOne/`, cartella oggi assente).

Cosa era falso negli ingressi:

| File | Affermazione | Realta' |
|---|---|---|
| `README.md` | struttura con `Main_files/` nella radice del tema | `Main_files/` e' dentro `docs/`; la radice ha anche `lang/`, `tests/`, `composer.json`, `theme.json` e altri |
| `README.md` | "Laravel 11.x (12-ready)", "PHP >= 8.3", "Filament 4.x" | host: PHP `^8.4`, Laravel `^13.0`; `composer.json` del tema richiede Filament `^5.0`. "Node >= 18 / NPM >= 9" non e' dichiarato in `package.json` (nessun `engines`) |
| `README.md` | nessuna menzione di Predict ne' dello stato | aggiunto |
| `00-INDEX.md` | "Predict Module" come "Main module" | modulo assente da questo repository |
| `00-INDEX.md` | link a `../../docs/directory-structure-rules.md` | `laravel/Themes/docs` non esiste piu': la regola e' in `Barthelemy/docs/directory-structure-rules.md` (rinomina `bc58be108`) |
| `00-INDEX.md` | frontmatter duplicato in fondo al file | rimosso, il testo "Ultimo Aggiornamento" conservato come frase |

## Modifiche (solo `laravel/Themes/TwentyOne/docs/`)
- `README.md`: sezione "Stato", struttura e requisiti corretti; `type: readme`, `status: draft` (il resto del file, installazione e contributi, non e' stato rivisto).
- `00-INDEX.md`: sezione "Stato del tema" con prove, tabella di classificazione delle cartelle (radice legacy Predict con 49 coppie che differiscono solo per maiuscole o `_`/`-`, `wiki/` canonica, `predict/` e `kalshi-analysis/` legacy, evidence, supporto), build reale; link ripuntato; frontmatter completo.
- `wiki/index.md`, `wiki/log.md`: link ripuntati (4: `ai-harness-theme-discipline.md` x2, `xotbase-check.md`, `coordinate-picker-state-binding-rule.md`) o annotati `_(target mancante, verificato 2026-10-14)_` (6); voce datata in `wiki/log.md`.

## Verifica
`docs-organizer.py --busy-minutes 0 links ... --by-name` sui file toccati (`00-INDEX.md`, `wiki/index.md`, `wiki/log.md`, `README.md`): 11 -> 6 eventi (misura finale con la story creata: 0 eventi sulla story); i 6 rimasti sono annotati (`llm-wiki.txt` x2, `Modules/Predict/docs/00-INDEX.md`, `./sources/`, `kinetic-web-design.md`, `coordinate-picker-filament5-save-pattern.md` che esiste solo in copie diverse). Perimetro intero: 150 -> 145. Un controllo indipendente non trova link non annotati rotti nei file toccati.

## Decisioni
- Classificato "non usato da Fixcity, importato da Predict": nessun file toccato oltre agli ingressi; nessun `superseded` sui duplicati (DOC-004 richiede revisione).
- `status: draft` sul README: affermo solo cio' che ho verificato.

## Aperto
- Decidere se il submodule TwentyOne resta nel monorepo Fixcity o va archiviato (nessun uso trovato).
- `gsap` importato ma non dichiarato in `package.json`: da risolvere se il tema viene mai costruito qui.
- 49 coppie di duplicati per maiuscole in radice; 67 file del tema hanno link non risolti (150 eventi).

## Boy scout 2026-10-22

Tema Predict su base FixCity senza `Modules/Predict`. Corretti
`ResolveFeaturedMarketsGridDataAction` (`$this__` → `$this->tx`) e
`ResolveCinematicHeroDataAction` (niente import hard di Predict; `class_exists` + fallback).
PHPStan actions: 74 → 0 (residuo Safe file_get_contents nei test mount).
