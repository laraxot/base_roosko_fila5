---
<<<<<<< HEAD
qmd: "INDEX"
issues: []
discussions: []
=======
>>>>>>> laraxot/dev
title: "Rules Index"
type: index
created: 2026-05-11
updated: 2026-05-11
tags: [rules, index, on-demand]
related:
<<<<<<< HEAD
  - ../../../../../../docs/wiki/rules/00-TRIGGER_MAP.md
  - ../../../../../../docs/wiki/rules/on-demand-pattern.md
status: active
=======
  - ../rules/00-TRIGGER_MAP.md
  - ../rules/on-demand-pattern.md
>>>>>>> laraxot/dev
---

# Rules Index

Le Rules progettuali vivono qui, nel wiki del Theme **Meetup**, e vengono caricate **on-demand**.

<<<<<<< HEAD
> Vedi anche → [Trigger Map](../../../../../../docs/wiki/rules/00-TRIGGER_MAP.md)
=======
> Vedi anche → [Trigger Map](../rules/00-TRIGGER_MAP.md)
>>>>>>> laraxot/dev

## Regola

1. individua il trigger del task
<<<<<<< HEAD
2. consulta `../../../../../../docs/wiki/rules/00-TRIGGER_MAP.md`
=======
2. consulta `../rules/00-TRIGGER_MAP.md`
>>>>>>> laraxot/dev
3. se serve, esegui `qmd search "<topic>"`
4. leggi solo la Rules wiki pertinente

## Pattern di caricamento

| Pattern | Comando |
|---------|---------|
| Carica Rules specifica | `Read ../rules/<name>.md` |
| Ricerca semantica | `qmd search "<topic>"` |
<<<<<<< HEAD
| Via trigger map | Consulta `../../../../../../docs/wiki/rules/00-TRIGGER_MAP.md` |
=======
| Via trigger map | Consulta `../rules/00-TRIGGER_MAP.md` |
>>>>>>> laraxot/dev

## Note

- La sorgente di verita' per le Rules e' sempre il wiki locale
- Non embeddare Rules nei prompt di avvio
<<<<<<< HEAD
- Per Rules globali, consulta il [wiki root](../../../../../../docs/wiki/rules/INDEX.md)
=======
- Per Rules globali, consulta il [wiki root](../../docs/wiki/rules/INDEX.md)
>>>>>>> laraxot/dev

## Aggiungere una Nuova RULES

1. Crea `../rules/<nome>.md` con contenuto completo
<<<<<<< HEAD
2. Aggiungi la voce in `../../../../../../docs/wiki/rules/00-TRIGGER_MAP.md`
=======
2. Aggiungi la voce in `../rules/00-TRIGGER_MAP.md`
>>>>>>> laraxot/dev
3. Aggiorna questo indice se la Rules e' ricorrente
4. Committa: `docs: add rules <nome>`

