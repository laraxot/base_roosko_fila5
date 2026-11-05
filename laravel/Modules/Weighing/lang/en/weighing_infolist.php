<?php

declare(strict_types=1);

return [
    'fields' => [
        'ticket_number' => ['label' => 'Ticket'],
        'status' => ['label' => 'Status'],
        'direction' => ['label' => 'Direction'],
        'scale' => ['name' => ['label' => 'Scale']],
        'vehicle_plate' => ['label' => 'Plate'],
        'trailer_plate' => ['label' => 'Trailer plate'],
        'gross_kg' => ['label' => 'Gross'],
        'tare_kg' => ['label' => 'Tare'],
        'net_kg' => ['label' => 'Net'],
        'gross_weighed_at' => ['label' => 'Gross weighed at'],
        'tare_weighed_at' => ['label' => 'Tare weighed at'],
        'closed_at' => ['label' => 'Closed at'],
        'weighable_type' => ['label' => 'Linked to'],
        'weighable_id' => ['label' => 'Linked id'],
        'voided_at' => ['label' => 'Voided at'],
        'void_reason' => ['label' => 'Void reason'],
        'notes' => ['label' => 'Notes'],
        'raw_readings' => ['label' => 'Recorded readings'],
    ],
];
