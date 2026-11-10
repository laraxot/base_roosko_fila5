<?php

declare(strict_types=1);

return [
    'fields' => [
        'code' => ['label' => 'Codice', 'placeholder' => 'Es. PESA-01', 'helper_text' => ''],
        'name' => ['label' => 'Nome', 'placeholder' => 'Es. Pesa a ponte ingresso', 'helper_text' => ''],
        'type' => ['label' => 'Tipo', 'placeholder' => '', 'helper_text' => ''],
        'driver' => ['label' => 'Collegamento', 'placeholder' => '', 'helper_text' => 'Manuale: il peso si digita. Rete: l\'indicatore trasmette righe ASCII via TCP'],
        'host' => ['label' => 'Indirizzo IP / host', 'placeholder' => 'Es. 192.168.1.60', 'helper_text' => 'Indicatore o convertitore seriale/ethernet nella rete locale (10.x, 172.16-31.x, 192.168.x); altri indirizzi solo se elencati in weighing.allowed_scale_hosts'],
        'port' => ['label' => 'Porta', 'placeholder' => '4001', 'helper_text' => 'Vuoto: porta di default da configurazione'],
        'max_capacity_kg' => ['label' => 'Portata massima (kg)', 'placeholder' => '60000', 'helper_text' => 'Letture oltre la portata vengono rifiutate'],
        'resolution_kg' => ['label' => 'Divisione (kg)', 'placeholder' => '20', 'helper_text' => 'Risoluzione dello strumento (e)'],
        'verified_until' => ['label' => 'Verificazione periodica valida fino al', 'placeholder' => '', 'helper_text' => 'Scaduta o vuota: la bilancia non registra pesate (D.M. 93/2017)'],
        'is_active' => ['label' => 'Attiva', 'placeholder' => '', 'helper_text' => ''],
    ],
];
