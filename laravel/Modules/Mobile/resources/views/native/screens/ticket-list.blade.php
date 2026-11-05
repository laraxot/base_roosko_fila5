<native:scroll-view class="w-full h-full">
    <native:column class="w-full p-4">
        <native:text class="text-xl font-bold">{{ $title }}</native:text>

        @if ($isRefreshing)
            <native:text class="text-sm text-gray-500 mt-2">Aggiornamento in corso…</native:text>
        @elseif ($isLoading)
            <native:text class="text-sm text-gray-500 mt-2">Caricamento…</native:text>
        @elseif ($tickets === [])
            <native:text class="text-sm text-gray-500 mt-2">Nessuna segnalazione trovata.</native:text>
        @else
            @foreach ($tickets as $ticket)
                <native:pressable
                    class="w-full mt-3 p-4 rounded-xl bg-white"
                    @tap="navigateToDetail('{{ $ticket['id'] }}')"
                >
                    <native:text class="font-semibold">{{ $ticket['title'] }}</native:text>
                    <native:text class="text-sm text-gray-600">{{ $ticket['address'] }}, {{ $ticket['city'] }}</native:text>
                    <native:text class="text-xs text-gray-500">{{ $ticket['type_label'] }} — {{ $ticket['status_label'] }}</native:text>
                </native:pressable>
            @endforeach
        @endif

        @if ($error !== '')
            <native:text class="text-sm text-red-600 mt-3">{{ $error }}</native:text>
        @endif
    </native:column>
</native:scroll-view>

<native:fab icon="add" @tap="navigateToCreate" />
