<?php

declare(strict_types=1);

// Chiavi di Filament 5 assenti nella traduzione italiana del pacchetto. Laravel le unisce a quelle del
// pacchetto (override per chiave): restano valide anche quando Filament aggiorna i suoi file.

return [
    'builder' => [
        'reordering' => [
            'item' => 'Blocco',
            'position' => ':label, posizione :position di :count',
            'moved' => ':label spostato in posizione :position di :count',
        ],
        'block_picker' => [
            'no_search_results_message' => 'Nessun blocco corrisponde alla ricerca.',
            'search_prompt' => 'Cerca blocchi',
        ],
    ],
    'checkbox_list' => [
        'required_description' => 'Seleziona almeno un\'opzione.',
    ],
    'color_picker' => [
        'panel_label' => 'Selettore colore',
    ],
    'date_time_picker' => [
        'month_select' => [
            'label' => 'Mese',
        ],
        'year_input' => [
            'label' => 'Anno',
        ],
        'hour_input' => [
            'label' => 'Ora',
        ],
        'minute_input' => [
            'label' => 'Minuto',
        ],
        'second_input' => [
            'label' => 'Secondo',
        ],
    ],
    'file_upload' => [
        'placeholder' => 'Nessun file caricato',
        'actions' => [
            'download' => [
                'label' => 'Scarica',
            ],
            'open' => [
                'label' => 'Apri in una nuova scheda',
            ],
        ],
        'editor' => [
            'label' => 'Editor immagini',
        ],
    ],
    'js_field' => [
        'failed_to_load' => 'Non è stato possibile caricare questo campo. Ricarica la pagina e riprova.',
    ],
    'key_value' => [
        'actions' => [
            'move_down' => [
                'label' => 'Sposta giù',
            ],
            'move_up' => [
                'label' => 'Sposta su',
            ],
        ],
        'columns' => [
            'actions' => [
                'label' => 'Azioni',
            ],
            'reorder' => [
                'label' => 'Riordina',
            ],
        ],
        'reordering' => [
            'row' => 'Riga',
            'position' => ':label, posizione :position di :count',
            'moved' => ':label spostato in posizione :position di :count',
        ],
    ],
    'repeater' => [
        'reordering' => [
            'item' => 'Elemento',
            'position' => ':label, posizione :position di :count',
            'moved' => ':label spostato in posizione :position di :count',
        ],
        'columns' => [
            'actions' => [
                'label' => 'Azioni',
            ],
            'reorder' => [
                'label' => 'Riordina',
            ],
        ],
    ],
    'rich_editor' => [
        'actions' => [
            'close_panel' => [
                'label' => 'Chiudi pannello',
            ],
        ],
        'custom_blocks' => [
            'actions' => [
                'delete' => [
                    'label' => 'Elimina blocco',
                ],
                'edit' => [
                    'label' => 'Modifica blocco',
                ],
            ],
            'no_search_results_message' => 'Nessun blocco corrisponde alla ricerca.',
            'search_label' => 'Cerca blocchi',
            'search_prompt' => 'Cerca blocchi',
        ],
        'toolbar' => [
            'label' => 'Barra degli strumenti dell\'editor',
        ],
    ],
    'select' => [
        'actions' => [
            'clear' => [
                'label' => 'Cancella selezione',
            ],
            'remove_option' => [
                'label' => 'Rimuovi :label',
            ],
        ],
        'error_message' => 'Non è stato possibile caricare le opzioni. Riprova.',
        'required_description' => 'Seleziona un\'opzione.',
        'search_label' => 'Cerca',
    ],
    'tags_input' => [
        'tag_added' => 'Aggiunto: :tag',
        'tag_removed' => 'Rimosso: :tag',
    ],
    'toggle_buttons' => [
        'required_description' => 'Seleziona almeno un\'opzione.',
    ],
];
