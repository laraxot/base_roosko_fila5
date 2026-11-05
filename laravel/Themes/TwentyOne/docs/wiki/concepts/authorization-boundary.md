---
title: "Authorization Boundary"
type: concept
tags: [twentyone, authorization, permissions, bridge-only]
created: 2026-09-24
updated: 2026-09-24
---

# Authorization Boundary

## Scopo

TwentyOne è un tema bridge-only: possiede layout, blocchi e composizione
visiva, ma non è il proprietario di ruoli, permessi, team o dati di
autorizzazione.

## Regole

- Il modulo `User` possiede `UserContract`, i modelli `Permission`/`Team`, i
  trait Spatie e la semantica di `hasPermissionToOrCreate()`.
- Xot espone il contratto e i contratti condivisi; non duplica l'implementazione
  del metodo nel tema o nei widget.
- I consumer del tema usano `Gate`, `can()` e le Policy Laravel per autorizzare
  azioni; non controllano i permessi con liste hard-coded nel Blade.
- Se `permission.teams` è attivo, il tema deve rispettare il team context
  impostato dal modulo User tramite `setPermissionsTeamId()` e non modificarne la
  configurazione.
- Il rendering di un elemento non equivale a un controllo di sicurezza: il
  controllo autorevole resta nel Gate/Policy e nel producer del dato.

## Riferimenti

- [User permissions](../../../../../Modules/User/docs/permissions.md)
- [Spatie permission methods](../../../../../Modules/User/docs/SPATIE_PERMISSIONS_METHODS.md)
- [Xot roles and permissions](../../../../../Modules/Xot/docs/roles-permissions.md)

## Criterio di manutenzione

Prima di introdurre un controllo autorizzativo nel tema, verificare il
proprietario del dato e riusare il contratto esistente. Se il controllo richiede
nuovi permessi, modificare il modulo User/Xot e aggiornare i propri indici; non
nascondere la decisione in TwentyOne.
