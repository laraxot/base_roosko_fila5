<?php

declare(strict_types=1);

// Chiavi di Filament 5 assenti nella traduzione italiana del pacchetto. Laravel le unisce a quelle del
// pacchetto (override per chiave): restano valide anche quando Filament aggiorna i suoi file.

return [
    'js_component' => [
        'failed_to_load' => 'Non è stato possibile caricare questo componente. Ricarica la pagina e riprova.',
    ],
    'callout' => [
        'statuses' => [
            'danger' => 'Errore:',
            'info' => 'Nota:',
            'success' => 'Fatto:',
            'warning' => 'Attenzione:',
        ],
    ],
    'section' => [
        'actions' => [
            'collapse' => [
                'label' => 'Comprimi sezione',
            ],
            'expand' => [
                'label' => 'Espandi sezione',
            ],
        ],
    ],
    'tabs' => [
        'actions' => [
            'more' => [
                'label' => 'Altre schede',
            ],
        ],
    ],
    'wizard' => [
        'header' => [
            'step' => [
                'statuses' => [
                    'completed' => 'Completato',
                    'upcoming' => 'Non completato',
                ],
            ],
        ],
    ],
];
