---
title: "docs archive policy Modules"
type: note
tags: [documentation]
created: 2026-09-26
updated: 2026-09-26
qmd: "docs archive policy Modules"
issues: []
discussions: []
status: stub
---

# Docs archive policy

`docs/legacy/` is local-only scratch/history and must not be used as a canonical documentation source.

Active module knowledge belongs in normal `docs/*.md`, `docs/wiki/**`, or a precise topical subdirectory. This keeps QMD ingestion deterministic and prevents stale duplicates from outranking current documentation.

The module `.gitignore` ignores `docs/legacy/`; when a useful archived note is still valid, promote it into a live document outside `legacy` and link it from the local docs index.
