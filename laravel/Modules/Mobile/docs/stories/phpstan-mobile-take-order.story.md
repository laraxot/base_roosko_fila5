---
title: "[STORY] PHPStan Mobile: TakeOrderAction senza modello Fixcity"
qmd: "phpstan mobile take order action order queue waiter_session_id fixcity"
type: story
status: done
module: Mobile
tags: [phpstan, mobile, actions, offline-queue, module-agnosticism, swarm]
updated: 2026-10-14
related:
  - 1.7.mobile-waiter-order-taking
  - 1.11.mobile-module-boundary
  - 1.12.phpstan-level-max-mobile-cleanup
  - 2026-10-08-phpstan-mobile-scanqr-splitbill
---

# PHPStan Mobile: TakeOrderAction

Fase BMAD: Investigate, poi Dev (Quick Flow) e Docs.

## Scopo

`TakeOrderAction` e' la presa ordine del cameriere, offline-first (story 1.1 e 1.7): accoglie
tavolo, righe d'ordine e QR dal dispositivo e li accoda; `SyncOfflineOrdersAction` li invia al
server quando torna la rete. `ScanQrAction` rimanda a questa azione per l'inserimento delle righe.

## Causa radice

La versione in HEAD importava `Modules\Fixcity\Models\Ticket`, modello di un altro progetto del
monorepo (Fixcity, segnalazioni cittadine). In questo progetto non esiste il modulo Fixcity, quindi
`Ticket::query()` / `withoutEvents()` erano su classe sconosciuta e gli errori su `firstOrCreate()`,
`update()`, `$ticket->id`, `$ticket->code` e il cast a string erano solo la cascata di `mixed`.
Altre due anomalie nello stesso file: `namespace Modules\Mobile\Actions` dichiarato in
`app/Actions/Mobile/` (PSR-4 vuole `...\Actions\Mobile`) e il blocco `withoutEvents() + update()`
che riscriveva `content` con lo stesso valore appena creato (no-op).

## Decisione: (b), logica sul modello che Mobile gia' possiede

Evidenza:

- Il comportamento dichiarato nel docblock originale ("crea/aggiorna in stato offline, sincronizza
  quando online") coincide con `Modules\Mobile\Models\OrderQueue` (tabella `mobile_order_queue`,
  stato `pending` poi `synced|failed`), gia' usato da `SyncOfflineOrdersAction`, `SplitBillAction`,
  `OrderWidget`, factory e seeder del modulo. Nessun Model nuovo, nessun contratto nuovo.
- Nessuna dipendenza verso altri moduli: la direzione resta `Restaurant -> Mobile` (story 1.11) e
  Mobile non cita piu' Fixcity (`docs/wiki/rules/module-agnosticism.md`). Un contratto/config per
  risolvere il Model sarebbe stato un'astrazione con una sola implementazione.
- Non e' ammessa la rimozione (caso c): `rg -i "take[-_ ]?order"` non trova chiamanti in codice
  (solo il docblock di `ScanQrAction` e le story), ma le story 1.1 e 1.7 la descrivono come
  funzionalita' attiva (il controller API che la chiamava non e' piu' nel modulo, vedi 1.12).

Nota di processo: all'inizio dell'intervento il working tree conteneva gia' una modifica non
bloccata (mtime 2026-10-14 12:27) che sostituiva `Ticket` con `OrderQueue` e azzerava PHPStan, ma
non poteva persistere: `waiter_session_id` e' NOT NULL nella migrazione e non veniva valorizzato, e
`firstOrCreate(['table_id' => ...])` riusava qualunque riga del tavolo (anche `synced`),
sovrascrivendone `order_data`. Rispetto all'originale, che creava sempre una riga nuova, era un
cambio di comportamento. Va quindi trattata come bozza, non come baseline.

## Fix

- Namespace corretto in `Modules\Mobile\Actions\Mobile`.
- Una riga `OrderQueue` nuova per ogni chiamata (`create`, come l'originale con codice casuale),
  `status = OrderQueue::STATUS_PENDING`, `order_data` con lo stesso contenuto di prima. Rimosso il
  blocco `withoutEvents() + update()` (no-op).
- `waiter_session_id` letto dal payload e scritto nella colonna (e' la chiave che
  `SyncOfflineOrdersAction` usa per trovare la coda). `waiter_id` resta dentro `order_data`.
  `InvalidArgumentException` se `waiter_session_id` o `table_id` mancano: senza di essi l'ordine
  resterebbe in coda senza mai sincronizzarsi.
- Ritorna `$order->id` (uuid della riga) al posto del codice ticket. Nessun chiamante da adattare.
- Docblock riscritto sullo scopo reale. Un test Pest unit sulla guardia in
  `tests/Unit/Actions/TakeOrderActionTest.php`.

## Verifica

- `php -l` su azione e test: nessun errore.
- `phpstan analyse` sui due file: `[OK] No errors` (prima: errori su HEAD alle righe 43, 53, 54, 59 e a
  cascata, riportati dal coordinatore; il tentativo precedente nel working tree era verde ma non persisteva).
- PHPMD (`tools/phpmd.sh ... Modules/Geo/docs/phpmd.ruleset.xml`): working tree iniziale e HEAD
  senza finding; dopo la prima stesura 1 finding (`MissingImport` su `\InvalidArgumentException`),
  corretto con `use`; stato finale: 0 finding su azione e test.
- `pest Modules/Mobile/tests/Unit`: 2 passed (7 assertions), di cui il nuovo test sulla guardia.
- Non verificato: la scrittura reale su `mobile_order_queue` (MySQL di test non raggiungibile da
  questo host, come da story 2026-10-08).

## Aperto

- `mobile_order_queue.table_id` e' `foreignUuid` nella migrazione, ma `OrderQueue` (`@property int`),
  factory, seeder e `Restaurant\DiningTable` (`id()`) usano interi. L'azione segue il Model e
  converte `table_id` con `(int)`: va allineata migrazione o Model in una story dedicata.
- `created_at_utc` usa `date('c')` (fuso applicativo), non `gmdate`: comportamento invariato.
- `Mobile.lock` e' del 2026-10-05 (task psr4-autoload, agent codex): trattato come stale.
