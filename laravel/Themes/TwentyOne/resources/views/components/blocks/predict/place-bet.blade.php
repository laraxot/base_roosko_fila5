{{-- Blocco CMS "Scommetti": PlaceBetWidget per il mercato della pagina (predictId arriva dai dati pagina). --}}
@php
    $predictId = $data['predictId'] ?? $predictId ?? null;
@endphp
@if(is_numeric($predictId))
    <div class="mt-6">
        @livewire(\Modules\Predict\Filament\Widgets\PlaceBetWidget::class, ['predictId' => (int) $predictId], key('place-bet-'.$predictId))
    </div>
@endif
