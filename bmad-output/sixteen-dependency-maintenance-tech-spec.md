---
title: Sixteen dependency maintenance
track: Quick Flow
status: verified
updated: 2026-10-19
---

# Problem

`laravel/Themes/Sixteen/package.json` contains newer constraints than the
installed lockfile and `node_modules`; the theme therefore cannot claim a
reproducible latest-dependency build.

# Scope

## MUST

1. Audit every direct Sixteen dependency against npm registry metadata.
2. Update direct dependencies to current latest releases where the existing
   Node/Vite/Tailwind/Laravel-Vite stack can install them.
3. Regenerate the lockfile through npm and remove invalid installed-package
   states.
4. Preserve Tailwind + Alpine + Lit + DaisyUI + Flowbite and keep Bootstrap
   Italia out of the runtime.
5. Verify `npm install`, `npm run build`, vulnerability status, and the
   existing browser/font smoke checks.

## OUT OF SCOPE

Bootstrap Italia adoption, unrelated root/modules manifests, and redesigning
application code merely to accommodate a dependency's breaking API.

# Technical approach

Use npm registry `latest` metadata, upgrade the theme manifest in dependency
groups, then let npm resolve and lock the complete graph. Classify any major
upgrade that cannot pass the build as a follow-up rather than masking it with
an unsafe lockfile.

# Acceptance checks

- `npm outdated` is empty or has an explicit documented compatibility reason.
- `npm ls --depth=0` has no invalid entries.
- `npm run build` succeeds from the Sixteen directory.
- no Bootstrap Italia package/import/CDN is introduced.
- existing Sixteen unit/browser checks remain runnable.

# Risks

Major releases may change Flowbite, PostCSS, Jest, Puppeteer, or utility APIs;
the build and smoke checks are the rollback gate. `postcss-selector-parser`
has a production audit advisory whose non-breaking fix is a major release, so
it must be validated rather than force-fixed blindly.

# Font-pipeline correction

The canonical solution is Laravel Vite's `fonts` option with
`google(...)` entries for Titillium Web, Lora, and Roboto Mono. It lets the
Laravel Vite plugin download/emit the configured font assets and generate the
font manifest while retaining Vite's `/themes/Sixteen/` base path. The earlier
investigation stopped at manually self-hosted `@font-face` files because it
treated the symptom (font 404/fallback) as the whole problem and did not first
inspect the Laravel Vite plugin's font integration or use the official Laravel
Vite “Working with fonts” path as the primary design.
