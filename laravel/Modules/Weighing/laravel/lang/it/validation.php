<?php

declare(strict_types=1);

// Gruppo globale `validation` per 'it': senza questo file il validatore di Laravel restituiva la chiave grezza (`validation.required`).
// Fonte unica: il set completo vive nel modulo Xot (vedi Modules/Xot/tests/Unit/ValidationMessagesTest.php).
return require base_path('Modules/Xot/lang/it/validation.php');
