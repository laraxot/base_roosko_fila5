import eslint from '@eslint/js';
import globals from 'globals';
import tseslint from 'typescript-eslint';

export default tseslint.config(
    {
        ignores: [
            '**/node_modules/**',
            '**/vendor/**',
            '**/public/**',
            '**/storage/**',
            '**/bootstrap/cache/**',
            '**/Themes/*/public/**',
            '**/Modules/**',
            '**/packages/**',
            '**/resources/dist/**',
            '**/.github/**',
        ],
    },
    eslint.configs.recommended,
    ...tseslint.configs.recommended,
    {
        files: ['**/*.{js,mjs,cjs,ts,tsx}'],
        languageOptions: {
            globals: { ...globals.browser, ...globals.node, ...globals.jest },
        },
        rules: {
            'no-console': 'warn',
            'no-undef': 'warn',
            'no-unused-vars': 'warn',
            'no-unused-expressions': 'warn',
            'no-empty': 'warn',
            '@typescript-eslint/no-unused-vars': [
                'warn',
                { argsIgnorePattern: '^_', varsIgnorePattern: '^_' },
            ],
            '@typescript-eslint/no-require-imports': 'warn',
            '@typescript-eslint/no-unused-expressions': 'warn',
            '@typescript-eslint/no-this-alias': 'warn',
        },
    },
);
