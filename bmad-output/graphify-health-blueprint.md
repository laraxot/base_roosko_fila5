# Graphify — BMAD / Filament Blueprint health record

## Intent

Graphify is developer knowledge tooling for the repository, not a Laravel runtime
dependency. It supplies a navigable code/document graph to support BMAD research,
second-brain recall, and focused code exploration.

## Decision

- Keep the Python CLI installed outside the application environment with `uv`.
- Keep the committed `graphify-out/` index as a generated project artifact.
- Exclude dependencies, build output, runtime state, screenshots, and Graphify's own
  output through the root `.graphifyignore`.
- Register the Codex guidance and Git lifecycle hooks with Graphify's native
  installers; do not add Composer, npm, or database runtime code.

## Verification

- `graphify --version` → `graphify 0.9.92`.
- `graphify hook status` → post-commit, post-checkout, post-merge, post-rewrite,
  and merge driver installed.
- `graphify diagnose multigraph --graph graphify-out/graph.json` → 2,124 nodes,
  3,305 valid edges, no collapsed endpoint groups.
- Focused code extraction of `laravel/Themes/Sixteen` completed without an API key:
  53,618 nodes and 162,564 edges.
- `graphify query` and `graphify god-nodes` return usable results from the project
  graph.

## Known boundary

The full repository contains a large monorepo and generated/dependency trees. A
blind `graphify update .` is intentionally not the normal verification path: use a
focused module/theme extraction or update after tightening the ignore boundary.
The focused Sixteen extraction also reported one pre-existing parser warning in
`src/Events/BuildingSixteenMenu.php`; this is an input warning, not a Graphify
installation failure, and needs a separate PHP syntax review if that file is in
scope.
