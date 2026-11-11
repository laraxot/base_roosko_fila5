---
title: "00 INDEX"
type: index
status: active
tags: [documentation]
created: 2026-09-26
updated: 2026-10-14
qmd: "twentyone theme docs indice stato non usato fixcity importato progetto predict classificazione cartelle"
issues: []
discussions: []
---

# 📚 Twenty One Theme - Documentation Index

**Path**: `laravel/Themes/TwentyOne/docs/`  
**Tema**: @Themes/TwentyOne

## Stato del tema (verificato 2026-10-14)

TwentyOne esiste come codice ma **non e' il tema di Fixcity**: e' un tema importato dal progetto Predict (mercati di previsione). Prove:

- `pub_theme` vale `Sixteen` in tutti e 4 i `laravel/config/**/xra.php`; `public_html/themes/` contiene solo il link `Sixteen`.
- `app/Actions/Markets/ResolveFeaturedMarketsGridDataAction.php` importa `Modules\Predict\Actions\Homepage\GetHomepageMarketCardsAction`; `Modules\Predict` ha 30 occorrenze in `app/` e `resources/views/`, ma `laravel/Modules/` non contiene `Predict`. `resources/views/components/predict/` ha 21 componenti (market-card, multi-outcome-card, trading-form, hero-betting e altri).
- Nel codice del progetto `Themes\TwentyOne` compare solo nell'elenco PSR-4 di `RegisterRuntimePsr4NamespacesAction`; `composer.json` ha `extra.laravel.providers` vuoto e `app/Providers/` non esiste.
- E' un submodule (`gitmodules.ini`, `git@github.com:laraxot/theme_twentyone_fila5.git`) con `.git` proprio; ultimo commit 2026-10-08.
- Dei 286 `.md` in `docs/`, 154 citano Predict e 16 citano Fixcity.

Classificazione delle cartelle:

| Cartella | Ruolo | Nota |
|---|---|---|
| radice (186 `.md`) | legacy del progetto Predict, con regole di tema ancora valide | 49 coppie differiscono solo per maiuscole o `_`/`-` (es. `GSAP_ANIMATIONS_GUIDE.md` / `gsap-animations-guide.md`): triage in DOC-004, non cancellare. Questo indice ne nomina 13 |
| `wiki/` (42) | locale, canonica per governance | `index.md`, `log.md`, `concepts/` |
| `predict/` (34), `kalshi-analysis/` (4) | legacy, estranea a Fixcity | analisi di prodotti di mercati di previsione |
| `Main_files/` (36), `screenshots/` (24), `raw/` (12) | evidence | riferimenti statici e immagini; `raw/` e' immutabile |
| `bmad/`, `phpstan/`, `i18n/`, `memories/`, `llm-wiki/`, `roadmap/`, `root-md-files/`, `outputs/` | supporto | dossier, note di processo |
| `stories/` | canonica (cronologia BMAD) | creata il 2026-10-14: [stories/2026-10-14-docs-studio-stato-e-ingressi.story.md](stories/docs-studio-stato-e-ingressi.story.md) |

Build reale (`package.json`): `npm run build` (`vite build`, uscita in `./public` con `manifest.json`) e `npm run copy` (copia `./public/*` in `public_html/themes/TwentyOne/`, cartella oggi assente).

## Regole Strutturali

- [Directory Structure Rules](../../Barthelemy/docs/directory-structure-rules.md) - vietati `lang/lang/` e `_docs/` nei temi (la regola vive nell'ex hub dei temi, ora `Barthelemy/docs`)

## 📄 Documenti

### Manuale utente
| File | Scopo |
|------|-------|
| [Manuale utente front office](../../../Modules/Predict/docs/manuale/01-visitatore.md) | **Per visitatori e giocatori**: schermate numerate, passo per passo (capitoli 1–2) |
| [manuale-uso-front-office.md](manuale-uso-front-office.md) | Scheda tecnica del front office (rotte verificate, flussi, a11y, errori) |
| [stories/front-office-user-manual-screenshots.story.md](stories/front-office-user-manual-screenshots.story.md) | Story BMAD: manuale utente FO con schermate + correzioni del percorso del giocatore |
| [manuale-uso-front-office.pdf](manuale-uso-front-office.pdf) | Stessa guida in PDF stampabile |
| [stories/front-office-manual-and-a11y.story.md](stories/front-office-manual-and-a11y.story.md) | Story BMAD: manuale + fix a11y/404/soft-404 |

### Architettura
| File | Scopo |
|------|-------|
| ARCHITECTURE_UX_IMPROVEMENTS.md | Architecture & UX |
| HOMEPAGE_ARCHITECTURE.md | Homepage architecture |
| HEADERNAV_CMS_ARCHITECTURE.md | Header/Nav CMS |
| FOOTER_ARCHITECTURE.md | Footer architecture |
| [CMS document shell](../../../Modules/Cms/docs/foot-component-architecture.md) | Layout owns `</body>` and `</html>`; `cms::foot` renders scripts and stacks |

### Filament
| File | Scopo |
|------|-------|
| FILAMENT_5_IMPLEMENTATION.md | Filament v5 setup |
| FILAMENT_TABLES_SETUP.md | Tables setup |

### GSAP & Animations
| File | Scopo |
|------|-------|
| GSAP_ANIMATIONS_GUIDE.md | GSAP guide |
| GSAP_SCROLLTRIGGER_CONFIGURATION.md | ScrollTrigger config |

### Frontend
| File | Scopo |
|------|-------|
| BUILD_PROCESS.md | Build process |
| CODE_QUALITY_ANALYSIS.md | Code quality |
| PREDICT_DETAIL_AGNOSTIC_CONTRACT.md | Contratto del detail page agnostico per container0/slug0 |
| ORDER_BOOK_MULTI_OUTCOME_CONTRACT.md | Contratto UI: l'order book itera outcome, non tipi yes/no |
| wiki/concepts/config-tenant-boundary.md | Boundary: il tema non possiede config tenant/locali |
| wiki/concepts/authorization-boundary.md | Boundary: il tema riusa Gate/Policy e non possiede permessi o team |

## 🔗 Riferimenti

- [Predict Module](../../Modules/Predict/docs/00-INDEX.md) _(target mancante, verificato 2026-10-14)_ - Main module
- [Xot Module](../../../Modules/Xot/docs/00-index.md) - Base classes
- [AGENTS.md](../../../AGENTS.md) - Project guidelines

---

**Ultimo aggiornamento dell'elenco dei documenti**: 2026-07-01. Stato del tema e classificazione delle cartelle: 2026-10-14 (sezioni in alto).
