{{--
    Blocco CMS "contact": mostra l'indirizzo di assistenza preso dalla configurazione
    (`mail.from.address`), così resta giusto quando l'amministratore lo cambia.
--}}
@php
    $email = config('mail.from.address');
    $text = $data['text'] ?? null;
@endphp
<section class="mx-auto w-full max-w-3xl px-4 pb-8 sm:px-6">
    <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
        @if(is_string($text) && $text !== '')
            <p class="mb-4 text-gray-700 dark:text-gray-300">{{ $text }}</p>
        @endif
        @if(is_string($email) && $email !== '')
            <a href="mailto:{{ $email }}" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-5 py-3 font-semibold text-white hover:bg-blue-700">
                <x-filament::icon icon="heroicon-o-envelope" class="h-5 w-5" />
                {{ $email }}
            </a>
        @endif
    </div>
</section>
