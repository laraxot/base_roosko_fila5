<?php

declare(strict_types=1);

return [
    'navigation' => [
        'name' => 'Bilance',
        'plural' => 'Bilance',
        'group' => ['name' => 'Pesatura', 'description' => 'Bilance e ticket di pesata'],
        'label' => 'Bilance',
        'icon' => 'heroicon-o-scale',
        'sort' => 20,
    ],
    'label' => 'Bilancia',
    'plural_label' => 'Bilance',
    'plural' => ['label' => 'Bilance'],
    'fields' => [
        'code' => ['label' => 'Codice'],
        'name' => ['label' => 'Nome'],
    ],
    'actions' => [
        'create' => ['label' => 'Nuova bilancia', 'icon' => 'heroicon-o-plus', 'tooltip' => 'Censisci una bilancia'],
        'edit' => ['label' => 'Modifica', 'icon' => 'heroicon-o-pencil-square', 'tooltip' => 'Modifica la bilancia'],
        'delete' => ['label' => 'Elimina', 'icon' => 'heroicon-o-trash', 'tooltip' => 'Elimina la bilancia'],
        'view' => ['label' => 'Dettaglio', 'icon' => 'heroicon-o-eye', 'tooltip' => 'Apri la bilancia'],
        'back' => ['label' => 'Indietro', 'tooltip' => 'Torna all\'elenco'],
    ],
];
