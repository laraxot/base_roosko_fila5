---
title: "Mobile: studio del modulo e riordino degli ingressi docs"
type: story
status: done
created: 2026-10-14
updated: 2026-10-14
module: Mobile
tags: [docs, organizzazione, mobile, nativephp, bmad]
related: [bmad-output/stories/docs-organization/README.md, bmad-output/docs-architecture/module-theme-docs-contract.md, laravel/Modules/Fixcity/docs/stories/dcp-56-organizzazione-docs-moduli-e-temi.story.md]
qmd: "mobile docs studio ingressi readme stale 00-INDEX nativephp apk verifica"
---

# Mobile: studio del modulo e riordino degli ingressi docs

Fase BMAD: documentazione e qualita' (DOC-010 e DOC-002 del piano `bmad-output/stories/docs-organization/`).

## Richiesta

Studiare il codice vero di `laravel/Modules/Mobile`, confrontarlo con `docs/` e correggere gli ingressi con soli fatti verificati,
seguendo il contratto dei docs (README, 00-INDEX, wiki, stories, decision log). Mobile non aveva `wiki/` e aveva due ingressi
(`00-INDEX.md` e `INDEX.md`) che si dichiaravano entrambi canonici.

## Analisi

Il modulo e' una shell NativePHP con codice di due origini: le 4 schermate native per le segnalazioni FixCity
(`app/Native/Screens/`, dati simulati) e il residuo del progetto ristorante (modelli `OrderQueue` e `WaiterSession`, 6 Actions, 2 Notifications,
risorse Filament, migration `create_mobile_tables.php`, 5 seeder). Le rotte sono vuote per scelta (Folio + Volt).

Il README era scritto come specifica e descriveva comandi, file e rotte inesistenti. Le 11 divergenze sono elencate in testa al README con
la prova di ciascuna. Il piu' rischioso: `build-apk.sh` stampa spunte fisse (`view_map ✓`, `moderate_reports ✓`) senza eseguire nulla, e la
checklist del README lo citava come "APK build script created".

Prove raccolte il 2026-10-14: `nativephp/mobile` 4.6.0; APK debug presente con SHA-256 uguale a quello di `INDEX.md`; `android-build.log` con
install e avvio su `emulator-5554` il 2026-09-28; PHPStan e Pest verdi; `/it/mobile` e `/it/mobile/map` 404.

## Modifiche (solo in `laravel/Modules/Mobile/docs/`)

| File | Modifica |
|---|---|
| `00-INDEX.md` | ingresso unico: identita', cosa contiene davvero, tabella directory (canoniche, evidence, legacy), link ai soli file esistenti; corretto il link rotto a `fixcity-nativephp-mobile-2026-09-27.md` (esiste `fixcity-nativephp-mobile.md`) |
| `README.md` | frontmatter aggiunto, `status: stale`, nota di audit e 11 correzioni verificate in testa; testo originale invariato; riga Package annotata |
| `INDEX.md` | `status: superseded`, `superseded_by: 00-INDEX.md`, nota con le due affermazioni superate (4.6.0 invece di 4.5.2; "agnostico" = nessun import da `Modules\Restaurant`) |
| `decision-log.md` | frontmatter YAML valido (`#XXX` quotato) e compilato: 3 decisioni con prova, 6 domande aperte |
| `investigation/investigation.md` | frontmatter YAML valido e `status: stale` con motivo (modello non compilato) |
| `wiki/nativephp-build-and-run.md` | nuova: comandi `native:*` reali, configurazione, tabella delle prove, limiti del codice |
| `wiki/log.md` | nuova: voce 2026-10-14 |

Non toccati: `stories/` (policy), `bmad/`, `prompts/01.md` (richiesta dell'utente), nessun file di codice.

## Verifica

```
phpstan analyse Modules/Mobile            (da laravel/)   exit 0, nessun errore
pest Modules/Mobile/tests                                  1 passed (4 assertions), 0.07s
sha256sum nativephp/android/app/build/outputs/apk/debug/app-debug.apk
  3b9cd7efc35a3f30c0f27c8a9d84baac9eccb77828ff28e6d603831eea3b32a0
php artisan native:version                                 NativePHP Mobile: 4.6.0
php artisan route:list --name=mobile                       nessuna rotta
curl 127.0.0.1:8081  /it 200  /it/mobile 404  /it/mobile/map 404  /it/tickets 200
tinker interface_exists(Modules\Mobile\App\Contracts\MobileGatewayContract)  false
docs-organizer links Mobile/docs --by-name --busy-minutes 0 (dry-run)
  prima: 1 unresolved   dopo: 0 (misurato dopo la creazione di questa story)
```

I link nei file toccati puntano tutti a file esistenti. La validita' YAML del frontmatter e' stata controllata con `yaml.safe_load` su tutti
i `.md` di `docs/`: prima 2 file invalidi (`decision-log.md`, `investigation/investigation.md`), poi 0 (restano 6 file senza frontmatter:
`prompts/01.md` e 5 story, escluse dal tool per policy).

## Decisioni

- Il canonico e' `00-INDEX.md` (contratto), non `INDEX.md`; il vecchio ingresso non si cancella, si marca `superseded`.
- Il README non si riscrive: si marca `stale` e si corregge in testa, perche' contiene criteri di accettazione che servono ancora.
- Un solo `wiki/` minimo, con due file che servono davvero (come costruire e provare; log). Nessuna cartella vuota.
- Nessuna modifica al codice: i difetti trovati vanno nel decision log come domande aperte.

## Aperto

1. Decidere il destino del dominio cameriere/ordini/KDS (decision log, domanda 1).
2. Namespace `App` di `MobileGatewayContract` e `MobileDemoData`; duplicati byte per byte (`OrderQueue`, `WaiterSession`, `MobilePluginRegistry`).
3. Pagine Folio `pages/mobile/*` in 404 e rotte `mobile.map` e `mobile.create` inesistenti.
4. `build-apk.sh` e `batch-rename-docs.sh` nella radice del modulo: uno stub che finge verifiche, l'altro non esaminato; da valutare prima di toglierli.
5. Rieseguire la prova runtime Android (`/it/tickets` nell'app) con il comando `native:run`.
6. `docs-organizer stale` rifiuta i file senza frontmatter (README.md di Mobile): difetto dello strumento, qui aggirato aggiungendo il frontmatter a mano.
