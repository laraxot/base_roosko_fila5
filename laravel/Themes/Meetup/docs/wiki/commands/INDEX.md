---
<<<<<<< HEAD
qmd: "INDEX"
issues: []
discussions: []
=======
>>>>>>> laraxot/dev
title: "Commands Index"
type: index
created: 2026-05-11
updated: 2026-05-11
tags: [commands, index, on-demand]
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

# Commands Index

Le Commands progettuali vivono qui, nel wiki del Theme **Meetup**, e vengono caricate **on-demand**.

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
4. leggi solo la Commands wiki pertinente

## Pattern di caricamento

| Pattern | Comando |
|---------|---------|
| Carica Commands specifica | `Read ../commands/<name>.md` |
| Ricerca semantica | `qmd search "<topic>"` |
<<<<<<< HEAD
| Via trigger map | Consulta `../../../../../../docs/wiki/rules/00-TRIGGER_MAP.md` |
=======
| Via trigger map | Consulta `../rules/00-TRIGGER_MAP.md` |
>>>>>>> laraxot/dev

## Note

- La sorgente di verita' per le Commands e' sempre il wiki locale
- Non embeddare Commands nei prompt di avvio
<<<<<<< HEAD
- Per Commands globali, consulta il [wiki root](../../docs/wiki/commands/INDEX.md) _(target mancante, verificato 2026-10-08)_
=======
- Per Commands globali, consulta il [wiki root](../../docs/wiki/commands/INDEX.md)
>>>>>>> laraxot/dev

## Aggiungere una Nuova COMMANDS

1. Crea `../commands/<nome>.md` con contenuto completo
<<<<<<< HEAD
2. Aggiungi la voce in `../../../../../../docs/wiki/rules/00-TRIGGER_MAP.md`
=======
2. Aggiungi la voce in `../rules/00-TRIGGER_MAP.md`
>>>>>>> laraxot/dev
3. Aggiorna questo indice se la Commands e' ricorrente
4. Committa: `docs: add commands <nome>`

