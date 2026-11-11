# Roosko

Applicazione base di **Roosko**, il gestionale per impianti di trattamento e cernita dei rifiuti. Il nome viene da *rusco*, rifiuto in dialetto.

Roosko segue ogni rifiuto, pericoloso e non, dall'arrivo in impianto fino all'uscita:

1. **Conferimento**: arrivo con FIR, pesata lordo e tara, accettazione o respingimento.
2. **Identificazione**: ogni contenitore accettato diventa un'unità con etichetta QR o tag RFID.
3. **Stoccaggio mappato**: ogni unità va in una posizione del magazzino; il sistema controlla che l'area sia autorizzata per quel codice EER e che i limiti di stoccaggio non siano superati.
4. **Lavorazione**: triturazione, cernita, pressatura; i prodotti diventano nuove unità con nuova etichetta, legate ai lotti d'origine con bilancio di massa.
5. **Uscita**: vendita come End of Waste, recupero presso terzi, smaltimento o bonifica, con FIR o DDT.
6. **Adempimenti**: registro di carico e scarico compilato in automatico e trasmissioni a RENTRI (FIR digitale, accettazione, registro).

In piazzale si lavora da palmare o telefono: lettori barcode/RFID in modalità tastiera, fotocamera per i QR, pagina operatore installabile come app.

## Architettura

Monolite modulare [Laraxot](https://github.com/laraxot): Laravel 13, Filament 5, `nwidart/laravel-modules`. Ogni modulo è un repository separato e ha un proprio pannello di amministrazione all'indirizzo `/{modulo}/admin`; il pannello principale `/admin` elenca i moduli a cui l'utente ha accesso (ruolo `{modulo}::admin`).

### Moduli di dominio

| Modulo | Pannello | Cosa fa | Repository |
| --- | --- | --- | --- |
| Roosko | `/roosko/admin` | Prodotto: conferimenti, lotti, lavorazioni in corso, uscite, regole di stoccaggio, pagina operatore | [module_roosko_fila5](https://github.com/laraxot/module_roosko_fila5) |
| Warehouse | `/warehouse/admin` | Magazzini, aree, posizioni, mappa, unità di stoccaggio, movimenti | [module_warehouse_fila5](https://github.com/laraxot/module_warehouse_fila5) |
| Processing | `/processing/admin` | Macchine, ordini di lavorazione, bilancio di massa, tracciabilità | [module_processing_fila5](https://github.com/laraxot/module_processing_fila5) |
| Weighing | `/weighing/admin` | Bilance (TCP/ASCII, manuale), pesate a due fasi, ticket | [module_weighing_fila5](https://github.com/laraxot/module_weighing_fila5) |
| Labeling | `/labeling/admin` | Identificatori QR/barcode/RFID, template etichette, stampa ZPL e PDF | [module_labeling_fila5](https://github.com/laraxot/module_labeling_fila5) |
| Waste | `/waste/admin` | Codici EER, soggetti e unità locali, autorizzazioni, FIR, registri di carico e scarico | [module_waste_fila5](https://github.com/laraxot/module_waste_fila5) |
| Rentri | `/rentri/admin` | Client delle API RENTRI: credenziali, FIR digitale, trasmissione registri | [module_rentri_fila5](https://github.com/laraxot/module_rentri_fila5) |

Labeling, Weighing, Warehouse e Processing sono generici e non sanno nulla di rifiuti: si possono riusare in altri progetti. Waste e Rentri contengono il dominio normativo; Roosko li orchestra.

Dipendenze (ogni modulo dipende anche da Xot):

- Labeling, Weighing: nessuna
- Warehouse → Labeling
- Processing → Warehouse
- Waste → Geo
- Rentri → Waste
- Roosko → tutti i moduli di dominio

### Moduli di base

Xot (basi Laraxot), User, Geo, Lang, Media, UI, Tenant, Job, Activity, Notify, Gdpr e gli altri elencati in [`gitmodules.ini`](gitmodules.ini).

## Struttura del repository

```
.
├── gitmodules.ini      # elenco dei repository di moduli e temi, con il percorso di destinazione
├── laravel/            # applicazione Laravel
│   ├── Modules/        # un modulo per cartella, ognuno dal proprio repository
│   └── Themes/         # temi del frontend
└── public_html/        # document root
```

## Requisiti

- PHP 8.4
- Composer 2 (Node e npm solo per chi modifica JS o CSS: i bundle compilati sono nel repository)
- Un database supportato da Laravel. Ogni modulo usa una connessione con il proprio nome (`labeling`, `weighing`, `warehouse`, `processing`, `waste`, `rentri`, `roosko`): il modulo Tenant la crea copiando la connessione predefinita, e in `laravel/config/local/roosko/database.php` si ridefinisce solo quella che deve puntare a un database diverso
- HTTPS per la fotocamera della pagina operatore

## Installazione

1. Clona questo repository e porta in `laravel/Modules` e `laravel/Themes` i repository elencati in [`gitmodules.ini`](gitmodules.ini), ognuno nel percorso indicato.
2. Installa le dipendenze:
   ```bash
   cd laravel
   composer install   # installa anche wikimedia/composer-merge-plugin
   composer update    # al primo giro: aggiunge le dipendenze dei moduli attivi (merge-plugin)
   cp .env.example .env
   php artisan key:generate
   ```
3. Configura il database in `.env`. L'host `roosko.local` (`APP_URL` di `.env.example`) usa il tenant `config/local/roosko`, che attiva già i moduli necessari; in locale vale lo stesso per `config/localhost`.
4. Esegui le migrazioni:
   ```bash
   php artisan migrate
   ```
5. Collega gli asset alla document root con symlink relativi (`public_html/assets/<modulo>` →
   `../../laravel/Modules/<Modulo>/public`, `public_html/themes/<Tema>` → `../../laravel/Themes/<Tema>/public`).
   Niente copie: i bundle compilati sono già nella cartella `public/` di moduli e temi.
   ```bash
   php artisan xot:module-link && php artisan xot:theme-link
   ```
   Se modifichi JS o CSS di un modulo: `npm ci && npm run build` nel modulo e commit di `public/`. La CI
   (job «Bundle Vite versionati») controlla che il bundle corrisponda ai sorgenti.
6. Assegna agli utenti i ruoli `{modulo}::admin` dei moduli che devono vedere.

In produzione si rilascia con `./deploy.sh`: backup, dipendenze, link degli asset, migrazioni, cache,
riavvio delle code e `roosko:production-check`. Il runbook completo è in
`laravel/Modules/Roosko/docs/operations/production-runbook.md`.

Prima di andare in produzione su RENTRI configura una credenziale dell'ambiente **demo** (`https://demoapi.rentri.gov.it`) e verifica i punti segnati nella documentazione del modulo Rentri.

## Qualità

Dalla cartella `laravel/`:

```bash
./vendor/bin/phpstan analyse    # livello 10
./vendor/bin/pest
```

Regole del progetto: logica di business solo in Spatie queueable actions (niente Service né Job), DTO con `spatie/laravel-data`, stati come enum, etichette solo nei file di traduzione, PHPStan livello 10.

## Documentazione

- Ogni modulo ha la sua guida in `laravel/Modules/{Modulo}/docs/README.md`: modelli, actions, flussi, punti di estensione e punti aperti.
- Il manuale d'uso per gli utenti (operatori, accettazione, responsabile tecnico, amministratore) descrive i flussi con le schermate dei pannelli.
