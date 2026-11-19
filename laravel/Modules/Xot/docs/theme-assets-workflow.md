---
title: "Theme Assets Workflow - CSS/JS Frontend"
type: note
status: active
updated: 2026-11-11
tags: [docs, xot, assets, themes, symlink, deploy]
qmd: "theme assets workflow css js frontend build xot symlink public_html themes npm run copy obsoleto"
related:
  - ../app/Console/Commands/ThemeLinkCommand.php
  - ../app/Console/Commands/ModuleLinkCommand.php
---
# Asset dei temi: build nel tema, servizio per symlink

**Regola:** CSS e JS del front office si modificano nella cartella del tema (`laravel/Themes/<Tema>/resources`), si
compilano lì, e il risultato va in `laravel/Themes/<Tema>/public`. Nessuna copia verso la document root:
**`npm run copy` non esiste più e non va usato** — copiare i file in `public_html/` romperebbe il symlink e
lascerebbe asset vecchi.

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

## Come arrivano al browser

```
public_html/themes/<Tema>   ->   ../../laravel/Themes/<Tema>/public      (symlink relativo)
public_html/assets/<modulo> ->   ../../laravel/Modules/<Modulo>/public   (symlink relativo)
```

- `@vite([...], 'themes/<Tema>')` legge `public_html/themes/<Tema>/manifest.json`, cioè il manifest del tema.
- `public_html` è la document root: non si cancella né si svuota mai.
- I symlink sono **relativi** (restano validi se il progetto cambia percorso) e si creano una volta per
  installazione, in due modi:
  - `deploy.sh` (funzione `link_public_asset`), in modo idempotente. Se al posto del link c'è una cartella
    vera (vecchia copia o vecchia build), lo script la sposta fuori dalla document root in
    `$DEPLOY_ASSETS_BACKUP_DIR` (non la cancella) e la sostituisce con il link.
  - i comandi artisan `php artisan xot:theme-link` e `php artisan xot:module-link` (con `--dry-run` per
    provare). Davanti a una cartella vera o a un link che punta altrove avvisano e la saltano: per
    sostituirla serve `--force`, dopo averne controllato il contenuto. Sorgenti:
    `app/Console/Commands/ThemeLinkCommand.php`, `app/Console/Commands/ModuleLinkCommand.php`.
    I comandi aggiungono anche il nome del link al `.gitignore` della cartella del docroot: il link non si
    committa, lo crea ogni installazione.

**Perché un symlink e non `npm run copy`:**

- **Una sola copia degli asset.** L'output della build è versionato nel repository del tema (`Themes/<Tema>/public`),
  quindi ciò che si committa è ciò che si serve. Con la copia, `public_html` andava fuori sincronia appena qualcuno
  dimenticava il comando.
- **Niente passo manuale nel deploy.** Basta il `git pull` del tema.
- **Il web server vede solo `public`.** I sorgenti del tema (`resources`, `node_modules`, script) restano fuori dalla
  document root.

## Lavorare su un tema

```bash
cd laravel/Themes/<Tema>
npm install      # prima volta
npm run dev      # sviluppo con hot reload
npm run build    # produzione: scrive in ./public (servito dal symlink)
```

Vale per ogni tema: si lavora sempre dalla cartella del tema, mai dalla root Laravel.

## package.json e vite.config.js del tema

```json
{
  "scripts": {
    "dev": "vite",
    "build": "vite build",
    "watch": "vite build --watch"
  }
}
```

```javascript
export default defineConfig({
  build: {
    outDir: 'public'
  }
});
```

Lo script `copy` è stato tolto da `package.json` dei temi: non serve più.

## Non fare

- Non modificare a mano i file compilati in `Themes/<Tema>/public` o in `public_html/`.
- Non lanciare `npm run build` dalla root Laravel per gli asset di un tema.
- Non sostituire i symlink di `public_html/` con copie dei file, e non cancellare `public_html/`.
- Non aggiungere uno script `copy` al `package.json` del tema.

## Asset dei moduli: stessa regola

Ogni modulo che espone file al browser li tiene in `Modules/<Modulo>/public`, versionata nel repository del modulo:
ad esempio Geo (build Vite, `outDir: './public'`), Xot (css del pannello), un modulo di dominio con le sue icone
(`public/svg` -> `../resources/svg`).

- `deploy.sh` e `xot:module-link` creano un link per ogni `Modules/*/public`. Il nome è in minuscolo perché gli URL
  lo sono (`/assets/geo/...`), e così resta uguale al namespace usato da `AssetAction`
  (`xot::css/x.css` -> `/assets/xot/css/x.css`).
- `public_html/assets/.gitignore` elenca `<modulo>/` (e la stessa voce senza barra, perché git vede un symlink come un
  file): il repository radice non versiona i link, che sono un prodotto del deploy, né il loro contenuto, che
  appartiene al modulo. Il test `Modules/Xot/tests/Unit/Deploy/ModulePublicAssetsLinksTest.php` fallisce se un modulo
  con `public/` manca dall'elenco.
- `AssetAction` cerca prima in `Modules/<Modulo>/public` e restituisce l'URL sotto `/assets/<modulo>` senza copiare;
  la copia da `resources/` resta solo come ripiego per i moduli non ancora migrati.
- Una cartella vera trovata al posto del link (vecchia copia o vecchia build) viene spostata da `deploy.sh` fuori dalla
  document root, in `$DEPLOY_ASSETS_BACKUP_DIR` (default sotto `$HOME`, vedi l'intestazione di `deploy.sh`), e
  sostituita dal link. `public_html` non si tocca.

Modulo nuovo con asset pubblici: crea `Modules/<Modulo>/public`, aggiungi `<modulo>/` e `<modulo>` a
`public_html/assets/.gitignore`, esegui `deploy.sh` o `php artisan xot:module-link` (o il `ln -s` a mano in sviluppo).

## Problemi comuni

| Sintomo | Causa | Cosa fare |
| --- | --- | --- |
| `ViteManifestNotFoundException` per `themes/<Tema>` | Manca il symlink o la build del tema | Controlla `ls -la public_html/themes/`; esegui `deploy.sh` o `php artisan xot:theme-link`; `npm run build` nel tema |
| Modifiche non visibili dopo la build | `public_html/themes/<Tema>` è una cartella vera (copia vecchia) o il link è rotto | `ls -la public_html/themes`: se è una cartella normale, rinominala e ricrea il link con `xot:theme-link` (o `--force`); svuota la cache del browser (Ctrl+F5) |
| CSS vecchio dopo una modifica | Build del tema non eseguita o non committata | `npm run build` nel tema e commit di `public/` |
| Icone di un modulo mancanti (`/assets/<modulo>/svg/*.svg`) | Manca `public_html/assets/<modulo>` | Lo crea `deploy.sh` o `php artisan xot:module-link` (link a `Modules/<Modulo>/public`) |
| Campo mappa assente nei pannelli (`assets/geo/manifest.json`) | Manca `public_html/assets/geo` o la build Geo non è committata | `deploy.sh` o `xot:module-link`; in sviluppo `cd Modules/Geo && ../../node_modules/.bin/vite build` e commit di `public/` |
| Errori di build | Dipendenze o ambiente | `npm install` nella cartella del tema; verificare versioni di Node/npm; controllare la sintassi Sass/JS, `build.outDir` in `vite.config.js` (deve essere `public`) e le dipendenze in `package.json` |
