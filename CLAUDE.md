# Claude — On-Demand Stub

Questo file è uno stub. Le regole vivono nella wiki, caricate on-demand.

## Regole standing (agenti)

- [No branch Git](docs/wiki/rules/agent-no-git-branch-creation.md) — **mai** `checkout -b` / cambio branch
- [Git forward-only](docs/wiki/rules/git-forward-only.md) — patch in avanti, no rollback come scorciatoia
- [Nwidart Modular Architecture](docs/wiki/rules/NWIDART_MODULAR_ARCHITECTURE.md) — ogni modulo ha vendor/, composer.json, tool config indipendenti
- [PHPStan a zero + qualita + docs + BMAD](docs/wiki/memories/phpstan-quality-docs-bmad-standing-order.md) — **sempre**, senza chiedere conferma: azzerare PHPStan, alzare la qualita, riordinare docs di moduli/temi, tutto via BMAD + second brain
- [BMAD docs solo in moduli/temi](docs/wiki/memories/bmad-docs-never-in-bmad-output.md) — **mai** documenti in `bmad-output/`; solo `laravel/Modules/*/docs/` e `laravel/Themes/*/docs/`
- [Story BMAD al posto di docs/chat](docs/wiki/rules/agent-chat-directory.md) — coordinamento, decisioni, discussione fra agenti, checkpoint e handoff vanno nella story owner (`Modules/*/docs/stories/`, `Themes/*/docs/stories/`); `docs/chat/` e' archivio in sola lettura
- [Nomi dei file .md](docs/wiki/rules/markdown-filename-conventions.md) — **mai** date, timestamp o suffissi `-1.md` `appunti-continuous-integration-tools.md` `-3.md` nel nome (la data va nella frontmatter); prima di creare un `.md`: `python3 -I bashscripts/tools/docs/md-filenames.py name <percorso>`. Vale anche per le story. Un hook rifiuta il nome sbagliato.
- [Pagine del tema](laravel/Themes/Sixteen/docs/page-directory-structure.md) — **mai** file o cartelle nuove in `Themes/*/resources/views/pages` oltre `auth`, `[container0]`, `tests` e il solo file `index.blade.php`; la homepage sono i blocchi di `config/local/fixcity/database/content/pages/home.json` (mai `pages/home.blade.php` o `pages/homepage.blade.php`); un 404 su un URL di modulo si risolve col link o col JSON CMS, **non** con una pagina o un alias nel tema. Controllo: `cd laravel && vendor/bin/pest Themes/Sixteen/tests/Unit/NoSemanticFolioPageDirectoriesTest.php`

## Leggi Prima

1. [Trigger Map](docs/wiki/rules/00-TRIGGER_MAP.md) — fonte primaria per regole
2. [Skills Index](docs/wiki/skills/INDEX.md) — skills caricate on-demand
3. [Commands Index](docs/wiki/commands/INDEX.md) — commands on-demand
4. [Memories Index](docs/wiki/memories/INDEX.md) — memorie on-demand

## On-Demand Pattern

**Regole, memorie, skills e commands NON sono pre-caricati:**
- Vivono solo in `docs/wiki/`
- Si caricano on-demand via trigger map o `qmd search`
- Mai duplicati nei bootstrap (CLAUDE.md, QWEN.md, GEMINI.md, AGENTS.md)

## QMD

```bash
qmd search "<topic>"
```

## QMD Cache

La cache QMD è locale e non è la fonte della memoria: verificare il percorso con `qmd status`; le fonti versionate restano nei docs.

## Ruflo MCP

MCP citati dalla configurazione (verificare disponibilità e handshake nel client corrente):
- `ruflo` — Memory, swarm, hooks
- `token-optimizer` — Context compression
- `sqlite` — Database queries
- `puppeteer` — Browser automation

Usa i tool MCP esplicitamente quando necessario.

## .claude Symlink

`.claude` → `bashscripts/ai/.agents/` (symlink)

Il `.claude` locale è un symlink a `bashscripts/ai/.agents/` — è la stessa directory.



- [Project identity and decision map](docs/second-brain/project/project-identity-and-decision-map.md) — purpose, politics, invariants, philosophy, vision and recovery of context.
