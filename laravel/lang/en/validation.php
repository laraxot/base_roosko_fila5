<?php

declare(strict_types=1);

// Gruppo globale `validation` per 'en': le altre regole restano quelle del framework (il loader le unisce a questo file).
// Solo `uploaded`: Livewire passa il nome interno del campo (`data.images.f…`) come :attribute, che in inglese comparirebbe grezzo.
return [
    'uploaded' => 'Upload failed: the file may be too large. Try again with a smaller file.',
];
