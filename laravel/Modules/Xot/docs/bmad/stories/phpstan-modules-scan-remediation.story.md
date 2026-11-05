---
title: "Ripristinare il gate PHPStan globale dei moduli"
type: story
module: Xot
status: in-progress
created: 2026-11-05
updated: 2026-11-05
tags: [bmad, phpstan, modules, remediation]
qmd: "PHPStan analyse Modules full tree trait extraction errors remediation gate"
related:
  - ./model-methods-traits-extraction.story.md
  - ./accessor-twin-contract-audit.story.md
---
# Ripristinare il gate PHPStan globale dei moduli

## Obiettivo

Eseguire `php -d memory_limit=2G ./vendor/bin/phpstan analyse Modules`, correggere
gli errori alla sorgente e mantenere il gate pulito durante le estrazioni Eloquent
condivise. La story precedente citata nel `sprint-status.yaml` non è presente nel
filesystem; questa story ripristina una traccia reale e verificabile.

## Criteri di accettazione

- [x] Completare il gate globale dopo che gli agenti hanno terminato le scritture concorrenti.
- [x] Correggere ogni errore risultante, assegnando ownership BMAD e lock per file.
- [x] Ottenere exit 0 sul comando globale e registrare il conteggio.
- [ ] Eseguire Pest mirato per i moduli toccati o documentare lo skip ammesso.

## Primo esito

- Primo tentativo completo: exit 1 durante una finestra di modifiche concorrenti,
  con errori fatali di collisione trait in `Sigma/Asz00k1.php` e `Sigma/Qua00f.php`.
- Retry completo: 66 errori PHPStan, concentrati nei trait Sigma appena estratti
  (namespace/import PHPDoc non risolti e un helper rimasto inutilizzato). Il tree
  condiviso cambia durante l'audit; il log dettagliato è
  `/tmp/phpstan-modules-scan-remediation.log`.
- Terzo scan completo: `php -d memory_limit=2G ./vendor/bin/phpstan analyse Modules`
  exit 0, `10568/10568`, `[OK] No errors`. Le estrazioni concorrenti erano nel
  frattempo state completate/corrette. Il primo snapshot non va usato come
  inventario finale.
- Nessun ignore PHPStan né baseline aggiunto. I file Sigma hanno ownership/lock
  attivi; i fix vanno coordinati col relativo owner, senza sovrascriverne i diff.
