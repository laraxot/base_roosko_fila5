<?php

declare(strict_types=1);

// Gruppo globale `validation` per 'es': senza questo file il validatore di Laravel restituiva la chiave grezza (`validation.required`).
// Fonte unica: il set completo vive nel modulo Xot (vedi Modules/Xot/tests/Unit/ValidationMessagesTest.php).
// Set di Xot piu' le regole che Xot non ha (gt, lt, password.*, uuid...) e i messaggi di User: senza, ricadevano sull'italiano (DCP-146).
return require base_path('Modules/User/lang/es/validation.php');
