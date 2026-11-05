<?php

declare(strict_types=1);

return [
    'fields' => [
        'ticket_number' => ['label' => 'Ticket'],
        'direction' => ['label' => 'Direction'],
        'status' => ['label' => 'Status'],
        'vehicle_plate' => ['label' => 'Plate'],
        'scale' => ['code' => ['label' => 'Scale']],
        'scale_id' => ['label' => 'Scale'],
        'gross_kg' => ['label' => 'Gross'],
        'tare_kg' => ['label' => 'Tare'],
        'net_kg' => ['label' => 'Net'],
        'weighable_type' => ['label' => 'Linked to'],
        'gross_weighed_at' => ['label' => 'First weighing'],
        'closed_at' => ['label' => 'Closed at'],
    ],
    'actions' => [
        'view' => ['label' => 'Details', 'icon' => 'heroicon-o-eye', 'tooltip' => 'Open the ticket'],
        'layout' => ['label' => 'View', 'tooltip' => 'Switch list/grid view'],
    ],
];
