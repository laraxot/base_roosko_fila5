<?php

declare(strict_types=1);

return [
    'navigation' => [
        'name' => 'Snapshot',
        'plural' => 'Snapshots',
        'group' => [
            'name' => 'Monitoraggio',
            'description' => 'Gestione degli snapshot di sistema',
        ],
        'label' => 'Snapshot',
        'sort' => 63,
        'icon' => 'activity-snapshot-animated',
    ],
    'fields' => [
        'model_type' => [
            'tooltip' => '',
        ],
        'model_id' => [
            'tooltip' => '',
        ],
        'state' => [
            'tooltip' => '',
        ],
        'created_by_type' => [
            'tooltip' => '',
        ],
        'created_by_id' => [
            'tooltip' => '',
        ],
    ],
    'actions' => [
        'view' => [
            'icon' => 'view',
        ],
        'edit' => [
            'icon' => 'edit',
        ],
        'delete' => [
            'icon' => 'delete',
        ],
    ],
    'label' => 'Snapshot',
    'plural_label' => 'Snapshot (Plurale)',
];
