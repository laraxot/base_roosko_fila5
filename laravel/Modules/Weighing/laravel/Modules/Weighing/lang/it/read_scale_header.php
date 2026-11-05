<?php

declare(strict_types=1);

/*
 * ReadScaleHeaderAction (riusabile da altri moduli): testi letti da `actions.read_scale.*`.
 */
return [
    'actions' => [
        'read_scale' => [
            'label' => 'Leggi bilancia',
            'tooltip' => 'Legge il peso attuale senza registrarlo',
            'success' => ':scale: :weight kg (stabile)',
            'unstable' => ':scale: :weight kg (NON stabile, ripetere la lettura)',
            'error' => 'Lettura non riuscita',
            'missing_scale' => 'Scegliere prima la bilancia',
        ],
    ],
];
