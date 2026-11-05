<?php

declare(strict_types=1);

return [
    'actions' => [
        'read_scale' => [
            'label' => 'Read scale',
            'tooltip' => 'Read the current weight without recording it',
            'success' => ':scale: :weight kg (stable)',
            'unstable' => ':scale: :weight kg (NOT stable, read again)',
            'error' => 'Reading failed',
            'missing_scale' => 'Choose the scale first',
        ],
    ],
];
