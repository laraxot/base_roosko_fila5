// SSOT della convenzione Conventional Commits del repo root.
// Usata da:
//  - .github/workflows/commit-lint.yml (validazione commit nelle PR)
//  - hook git locali (opzionale: husky/commitlint -e)
// Allineata a .github/workflows/semantic-pr.yml e release-please-config.json.
module.exports = {
  extends: ['@commitlint/config-conventional'],
  rules: {
    'type-enum': [
      2,
      'always',
      [
        'feat', // Nuova funzionalita'        -> minor
        'fix', // Bug fix                      -> patch
        'perf', // Miglioramento performance   -> patch
        'refactor', // Refactoring             -> patch
        'revert', // Revert di un commit
        'docs', // Documentazione
        'test', // Test
        'build', // Build system / dipendenze
        'ci', // Continuous Integration
        'style', // Formattazione (no logica)
        'chore', // Manutenzione
      ],
    ],
    'subject-case': [2, 'never', ['upper-case', 'pascal-case', 'start-case']],
    'subject-empty': [2, 'never'],
    'type-empty': [2, 'never'],
    'header-max-length': [2, 'always', 100],
    'body-max-line-length': [2, 'always', 100],
    'footer-max-line-length': [2, 'always', 100],
  },
};
