<?php

declare(strict_types=1);

return [
    'scale_unreachable' => 'Bilancia :scale non raggiungibile su :host::port.',
    'scale_address' => [
        'missing_host' => 'Indicare l\'indirizzo IP o il nome di rete dell\'indicatore.',
        'invalid_host' => ':host non e\' un indirizzo IP o un nome di rete valido.',
        'unresolvable' => 'Il nome di rete :host non si risolve in un indirizzo IPv4.',
        'not_allowed' => 'L\'indirizzo :host non e\' consentito per una bilancia: sono ammessi solo indirizzi della rete locale o quelli elencati in weighing.allowed_scale_hosts.',
        'port' => 'Porta :port non consentita: usare una porta fra :min e :max.',
    ],
    'scale_missing_host' => 'La bilancia :scale non ha un indirizzo di rete configurato.',
    'scale_connection_closed' => 'La bilancia :scale ha chiuso la connessione.',
    'scale_timeout' => 'La bilancia :scale non ha inviato letture entro :seconds secondi.',
    'reading_unparsable' => 'Lettura della bilancia non interpretabile: verificare il formato dell\'indicatore.',
    'invalid_pattern' => 'Espressione regolare del peso non valida: :pattern',
    'scale_manual' => 'La bilancia :scale e\' manuale: digitare il peso letto sul display.',
    'scale_inactive' => 'La bilancia :scale non e\' attiva.',
    'scale_verification_expired' => 'La verificazione periodica della bilancia :scale e\' scaduta (:date).',
    'scale_overload' => 'Peso :kg kg oltre la portata della bilancia :scale (:max kg).',
    'weighing_locked' => 'Il ticket :ticket e\' chiuso o annullato e non si puo\' modificare (:attributes).',
    'negative_net' => 'Netto negativo: lordo :gross kg inferiore alla tara :tare kg.',
    'weight_not_positive' => 'Il peso deve essere maggiore di zero.',
    'weighable_not_saved' => 'L\'elemento da collegare alla pesata va salvato prima.',
    'invalid_transition' => 'Il ticket :ticket non puo\' passare da :from a :to.',
    'weighing_voided' => 'Il ticket :ticket e\' annullato.',
    'void_reason_required' => 'Indicare il motivo dell\'annullamento.',
];
