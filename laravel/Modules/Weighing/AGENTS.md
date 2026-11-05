m00283–m00408 Sixteen redesign execution completed; artifacts created (\"tests.index.json\", layout-test.blade.php, dynamic [slug].blade.php), decisions D10/D11 eliminating design-comuni redundancy, subagents completed, MEMORY.md updated with initiative kp-174d15a58ff94901b2b0df0d86b8907a, open objectives listed (homepage rebuild, segnalazione-dettaglio, all pages, WCAG 2.1 AA, tab groups), constraints AGENTS.md + UX strict, errors resolved (parse error, view not found, undefined $bodyPage, 404 /tests), loop remains open.
m00417–m00554 Docs organized for qmd + graphify + second brain + BMAD compliance; header/footer deep study performed with data flow analysis and bug fix for duplicated header slug; layout-test created with Tailwind+Alpine+Lit+DaisyUI+Flowbite, NO Bootstrap; header receives blocks prop → extracts first block's data → falls back to config('comune.*') → localizes URLs via LaravelLocalization; key data fields documented; layout-test verified; loop open.
m00575–m00576 Header/footer bug fixed: duplicate <x-section slug=\"header\" /> removed, corrected to header + footer; layout-test now contains proper header and footer sections; visual parity 95%+ achieved; stack compliance verified.
Standing rule: every `components/blocks/*` view must remain reusable and data-driven. CMS JSON supplies page-specific labels and URLs, `config()` supplies safe theme defaults, nullable relations are guarded, and repeated UI belongs in shared partials. Verify parity at 375px, 768px, and 1440px when changing front-office markup.

Verification context rule: run Sixteen asset checks from `laravel/Themes/Sixteen`; run Laravel/PHP tests from `laravel`. Running `php artisan` inside the theme loads its Testbench bootstrap and is not a valid application-test context. Report environment/bootstrap blockers as unverified.

Documentation rule: new BMAD plans and decisions belong in the owning module/theme `docs/` or in `plans/`; root `bmad-output/` is legacy/read-only for new project documents. Search Hindsight/Second Brain before changing a convention, and record durable corrections beside the owning code.

## graphify

This project has a knowledge graph at graphify-out/ with god nodes, community structure, and cross-file relationships.

When the user types `/graphify`, use the installed graphify skill or instructions before doing anything else.

Rules:
- When graphify-out/graph.json exists, run `graphify query "<question>"` before your first grep, search or source-file read for a codebase question, and put this rule in every subagent prompt that explores code. Read raw files only for lines the graph or an earlier read already located. Use `graphify path "<A>" "<B>"` for relationships and `graphify explain "<concept>"` for focused concepts. These return a scoped subgraph, usually much smaller than GRAPH_REPORT.md or raw grep output.
- Dirty graphify-out/ files are expected after hooks or incremental updates; dirty graph files are not a reason to skip graphify. Only skip graphify if the task is about stale or incorrect graph output, or the user explicitly says not to use it.
- If graphify-out/wiki/index.md exists, use it for broad navigation instead of raw source browsing.
- Read graphify-out/GRAPH_REPORT.md only for broad architecture review or when query/path/explain do not surface enough context.
- After modifying code, run `graphify update .` to keep the graph current (AST-only, no API cost).
- A committed graphify-out/graph.json is rewritten whole on every build, so a large diff to it in a pull request is expected. Do not flag it as churn or suggest skipping the graph rebuild or its hooks.

## Project knowledge recovery

For environment evidence, architecture ownership and repeatable recovery, read
[the project recovery map](docs/second-brain/project/project-confidence-recovery.md).
Revalidate recorded checks after changes; never treat historical green reports as current proof.

Routine verification, isolated fixture setup, asset review/commit and backup to the existing
remote branch are part of the authorized delivery. Execute them directly; do not hand
these tasks back to the user. Preserve credentials outside versioned files and reports.

Typing standing rule: `mixed` is a last resort only when the type cannot be determined.
Prefer precise types, domain contracts, generics and array shapes; validate external data
before use. Never introduce `mixed` to silence static analysis. Canon:
[Source-first and last-resort mixed](docs/wiki/memories/source-first-before-fix.md).

- [Project identity and decision map](docs/second-brain/project/project-identity-and-decision-map.md) — purpose, politics, invariants, philosophy, vision and recovery of context.

Execution standing rule: use coordinated parallel swarm + subagents + BMAD + Second Brain.
The lead owns the plan and integration; assign independent scopes, one writer per file,
locks and explicit evidence. Persist decisions and handoffs before losing session context.

Quality standing rule: when reviewing or implementing, correct discovered in-scope defects
and apply clear, low-risk improvements immediately; do not leave them as recommendations or
ask the user to authorize routine reversible work. Study the owning code/docs first, preserve
concurrent work, run the narrowest relevant verification, and record durable decisions in
the owner docs and Second Brain. Clearly report any blocker or side effect that requires a
separate decision.
