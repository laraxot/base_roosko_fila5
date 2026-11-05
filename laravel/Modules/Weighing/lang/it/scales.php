<?php

declare(strict_types=1);

return [
    'fields' => [
        'code' => ['label' => 'Codice'],
        'name' => ['label' => 'Nome'],
        'type' => ['label' => 'Tipo'],
        'driver' => ['label' => 'Collegamento'],
        'host' => ['label' => 'Host'],
        'max_capacity_kg' => ['label' => 'Portata'],
        'resolution_kg' => ['label' => 'Divisione'],
        'verified_until' => ['label' => 'Verificata fino al'],
        'is_active' => ['label' => 'Attiva'],
    ],
    'actions' => [
        'create' => ['label' => 'Nuova bilancia', 'icon' => 'heroicon-o-plus', 'tooltip' => 'Censisci una bilancia'],
        'view' => ['label' => 'Dettaglio', 'icon' => 'heroicon-o-eye', 'tooltip' => 'Apri la bilancia'],
        'edit' => ['label' => 'Modifica', 'icon' => 'heroicon-o-pencil-square', 'tooltip' => 'Modifica la bilancia'],
        'delete' => ['label' => 'Elimina', 'icon' => 'heroicon-o-trash', 'tooltip' => 'Elimina le bilance selezionate'],
        'layout' => ['label' => 'Vista', 'tooltip' => 'Cambia vista elenco/griglia'],
    ],
];
