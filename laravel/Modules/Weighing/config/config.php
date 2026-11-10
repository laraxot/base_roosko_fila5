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

    /*
     * Indirizzi a cui il server puo' collegarsi per leggere le bilance (EnsureScaleAddressAllowedAction).
     * Vuota: solo IP privati di LAN (10/8, 172.16/12, 192.168/16, fc00::/7) e hostname che si
     * risolvono li'; niente loopback ne' indirizzi pubblici. Valorizzata: solo le voci elencate,
     * IP, CIDR (es. '192.168.10.0/24'), hostname esatti o '*.dominio'. Metadata cloud e link-local
     * restano sempre vietati. Env WEIGHING_ALLOWED_SCALE_HOSTS (separati da virgola) letta
     * da config('env.*'): la chiave va aggiunta a config/env.php della base.
     */
    'allowed_scale_hosts' => array_values(array_filter(array_map(
        trim(...),
        explode(',', is_string($hosts = config('env.WEIGHING_ALLOWED_SCALE_HOSTS')) ? $hosts : ''),
    ))),

    // Porte ammesse per gli indicatori di rete (niente SSH, SMTP, HTTP...).
    'scale_ports' => [
        'min' => 1024,
        'max' => 65535,
    ],

    // Letture "Leggi bilancia" al minuto dalle azioni Filament (rateLimit: per IP, componente e azione).
    'read_rate_limit' => 30,

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
