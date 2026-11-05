<?php

declare(strict_types=1);

use NunoMaduro\PhpInsights\Domain\Insights\ForbiddenSecurityIssues;
use PHP_CodeSniffer\Standards\Generic\Sniffs\Files\LineLengthSniff;

return [
    'preset' => 'laravel',
    'ide' => null,
    'exclude' => [
        'vendor',
        'node_modules',
        'storage',
        'bootstrap',
        'database',
        'config',
        'routes',
        'tests',
    ],
    'add' => [],
    'remove' => [
        ForbiddenSecurityIssues::class,
    ],
    'config' => [
        LineLengthSniff::class => [
            'lineLimit' => 120,
            'absoluteLineLimit' => 200,
        ],
    ],
    'requirements' => [
        'min-quality' => 99,
        'min-complexity' => 99,
        'min-architecture' => 99,
        'min-style' => 99,
    ],
    'threads' => 12,
];
