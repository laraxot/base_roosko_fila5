# Memory Bootstrap Stub

Questo file non deve contenere memorie embeddate.

## Regola

Le memorie vivono nel wiki e si caricano on-demand:

1. consulta `docs/wiki/memories/INDEX.md`;
2. consulta `docs/wiki/rules/00-TRIGGER_MAP.md`;
3. usa `qmd search "<topic>"` per recuperare solo il contesto necessario;
4. leggi la memory pertinente nel wiki.

## Memorie chiave

- `docs/wiki/rules/file-locking-validation-protocol.md` (FLVP: Lock before edit, Validate after)
- `docs/wiki/memories/agent-conduct-rules.md`
- `docs/wiki/memories/docs-first-before-code.md`
- `docs/second-brain/project/project-confidence-recovery.md`

## Guardrail

- Non duplicare memorie qui.
- Non usare `MEMORY.md` come dump cronologico.
- Le regole generiche condivise possono vivere in `docs/wiki/memories/`; la conoscenza specifica Fixcity resta in `docs/second-brain/project/` o nei docs owner. Controllare i symlink prima di spostare memorie.
- Mantieni git forward-only.

- [Project identity and decision map](docs/second-brain/project/project-identity-and-decision-map.md) — purpose, politics, invariants, philosophy, vision and recovery of context.
