---
type: story
tags: [security, weighing, authorization, permissions, rate-limit]
epic: security-audit
story: A2-A3-M4-B5
slug: weighing-authorization
title: "Weighing: policy che negano le abilità non dichiarate, azioni autorizzate, permessi seminati, rate limit sulla lettura bilancia"
status: review
phase: REVIEW
owner: Weighing
depends_on: []
related:
  - "../../../Labeling/docs/stories/labeling-authorization.story.md"
created: 2026-11-06
updated: 2026-11-06
---

# Weighing: autorizzazione completa

## Story

Come **responsabile dell'impianto**
voglio **che il pesatore possa aprire e chiudere pesate ma non annullarle, e che chi consulta non legga la bilancia**
così da **proteggere il valore legale dei ticket senza dare super-admin a tutti**.

## Perché / evidenze

- **A2**: senza `deleteAny` dichiarato, chi aveva `scale.viewAny` poteva eliminare in blocco le bilance.
- **A3**: "Leggi bilancia" (connessione TCP), apri/chiudi/annulla pesata controllavano solo lo stato del record.
- **M4**: permessi delle policy mai seminati (`PermissionDoesNotExist` → 500).
- **B5**: letture bilancia ripetibili senza limite.

## Soluzione

- `DeniesUndeclaredFilamentAbilities` in `ScalePolicy` e `WeighingPolicy`; nuove abilità `readScale` (`scale.readScale`) e `void` (`weighing.void`).
- Leggi bilancia: `authorize()` + controllo prima della lettura + `rateLimit(weighing.read_rate_limit)`.
- Nuova pesata / pesata singola `authorize('create')`, chiusura `authorize('update')`, annullamento `authorize('void')`.
- `WeighingPermissionSeeder` (permessi + ruolo `weighing::admin`), chiamato dal DatabaseSeeder.

## Acceptance criteria

1. Le policy negano `deleteAny`, `forceDeleteAny`, `reorder`.
2. Senza abilità i bottoni leggi/apri/singola/chiudi/annulla non sono autorizzati; ognuno si apre con la sua abilità; `update` non concede `void`.
3. Il seeder crea esattamente i permessi usati dalle policy.
4. Le letture bilancia hanno un rate limit configurabile.

## Note per i consumer

- Roosko che riusa `ReadScaleFieldAction` eredita il controllo `scale.readScale`: gli operatori di piazzale devono avere quel permesso.

## Dev agent record

- File: `app/Models/Policies/*.php`, `app/Filament/Actions/Concerns/InteractsWithScaleReading.php`,
  `app/Filament/Resources/WeighingResource/Pages/{ListWeighings,ViewWeighing}.php`,
  `database/seeders/{WeighingPermissionSeeder,WeighingDatabaseSeeder}.php`, `config/config.php`, `docs/README.md`.
- Test: `tests/Feature/WeighingAuthorizationTest.php`.
