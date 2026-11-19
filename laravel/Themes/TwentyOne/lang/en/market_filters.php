<?php

declare(strict_types=1);

return [
    'fields' => [
        'toggle' => [
            'label' => 'Toggle filters',
        ],
        'sort' => [
            'label' => 'Sort markets',
        ],
    ],
    'sort' => [
        'hot' => [
            'label' => 'Hot',
        ],
        'new' => [
            'label' => 'New',
        ],
        'volume' => [
            'label' => 'Volume',
        ],
        'participants' => [
            'label' => 'Participants',
        ],
        'ending' => [
            'label' => 'Expiry',
        ],
        'probability' => [
            'label' => 'Probability',
        ],
    ],
    'sections' => [
        'category' => [
            'title' => 'Category',
        ],
        'status' => [
            'title' => 'Status',
        ],
        'type' => [
            'title' => 'Market Type',
        ],
        'expiry' => [
            'title' => 'Expiry',
        ],
    ],
    'category' => [
        'all' => [
            'label' => 'All Categories',
        ],
    ],
    'status' => [
        'all' => [
            'label' => 'All Statuses',
        ],
        'active' => [
            'label' => 'Open',
        ],
        'open' => [
            'label' => 'In progress',
        ],
        'published' => [
            'label' => 'Published',
        ],
        'closed' => [
            'label' => 'Closed',
        ],
        'resolved' => [
            'label' => 'Resolved',
        ],
    ],
    'type' => [
        'all' => [
            'label' => 'All Types',
        ],
        'binary' => [
            'label' => 'Binary (2 options)',
        ],
        'multi' => [
            'label' => 'Multiple (3+ options)',
        ],
    ],
    'expiry' => [
        'all' => [
            'label' => 'All Expiries',
        ],
        'next_24h' => [
            'label' => 'Next 24 hours',
        ],
        'next_7d' => [
            'label' => 'Next 7 days',
        ],
        'next_30d' => [
            'label' => 'Next 30 days',
        ],
        'next_90d' => [
            'label' => 'Next 90 days',
        ],
    ],
    'actions' => [
        'apply' => [
            'label' => 'Apply Filters',
        ],
        'reset' => [
            'label' => 'Reset',
        ],
    ],
];
