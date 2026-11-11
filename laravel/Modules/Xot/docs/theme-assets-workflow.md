---
title: "Theme Assets Workflow - CSS/JS Frontend"
type: note
status: current
updated: 2026-11-11
tags: [docs, xot, assets, symlink]
qmd: "theme assets workflow css js frontend build symlink public_html npm run copy obsoleto"
related: [console/commands/xot-asset-links.md]
---
# Theme Assets Workflow - CSS/JS Frontend

**Regola**: gli asset di temi e moduli si compilano nella cartella del tema/modulo e vengono serviti
tramite **symlink** da `public_html/`. **`npm run copy` non esiste più e non va usato**: copiare i
file in `public_html/` romperebbe il symlink e lascerebbe asset vecchi.

## Struttura

```
base_<progetto>/
├── laravel/Themes/<Tema>/
│   ├── resources/      sorgenti CSS/JS
│   └── public/         asset compilati da Vite (outDir)
└── public_html/        DOCUMENT ROOT — non va mai cancellata
    ├── themes/<Tema>  -> ../../laravel/Themes/<Tema>/public   (symlink relativo)
    └── assets/xot     -> ../../laravel/Modules/Xot/public     (symlink relativo)
```

I symlink sono **relativi** (restano validi se il progetto cambia percorso) e si creano una volta
per installazione:

```bash
cd laravel
php artisan xot:theme-link     # public_html/themes/<Tema>   -> ../../laravel/Themes/<Tema>/public
php artisan xot:module-link    # public_html/assets/<modulo> -> ../../laravel/Modules/<Modulo>/public
```

Dettagli: [console/commands/xot-asset-links.md](console/commands/xot-asset-links.md).

## Workflow

```bash
cd laravel/Themes/<Tema>
npm install        # la prima volta
npm run build      # compila in public/: e' gia' online tramite symlink
# oppure
npm run dev        # sviluppo con hot reload
```

Nessun passo di copia: dopo `npm run build` il browser vede subito i nuovi file.

## Non fare

- Non modificare a mano i file compilati in `public/` o in `public_html/`.
- Non lanciare `npm run build` dalla root Laravel per gli asset di un tema.
- Non sostituire i symlink di `public_html/` con copie dei file, e non cancellare `public_html/`.
- Non aggiungere uno script `copy` al `package.json`.

## package.json del tema

```json
{
  "scripts": {
    "dev": "vite",
    "build": "vite build",
    "watch": "vite build --watch"
  }
}
```

## Troubleshooting

### Modifiche non visibili?
1. `npm run build` eseguito nella cartella del tema?
2. `public_html/themes/<Tema>` è un symlink? (`ls -la public_html/themes`). Se è una cartella
   normale, è una copia vecchia: rinominarla, poi `php artisan xot:theme-link`.
3. Svuotare la cache del browser (Ctrl+F5).
4. Controllare `build.outDir` in `vite.config.js` (deve essere `public`).

### Errori di build?
1. `npm install` nella cartella del tema.
2. Verificare le versioni di Node e npm.
3. Controllare le dipendenze in `package.json`.
