<?php

declare(strict_types=1);

return [
    'fields' => [
        'ticket_number' => ['label' => 'Ticket'],
        'status' => ['label' => 'Stato'],
        'direction' => ['label' => 'Verso'],
        'scale' => ['name' => ['label' => 'Bilancia']],
        'vehicle_plate' => ['label' => 'Targa'],
        'trailer_plate' => ['label' => 'Targa rimorchio'],
        'gross_kg' => ['label' => 'Lordo'],
        'tare_kg' => ['label' => 'Tara'],
        'net_kg' => ['label' => 'Netto'],
        'gross_weighed_at' => ['label' => 'Lordo pesato il'],
        'tare_weighed_at' => ['label' => 'Tara pesata il'],
        'closed_at' => ['label' => 'Chiusa il'],
        'weighable_type' => ['label' => 'Collegata a'],
        'weighable_id' => ['label' => 'Id collegato'],
        'voided_at' => ['label' => 'Annullata il'],
        'void_reason' => ['label' => 'Motivo annullamento'],
        'notes' => ['label' => 'Note'],
        'raw_readings' => ['label' => 'Letture registrate'],
    ],
];
