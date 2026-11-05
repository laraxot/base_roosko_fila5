<?php

declare(strict_types=1);

return [
    'navigation' => [
        'name' => 'Pesate',
        'plural' => 'Pesate',
        'group' => ['name' => 'Pesatura', 'description' => 'Bilance e ticket di pesata'],
        'label' => 'Pesate',
        'icon' => 'heroicon-o-clipboard-document-list',
        'sort' => 10,
    ],
    'label' => 'Pesata',
    'plural_label' => 'Pesate',
    'plural' => ['label' => 'Pesate'],
    'fields' => [
        'direction' => ['label' => 'Verso', 'placeholder' => '', 'helper_text' => 'Ingresso: prima pesata lordo. Uscita: prima pesata tara'],
        'scale_id' => ['label' => 'Bilancia', 'placeholder' => 'Nessuna (peso digitato)', 'helper_text' => ''],
        'vehicle_plate' => ['label' => 'Targa', 'placeholder' => 'Es. AB123CD', 'helper_text' => ''],
        'first_kg' => ['label' => 'Prima pesata (kg)', 'placeholder' => '0', 'helper_text' => ''],
        'second_kg' => ['label' => 'Seconda pesata (kg)', 'placeholder' => '0', 'helper_text' => 'Tara per gli ingressi, lordo per le uscite'],
        'net_kg' => ['label' => 'Peso netto (kg)', 'placeholder' => '0', 'helper_text' => 'Pesata singola: il valore letto e\' gia\' netto'],
        'raw_reading' => ['label' => 'Lettura grezza', 'placeholder' => '', 'helper_text' => ''],
        'void_reason' => ['label' => 'Motivo dell\'annullamento', 'placeholder' => '', 'helper_text' => ''],
    ],
    'actions' => [
        'back' => ['label' => 'Indietro', 'tooltip' => 'Torna all\'elenco'],
        'view' => ['label' => 'Dettaglio', 'icon' => 'heroicon-o-eye', 'tooltip' => 'Apri il ticket'],
        'open_weighing' => [
            'label' => 'Nuova pesata',
            'icon' => 'heroicon-o-arrow-right-end-on-rectangle',
            'tooltip' => 'Registra la prima pesata e apre il ticket',
            'modal_heading' => 'Prima pesata',
            'modal_description' => 'Il numero di ticket viene assegnato al salvataggio.',
        ],
        'record_single_weighing' => [
            'label' => 'Pesata singola',
            'icon' => 'heroicon-o-scale',
            'tooltip' => 'Bilancia a pianale: registra direttamente il peso netto',
            'modal_heading' => 'Pesata singola',
            'modal_description' => 'Il ticket viene registrato gia\' chiuso.',
        ],
        'close_weighing' => [
            'label' => 'Seconda pesata',
            'icon' => 'heroicon-o-check-badge',
            'tooltip' => 'Registra la seconda pesata e calcola il netto',
            'modal_heading' => 'Seconda pesata',
            'modal_description' => 'Dopo la chiusura il ticket non si puo\' modificare.',
        ],
        'void_weighing' => [
            'label' => 'Annulla ticket',
            'icon' => 'heroicon-o-x-circle',
            'tooltip' => 'Annulla il ticket indicandone il motivo',
            'modal_heading' => 'Annullare il ticket?',
            'modal_description' => 'I pesi restano registrati per la tracciabilita\'.',
        ],
    ],
];
