<?php

declare(strict_types=1);

use function Laravel\Folio\{middleware, name};

middleware(['guest']);
name('register');

/*
 * Registrazione giocatore: stessa grafica della pagina di accesso.
 * Logica (regole, limite tentativi, consensi GDPR, creazione utente) in
 * Modules\Gdpr\Filament\Widgets\Auth\RegisterWidget; vestito in
 * pub_theme::filament.widgets.auth.register. Prima qui c'era un form Volt in inglese
 * che creava App\Models\User senza consensi privacy.
 */
?>
<x-layouts.app>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 flex items-center justify-center p-4">
        <div class="w-full max-w-md">
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl shadow-lg mb-6" aria-hidden="true">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ __('pub_theme::auth.register.title') }}</h1>
                <p class="text-gray-600 text-sm">{{ __('pub_theme::auth.register.subtitle') }}</p>
            </div>

            <div class="bg-white/90 backdrop-blur-sm border border-white/20 rounded-2xl shadow-xl p-8">
                @livewire(\Modules\Gdpr\Filament\Widgets\Auth\RegisterWidget::class)
            </div>
        </div>
    </div>
</x-layouts.app>
