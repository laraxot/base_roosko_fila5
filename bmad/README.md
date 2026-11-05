# BMAD nel progetto

Cartella unica per **runtime** e **payload** BMAD Method v6.

| Percorso | Scopo |
|----------|--------|
| `config.yaml` | Config progetto (workflow-init) |
| `sprint-status.yaml` | Stato sprint BMM |
| `agent-overrides/` | Override agenti |
| `v6/` | Payload upstream on-demand (comandi, skill, template) — installato da `bashscripts/tools/install-bmad-v6-project.sh` |

Non precaricare `v6/` in bootstrap. Routing: `bashscripts/ai/skills/bmad-on-demand-routing.md`.

Slash Cursor: `.cursor/commands/bmad/` → legge `bmad/v6/commands/<nome>.md`.
