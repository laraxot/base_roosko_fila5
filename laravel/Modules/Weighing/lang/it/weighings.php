<?php

declare(strict_types=1);

return [
    'fields' => [
        'ticket_number' => ['label' => 'Ticket'],
        'direction' => ['label' => 'Verso'],
        'status' => ['label' => 'Stato'],
        'vehicle_plate' => ['label' => 'Targa'],
        'scale' => ['code' => ['label' => 'Bilancia']],
        'scale_id' => ['label' => 'Bilancia'],
        'gross_kg' => ['label' => 'Lordo'],
        'tare_kg' => ['label' => 'Tara'],
        'net_kg' => ['label' => 'Netto'],
        'weighable_type' => ['label' => 'Collegata a'],
        'gross_weighed_at' => ['label' => 'Prima pesata'],
        'closed_at' => ['label' => 'Chiusa il'],
    ],
    'actions' => [
        'view' => ['label' => 'Dettaglio', 'icon' => 'heroicon-o-eye', 'tooltip' => 'Apri il ticket'],
        'layout' => ['label' => 'Vista', 'tooltip' => 'Cambia vista elenco/griglia'],
    ],
];
