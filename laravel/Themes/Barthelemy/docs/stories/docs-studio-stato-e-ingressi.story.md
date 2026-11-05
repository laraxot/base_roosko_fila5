---
title: "Barthelemy: studio dello stato reale e correzione degli ingressi docs"
type: story
status: done-with-open-decisions
created: 2026-10-14
updated: 2026-10-14
theme: Barthelemy
owner: theme:Barthelemy
related:
  - ../00-INDEX.md
  - ../README.md
  - ../../../Sixteen/docs/bmad/stories/docs-studio-stato-e-ingressi.story.md
  - ../../../../../bmad-output/stories/docs-organization/DOC-010.module-theme-quality.story.md
tags: [docs, barthelemy, bmad, ingressi, verifica, legacy, hub-temi, doc-010, doc-002]
qmd: "barthelemy docs studio stato legacy guscio senza codice ex hub Themes/docs bc58be108 shared-components 1887 file pub_theme sixteen"
---

# Barthelemy: studio dello stato reale e correzione degli ingressi docs

Fase BMAD: manutenzione documentale (DOC-010, DOC-002). Cartella `stories/` creata solo per questa story.

## Richiesta
Stabilire se Barthelemy e' un tema vivo o legacy con prove e correggere `README.md` e `00-INDEX.md` con fatti verificati, senza cancellare, spostare o rinominare.

## Analisi
**Barthelemy non ha codice.** Nel tema ci sono `package.json` (`laraxot/theme-barthelemy-fila5`, `0.0.0`, privato), `.releaserc.json`, `.gitignore`, `.github/workflows/` vuota, `scripts/ci/contributor-lines-report.mjs` e `docs/`. Mancano `composer.json`, `theme.json`, `app/`, `resources/`, `lang/`, `tests/` e Vite. Non e' in `gitmodules.ini`, non ha `.git` (lo traccia la radice, 1.950 file tracciati, quasi tutti docs), non e' nell'elenco PSR-4 di `RegisterRuntimePsr4NamespacesAction`, `pub_theme` e' `Sixteen` nei 4 `xra.php`, `public_html/themes/` ha solo `Sixteen`. `rg Barthelemy` su `laravel/config`, `app`, `bootstrap`, `routes`, `composer.json` e `Modules/*/{app,config,database}` trova solo la localita' "Saint Barthelemy" nel dump `Modules/Geo/database/_dump/geonames_cap.sql`.

**La cartella `docs/` e' l'ex hub dei temi.** `git show -M bc58be108` (2026-06-12, messaggio "delete laravel/--cache-result"): 1.988 rinomine `R100` da `laravel/Themes/docs/` a `laravel/Themes/Barthelemy/docs/` (tra cui `README.md` e `DOCUMENTATION_INDEX.md`), 1 verso `Sixteen/docs`. Per questo i temi fratelli puntavano a un hub inesistente (`../../docs/wiki/concepts/ai-harness-theme-discipline.md`: 6 link in 4 file di Sixteen, TwentyOne e Meetup, ora ripuntati a `Barthelemy/docs/wiki/concepts/`; `../../docs/directory-structure-rules.md` nell'indice di TwentyOne) e la Meetup story del 2026-10-08 constatava "`Themes/docs` non esiste nel repo".

Cosa era falso negli ingressi e nei documenti:

| File | Affermazione | Realta' |
|---|---|---|
| `README.md` | hub dei temi che elenca solo Sixteen e TwentyOne, con Sixteen "Bootstrap Italia parity" e TwentyOne "available" | aggiunta la tabella a quattro temi con prove: TwentyOne e' importato da Predict e non usato, Barthelemy e Meetup sono gusci |
| `00-INDEX.md` | "Indice minimo dei documenti reali del tema" | sono documenti del vecchio hub dei temi, non di un tema Barthelemy |
| `wiki/THEME-ARCHITECTURE.md` | "Tema principale FixCity Fila5 ... 70% completato", albero con `app/Providers/ThemeServiceProvider.php`, `config/theme.php`, `public/`, `resources/` | nessuna di queste cartelle esiste; il tema principale e' Sixteen. Marcato `stale` |
| `PROJECT-STRUCTURE.md` | struttura wiki "Root root" con `wiki/decisions/` e "151+ regole" | `wiki/decisions/` non esiste, `wiki/rules/` ha 1 file (`INDEX.md`). Marcato `stale` |
| `vhost-configuration.md` | tema attivo "Sixteen (Bootstrap Italia compliant)" | Sixteen non ha la dipendenza Bootstrap Italia; non modificato, segnalato nell'indice |

`shared-components/`: 1.887 file in un solo livello (1.753 `.md`, 96 `.html`, 8 `.sh`, 7 `.json`, 5 `.mdc`, 5 `.jpg`, 4 `.stub`, 3 `.png`, 2 `.py`, 2 `.php`); 63 `.html` sono riferimenti di parity di Sixteen (`*-reference-body.html`, `*-local-body.html`); 150 nomi finiscono in `-Modules.md`, 167 in `-N.md`, 18 iniziano con `-`. E' materiale di moduli e di Sixteen, non di un tema Barthelemy.

## Modifiche (solo `laravel/Themes/Barthelemy/docs/`)
- `README.md`: sezione "Cosa e' questa cartella" con le prove, tabella dei quattro temi con stato, `type: readme`, `status: active`.
- `00-INDEX.md`: stato legacy, classificazione delle cartelle (canonica, locale, evidence/legacy da triare, supporto), link alle note `stale` e a questa story; `status: active`.
- `wiki/THEME-ARCHITECTURE.md`, `PROJECT-STRUCTURE.md` e `DOCUMENTATION_INDEX.md`: `status: stale` e nota datata (`docs-organizer.py stale --fix`). Quest'ultimo era un inventario del 2026-03-30 ("Total Themes: 2", 745 file) contro 1.797 `.md` e 4 temi di oggi; tolto anche il blocco frontmatter duplicato che aveva nel corpo (nessun testo perso).
- `DOCUMENTATION_INDEX.md`, `README.md`, `wiki/log.md`: 1 link ripuntato (`filament-components-usage.md` -> `Modules/UI/docs/legacy/`), 12 annotati `_(target mancante, verificato 2026-10-14)_`. `wiki/log.md`: voce datata.

## Verifica
`docs-organizer.py --busy-minutes 0 links ... --by-name` sui file toccati (`README.md`, `00-INDEX.md`, `DOCUMENTATION_INDEX.md`, `wiki/log.md`, `PROJECT-STRUCTURE.md`, `wiki/THEME-ARCHITECTURE.md`): 15 eventi prima, 14 dopo (`DOCUMENTATION_INDEX.md` 11 -> 10, gli altri file invariati: 1 ripuntato; i 12 annotati restano contati dallo strumento, che non riconosce l'annotazione; `README.md` 1, `wiki/log.md` 1, `PROJECT-STRUCTURE.md` 1, `wiki/THEME-ARCHITECTURE.md` 1, `00-INDEX.md` 0). Perimetro intero: 3.069 -> 3.068 (700 file con link non risolti, 247 + 208 solo in due file di `shared-components/`). Controllo indipendente: 0 link non annotati rotti in README, 00-INDEX, DOCUMENTATION_INDEX, wiki/log.

## Decisioni
- Barthelemy e' classificato legacy: nessun codice da documentare. La cartella resta come hub storico dei temi; non e' stata spostata (richiede migration map, DOC-006).
- Nessuna cancellazione di `shared-components/`: la classificazione e' dichiarata nell'indice, il triage e' DOC-004/006.

## Aperto
- Spostare l'hub dei temi in un percorso proprio (`laravel/Themes/docs`) o decidere la fine del guscio Barthelemy: richiede migration map e revisione umana; fino ad allora i link `../../docs/...` degli altri temi restano ripuntati caso per caso.
- 3.068 link non risolti nel perimetro, quasi tutti in `shared-components/`.
- `vhost-configuration.md` contiene un blocco frontmatter duplicato nel corpo (riga 20) e la dicitura "Bootstrap Italia compliant" per Sixteen; `THEME_PHILOSOPHY_COMPLETE.md` (311 righe) non e' stato riletto riga per riga.
