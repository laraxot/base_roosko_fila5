<?php

declare(strict_types=1);

// Chiavi di Filament 5 assenti nella traduzione italiana del pacchetto. Laravel le unisce a quelle del
// pacchetto (override per chiave): restano valide anche quando Filament aggiorna i suoi file.

return [
    'form' => [
        'or_groups' => [
            'group' => [
                'label' => 'Gruppo',
            ],
            'block' => [
                'picker_group' => 'Logica',
            ],
        ],
    ],
    'max_rules_reached_tooltip' => 'Hai raggiunto il massimo di :count regole.',
];
