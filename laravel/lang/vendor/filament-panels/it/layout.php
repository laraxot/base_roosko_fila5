<?php

declare(strict_types=1);

// Chiavi di Filament 5 assenti nella traduzione italiana del pacchetto. Laravel le unisce a quelle del
// pacchetto (override per chiave): restano valide anche quando Filament aggiorna i suoi file.

return [
    'skip_to_content' => [
        'label' => 'Vai al contenuto',
    ],
    'actions' => [
        'open_database_notifications' => [
            'label_with_unread_count' => '{1} Notifiche, :count notifica non letta|[2,*] Notifiche, :count notifiche non lette',
        ],
        'theme_switcher' => [
            'label' => 'Tema',
        ],
    ],
    'navigation' => [
        'label' => 'Navigazione laterale',
    ],
    'topbar' => [
        'label' => 'Barra superiore',
    ],
];
