---
qmd: "twentyone theme readme stato non usato fixcity importato progetto predict struttura requisiti build"
issues: []
discussions: []
title: "TwentyOne Theme Documentation"
type: readme
tags: [theme, documentation]
created: 2026-06-05
updated: 2026-10-27
status: draft
---

# TwentyOne Theme Documentation

## Stato (verificato 2026-10-14)
TwentyOne **non e' il tema pubblico di Fixcity** (`pub_theme` e' `Sixteen` in tutti e 4 i `laravel/config/**/xra.php`) ed e' stato importato dal progetto Predict: le sue Actions e 21 componenti Blade in `resources/views/components/predict/` dipendono da `Modules\Predict`, che in `laravel/Modules/` non esiste. E' un submodule con repo proprio (`theme_twentyone_fila5`). Prove e classificazione delle cartelle in [00-INDEX.md](00-INDEX.md).

## Introduzione
TwentyOne è un tema per Laravel basato su Tailwind CSS 4 e Vite 7 (`package.json`), con pagine Folio (17 file di `resources/views/pages/` usano Volt) e script GSAP in `resources/js/gsap-*.js`; `gsap` pero' non e' dichiarato in `package.json` e da dove si risolva l'import non e' verificato.

## Caratteristiche Principali
- 🎨 Design moderno con Tailwind CSS
- ⚡ Build system ottimizzato con Vite
- 📱 Responsive design
- 🔧 Facilmente personalizzabile
- 🚀 Performance ottimizzate

## Struttura del Tema
```
TwentyOne/
├── app/              # solo 2 Actions (Markets, Hero) e Http/Livewire/_components.json; nessun Provider
├── docs/             # Documentazione (Main_files/ e' qui dentro, non nella radice)
├── lang/             # en, it
├── public/           # asset compilati e manifest.json
├── resources/        # css, js (GSAP, tradingview-chart), json, lang, views
├── scripts/          # ci/, publish.sh
├── tests/Unit/       # 2 test sul contratto di mount delle pagine Folio
└── composer.json, package.json, vite.config.js, tailwind.config.js, postcss.config.js, theme.json
```

## Requisiti
- Il progetto ospite richiede PHP `^8.4` e Laravel `^13.0` (`laravel/composer.json`).
- `composer.json` del tema richiede `filament/filament`, `filament/tables` e `filament/forms` `^5.0` (non Filament 4).
- Node.js e NPM: nessuna versione minima dichiarata nel tema (`package.json` non ha `engines`); le indicazioni "Node >= 18, NPM >= 9" delle versioni precedenti di questo file non sono verificate.

## Installazione
1. Clona il repository nella cartella `laravel/Themes/TwentyOne`
2. Esegui `composer install` per installare le dipendenze PHP
3. Esegui `npm install` per installare le dipendenze JavaScript
4. Esegui `npm run build` per compilare gli asset del tema in `public/`
5. Esegui `npm run copy` per pubblicare manifest e asset in `public_html/themes/TwentyOne`

## Configurazione
Il tema può essere configurato attraverso i seguenti file:
- `tailwind.config.js` - Configurazione Tailwind CSS
- `vite.config.js` - Configurazione Vite
- `theme.json` - Configurazioni specifiche del tema

## Sviluppo
Per iniziare lo sviluppo:
1. Esegui `npm run dev` per avviare il server di sviluppo Vite
2. Modifica i file nella cartella `resources`
3. Le modifiche verranno automaticamente compilate

## Build per Produzione
Per creare una build ottimizzata per la produzione:
```bash
npm run build
npm run copy
```

Nel progetto corrente il solo `npm run build` non basta: Laravel legge gli asset del tema dal path runtime `public_html/themes/TwentyOne`, quindi dopo ogni build serve la copia verso quella destinazione.

## Personalizzazione
Il tema può essere personalizzato in diversi modi:
1. Modificando i file nella cartella `resources`
2. Sovrascrivendo i componenti nella cartella `app`
3. Aggiungendo nuovi stili in `resources/css`

## Troubleshooting
Per problemi comuni, consulta la documentazione nella cartella `docs`:
- [Vite Manifest Error](vite_manifest_error.md)
- [Vite Error](vite-error.md)
- [Publishing](publishing.md)
- [Section Template Contract](section-template-contract.md)

## Documentazione correlata
- Non creare `docs/archive/`: vale la [regola Second Brain no-docs-archive](../../../../bashscripts/ai/wiki/concepts/no-docs-archive-rule.md).
- `laravel/Themes/TwentyOne/docs/` - Documentazione del tema
- `project_docs/` - Documentazione di progetto (architettura, sviluppo)
- `project_docs/roadmaps/` - Roadmap master e stato avanzamento

## LLM Wiki Workflow

- Canonical wiki layer: [../../../../docs/wiki/README.md](../../../../docs/wiki/README.md)
- Compiled index: [../../../../docs/wiki/index.md](../../../../docs/wiki/index.md)

## Contribuire
Le contribuzioni sono benvenute! Per contribuire:
1. Crea un fork del repository
2. Crea un branch per la tua feature
3. Invia una pull request

## Licenza
Questo tema è rilasciato sotto la licenza MIT. Vedi il file [LICENSE](../LICENSE) per maggiori dettagli. 


## Standard Rules & Workflow

- [[BMAD Method](../../../../docs/wiki/concepts/bmad-method.md)]
- [[Context Engineering](../../../../docs/wiki/concepts/context-engineering.md)]
- [[LLM Wiki Governance](../../../../docs/wiki/concepts/llm-wiki-governance.md)]

## Documentation

- [On-Demand Pattern](./on-demand-pattern.md) — Pattern per caricamento efficiente
- [QMD Setup](./qmd-setup.md) — Configurazione ricerca locale
- [Performance](./performance-optimization.md) — Metriche e best practice
- [Project Structure](./project-structure.md) — Directory layout
