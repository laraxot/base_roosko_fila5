---
title: "[STORY] PHPStan Mobile: ScanQrAction e SplitBillAction"
type: story
status: done
priority: medium
created: 2026-10-08
updated: 2026-10-08
module: Mobile
tags: [phpstan, mobile, actions, dead-parameter, transaction, swarm]
related:
  - 1.12.phpstan-level-max-mobile-cleanup
---

# PHPStan Mobile: ScanQrAction e SplitBillAction

Fase BMAD: Dev + Docs.

## Richiesta

Azzerare gli errori PHPStan guardando lo scopo. Perimetro: `method.unusedParameter` in `ScanQrAction`, `argument.type` in `SplitBillAction`.

## Analisi

### ScanQrAction: `$waiterSessionId` era vestigiale, non logica persa

Il parametro non e' mai stato usato: la prima versione (05c6ae1, 2026-09-24, con i modelli Restaurant) lo passava a `handleProductQr()` senza leggerlo. Il docblock prometteva "adds to current order", ma nemmeno allora l'azione scriveva su un ordine. Non c'e' specifica per implementarlo: la story 1.7 chiede solo di "scansionare QR/barcode dei piatti"; `OrderQueue` e' una coda di ordini offline per sessione cameriere, quindi "ordine corrente" e' ambiguo. Le righe d'ordine entrano da `TakeOrderAction`, che riceve `items` e `qr_code`.

Chiamanti: nessuno nel monorepo (`rg` su Modules e Themes; solo una riga nel `docs/wiki/log.md` del monorepo). Quindi il parametro e' stato tolto anche da `execute()`, dove PHPStan non lo segnalava ma aveva lo stesso difetto; il docblock ora dice cosa fa davvero. L'azione resta a dati mock di demo (stato da commit 1536bb5).

### SplitBillAction: shape del risultato

`DB::transaction()` e' generico sul tipo di ritorno della closure; la closure dichiarava `array` generico e accumulava in `$results` catturato per riferimento, con un `@var` inline sul risultato. Ora `$results` e' locale alla closure (niente `&`, e un eventuale retry della transazione non accumula doppioni) e il tipo e' dichiarato nel `@return` pubblico: `list<array{order_id: string, amount: float, method: string, notes: string}>`. Tolto il `@var`.

Il commento `// Check if fully paid` era orfano: la versione Restaurant chiudeva l'ordine (`status = paid`) a pagamenti completi; `OrderQueue` non ha lo stato `paid` (pending, syncing, synced, failed), quindi oggi le quote restano in `order_data.splits`. Commento corretto.

## Verifica

- `php -l` sui due file: nessun errore.
- `phpstan analyse` sui due file (con gli altri 5 dello swarm `small-modules`): `[OK] No errors`.
- Pest: non eseguito (MySQL di test 10.100.200.53:3306 in timeout da questo host, rc=124; in `tests/Unit` solo `Datas/RestaurantDataTest`, nessun test sulle due azioni).

## Aperto

- `SplitBillAction`: il controllo `status === 'paid'` non puo' scattare con gli stati di `OrderQueue`, e un ordine non viene mai marcato saldato. Serve la decisione su dove vive il dominio ristorante (vedi debito di 1.12).
- Le tre `handle*Qr` restituiscono dati mock.
