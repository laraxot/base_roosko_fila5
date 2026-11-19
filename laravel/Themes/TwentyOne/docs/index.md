---
title: "TwentyOne Theme — Docs Index"
type: index
tags: [twentyone, theme, docs, index]
created: 2026-09-03
updated: 2026-09-03
---

# TwentyOne Theme — Indice documentazione

Indice organizzato per argomento di `Themes/TwentyOne/docs/`. Nessun file esistente e' stato
rinominato o cancellato: i documenti duplicati o superati sono raggruppati in
[Storico / da consolidare](#storico--da-consolidare) invece di essere rimossi.

Indici correlati gia' esistenti (non sostituiti da questo file):
- [00-INDEX.md](./00-INDEX.md) — indice storico precedente, per argomento architettura/Filament/GSAP
- [wiki/index.md](./wiki/index.md) — second brain / wiki LLM del tema (concepts, entities, decisions, rules...)
- [raw/index.md](./raw/index.md) — layer "raw" (fonti immutabili) e regola raw vs wiki
- [predict/README.md](./predict/README.md) — indice della documentazione Predict specifica del tema
- [phpstan/README.md](./phpstan/README.md) — nota su errori Vite manifest (vedi anche sezione Build)

## Overview e policy documentali

- [README.md](./README.md) — presentazione tema, installazione, build, struttura
- [readme-en.md](./readme-en.md) — presentazione in inglese
- [docs-root-policy.md](./docs-root-policy.md) — regola: unico root documentale `docs/`, niente `_docs/`
- [PROJECT-STRUCTURE.md](./project-structure.md) — layout directory del tema
- [ON-DEMAND-PATTERN.md](./ON-DEMAND-PATTERN.md) — pattern di caricamento efficiente della conoscenza
- [QMD-SETUP.md](./QMD-SETUP.md) — configurazione ricerca locale (QMD)
- [PERFORMANCE-OPTIMIZATION.md](./PERFORMANCE-OPTIMIZATION.md) — metriche e best practice performance

## Metodologia e qualita' del processo

- [bmad-methodology.md](./bmad-methodology.md) — metodologia BMAD applicata al tema
- [gsd-methodology.md](./gsd-methodology.md) — metodologia GSD
- [GSD_WORKFLOW.md](./gsd-workflow.md) — workflow operativo GSD
- [error-handling-process.md](./error-handling-process.md) — processo di gestione errori
- [quality-tools.md](./quality-tools.md) — strumenti di qualita' usati nel tema
- [actions.md](./actions.md) — azioni (Spatie Queueable Actions) del tema
- [CODE_QUALITY_ANALYSIS.md](./code-quality-analysis.md) — analisi qualita' del codice (riferimento corrente; altre analisi datate in Storico)
- [stories/blade-i18n-no-hardcoded-text.story.md](./stories/blade-i18n-no-hardcoded-text.story.md) — niente testi fissi nelle Blade: chiavi `pub_theme::` in it, en, de, es (prima meta' del tema)

## Regole strutturali e naming

- [file-naming-rules.md](./file-naming-rules.md) — regole di naming file
- [module-namespace-rules.md](./module-namespace-rules.md) — regole namespace moduli
- [provider-inheritance-rules.md](./provider-inheritance-rules.md) — regole ereditarieta' service provider
- [no-ai-tool-scaffold-dirs.md](./no-ai-tool-scaffold-dirs.md) — divieto cartelle di scaffold generate da AI tool
- [BLADE_OLD_FILES_IN_PAGES_RULE.md](./blade-old-files-in-pages-rule.md) — regola file Blade legacy in `pages/`
- [route-names-philosophy.md](./route-names-philosophy.md) — filosofia naming delle route
- [routing-architecture.md](./routing-architecture.md) — architettura del routing del tema
- [section-template-contract.md](./section-template-contract.md) — contratto dei template di sezione

## Architettura tema e filosofia Zen

- [blade-generic-architecture.md](./blade-generic-architecture.md) — architettura Blade generica/agnostica
- [ZEN_ARCHITECTURE_PHILOSOPHY.md](./zen-architecture-philosophy.md) — filosofia architetturale Zen
- [ZEN_NAKED_PAGE_PHILOSOPHY.md](./zen-naked-page-philosophy.md) — filosofia "naked page"
- [THEME_PHILOSOPHY_ZEN.md](./theme-philosophy-zen.md) — filosofia generale del tema Zen
- [THEME_ARCHITECTURE_ZEN_BLADE_NAMING.md](./theme-architecture-zen-blade-naming.md) — convenzioni naming file Blade
- [LAYOUT_ARCHITECTURE_PHILOSOPHY.md](./layout-architecture-philosophy.md) — filosofia architettura layout
- [filament-philosophy.md](./filament-philosophy.md) — filosofia d'uso componenti Filament nel tema
- [JSON_ARCHITECTURE.md](./json-architecture.md) — architettura basata su configurazione JSON
- [SINGLE_DESIGN_SYSTEM_POLICY.md](./single-design-system-policy.md) — policy design system unico
- [SEMANTIC_CSS_GUIDE.md](./semantic-css-guide.md) — guida CSS semantico

## UI e frontend

- [components.md](./components.md) — componenti Blade del tema
- [VOLT_CLASS_BASED_COMPONENTS.md](./volt-class-based-components.md) — componenti Volt class-based
- [dark-light-mode.md](./dark-light-mode.md) — gestione tema chiaro/scuro
- [filters-collapsible-philosophy.md](./filters-collapsible-philosophy.md) — filosofia filtri collassabili
- [icon-rendering-policy.md](./icon-rendering-policy.md) — policy di rendering icone

## GSAP, animazioni e kinetic design

- [GSAP_ANIMATIONS_GUIDE.md](./gsap-animations-guide.md) — guida generale animazioni GSAP
- [GSAP_SCROLLTRIGGER_CONFIGURATION.md](./gsap-scrolltrigger-configuration.md) — configurazione ScrollTrigger
- [GSAP_SCROLLTRIGGER_THEME_GUIDE.md](./gsap-scrolltrigger-theme-guide.md) — guida ScrollTrigger applicata al tema
- [cinematic-particles-philosophy.md](./cinematic-particles-philosophy.md) — filosofia particelle cinematiche
- [kinetic-design.md](./kinetic-design.md) — principi di kinetic design
- [KINETIC_WEB_DESIGN_SPEC.md](./kinetic-web-design-spec.md) — specifica kinetic web design
- [modern-web-design-guidelines.md](./modern-web-design-guidelines.md) — linee guida design moderno
- [web-design-study.md](./web-design-study.md) — studio di design web
- [web-effectiveness-rules-inputcomm.md](./web-effectiveness-rules-inputcomm.md) — regole di efficacia web (input/comunicazione)

## Homepage

- [HOMEPAGE_ARCHITECTURE.md](./homepage-architecture.md) — architettura homepage
- [HOMEPAGE_CMS_ARCHITECTURE.md](./homepage-cms-architecture.md) — architettura CMS della homepage
- [HOMEPAGE_LAYOUT_ARCHITECTURE.md](./homepage-layout-architecture.md) — architettura layout homepage
- [HEADERNAV_CMS_ARCHITECTURE.md](./headernav-cms-architecture.md) — architettura CMS header/nav
- [FOOTER_ARCHITECTURE.md](./footer-architecture.md) — architettura footer
- [ARCHITECTURE_UX_IMPROVEMENTS.md](./architecture-ux-improvements.md) — miglioramenti architettura/UX
- [homepage-governance.md](./homepage-governance.md) — governance dei contenuti homepage
- [HOMEPAGE_IMPROVEMENT_PLAN.md](./homepage-improvement-plan.md) — piano di miglioramento homepage
- [HOMEPAGE_SPRINT1_SUMMARY.md](./homepage-sprint1-summary.md) — riepilogo sprint 1 homepage
- [homepage-ui-ux-audit.md](./homepage-ui-ux-audit.md) — audit UI/UX homepage
- [HOMEPAGE_SEO.md](./homepage-seo.md) — SEO homepage
- [TROUBLESHOOTING_HOMEPAGE.md](./troubleshooting-homepage.md) — troubleshooting problemi homepage

## Filament — setup, tabelle, widget

- [FILAMENT_4_INTEGRATION.md](./filament-4-integration.md) — integrazione Filament 4
- [FILAMENT_5_IMPLEMENTATION.md](./filament-5-implementation.md) — implementazione Filament 5
- [FILAMENT_5_INSTALLATION_GUIDE.md](./filament-5-installation-guide.md) — guida installazione Filament 5
- [FILAMENT_TABLES_SETUP.md](./filament-tables-setup.md) — setup tabelle Filament
- [filament-structure-analysis.md](./filament-structure-analysis.md) — analisi struttura Filament
- [filament-views-structure.md](./filament-views-structure.md) — struttura view Filament
- [filament-widget-best-practices.md](./filament-widget-best-practices.md) — best practice widget
- [filament-widget-common-errors.md](./filament-widget-common-errors.md) — errori comuni widget
- [filament-widget-integration.md](./filament-widget-integration.md) — integrazione widget
- [filament-widget-properties.md](./filament-widget-properties.md) — proprieta' widget
- [filament-widget-testing.md](./filament-widget-testing.md) — testing widget
- [FILAMENT_WIDGET_TRANSPARENT_BACKGROUND.md](./FILAMENT_WIDGET_TRANSPARENT_BACKGROUND.md) — sfondo trasparente widget

Nota: l'ampio cluster di analisi su login/auth widget Filament (~20 file, versioni
successive della stessa indagine) e' raggruppato in
[Storico / da consolidare](#filament-loginauth-widget-cluster-di-analisi-ripetute).

## Autenticazione

- [auth.md](./auth.md) — sistema di autenticazione del tema

Nota: `login-improvements.md` e il cluster filament-login/auth sono in Storico.
`antigravity-auth-success.md` (root, distinto dall'omonimo in `memories/`) e' in Storico.

## Predict / prediction market (root)

- [PREDICT_DETAIL_AGNOSTIC_CONTRACT.md](./predict-detail-agnostic-contract.md) — contratto detail page agnostico
- [ORDER_BOOK_MULTI_OUTCOME_CONTRACT.md](./order-book-multi-outcome-contract.md) — contratto order book multi-outcome
- [NO_PREDICT_SPECIFIC_PAGES.md](./no-predict-specific-pages.md) — regola: no pagine Predict-specific nel tema
- [NO_PREDICT_SPECIFIC_PAGES_IN_THEME.md](./no-predict-specific-pages-in-theme.md) — variante/duplicato della regola precedente (vedi Storico)
- [predict-detail-page-reusable-hero-fix.md](./predict-detail-page-reusable-hero-fix.md) — fix hero riutilizzabile detail page
- [predicts-listing-fix.md](./predicts-listing-fix.md) — fix listing predicts
- [market-detail-trust-ui.md](./market-detail-trust-ui.md) — UI di trust nel market detail
- [kalshi-competitor-analysis.md](./kalshi-competitor-analysis.md) — analisi competitor Kalshi

Approfondimenti dedicati in sottocartelle: [predict/](./predict/README.md) (34 file, indicizzati
nel proprio README) e [kalshi-analysis/](#kalshi-analysis) (vedi sezione Sottocartelle).

## Build, asset pipeline e fix di errori runtime

- [BUILD_PROCESS.md](./build-process.md) — processo di build del tema
- [assets-build-workflow.md](./assets-build-workflow.md) — workflow build asset
- [theme-workflow.md](./theme-workflow.md) — workflow di sviluppo tema
- [publishing.md](./publishing.md) — processo di pubblicazione del tema
- [vite-error.md](./vite-error.md) — errore Vite (generico)
- [vite_manifest_error.md](./vite-manifest-error.md) — errore manifest Vite
- [LOGO_FIX.md](./logo-fix.md) — fix logo
- [svg-icon-error-fix.md](./svg-icon-error-fix.md) — fix errore icone SVG
- [array_offset_null_error.md](./array-offset-null-error.md) — fix errore array offset null
- [codex-error-fix.md](./codex-error-fix.md) — fix errore Codex

Nota: `index-blade-error-fix.md` e `index-blade-errors-and-fixes.md` sono duplicati dello
stesso incidente, vedi Storico.

## Git e gestione conflitti

- [conflict-resolution.md](./conflict-resolution.md) — guida generale risoluzione conflitti
- [merge-conflicts-list.md](./merge-conflicts-list.md) — elenco conflitti di merge affrontati
- [merge-conflicts-resolution.md](./merge-conflicts-resolution.md) — risoluzione dei conflitti elencati

## Prodotto: requisiti

- [prd.md](./prd.md) — Product Requirements Document del tema

Nota: esistono due serie parallele di documenti roadmap/strategy/sprint/research/launch
(una generata come set "Modern Tailwind + Vite Theme", una scritta a mano con nomi simili).
Sono raggruppate integralmente in Storico per evitare di scegliere arbitrariamente quale sia
la versione corrente.

## Sottocartelle

- [predict/](./predict/README.md) — 34 file, indice proprio in `predict/README.md`
- [kalshi-analysis/](./kalshi-analysis/analisi.md) — analisi competitor Kalshi:
  [ANALISI.md](./kalshi-analysis/analisi.md),
  [dESIGN_ANALYSIS.md](./kalshi-analysis/design-analysis.md),
  [DETAIL_PAGE_ANALYSIS.md](./kalshi-analysis/detail-page-analysis.md),
  [HOMEPAGE_ANALYSIS.md](./kalshi-analysis/homepage-analysis.md)
- [phpstan/](./phpstan/README.md) — governance PHPStan/config immutabile:
  [README.md](./phpstan/README.md) (nota errore Vite manifest),
  [immutable-config-governance.md](./phpstan/immutable-config-governance.md),
  [script-placement-boundary.md](./phpstan/script-placement-boundary.md)
- [prompts/](./prompts/study.md) — prompt operativi:
  [particles.md](./prompts/particles.md), [study.md](./prompts/study.md),
  fixes: [01.md](./prompts/fixes/01.md), [02.md](./prompts/fixes/02.md), [03.md](./prompts/fixes/03.md),
  [predicts_list.md](./prompts/fixes/predicts-list.md), [predicts_view.md](./prompts/fixes/predicts-view.md)
- [roadmap/](./roadmap/theme-roadmap.md) — [theme-roadmap.md](./roadmap/theme-roadmap.md) (vedi anche cluster roadmap in Storico)
- [memories/](./memories/theme-asset-pipeline.md) — [theme-asset-pipeline.md](./memories/theme-asset-pipeline.md);
  `antigravity-auth-success.md` duplicato del root, vedi Storico
- [llm-wiki/](./llm-wiki/AGENTS.md) — [AGENTS.md](./llm-wiki/AGENTS.md)
- [i18n/](./i18n/root-boundary.md) — [root-boundary.md](./i18n/root-boundary.md)
- [concepts/](./concepts/xotbase-never-extend-filament.md) — [xotbase-never-extend-filament.md](./concepts/xotbase-never-extend-filament.md)
- [root-txt-files/](./root-txt-files/untitled-note.md) — [untitled-note.md](./root-txt-files/untitled-note.md), [untitled-note-continued.md](./root-txt-files/untitled-note-continued.md) (note datate storiche)
- [wiki/](./wiki/index.md) — second brain / wiki LLM (19 file), indice proprio in `wiki/index.md`
- [raw/](./raw/index.md) — layer raw immutabile, indice proprio in `raw/index.md`

## Storico / da consolidare

Documenti non cancellati ne' rinominati, ma superati o duplicati da altri file sullo stesso
argomento. Vanno collegati/consolidati in un secondo momento, non rimossi.

### Filament login/auth widget — cluster di analisi ripetute

Circa 20 documenti prodotti in passaggi successivi sulla stessa indagine (login widget
Filament / AuthenticateUser). Da consolidare in un unico documento quando si torna sul tema:

- [filament-auth-analysis.md](./filament-auth-analysis.md)
- [filament-auth-pros-cons.md](./filament-auth-pros-cons.md)
- [filament-authenticates-users-analysis.md](./filament-authenticates-users-analysis.md)
- [filament-authenticates-users-evaluation.md](./filament-authenticates-users-evaluation.md)
- [filament-authenticates-users-impact.md](./filament-authenticates-users-impact.md)
- [filament-authenticates-users-implementation-guide.md](./filament-authenticates-users-implementation-guide.md)
- [filament-authenticates-users-monitoring.md](./filament-authenticates-users-monitoring.md)
- [filament-authenticates-users-technical-deep-dive.md](./filament-authenticates-users-technical-deep-dive.md)
- [filament-laravel-ui-deep-analysis.md](./filament-laravel-ui-deep-analysis.md)
- [filament-laravel-ui-integration.md](./filament-laravel-ui-integration.md)
- [filament-login-analysis.md](./filament-login-analysis.md)
- [filament-login-implementation.md](./filament-login-implementation.md)
- [filament-login-widget-analysis.md](./filament-login-widget-analysis.md)
- [filament-login-widget-code-analysis.md](./filament-login-widget-code-analysis.md)
- [filament-login-widget-deep-analysis.md](./filament-login-widget-deep-analysis.md)
- [filament-login-widget-implementation.md](./filament-login-widget-implementation.md)
- [filament-login-widget-related-files.md](./filament-login-widget-related-files.md)
- [login_filament_widget_error.md](./login-filament-widget-error.md)
- [login_filament_widget_pro_cons.md](./login-filament-widget-pro-cons.md)
- [login-improvements.md](./login-improvements.md)

### Prodotto: roadmap / strategy / sprint / research / launch — due serie parallele

- [roadmap.md](./roadmap.md)
- [ROADMAP.md](./ROADMAP.md)
- [ROADMAP_2025.md](./roadmap-2025.md)
- [product_roadmap.md](./product-roadmap.md)
- [strategy.md](./strategy.md)
- [product-strategy.md](./product-strategy.md)
- [product_strategy.md](./product_strategy.md)
- [sprint.md](./sprint.md)
- [sprint_planning.md](./sprint-planning.md)
- [research.md](./research.md)
- [user_research.md](./user-research.md)
- [launch.md](./launch.md)
- [product_launch_plan.md](./product-launch-plan.md)

Vedi anche [roadmap/theme-roadmap.md](./roadmap/theme-roadmap.md), ulteriore variante
roadmap in sottocartella.

### Analisi qualita'/redundancy datate (audit puntuali sovrapposti)

- [REDUNDANCY_ANALYSIS.md](./REDUNDANCY_ANALYSIS.md)
- [redundancy-audit.md](./redundancy-audit.md)
- [copilot-redundancy-audit.md](./copilot-redundancy-audit.md)
- [analisi-completa-tema.md](./analisi-completa-tema.md)
- [analisi-metodi-duplicati.md](./analisi-metodi-duplicati.md)
- [dry-kiss-analysis.md](./dry-kiss-analysis.md)
- [code-quality-improvement-report.md](./code-quality-improvement-report.md)
- [code-quality-report.md](./code-quality-report.md)

### Coppie di duplicati puntuali

- [INDEX_BLADE_ERROR_FIX.md](./index-blade-error-fix.md) vs [index-blade-errors-and-fixes.md](./index-blade-errors-and-fixes.md)
- [NO_PREDICT_SPECIFIC_PAGES.md](./no-predict-specific-pages.md) vs [NO_PREDICT_SPECIFIC_PAGES_IN_THEME.md](./no-predict-specific-pages-in-theme.md)
- [antigravity-auth-success.md](./antigravity-auth-success.md) (root) vs [memories/antigravity-auth-success.md](./memories/antigravity-auth-success.md) (contenuto diverso, stesso argomento)

### Duplicati per naming (hyphen/underscore) in `predict/`

Non spostati ne' rinominati: stesso argomento, varianti di nome con trattino/underscore.

- [predict/futuur-analysis.md](./predict/futuur-analysis.md) vs [predict/futuur-design-analysis.md](./predict/futuur-design-analysis.md) vs [predict/futuur_design_analysis.md](./predict/futuur_design_analysis.md)
- [predict/prediki-analysis.md](./predict/prediki-analysis.md) vs [predict/prediki_analysis.md](./predict/prediki_analysis.md)
- [predict/prediki-design-analysis.md](./predict/prediki-design-analysis.md) vs [predict/prediki_design_analysis.md](./predict/prediki_design_analysis.md)

---

Manutenzione: quando si aggiunge un nuovo `.md` in `docs/`, aggiungere una riga in questo
indice nella sezione tematica pertinente (o in Storico se e' un duplicato di un documento
gia' indicizzato). Non spostare ne' rinominare file esistenti solo per pulizia dell'indice.
