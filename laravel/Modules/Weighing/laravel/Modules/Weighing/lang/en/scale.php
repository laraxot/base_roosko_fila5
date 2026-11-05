<?php

declare(strict_types=1);

return [
    'navigation' => [
        'name' => 'Scales',
        'plural' => 'Scales',
        'group' => ['name' => 'Weighing', 'description' => 'Scales and weighing tickets'],
        'label' => 'Scales',
        'icon' => 'heroicon-o-scale',
        'sort' => 20,
    ],
    'label' => 'Scale',
    'plural_label' => 'Scales',
    'plural' => ['label' => 'Scales'],
    'fields' => [
        'code' => ['label' => 'Code'],
        'name' => ['label' => 'Name'],
    ],
    'actions' => [
        'create' => ['label' => 'New scale', 'icon' => 'heroicon-o-plus', 'tooltip' => 'Register a scale'],
        'edit' => ['label' => 'Edit', 'icon' => 'heroicon-o-pencil-square', 'tooltip' => 'Edit the scale'],
        'delete' => ['label' => 'Delete', 'icon' => 'heroicon-o-trash', 'tooltip' => 'Delete the scale'],
        'view' => ['label' => 'Details', 'icon' => 'heroicon-o-eye', 'tooltip' => 'Open the scale'],
        'back' => ['label' => 'Back', 'tooltip' => 'Back to the list'],
    ],
];
