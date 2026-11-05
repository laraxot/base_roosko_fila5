<?php

declare(strict_types=1);

return [
    'scale_unreachable' => 'Bilancia :scale non raggiungibile su :host::port (:reason).',
    'scale_missing_host' => 'La bilancia :scale non ha un indirizzo di rete configurato.',
    'scale_connection_closed' => 'La bilancia :scale ha chiuso la connessione.',
    'scale_timeout' => 'La bilancia :scale non ha inviato letture entro :seconds secondi.',
    'reading_unparsable' => 'Lettura della bilancia non interpretabile: ":raw".',
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
