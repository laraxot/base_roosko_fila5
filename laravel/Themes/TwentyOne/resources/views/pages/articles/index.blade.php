<?php

declare(strict_types=1);

use function Laravel\Folio\name;
use function Laravel\Folio\render;

name('articles.index');

// Indirizzo storico: l'elenco dei mercati è /{lang}/predicts (prima qui c'era un elenco
// deprecato vuoto con l'avviso "Deprecation Notice").
render(static fn () => redirect()->to(url(app()->getLocale().'/predicts'), 301));
?>
