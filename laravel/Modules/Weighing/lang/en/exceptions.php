<?php

declare(strict_types=1);

return [
    'scale_unreachable' => 'Scale :scale is unreachable at :host::port.',
    'scale_address' => [
        'missing_host' => 'Enter the indicator IP address or network name.',
        'invalid_host' => ':host is not a valid IP address or network name.',
        'unresolvable' => 'Network name :host does not resolve to an IPv4 address.',
        'not_allowed' => 'Address :host is not allowed for a scale: only local network addresses or those listed in weighing.allowed_scale_hosts are allowed.',
        'port' => 'Port :port is not allowed: use a port between :min and :max.',
    ],
    'scale_missing_host' => 'Scale :scale has no network address.',
    'scale_connection_closed' => 'Scale :scale closed the connection.',
    'scale_timeout' => 'Scale :scale sent no reading within :seconds seconds.',
    'reading_unparsable' => 'Unreadable scale output: check the indicator format.',
    'invalid_pattern' => 'Invalid weight regular expression: :pattern',
    'scale_manual' => 'Scale :scale is manual: type the weight shown on the display.',
    'scale_inactive' => 'Scale :scale is not active.',
    'scale_verification_expired' => 'The periodic verification of scale :scale has expired (:date).',
    'scale_overload' => 'Weight :kg kg exceeds the capacity of scale :scale (:max kg).',
    'weighing_locked' => 'Ticket :ticket is closed or voided and cannot be changed (:attributes).',
    'negative_net' => 'Negative net: gross :gross kg is below tare :tare kg.',
    'weight_not_positive' => 'The weight must be greater than zero.',
    'weighable_not_saved' => 'Save the item before linking it to the weighing.',
    'invalid_transition' => 'Ticket :ticket cannot go from :from to :to.',
    'weighing_voided' => 'Ticket :ticket is voided.',
    'void_reason_required' => 'Please give the reason for voiding.',
];
