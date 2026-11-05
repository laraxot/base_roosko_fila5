# Bashscripts — semantic collision resolution

## Scope

This pass audits shell files under `bashscripts/` for Git merge residue and
duplicate-path collisions. A global `HEAD`/incoming choice is not safe here:
the directory contains libraries, compatibility paths, and intentionally
deprecated destructive tools.

## Decisions

| Collision | Decision | Reason |
|---|---|---|
| `analysis/ai/ai_init.sh` vs `ai/ai_init.sh` | Keep `ai/ai_init.sh` as the implementation; make the analysis path an exec wrapper | The former contained two overlapping implementations and was syntactically invalid; one source of truth prevents drift. |
| `utils/parse_gitmodules_ini.sh` vs `lib/parse_gitmodules_ini.sh` | Keep `lib/parse_gitmodules_ini.sh` as the implementation; make the utils path a source wrapper | Existing subtree callers use the library path; the utils copy contained invalid PHP array syntax. |
| Shell files with literal Git markers | No automatic side selection | No markers remain in the current tree; any future conflict requires semantic review. |

## Safety contract

- Do not use `git checkout --ours/--theirs` as a fleet-wide resolver.
- Do not revive `init-subtrees.sh`, `reset_subtrees.sh`, or other deprecated
  destructive scripts merely because their names collide.
- Validate every changed shell file with `bash -n` and scan hidden paths too.

## Verification

```bash
rg --hidden -n -g '*.sh' '^(<<<<<<<|=======|>>>>>>>)' bashscripts
bash -n bashscripts/analysis/ai/ai_init.sh
bash -n bashscripts/utils/parse_gitmodules_ini.sh
```

