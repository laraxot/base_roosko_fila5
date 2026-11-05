# 📱 Mobile — agnostic NativePHP host module

## Contratto del modulo

## Descrizione
Modulo mobile **NativePHP** riusabile per iOS/Android. Non contiene logica di dominio
FixCity né presuppone ristorante, Comune o altro verticale: il progetto host registra
le proprie schermate native e fornisce gli adapter tramite contratti.

## Consumer FixCity

Il consumer FixCity usa il GeoJSON canonico `public_html/data/tickets.json` e le
stesse policy/Actions del web. La schermata nativa iniziale è documentata in
`Modules/Fixcity/docs/bmad/fixcity-nativephp-mobile.md`.

### 1. Cittadino non loggato
- Visualizzazione mappa con segnalazioni (GeoJSON)
- Invio segnalazione rapida (guest)
- Dettaglio ticket

### 2. Cittadino loggato
- Track ticket in tempo reale
- Storico segnalazioni
- Notifiche push
- Risposta PA

### 3. Operatore di quartiere
- Gestione ticket assegnati
- Note interne
- Aggiornamento stato
- Geo-search

### 4. Amministrazione comunale
- Dashboard PA completa
- Statistiche e export
- Configurazione moduli
- Reportistica

## Installazione
```bash
cd laravel
composer require nativephp/mobile
# Genera lo shell Android NativePHP secondo la documentazione ufficiale
php artisan native:install
php artisan native:debug
```

L'APK debug verificato per questa demo è disponibile in
`laravel/nativephp/android/app/build/outputs/apk/debug/app-debug.apk`.
La firma release, iOS/macOS, push provider e store submission richiedono credenziali
e ambienti esterni: non vengono simulati né inclusi nel modulo.

## Architettura
```
Mobile/
├── app/
│   ├── Actions/       # Azioni business
│   ├── Contracts/     # Interfacce
│   ├── Models/        # Eloquent models
│   └── Providers/         # Module registration; no front-office controllers
├── resources/views/   # Blade (PWA-friendly)
├── docs/             # Documentazione
└── module.json       # Manifest
```

## Dipendenze
- nativephp/mobile ^4.5
- fixcity (core)
- geo (mappa)
- user (auth)

## Status verificato
- ✅ modulo agnostico, senza controller FO, route file o seeders verticali
- ✅ contratto gateway disponibile per il consumer di dominio
- ✅ NativePHP Mobile 4.5.2 installato e shell Android generato
- ✅ APK Android debug generato in `nativephp/android/app/build/outputs/apk/debug/app-debug.apk`
- ⚠️ le schermate native FixCity sono un adapter consumer da completare dopo la
  decisione del contratto di integrazione Folio/Volt; le pagine web restano nel modulo
  owner e non vengono duplicate qui
