<?php

declare(strict_types=1);

return [
    'values' => [
        'inbound' => ['label' => 'Ingresso', 'color' => 'success', 'icon' => 'heroicon-o-arrow-down-on-square', 'description' => 'Mezzo carico in ingresso: prima pesata lordo, seconda tara'],
        'outbound' => ['label' => 'Uscita', 'color' => 'warning', 'icon' => 'heroicon-o-arrow-up-on-square', 'description' => 'Mezzo vuoto in ingresso: prima pesata tara, seconda lordo'],
        'internal' => ['label' => 'Interna', 'color' => 'gray', 'icon' => 'heroicon-o-arrows-right-left', 'description' => 'Movimentazione interna: prima pesata lordo'],
    ],
];
