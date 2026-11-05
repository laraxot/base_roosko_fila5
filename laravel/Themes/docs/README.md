---
title: "Laravel themes documentation hub"
type: readme
status: active
created: 2026-10-20
updated: 2026-10-20
tags: [documentation, themes, bmad, ownership]
---

# Documentazione dei temi Laravel

Questo è l'hub di navigazione dei temi. Ogni tema mantiene il proprio contratto
tecnico in `laravel/Themes/<Theme>/docs/`; qui si registrano solo ownership,
confini e link canonici. Non è una seconda fonte di verità.

Il contratto comune per nomi, ownership, archivi e indici è
[DOCUMENTATION_GOVERNANCE.md](../../docs/DOCUMENTATION_GOVERNANCE.md).
Il `README.md` del singolo tema è l'ingresso canonico; gli eventuali `INDEX.md`
sono mappe operative mantenute per compatibilità e non introducono una seconda
versione dei contenuti.

## Tema pubblico attivo

- [Sixteen](../Sixteen/docs/README.md) — front office FixCity, CMS-driven, Folio,
  Tailwind + Alpine + Lit + DaisyUI + Flowbite.

## Temi non attivi

- [TwentyOne](../TwentyOne/docs/README.md) — importato da un contesto esterno,
  non è il tema pubblico corrente.
- [Barthelemy](../Barthelemy/docs/README.md) — tema disponibile ma non attivo.
- [Meetup](../Meetup/docs/README.md) — tema disponibile ma non attivo.

## Regole di ownership

- Il tema possiede shell, componenti visuali, asset, CSS/JS e test browser.
- Il modulo Cms possiede risoluzione di slug, pagine, blocchi e contratto
  `PageSlugMiddleware`/`<x-page>`.
- Il modulo di dominio possiede Action, modello, catalogo e policy dei dati.
- I JSON tenant dichiarano blocchi e contenuti; una Folio page generica li
  monta e non li duplica.
- Una documentazione nuova va nella cartella `docs/` del proprietario reale;
  `bmad-output/` è legacy/read-only per nuovi documenti di progetto.

## Verifica rapida

Per una pagina pubblica, risalire sempre a:

`URL → Folio shell → pageSlug CMS → JSON tenant → block view → module action`

Se una view speciale interrompe questa catena, prima correggere l'architettura
e poi aggiornare il contratto e il test che la protegge.
