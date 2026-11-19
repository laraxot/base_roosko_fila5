<?php

declare(strict_types=1);

return [
    'fields' => [
        'recordId' => [
            'tooltip' => '',
        ],
        'team_id' => [
            'tooltip' => '',
            'label' => 'Team',
            'placeholder' => 'team_id',
            'helper_text' => 'team_id',
            'description' => 'team_id',
        ],
    ],
    'actions' => [
        'submit' => [
            'icon' => 'submit',
        ],
        'attachAnother' => [
            'icon' => 'attachAnother',
        ],
        'cancel' => [
            'icon' => 'cancel',
        ],
    ],
    'navigation' => [
        'name' => 'Attach Role',
        'plural' => 'Attach Role',
        'group' => [
            'name' => 'General',
            'description' => 'General Settings',
        ],
        'label' => 'Attach Role',
        'sort' => 1,
        'icon' => 'heroicon-o-collection',
    ],
    'label' => 'Attach Role',
    'plural_label' => 'Attach Role (Plurale)',
];
