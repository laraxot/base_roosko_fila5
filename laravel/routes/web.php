<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Seo\Actions\Sitemap\BuildSitemapAction;
use Modules\Fixcity\Actions\FollowTicketAction;
use Modules\User\Http\Volt\LogoutAction;
use Themes\Sixteen\Actions\Assets\ServeDesignComuniAssetAction;
use Themes\Sixteen\Actions\Assets\ServeThemeAssetAction;

// NativePHP forwards static WebView requests through Laravel. Keep the theme
// asset surface explicit and confined to the theme-owned public directory.
Route::get('/themes/Sixteen/{path}', [ServeThemeAssetAction::class, 'execute'])
    ->where('path', '.*');

// Percorsi relativi `../assets/...` come nel riferimento Design Comuni (DCP-94/99): `/{lingua}/assets/*` serve gli stessi file di
// `themes/Sixteen/design-comuni/assets`, con la stessa difesa contro `..` e lo stesso percorso confinato nel tema.
Route::get('/{lang}/assets/{path}', ServeDesignComuniAssetAction::class)
    ->where('lang', '[a-z]{2}')
    ->where('path', '.+');

// sitemap.xml (DCP-166): pagine pubbliche in tutte le lingue, con hreflang; dal CMS, non da un elenco scritto a mano
Route::get('/sitemap.xml', BuildSitemapAction::class)->name('sitemap.xml');

// Gli header del tema inviano un form POST (con CSRF) a route('logout'), che e' la pagina Folio auth/logout (solo GET).
// Il POST va qui: un logout via GET e' esposto a CSRF. Il modulo User non carica le sue route (Folio + Volt).
Route::post('/{lang}/auth/logout', LogoutAction::class)
    ->where('lang', '[a-z]{2}')
    ->middleware('auth')
    ->name('logout.post');

// "Segui questa segnalazione" (DCP-148): POST con CSRF per il cittadino loggato; il dettaglio pubblico /{lang}/tickets/{id} e' una pagina Folio (solo GET).
Route::post('/{lang}/tickets/{ticket}/follow', FollowTicketAction::class)
    ->where('lang', '[a-z]{2}')
    ->whereNumber('ticket')
    ->middleware(['auth', 'throttle:30,1'])
    ->name('tickets.follow');
