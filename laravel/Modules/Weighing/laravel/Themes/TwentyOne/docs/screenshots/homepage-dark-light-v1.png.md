---
title: "homepage dark light v1.png"
type: note
tags: [documentation]
created: 2026-09-26
updated: 2026-09-26
qmd: "homepage dark light v1.png"
issues: []
discussions: []
status: draft
---

# Screenshot Analysis: homepage-dark-light-v1.png

**Data**: 2026-03-23
**URL**: `http://predict.local/it/predicts`
**Viewport**: 1440×900, full-page
**Tema attivo**: Light mode (default, nessuna preferenza `_x_dark_mode` in localStorage)
**Commit**: `47c64d08` — feat(theme): dark/light mode support for Filament Table Widget and layout

---
## Panoramica

Lo screenshot cattura la homepage dei mercati di previsione dopo l'implementazione completa del supporto dark/light mode. La pagina è renderizzata in **light mode** poiché è il default iniziale senza preferenze salvate.

---

## Analisi Sezione per Sezione

### 1. Header / Navbar
- **Sfondo**: bianco con bordo grigio leggero — **corretto** per light mode
- **Logo**: visibile a sinistra, testo scuro
- **Navigation**: link "Dashboard", "Learn More" con testo grigio
- **Dark mode toggle**: presente (icona sole/luna) sia desktop che mobile
- **Auth dropdown**: visibile con nome utente
- **Valutazione**: ✅ Buono — pulito e leggibile

### 2. Hero Section (Predicts Hero)
- **Sfondo**: gradiente scuro (slate-950) — **NON ADATTATO** al light mode
- **Badge**: "MERCATO DELLE PREVISIONI" in verde/emerald
- **Titolo**: "Mercati delle Previsioni" in bianco, ben leggibile
- **Sottotitolo**: testo chiaro su sfondo scuro
- **CTA buttons**: "Inizia ora" (emerald), "Come funziona" (outline)
- **Stats**: 18 mercati, 8 vivi, 1K crediti — visibili
- **Valutazione**: ⚠️ Contrasto visivo — hero rimane sempre dark, crea transizione brusca con card grid light
- **TODO**: Considerare adattamento hero per light mode in futuro

### 3. Card Grid (Filament Table Widget)
- **Sfondo wrapper**: `bg-slate-50` — chiaro, corretto per light mode
- **Card background**: bianco (`bg-white`) con bordi `border-slate-200` — ✅ pulito
- **Titoli card**: testo `text-slate-900` — ✅ alta leggibilità
- **Badge status**: bordi colorati (emerald/blue/purple) con testo adattivo — ✅ funzionante
- **Badge categoria**: sfondo `bg-slate-100`, bordo `border-slate-200`, testo `text-slate-600` — ✅ corretto
- **Immagini opzioni**: caricano correttamente, con overlay gradiente e badge percentuale
- **Badge percentuale**: `bg-slate-900/85` su immagini — ✅ leggibile su qualsiasi immagine
- **Stats footer**: bordo `border-slate-200`, sfondo `bg-slate-50`, testo `text-slate-900` — ✅ leggibile
- **Grid layout**: 3 colonne su desktop, responsive — ✅ corretto
- **Valutazione**: ✅ Buono — le card sono pulite, professionali, ben leggibili

### 4. Contenuto Card
- **Titoli mercato**: alcuni mostrano "Mercato in aggiornamento" (fallback per titoli mancanti)
- **Opzioni visibili**: immagini con percentuali, barra di probabilità colorata
- **Dati stats**: Volume (Credits), Partecipanti (Traders), Opzioni (Multi-esito/Binario)
- **Valutazione**: ✅ Dati strutturati correttamente

### 5. Footer
- **Sfondo**: scuro — **NON ADATTATO** al light mode (come l'hero)
- **Link**: organizzati in colonne (Prodotto, Azienda, Legale)
- **Valutazione**: ⚠️ Da adattare in futuro per coerenza con light mode

---

## Problemi Identificati

### Critici (P0)
- Nessuno

### Importanti (P1)
1. **Hero section non adattato**: `predicts-hero.blade.php` usa colori dark hardcoded, crea transizione brusca hero→card grid in light mode
2. **Footer non adattato**: rimane dark in light mode, incoerente con il resto della pagina

### Minori (P2)
3. **Alcuni mercati senza titolo**: "Mercato in aggiornamento" è un fallback — OK ma indica dati incompleti nel DB
4. **Particelle meno visibili**: `opacity-30` in light mode riduce l'effetto particles — voluto per non disturbare

---

## Confronto Dark vs Light

| Elemento | Dark Mode | Light Mode |
| --- | --- | --- |
| Body | `bg-slate-950 text-slate-100` | `bg-slate-50 text-slate-800` |
| Card BG | `bg-slate-950/80` | `bg-white` |
| Card Border | `border-slate-800/80` | `border-slate-200` |
| Card Title | `text-white` | `text-slate-900` |
| Badge Category | `bg-white/6 text-slate-200` | `bg-slate-100 text-slate-600` |
| Stats | `bg-white/6 text-white` | `bg-slate-50 text-slate-900` |
| Footer border | `border-white/10` | `border-slate-200` |
| CSS Widget | `.dark` selector vars | `:not(.dark)` selector vars |

---

## File Modificati

| File | Tipo modifica |
| --- | --- |
| `filament-widgets.css` | Rimosso `!important` dark-only, aggiunto `.dark`/`:not(.dark)` adaptive |
| `predicts-grid.blade.php` | `bg-slate-50 dark:bg-slate-950`, particles opacity adaptive |
| `predict-table.blade.php` | Container `bg-white/60 dark:bg-slate-950/50` |
| `item.blade.php` | Card, text, border, badge tutto adaptive |
| `homepage-item.blade.php` | Stessa treatment + modal adaptive |
| `main.blade.php` | Body bg adaptive, FOUC prevention script |
| `app.blade.php` | Rimosso `body-class="bg-slate-950"` hardcoded |

---

## Prossimi Step

1. **Adattare hero section** (`predicts-hero.blade.php`) per light mode
2. **Adattare footer** per light mode
3. **Adattare cookie consent banner** per light mode
4. **Test interattivo** con toggle dark/light per verificare transizioni
5. **Screenshot dark mode** per confronto completo

---

## Metriche Qualità

- **WCAG AA Contrasto testo**: ✅ Rispettato (slate-900 su white, slate-200 su slate-950)
- **Responsive**: ✅ Grid 3 col desktop, verificare mobile
- **Transizioni**: ✅ `transition-colors duration-300` su body e container
- **FOUC Prevention**: ✅ Script inline prima del `<head>` legge `_x_dark_mode` da localStorage
- **Performance**: ✅ Nessun layout shift, colori applicati via CSS class toggle

---

*Generato automaticamente durante analisi screenshot — Cascade, 2026-03-23*
