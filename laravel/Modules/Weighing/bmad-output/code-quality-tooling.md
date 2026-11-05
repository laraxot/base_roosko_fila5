# Code quality tooling

## Baseline

The repository already contains Laravel Pint, PHPStan, Pest, ESLint,
Stylelint, Prettier, Markdownlint, ShellCheck and shfmt. The useful gap was
integration: the tools existed independently, while the package scripts did
not run a consistent cross-stack gate.

## Gate design

- PHP: PHPStan uses the repository configuration, Pint runs in check mode, and
  Pest remains the behavioural gate.
- JavaScript/TypeScript: ESLint uses the flat config in `laravel/eslint.config.js`.
- CSS: Stylelint checks project CSS/SCSS while respecting the existing config.
- Shell: `laravel/scripts/quality-shell.sh` runs `bash -n`, ShellCheck, and a
  conflict-marker scan, including hidden directories.
- Dependency updates are reviewed separately; major updates are not mixed into
  a quality-gate change.

## Commands

```bash
cd laravel
npm run quality:php
npm run lint:js
npm run lint:css
npm run lint:sh
```

The complete gate is `npm run quality`. Existing legacy scripts may fail the
shell gate; that is intentional evidence to fix or explicitly retire them,
not a reason to weaken the gate.
