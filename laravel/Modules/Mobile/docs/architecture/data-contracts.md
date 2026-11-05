---
title: "Mobile data contracts"
type: architecture
status: active
module: Mobile
tags: [mobile, datas, contracts, psr-4]
updated: 2026-10-23
qmd: "contratti dati mobile"
---

# Contratti dati

Le classi dati pubbliche del modulo vivono in `app/Datas/` e usano il namespace
`Modules\Mobile\Datas`. `Datas` è il contenitore condiviso per gli oggetti che
trasportano dati tra Action, contratti e adapter; `Data` al singolare non è una
cartella canonica del modulo.

Questa distinzione mantiene coerenti PSR-4, autoload Composer e i moduli che
consumano i contratti senza conoscere la persistenza. Un nuovo DTO/data object va
quindi aggiunto a `app/Datas/`, con test sotto `tests/Unit/Datas/`, e referenziato
con il namespace `Modules\\Mobile\\Datas`.

I contratti `RestaurantGateway` e le Action Mobile dipendono da questi oggetti,
ma non dai modelli del ristorante: il confine resta agnostico e serializzabile.
