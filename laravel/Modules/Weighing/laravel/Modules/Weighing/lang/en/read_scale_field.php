<?php

declare(strict_types=1);

return [
    'actions' => [
        'read_scale' => [
            'label' => 'Read',
            'tooltip' => 'Fill in the weight from the selected scale',
            'success' => ':scale: :weight kg (stable)',
            'unstable' => ':scale: :weight kg (NOT stable, read again)',
            'error' => 'Reading failed',
            'missing_scale' => 'Choose the scale first',
        ],
    ],
];
