---
title: "00 INDEX: docs modulo Mobile"
type: index
status: active
updated: 2026-10-14
module: Mobile
tags: [mobile, nativephp, module, documentation]
qmd: "Mobile module NativePHP documentation index"
---

# Mobile - indice canonico

Ingresso unico del `docs/` di `laravel/Modules/Mobile`, secondo il
[contratto docs](../../../../bmad-output/docs-architecture/module-theme-docs-contract.md).
`INDEX.md` era un secondo ingresso: e' `superseded` da questo file.

## Identita' e ownership (verificato 2026-10-14)

- Modulo nwidart `Mobile` (`module.json`: alias `mobile`, richiede `fixcity`, `geo`, `user`), pacchetto `fixcity/mobile`
  che richiede `nativephp/mobile ^4.5` (installato 4.6.0). Repo git separato; working tree del modulo pulito.
- `MobileServiceProvider` estende `XotBaseServiceProvider` e non aggiunge nulla.
- Ruolo dichiarato: shell NativePHP per il front office FixCity. Il web e' Folio + Volt: i tre file in `routes/` restituiscono
  `[]` e `app/Http` non esiste (nessun controller). Le pagine Folio del modulo sono montate dal Cms su `/{locale}`.

## Cosa contiene davvero

| Area | Path | Stato |
|---|---|---|
| Schermate native per le segnalazioni | `app/Native/Screens/` (Map, TicketList, TicketDetail, CreateTicket) e `resources/views/native/screens/` | dati simulati; `CreateTicketScreen` rende la vista `ticket-detail` |
| Pagine Folio | `resources/views/pages/mobile/{index,map}.blade.php` | `/it/mobile` e `/it/mobile/map` danno 404; usano rotte `mobile.map` e `mobile.create` inesistenti |
| Configurazione | `config/mobile.php`, `config/bifrost.php` | `bifrost.php` ha ancora `updater.repo = restaurant-fila5` |
| Dominio cameriere, ordini, KDS | `app/Models` (`OrderQueue`, `WaiterSession`), `app/Actions/Mobile` (6), `app/Notifications` (2), `app/Filament`, `database/migrations/create_mobile_tables.php`, `database/seeders` (5), `app/Contracts/RestaurantGateway.php` | codice proprio del modulo, nessun import da `Modules\Restaurant`; decisione di rimozione aperta |
| Contratto agnostico | `app/Contracts/MobileGatewayContract.php` | namespace `Modules\Mobile\App\Contracts` non conforme al PSR-4: non caricabile |
| Test | `tests/Unit/Datas/RestaurantDataTest.php` | 1 test, passa; copre solo i data object ristorante |
| Qualita' | `phpstan analyse Modules/Mobile` | exit 0 il 2026-10-14 |

Prove di build e runtime (APK debug con SHA-256, log dell'emulatore, comandi `native:*` reali): [wiki/nativephp-build-and-run.md](wiki/nativephp-build-and-run.md).
iOS, push e offline non sono pronti: nessun artefatto.

## Directory

| Directory | Ruolo | Note |
|---|---|---|
| `wiki/` | canonica | regole e prove riusabili; [nativephp-build-and-run](wiki/nativephp-build-and-run.md), [log](wiki/log.md) |
| `stories/` | canonica (cronologia BMAD) | 9 file; le story 1.1, 1.7, 1.9, 1.10 sono del progetto ristorante, superseded da 1.11, da non cancellare ne' rinumerare |
| `decision-log.md` | canonica | decisioni indipendenti del modulo |
| `bmad/` | evidence | [README](bmad/README.md) e [dossier](bmad/bmad-dossier.md) trattano lo stesso tema (dossier BMAD) con testi diversi: nessuno dei due e' ancora il riferimento unico |
| `investigation/` | evidence | [investigation.md](investigation/investigation.md) e' un modello non compilato (Feature A/B/C), titolo diverso dal contenuto |
| `prompts/` | evidence | [01.md](prompts/01.md): richiesta dell'utente che ha portato alla rimozione dei controller |
| [README](README.md) | legacy, `stale` | 11 correzioni verificate in testa; criteri di accettazione, non stato |
| [INDEX](INDEX.md) | legacy, `superseded` | sostituito da questo file |

## Story e riferimenti

- contratto dati: [Datas e confine agnostico](architecture/data-contracts.md);

- [1.11 boundary del modulo](stories/1.11.mobile-module-boundary.story.md): ownership agnostica;
- [1.12 PHPStan cleanup](stories/1.12.phpstan-level-max-mobile-cleanup.story.md): rimozione dei controller Restaurant (sezione 2026-10-07);
- [1.8 integrazione NativePHP](stories/1.8.nativephp-integration.story.md): storico, non prova di stato attuale;
- [2026-10-14 studio docs e ingressi](stories/docs-mobile-studio-e-ingressi.story.md);
- consumer FixCity: [contratto NativePHP](../../Fixcity/docs/bmad/fixcity-nativephp-mobile.md) e [flussi attori](../../Fixcity/docs/actor-flows.md).

## Regole

La documentazione NativePHP descrive capability e acceptance criteria; un segno positivo richiede evidence runtime o native.
Prima di creare un `.md`, cercare il canonico con `qmd` o `rg`. I nuovi `.md` usano nomi lowercase senza date e senza suffissi
numerici sequenziali. Le story non si cancellano ne' si rinumerano.
