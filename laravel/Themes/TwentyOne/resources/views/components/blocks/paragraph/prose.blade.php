{{--
    Blocco CMS "paragraph": titolo facoltativo + testo HTML scritto dal redattore nel pannello CMS.
    Usato dalle pagine informative (chi siamo, come funziona, termini, privacy, …).
--}}
@php
    $title = $data['title'] ?? null;
    $text = $data['text'] ?? '';
    // `level: 1` per il primo blocco della pagina (titolo principale), 2 per le sezioni.
    $isMain = ($data['level'] ?? 2) == 1;
@endphp
<section class="mx-auto w-full max-w-3xl px-4 py-8 sm:px-6">
    @if(is_string($title) && $title !== '')
        @if($isMain)
            <h1 class="mb-6 text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl dark:text-white">{{ $title }}</h1>
        @else
            <h2 class="mb-4 text-2xl font-bold text-gray-900 dark:text-white">{{ $title }}</h2>
        @endif
    @endif
    <div class="text-base leading-relaxed text-gray-700 dark:text-gray-300 [&_p]:mb-4 [&_h3]:mb-2 [&_h3]:mt-8 [&_h3]:text-lg [&_h3]:font-semibold [&_h3]:text-gray-900 dark:[&_h3]:text-white [&_ul]:mb-4 [&_ul]:list-disc [&_ul]:pl-6 [&_ol]:mb-4 [&_ol]:list-decimal [&_ol]:pl-6 [&_li]:mb-1 [&_a]:text-blue-600 [&_a]:underline [&_strong]:font-semibold [&_strong]:text-gray-900 dark:[&_strong]:text-white">
        {!! is_string($text) ? $text : '' !!}
    </div>
</section>
