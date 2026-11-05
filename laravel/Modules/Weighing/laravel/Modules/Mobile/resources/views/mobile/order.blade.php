<x-layouts.app>
    <div class="bg-white shadow-sm sticky top-0 z-10">
        <div class="max-w-lg mx-auto px-4 py-4">
            <h1 class="text-xl font-bold text-gray-900">{{ $table->name }}</h1>
            <p class="text-sm text-gray-500 mt-1">
                Posti: {{ $table->seats ?? '—' }} · Stato: {{ $table->status }}
            </p>
        </div>
    </div>

    <div class="max-w-lg mx-auto px-4 py-4">
        <h2 class="font-semibold text-gray-900 mb-3">Ordini del tavolo</h2>

        @forelse ($orders as $order)
            <div class="block bg-white border border-gray-200 rounded-xl p-4 shadow-sm mb-3">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="font-medium text-gray-900">Ordine #{{ $order->id }}</div>
                        <div class="text-sm text-gray-500 mt-1">
                            {{ $order->customer_name ?? 'Cliente non registrato' }}
                        </div>
                        <div class="text-xs text-gray-400 mt-1">
                            Aperto: {{ $order->opened_at ?? $order->created_at?->toDateTimeString() ?? '—' }}
                        </div>
                    </div>
                    <span class="ml-2 px-2 py-1 text-xs font-medium rounded-full flex-shrink-0 bg-gray-100 text-gray-700">
                        {{ $order->status }}
                    </span>
                </div>
                <div class="mt-2 text-sm font-semibold text-gray-900">
                    Totale: {{ number_format((float) ($order->total ?? 0), 2, ',', '.') }} &euro;
                </div>
            </div>
        @empty
            <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm text-sm text-gray-500">
                Nessun ordine aperto per questo tavolo.
            </div>
        @endforelse
    </div>
</x-layouts.app>
