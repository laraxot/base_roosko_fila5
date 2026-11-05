<?php

declare(strict_types=1);

/*
 * ReadScaleFieldAction (suffix action dei campi peso, riusabile): testi letti da `actions.read_scale.*`.
 */
return [
    'actions' => [
        'read_scale' => [
            'label' => 'Leggi',
            'tooltip' => 'Compila il peso leggendolo dalla bilancia scelta',
            'success' => ':scale: :weight kg (stabile)',
            'unstable' => ':scale: :weight kg (NON stabile, ripetere la lettura)',
            'error' => 'Lettura non riuscita',
            'missing_scale' => 'Scegliere prima la bilancia',
        ],
    ],
];
