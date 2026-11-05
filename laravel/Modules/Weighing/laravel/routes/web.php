<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\User\Http\Volt\LogoutAction;

// Roosko si usa dai pannelli Filament dei moduli (/admin, /{modulo}/admin, /roosko/customer):
// le route dei pannelli e dei moduli sono registrate dai rispettivi provider.

// Gli header del tema inviano un form POST (con CSRF) a route('logout'), che e' la pagina Folio auth/logout (solo GET).
// Il POST va qui: un logout via GET e' esposto a CSRF. Il modulo User non carica le sue route (Folio + Volt).
Route::post('/{lang}/auth/logout', LogoutAction::class)
    ->where('lang', '[a-z]{2}')
    ->middleware('auth')
    ->name('logout.post');
