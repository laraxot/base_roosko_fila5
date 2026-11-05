# Module documentation

Each domain module owns its documentation under
`laravel/Modules/<Module>/docs/`.

## Canonical rules

- Start from the module's `docs/README.md` or `docs/INDEX.md`.
- Keep decisions, stories, runbooks and investigations in the owning module.
- Use `plans/` for cross-module BMAD blueprints; do not add new documents to
  root `bmad-output/`.
- Mark operational claims `NOT VERIFIED` until a command, file or test proves
  them. Do not duplicate a topic when a canonical document already exists.

## Change workflow

1. Read the module index and local instructions.
2. Search Hindsight/Second Brain before changing a convention or test.
3. Add the durable decision under `docs/bmad/` or `docs/second-brain/`.
4. Run the smallest relevant verification from the correct repository root.
5. Add the new document to the module index.

See [project documentation governance](../../docs/DOCUMENTATION_GOVERNANCE.md)
and the [central module index](../../docs/MODULE_DOCS_INDEX.md).
