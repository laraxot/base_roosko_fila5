---
issues: []
discussions: []
title: Config tenant boundary
type: concept
theme: TwentyOne
tags: [config, tenant, cms, theme-boundary]
created: 2026-07-01
updated: 2026-07-01
qmd: "twentyone theme config tenant boundary bridge CMS sacred"
related:
  - ../../../../../../docs/wiki/concepts/tenant-config-directory-sacred.md
  - ./theme-composer-boundary.md
  - ./second-brain-local-discipline.md
status: draft
---

# Config tenant boundary

TwentyOne è bridge-only: layout, composizione e shell. Non possiede i dati tenant.

## Regola

Il tema può leggere contenuti CMS e config tramite i contratti esistenti, ma non deve classificare come eliminabili:

- `laravel/config/{com,eu,net,local,localhost}/**`
- `laravel/config/local/*/database/content/**`

Questi file sono input runtime per tenant/local e non asset del tema.

## Prima di pulire

```bash
qmd search "config tenant runtime"
bash bashscripts/tools/guard-tenant-config-delete.sh
```

Se serve spostare ownership, documentare prima la nuova sorgente canonica e poi migrare.

## Convenzioni documentazione

- Nomi file `.md`: minuscolo kebab-case, senza date nel filename
- Frontmatter YAML obbligatorio in `docs/wiki/`
- Hub progetto: [tenant-config-directory-sacred.md](../../../../../../docs/wiki/concepts/tenant-config-directory-sacred.md)
