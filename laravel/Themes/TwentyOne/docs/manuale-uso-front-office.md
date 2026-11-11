---
title: "Manuale d'uso del Front Office"
type: manual
audience: [utente finale, editor contenuti, tester]
status: active
tags: [front-office, manuale, twentyone, accessibilità, cms]
related:
  - ../00-INDEX.md
  - ../../Cms/docs/foot-component-architecture.md
  - ../../Cms/docs/seo-head-architecture.md
---

# Manuale d'uso del Front Office — Tema TwentyOne (scheda tecnica)

> **Per gli utenti** (visitatori e giocatori, con schermate passo per passo) il manuale è
> [Modules/Predict/docs/manuale/01-visitatore.md](../../../Modules/Predict/docs/manuale/01-visitatore.md)
> e [02-giocatore.md](../../../Modules/Predict/docs/manuale/02-giocatore.md). Questa pagina è la
> mappa tecnica per chi sviluppa o testa il tema.

Guida completa all'esperienza pubblica della piattaforma di previsioni. Tutti i percorsi sono verificati su `http://192.168.1.40:8000` con locale `it`; le stesse pagine esistono in `en` e nelle altre lingue supportate da `mcamara/laravel-localization`.

> **Nota ambiente**: in sviluppo (`APP_DEBUG=true`) la barra debug di Laravel inietta markup extra. In produzione il documento HTML è pulito e singolo.

## 1. Architettura delle pagine (come leggere questo manuale)

Il front office è servito da **Laravel Folio** (routing file-based) + **Volt/Livewire** + il modulo **Cms**:

- `Themes/TwentyOne/resources/views/pages/` contiene le pagine reali.
- `[container0]/index.blade.php` e `[container0]/[slug0]/index.blade.php` sono il fallback CMS generico: `{locale}` e `{container0}` vengono risolti in slug CMS (`home`, `predicts.index`, `predicts.view`, …) tramite `Modules\Cms\Actions\ResolvePageAction`.
- Se il slug CMS non esiste → **HTTP 404 reale** con la pagina errori dedicata (corretto dal fix del soft-404).
- Il documento HTML è posseduto dal layout: `<x-cms::head />` apre/chiude `<head>`; `<x-cms::foot />` emette solo contenuto di coda; `</body></html>` sono nel layout.

## 2. Mappa delle rotte verificate

| URL | HTTP | Contenuto |
|---|---|---|
| `/{locale}` (es. `/it`) | 200 | Home CMS: hero, statistiche, mercati in evidenza |
| `/{locale}/predicts` | 200 | Listing mercati/previsioni |
| `/{locale}/predicts/{slug-o-id}` | 200/404 | Dettaglio mercato (`predicts.view`) |
| `/{locale}/categories`, `/{locale}/categories/{slug}` | 200 | categorie e vista categoria |
| `/{locale}/leaderboard` | 200 | classifica utenti |
| `/{locale}/blog` | 200 | articoli non-mercato (vuoto finché non se ne pubblicano); `/{locale}/articles` → 301 a `/predicts` |
| `/{locale}/{slug}` | 200 | pagine CMS semplici: `chi-siamo` (alias `about`), `how-it-works`, `contact`, `terms`, `privacy`, `cookies`, `disclaimer` |
| `/{locale}/auth/login`, `/{locale}/auth/register`, `/{locale}/auth/password/reset` | 200 | autenticazione (429 sotto raffica: rate-limit `SecurityMiddleware`) |
| `/{locale}/pages/profile`, `/{locale}/pages/wallet`, `/{locale}/profile/edit` | 200 / 302 al login | area utente (middleware `auth` dal JSON della pagina) |
| `/{locale}/profile`, `/{locale}/wallet`, `/{locale}/setting` | 200 | alias top-level degli stessi slug CMS (fallback `'{c}.index'` → `'{c}'`) |
| `/{locale}/search?q=` | 200 | ricerca mercati per titolo (`pages/search/index.blade.php`) |
| `/{locale}/pages/{slug}` | 200 | pagine CMS statiche (about, privacy-policy, terms…) |
| `/{locale}/{sconosciuto}` | **404** | pagina errore dedicata; pagine demo legacy (`cart`, `privacy`) non esposte |
| `/{locale}/predicts/create` | **404** | rotta inesistente (rimosso link footer) |

Slug CMS disponibili: `about`, `articles`, `home`, `leaderboard(.index)`, `predicts(.index/.view)`, `privacy-policy`, `profile`, `setting`, `terms(-and-conditions)`, `wallet`, `predict-view`, `crypto_001`.

## 3. Navigazione pubblica

- **Header**: logo (link home, aria-label), navigazione principal con mega-menu categorie, login/registrazione per gli ospiti, menu account per gli autenticati.
- **Mobile**: hamburger con `aria-expanded`/`aria-controls="mobile-menu"`, tab bar inferiore (Sfoglia Mercati / Categorie / Classifica) con target ≥44px.
- **Footer**: colonne Prodotti (Sfoglia Mercati, Categorie, Classifica), Azienda (Chi Siamo, Come Funziona…), link legali, social con nomi accessibili.

## 4. Flussi utente

### Consultare i mercati
`/{locale}/predicts` → lista filtrabile; click su un mercato → `predicts.view` con grafico prezzi, regole, FAQ, form trading (se autenticati).

### Leggere articoli
`/{locale}/articles` e `/{locale}/blog` → card con immagine, categoria, data; pagina articolo con ratings.

### Accedere
`/{locale}/login` (credenziali o social via Socialite) → redirect area utente. `/{locale}/register` per la registrazione.

### Area utente
`/{locale}/profile` (dati profilo), `/{locale}/wallet` (crediti/transazioni), `/{locale}/setting` (preferenze). Logout dal menu account.

### Lingue e tema
Selettore lingua nell'header (prefisso URL). Tema scuro di default; toggle dark-mode parziale (vedi §7).

## 5. Accessibilità (WCAG)

- Skip link "Vai al contenuto" (`main#main-content`), un solo `h1`, heading hierarchy corretta (h2→h3).
- Tutti i controlli icon-only hanno `aria-label`; dropdown con `aria-haspopup`/`aria-controls`/`aria-expanded` e `type="button"`.
- Target touch ≥24px (AA 2.5.8), header/footer links con padding esteso.
- `:focus-visible` visibile, `prefers-reduced-motion` rispettato, `lang` corretto, immagini con `alt`.
- Contrasto verificato: 0 violazioni sulle pagine testate.

## 6. Errori e stati

- **404 reale**: slug CMS mancante, `predicts/{id}` inesistente, `predicts/create` → pagina errori dedicata con link di recupero.
- **Fallback CMS**: `container0` non mappato → 404 (non più pagina vuota 200).
- **Debugbar** (solo `APP_DEBUG=true`): inietta markup/JS extra e un warning COOP su HTTP — non è un difetto di prodotto.

## 7. Limitazioni note

- Creazione mercati **non è esposta** nel front office (solo backend/Filament): il link footer "Crea Mercato" è stato rimosso.
- Dark mode: il FO è **dark-only di design** — sezioni hardcoded `slate-900` e i componenti toggle (`dark-mode-toggle`, `theme-switcher`) esistono in `resources/views/components/ui/` ma **non sono montati** in nessun layout attivo. Se si vorrà un light theme vero, è un progetto a parte (palette + `@variant dark` già pronti).
- Bundle CSS ~1.09MB (import Filament nel tema) — candidato a code-splitting.
- Tema `Two` mantiene shell manuale per Alpine/auth; `Sixteen` usa un modello shell autonomo (documentato in `Themes/Sixteen/docs/wiki/concepts/document-shell.md`).

## 8. Troubleshooting

| Sintomo | Causa probabile | Verifica |
|---|---|---|
| Pagina vuota | slug CMS mancante | `Page::where('slug',…)` in Tinker |
| 500 su tutte le rotte | `abort()` top-level in pagina Folio | spostare in `render()` callback |
| Doppi `</body></html>` in curl | debugbar dev-only | controllare con `APP_DEBUG=false` |
| Header diverso da CMS `simple` | tema usa layout propri | verificare `Themes/TwentyOne/resources/views/components/layouts/` |

## 9. Per editor/contenuti

- Pagine e sezioni si modificano nei JSON `config/local/{modulo}/database/content/` (Sushi) o nel DB del modulo.
- Footer: `Modules/Predict/config/database/content/sections/footer.json` (seed) + override `config/local/predict/…`.
- Traduzioni UI: `Modules/*/lang/{it,en}/` (chiavi `block_*`).
- Mai inserire `</body></html>` in componenti di coda: il layout possiede la chiusura documento.
