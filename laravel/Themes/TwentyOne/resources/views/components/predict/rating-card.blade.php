{{-- Componente riutilizzabile per le card dei rating --}}
@props([
    'rating',
    'predictId',
    'action' => 'bet'
])

@php
    $data = [
        'predict_id' => $predictId,
        'rating_id' => $rating->id,
    ];

    // Immagine dell'opzione; senza immagine un riquadro colorato (prima: foto casuali da picsum.photos,
    // cioè una richiesta a un sito esterno per ogni opzione e immagini senza senso).
    $image = $rating->getFirstMedia();
    $title = (string) ($rating->title ?? '');
    $palette = ['from-blue-500 to-indigo-600', 'from-rose-500 to-pink-600', 'from-emerald-500 to-teal-600', 'from-amber-500 to-orange-600', 'from-violet-500 to-purple-600'];
    $gradient = $palette[((int) ($rating->id ?? 0)) % count($palette)];
    $rawPercentage = $rating->pivot?->percentage ?? null;
    $percentage = is_numeric($rawPercentage) ? (float) $rawPercentage * 100 : null;
@endphp

<button type="button" wire:click="mountAction('{{ $action }}', @js($data))" class="block w-full" aria-label="{{ __('predict::place_bet.widget.bet_on', ['option' => $title]) }}">
    <div class="relative overflow-hidden rounded-lg group/rating cursor-pointer transform transition-all duration-300 hover:scale-105 hover:shadow-lg hover:z-10">
        {{-- Container quadrato per l'immagine --}}
        <div class="aspect-square bg-gray-100 dark:bg-gray-700 relative">
            {{-- Immagine principale --}}
            @if($image)
                {{ $image('150x150')->attributes(['class' => 'w-full h-full object-cover transition-transform duration-300 group-hover/rating:scale-110', 'alt' => $title]) }}
            @else
                <div class="w-full h-full bg-gradient-to-br {{ $gradient }} flex items-center justify-center" aria-hidden="true">
                    <span class="text-3xl font-black text-white/90">{{ mb_strtoupper(mb_substr($title, 0, 1)) }}</span>
                </div>
            @endif

            {{-- Overlay gradiente per migliore leggibilità --}}
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent transition-opacity duration-300 group-hover/rating:opacity-90"></div>

            {{-- Badge percentuale --}}
            <div class="absolute top-1 right-1 transform transition-all duration-300 group-hover/rating:scale-110 group-hover/rating:rotate-3">
                <div class="bg-white dark:bg-gray-800 text-gray-900 dark:text-white text-xs font-bold px-1.5 py-0.5 rounded shadow-lg border border-gray-200 dark:border-gray-600">
                    {{ $percentage !== null ? number_format($percentage, 0, ',', '.').'%' : '–' }}
                </div>
            </div>

            {{-- Titolo del rating --}}
            <div class="absolute bottom-1 left-1 right-1 transform transition-all duration-300 group-hover/rating:translate-y-0 group-hover/rating:scale-105">
                <div class="bg-black/70 dark:bg-gray-900/70 backdrop-blur-sm rounded px-1.5 py-1 border border-white/20">
                    <p class="text-xs font-bold text-white leading-tight line-clamp-2">
                        {{ $rating->title }}
                    </p>
                </div>
            </div>

            {{-- Effetto hover aggiuntivo --}}
            <div class="absolute inset-0 bg-blue-500/0 group-hover/rating:bg-blue-500/10 transition-all duration-300 rounded-lg"></div>
        </div>
    </div>
</button> 
