@php
    // Il blocco riceve solo i dati del JSON: il profilo e' quello dell'utente collegato.
    // Prima usava una variabile `$profile` mai passata (500 sulla pagina Wallet).
    $walletProfile = auth()->check()
        ? \Modules\Predict\Models\Profile::query()->where('user_id', (string) auth()->id())->first()
        : null;
@endphp
<div>
    @if ($walletProfile !== null)
        @livewire(\Modules\Predict\Filament\Widgets\TransactionTableWidget::class, ['profile' => $walletProfile])
    @else
        <p class="rounded-lg border border-gray-200 p-6 text-center text-gray-600 dark:border-gray-700 dark:text-gray-300">
            {{ __('predict::wallet.no_profile') }}
        </p>
    @endif
</div>
