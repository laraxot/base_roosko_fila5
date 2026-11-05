---
title: "NativePHP: build, esecuzione e prove verificate"
type: how-to
status: active
created: 2026-10-14
updated: 2026-10-14
module: Mobile
tags: [mobile, nativephp, android, build, evidence]
qmd: "mobile nativephp native:install native:run apk debug emulator evidence facade"
---

# NativePHP: build, esecuzione e prove verificate

Nota di riferimento del modulo `Mobile`. Sostituisce nei fatti la sezione "Build & Configuration" del
[README](../README.md), che descriveva comandi inesistenti. Ogni riga ha la sua prova; quello che non e' stato
rieseguito oggi e' marcato come tale.

## Versione e dipendenza

- `composer.json` del modulo richiede `nativephp/mobile ^4.5`. Il progetto lo carica tramite `wikimedia/composer-merge-plugin`
  (`laravel/composer.json`, include `Modules/*/composer.json`).
- Installata: 4.6.0 (`laravel/vendor/composer/installed.json`, `php artisan native:version` il 2026-10-14).
  `docs/INDEX.md` riportava 4.5.2 e il README `^3.0`: entrambi superati.
- `config/app.php` registra `Native\Mobile\NativeServiceProvider` e `Modules\Mobile\Providers\MobileServiceProvider`.
  `app/Providers/NativeServiceProvider.php` del progetto ha `plugins()` vuoto: nessun plugin NativePHP e' compilato nelle build.

## Comandi che esistono (eseguire da `laravel/`)

Elenco da `php artisan list` e dai `--help`, 2026-10-14:

| Comando | Uso |
|---|---|
| `php artisan native:install [android\|ios]` | installa le risorse native e scarica i binari PHP (`--force`, `--skip-php`, `--with-icu`) |
| `php artisan native:run [android\|ios] [udid]` | build, pacchetto ed esecuzione (`--build=debug\|release\|bundle\|profileable`, `--watch`, `--start-url=/percorso`) |
| `php artisan native:build`, `native:package` | build e pacchetto firmato per la distribuzione |
| `php artisan native:debug`, `native:emulator`, `native:jump`, `native:open` | diagnostica, emulatore, server di sviluppo, apertura del progetto Android Studio o Xcode |

Non esistono: `./vendor/bin/nativephp` (assente in `laravel/vendor/bin`), `npx nativephp configure:android`, la facade
`NativePHP::camera()` / `NativePHP::http()` / `NativePHP::location()` / `NativePHP::notifications()` (nessuna classe `NativePHP`
in `vendor/nativephp/mobile/src`). Le facade reali stanno in `Native\Mobile\Facades`: Biometrics, Browser, Camera, Device,
Dialog, File, Geolocation, Haptics, Microphone, MobileWallet, Network, PushNotifications, Scanner, SecureStorage, Share, System.

`laravel/Modules/Mobile/build-apk.sh` non compila nulla: scrive file demo in `resources/json/` (cartella oggi inesistente) e stampa
righe con la spunta (`view_map ✓`, `moderate_reports ✓`) senza verificare alcun flusso. Non e' una prova di nulla.

## Configurazione

- `config/mobile.php`: `MOBILE_ENABLED`, `MOBILE_AUTH_GUARD` (default `api`), `MOBILE_OFFLINE_TTL`, `MOBILE_SYNC_INTERVAL`,
  `NATIVEPHP_ONESIGNAL_APP_ID`, `device_storage`.
- `config/bifrost.php`: `NATIVEPHP_APP_NAME`, `NATIVEPHP_APP_VERSION`, `NATIVEPHP_APP_ID`, finestra, plugin. Contiene ancora
  il testo del progetto ristorante: `updater.repo = restaurant-fila5` e motivazioni dei permessi camera "per scansionare QR di
  menu e tavoli". Da rivedere prima di una build di rilascio.
- `laravel/.env` definisce `NATIVEPHP_APP_ID`, `NATIVEPHP_APP_VERSION`, `NATIVEPHP_APP_VERSION_CODE`, `NATIVEPHP_START_URL`
  (valori non riportati qui).

## Prove disponibili

| Affermazione | Prova | Stato |
|---|---|---|
| Esiste un APK debug | `laravel/nativephp/android/app/build/outputs/apk/debug/app-debug.apk`, 556649075 byte, SHA-256 `3b9cd7efc35a3f30c0f27c8a9d84baac9eccb77828ff28e6d603831eea3b32a0` | verificato il 2026-10-14 (coincide con `INDEX.md`) |
| E' stato installato e avviato su emulatore | `laravel/nativephp/android-build.log` (587 righe): `adb -s emulator-5554 install -r -d` e `am start` il 2026-09-28 02:46, "Build Completed" | letto il log, non rieseguito |
| Bundle estratto, `/it/tickets` 200 nell'app, CSS/JS 200 | solo la dichiarazione in `INDEX.md` | non verificato oggi |
| iOS, push, offline | nessun artefatto | non pronti |
| PHPStan sul modulo | `./vendor/bin/phpstan analyse Modules/Mobile` da `laravel/`, exit 0 il 2026-10-14 | verificato |
| Test | `./vendor/bin/pest Modules/Mobile/tests`: 1 test (`tests/Unit/Datas/RestaurantDataTest.php`), 4 asserzioni, passa | verificato; copre solo i data object ristorante |

Vincolo: una build iOS firmata richiede macOS e Xcode, non producibile da questo host Linux.

## Limiti noti del codice (non corretti qui)

- `Modules\Mobile\Contracts\RestaurantGateway` e `Modules\Mobile\Datas\*Data` dichiarano il namespace canonico del modulo
  `App` maiuscolo che non corrisponde al PSR-4 (`Modules\Mobile\` -> `app/`): `interface_exists` risponde falso per entrambi i nomi
  (tinker, 2026-10-14). Il contratto "agnostico" del README non e' quindi caricabile.
- `app/Native/Screens/CreateTicketScreen.php` rende la vista `mobile::native.screens.ticket-detail`; in `resources/views/native/screens/` esistono solo `map`, `ticket-detail` e `ticket-list`, nessuna vista di creazione.
- `MapScreen` e `TicketListScreen` usano dati simulati (`markerCount = 27`, `getDemoTickets()`), non il contratto GeoJSON di Fixcity.
- Duplicati byte per byte: `app/Models/OrderQueue.php` = `app/Models/Mobile/OrderQueue.php`, `WaiterSession.php` idem,
  `app/MobilePluginRegistry.php` = `app/Plugins/MobilePluginRegistry.php` (stesso namespace `Modules\Mobile`).
