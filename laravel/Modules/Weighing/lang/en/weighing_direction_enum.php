<?php

declare(strict_types=1);

return [
    'values' => [
        'inbound' => ['label' => 'Inbound', 'color' => 'success', 'icon' => 'heroicon-o-arrow-down-on-square', 'description' => 'Loaded vehicle coming in: first weighing gross, second tare'],
        'outbound' => ['label' => 'Outbound', 'color' => 'warning', 'icon' => 'heroicon-o-arrow-up-on-square', 'description' => 'Empty vehicle coming in: first weighing tare, second gross'],
        'internal' => ['label' => 'Internal', 'color' => 'gray', 'icon' => 'heroicon-o-arrows-right-left', 'description' => 'Internal handling: first weighing gross'],
    ],
];
