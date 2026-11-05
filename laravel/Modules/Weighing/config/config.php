<?php

declare(strict_types=1);

return [
    'navigation_sort' => 4,
    'name' => 'Weighing',
    'description' => 'Bilance e ticket di pesata',
    'icon' => 'heroicon-o-scale',
    'navigation' => [
        'enabled' => true,
        'sort' => 31,
    ],

    /*
     * Driver `tcp_ascii`: indicatore di peso collegato in rete (o tramite convertitore seriale/ethernet)
     * che trasmette righe ASCII, es. "ST,GS,+  12340 kg". Il formato varia per costruttore: si adatta qui.
     */
    'tcp_ascii' => [
        'connect_timeout' => 2.0,
        'read_timeout' => 3.0,
        // Righe lette al massimo cercando una lettura stabile (indicatori in trasmissione continua).
        'max_lines' => 10,
        // Comando da inviare prima di leggere (indicatori "a richiesta"), es. "SI\r\n" per MT-SICS. null = trasmissione continua.
        'request_command' => null,
        // Porta usata se la bilancia non ne ha una.
        'default_port' => 4001,
        // Regex del peso: deve avere il gruppo nominato `weight`; se compare piu' volte vale l'ultima occorrenza.
        'weight_pattern' => '/(?<weight>[+-]?\s*\d+(?:[.,]\d+)?)/',
        // Marcatore di peso stabile nella riga (formato comune "ST" stable / "US" unstable).
        'stable_marker' => 'ST',
        // Fattore di conversione del valore letto in kg (es. 1000 se l'indicatore trasmette tonnellate).
        'unit_factor' => 1.0,
    ],

    // Driver `fake`: lettura fissa per test e demo.
    'fake' => [
        'weight_kg' => 12500.0,
        'stable' => true,
    ],

    /*
     * Una pesata con valore legale richiede uno strumento con verificazione periodica in corso di validita'
     * (`scales.verified_until`). false solo per ambienti di prova.
     */
    'require_valid_verification' => true,
];
