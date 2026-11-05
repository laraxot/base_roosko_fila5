<?php

declare(strict_types=1);

return [
    'fields' => [
        'code' => ['label' => 'Code'],
        'name' => ['label' => 'Name'],
        'type' => ['label' => 'Type'],
        'driver' => ['label' => 'Connection'],
        'host' => ['label' => 'Host'],
        'max_capacity_kg' => ['label' => 'Capacity'],
        'resolution_kg' => ['label' => 'Division'],
        'verified_until' => ['label' => 'Verified until'],
        'is_active' => ['label' => 'Active'],
    ],
    'actions' => [
        'create' => ['label' => 'New scale', 'icon' => 'heroicon-o-plus', 'tooltip' => 'Register a scale'],
        'view' => ['label' => 'Details', 'icon' => 'heroicon-o-eye', 'tooltip' => 'Open the scale'],
        'edit' => ['label' => 'Edit', 'icon' => 'heroicon-o-pencil-square', 'tooltip' => 'Edit the scale'],
        'delete' => ['label' => 'Delete', 'icon' => 'heroicon-o-trash', 'tooltip' => 'Delete the selected scales'],
        'layout' => ['label' => 'View', 'tooltip' => 'Switch list/grid view'],
    ],
];
