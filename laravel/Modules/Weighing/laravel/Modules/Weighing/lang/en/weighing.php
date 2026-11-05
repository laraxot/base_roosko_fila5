<?php

declare(strict_types=1);

return [
    'navigation' => [
        'name' => 'Weighings',
        'plural' => 'Weighings',
        'group' => ['name' => 'Weighing', 'description' => 'Scales and weighing tickets'],
        'label' => 'Weighings',
        'icon' => 'heroicon-o-clipboard-document-list',
        'sort' => 10,
    ],
    'label' => 'Weighing',
    'plural_label' => 'Weighings',
    'plural' => ['label' => 'Weighings'],
    'fields' => [
        'direction' => ['label' => 'Direction', 'placeholder' => '', 'helper_text' => 'Inbound: first weighing is gross. Outbound: first weighing is tare'],
        'scale_id' => ['label' => 'Scale', 'placeholder' => 'None (typed weight)', 'helper_text' => ''],
        'vehicle_plate' => ['label' => 'Plate', 'placeholder' => 'E.g. AB123CD', 'helper_text' => ''],
        'first_kg' => ['label' => 'First weighing (kg)', 'placeholder' => '0', 'helper_text' => ''],
        'second_kg' => ['label' => 'Second weighing (kg)', 'placeholder' => '0', 'helper_text' => 'Tare for inbound, gross for outbound'],
        'net_kg' => ['label' => 'Net weight (kg)', 'placeholder' => '0', 'helper_text' => 'Single weighing: the reading is already net'],
        'raw_reading' => ['label' => 'Raw reading', 'placeholder' => '', 'helper_text' => ''],
        'void_reason' => ['label' => 'Void reason', 'placeholder' => '', 'helper_text' => ''],
    ],
    'actions' => [
        'back' => ['label' => 'Back', 'tooltip' => 'Back to the list'],
        'view' => ['label' => 'Details', 'icon' => 'heroicon-o-eye', 'tooltip' => 'Open the ticket'],
        'open_weighing' => [
            'label' => 'New weighing',
            'icon' => 'heroicon-o-arrow-right-end-on-rectangle',
            'tooltip' => 'Record the first weighing and open the ticket',
            'modal_heading' => 'First weighing',
            'modal_description' => 'The ticket number is assigned on save.',
        ],
        'record_single_weighing' => [
            'label' => 'Single weighing',
            'icon' => 'heroicon-o-scale',
            'tooltip' => 'Platform scale: record the net weight directly',
            'modal_heading' => 'Single weighing',
            'modal_description' => 'The ticket is recorded already closed.',
        ],
        'close_weighing' => [
            'label' => 'Second weighing',
            'icon' => 'heroicon-o-check-badge',
            'tooltip' => 'Record the second weighing and compute the net',
            'modal_heading' => 'Second weighing',
            'modal_description' => 'Once closed the ticket cannot be changed.',
        ],
        'void_weighing' => [
            'label' => 'Void ticket',
            'icon' => 'heroicon-o-x-circle',
            'tooltip' => 'Void the ticket giving a reason',
            'modal_heading' => 'Void the ticket?',
            'modal_description' => 'Weights stay recorded for traceability.',
        ],
    ],
];
