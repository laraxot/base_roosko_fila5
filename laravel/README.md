# Roosko — applicazione Laravel

Questa cartella è l'applicazione Laravel di Roosko. Panoramica del prodotto, moduli, installazione e regole del progetto sono nel [README principale](../README.md).

## Dove sono le cose

| Percorso | Contenuto |
| --- | --- |
| `Modules/` | un modulo per cartella, ognuno dal proprio repository (`gitmodules.ini` nella radice) |
| `Themes/` | temi del frontend |
| `config/local/roosko/` | configurazione del tenant Roosko (host `roosko.local`): moduli attivi, `xra.php`, database, autenticazione |
| `config/localhost/` | stessa configurazione per lo sviluppo su `localhost` |
| `modules_statuses.json` | moduli attivi quando nessun tenant è risolto |

## Tenant

Il modulo Tenant ricava il tenant dall'host (`roosko.local` → `config/local/roosko`) e unisce i file di quella cartella alla configurazione di Laravel. Per ogni modulo attivo crea una connessione al database con il nome del modulo, copiando la connessione predefinita; in `config/local/roosko/database.php` si ridefinisce solo quella che deve puntare a un database diverso (per esempio `user`).

## Comandi

```bash
composer install
php artisan migrate
./vendor/bin/phpstan analyse
./vendor/bin/pest
```

La documentazione di ogni modulo è in `Modules/{Modulo}/docs/README.md`.
