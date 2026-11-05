<?php

declare(strict_types=1);

return [
    'fields' => [
        'code' => ['label' => 'Code', 'placeholder' => 'E.g. SCALE-01', 'helper_text' => ''],
        'name' => ['label' => 'Name', 'placeholder' => 'E.g. Inbound weighbridge', 'helper_text' => ''],
        'type' => ['label' => 'Type', 'placeholder' => '', 'helper_text' => ''],
        'driver' => ['label' => 'Connection', 'placeholder' => '', 'helper_text' => 'Manual: the weight is typed. Network: the indicator sends ASCII lines over TCP'],
        'host' => ['label' => 'IP address / host', 'placeholder' => 'E.g. 192.168.1.60', 'helper_text' => 'Indicator or serial/ethernet converter'],
        'port' => ['label' => 'Port', 'placeholder' => '4001', 'helper_text' => 'Empty: default port from config'],
        'max_capacity_kg' => ['label' => 'Maximum capacity (kg)', 'placeholder' => '60000', 'helper_text' => 'Readings above capacity are rejected'],
        'resolution_kg' => ['label' => 'Division (kg)', 'placeholder' => '20', 'helper_text' => 'Instrument resolution (e)'],
        'verified_until' => ['label' => 'Periodic verification valid until', 'placeholder' => '', 'helper_text' => 'Expired or empty: the scale cannot record weighings'],
        'is_active' => ['label' => 'Active', 'placeholder' => '', 'helper_text' => ''],
    ],
];
