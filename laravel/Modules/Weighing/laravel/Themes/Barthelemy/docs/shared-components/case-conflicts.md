---
title: "case conflicts"
type: note
tags: [documentation]
created: 2026-09-26
updated: 2026-09-26
qmd: "case conflicts"
issues: []
discussions: []
status: draft
---

# Case-Insensitive File Conflicts

Nel modulo `User` sono presenti i seguenti file duplicati per sola differenza di maiuscole/minuscole:

- `Modules/User/.devcontainer`: `README.md`, `readme.md`
- `Modules/User/.github`: `CONTRIBUTING.md`, `contributing.md`
- `Modules/User/.github`: `SECURITY.md`, `security.md`
- `Modules/User/docs`: `INDEX.md`, `index.md`

Correggere mantenendo una sola variante coerente con le convenzioni del progetto e aggiornare gli eventuali riferimenti.
