---
title: Documentation navigation for modules and themes
track: Quick Flow
status: verified
updated: 2026-10-19
---

# Problem

The repository already has useful module/theme documentation, but the
canonical hubs expected by its governance guide were missing and several
legacy indexes compete with README entrypoints.

# Solution

Add one shallow README hub for `laravel/Modules/docs/` and one for
`laravel/Themes/docs/`. Make them routers with retrieval order, ownership and
quality rules; keep topic detail in the existing module/theme docs.

# Acceptance criteria

- Agents can reach a module or theme README from a stable hub path.
- New docs have a documented frontmatter/naming rule and verification evidence.
- Archives and generated/vendor docs are explicitly non-canonical.
- Sixteen's stack and no-Bootstrap constraint remain visible at the theme hub.
- No existing user documentation is deleted or moved in this pass.
