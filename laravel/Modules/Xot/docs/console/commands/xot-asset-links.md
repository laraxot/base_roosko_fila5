---
title: "xot:theme-link / xot:module-link — symlink asset nel docroot"
type: reference
module: Xot
status: active
created: 2026-11-10
updated: 2026-11-10
qmd: "xot theme-link module-link symlink public_html assets themes"
issues: []
discussions: []
related:
  - ../../../../docs/wiki/rules/public-path-public-html.md
  - ../README.md
---

# Symlink asset nel docroot (`public_html`)

## Regola

Il document root e' `<repo>/public_html` (mai `laravel/public` — vedi
[public-path-public-html](../../../../docs/wiki/rules/public-path-public-html.md)).
Le risorse pubbliche dei temi e dei moduli entrano nel docroot **solo via symlink**,
creati dai comandi dedicati in `app/Console/Commands/`:

| Comando | Sorgente | Destinazione |
|---|---|---|
| `php artisan xot:theme-link` | `laravel/Themes/{Theme}/public` | `public_html/themes/{Theme}` |
| `php artisan xot:module-link` | `laravel/Modules/{Modulo}/public` | `public_html/assets/{lower(modulo)}` |

## Il perche'

- `public/` del tema/modulo e' la sola cartella servibile: contiene `manifest.json`,
  asset Vite compilati e file statici.
- Il symlink evita la duplicazione: il build Vite del tema scrive dentro
  `Themes/{Theme}/public` e il docroot lo vede subito, senza `vendor:publish` o copy.
- `public_html/assets/{lower(m)}` usa il nome modulo in minuscolo
  (`Modules/Geo` → `assets/geo`), coerente con `Module::asset()` e Vite.

## La regola degli skip (importante)

**Moduli e temi senza cartella `public/` vengono saltati** — nessun symlink viene
creato per loro. I loro asset si servono con `php artisan vendor:publish`
(`module:publish-assets`) o con la build Vite del tema, **mai** con symlink
verso `resources/` o altre cartelle interne.

Verificato il 2026-11-10: solo `Geo` e `Xot` hanno `Modules/*/public` → solo
`assets/geo` e `assets/xot` sono symlink; tutti gli altri moduli vengono
skippati dal comando. Temi: `Sixteen` e' una cartella reale nel docroot
(gestita dal suo build), `Zero`/`Trattoria`/`TwentyOne` sono symlink;
`Meetup` non ha `public/` → saltato.

## Uso

```bash
# Tutti i temi/moduli
php artisan xot:theme-link
php artisan xot:module-link

# Uno solo
php artisan xot:theme-link --theme=TwentyOne
php artisan xot:module-link --module=Geo

# Simulazione
php artisan xot:theme-link --dry-run
php artisan xot:module-link --dry-run

# Sostituisce link errati o cartelle reali (la cartella reale viene
# spostata in {nome}.bak-{timestamp}, mai cancellata)
php artisan xot:theme-link --force
php artisan xot:module-link --force
```

## Comportamento

- Link gia' corretto → `link gia' corretto` (no-op).
- Link esistente che punta altrove → warning, serve `--force`.
- Cartella reale nella destinazione → warning, serve `--force` (backup `.bak-`).
- `public/` mancante → riga `nessuna cartella public/, saltato`.

## Non confondere

- `public_html/modules/{techplanner,ui}` e' un **albero legacy**: nessun
  riferimento nel codice; non estenderlo con symlink verso `Modules/*/resources/`.
- `public_html/assets/{Xot,Geo,...}` con iniziale maiuscola sono output Vite,
  non i symlink dei moduli (quelli sono minuscoli).

## Files

- `app/Console/Commands/ThemeLinkCommand.php`
- `app/Console/Commands/ModuleLinkCommand.php`
- Registrazione: `XotServiceProvider::registerCommands()`
- Log introduzione: `docs/wiki/log.md` (2026-11-09)
