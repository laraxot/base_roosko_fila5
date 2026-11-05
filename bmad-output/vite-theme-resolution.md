# BMAD — Vite Asset Resolution per Theme (Sixteen)

## Il problema
`@vite(['resources/css/app.css', 'resources/js/app.js'])` **senza** il secondo parametro `theme` causa errori di risoluzione asset in produzione.

## Perché serve `'themes/Sixteen'`

### 1. **Multi-theme architecture**
Il progetto supporta **più temi** (Sixteen, TwentyOne, Barthelemy, Meetup). Ogni tema ha:
- Il proprio `vite.config.js` 
- Il proprio entry point (`resources/css/app.css`, `resources/js/app.js`)
- Il proprio manifest (`public/build/themes/Sixteen/.vite/manifest.json`)

### 2. **Vite manifest per tema**
Quando Vite compila per un tema specifico, genera il manifest in:
```
public/build/themes/{theme}/.vite/manifest.json
```

Senza il parametro tema, Laravel cerca in `public/build/.vite/manifest.json` (default) che **non esiste** per i temi.

### 3. **Come funziona `@vite` con tema**
```php
@vite(['resources/css/app.css', 'resources/js/app.js'], 'themes/Sixteen')
```
- **Primo argomento**: array di entry points
- **Secondo argomento**: percorso relativo del tema da `resources/views/` (o alias configurato)

L'helper `vite()` in `Illuminate\Foundation\Vite` usa il secondo parametro per:
1. Costruire il percorso del manifest: `public/build/{theme}/.vite/manifest.json`
2. Risolvere gli URL degli asset con il prefisso corretto: `/build/themes/Sixteen/assets/...`

### 4. **Configurazione Vite per tema** (`themes/Sixteen/vite.config.js`)
```js
export default defineConfig({
    build: {
        outDir: '../../public/build/themes/Sixteen',
        manifest: true,
    },
    // ...
})
```

### 5. **Errore comune senza tema**
```
View [folio.layouts.default] not found.
```
Oppure asset 404:
```
GET /build/assets/app-abc123.js 404 (Not Found)
```
Perché cerca in `/build/` invece di `/build/themes/Sixteen/`

## Applicazione nel login
Il file `Themes/Sixteen/resources/views/pages/auth/login.blade.php` **DEVE** usare:
```php
@vite(['resources/css/app.css', 'resources/js/app.js'], 'themes/Sixteen')
```

## Best Practice
- **Sempre** specificare il tema nelle views che usano `@vite`
- Usare alias configurati in `vite.config.js` per coerenza
- Verificare che `public/build/themes/{theme}/.vite/manifest.json` esista dopo `npm run build`

## Referenza
- [Vite Guide - Assets](https://vite.dev/guide/assets.html)
- Laravel Vite Integration: `vendor/laravel/framework/src/Illuminate/Foundation/Vite.php`