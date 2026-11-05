<native:scroll-view class="w-full h-full">
    <native:column class="w-full p-4">
        <native:text class="text-xl font-bold">{{ $title }}</native:text>

        <native:column class="w-full mt-4 p-3 rounded-xl bg-white">
            <native:text class="text-sm text-gray-500">Segnalazione</native:text>
            <native:text class="text-lg font-semibold">{{ $ticket['title'] }}</native:text>
            <native:text class="text-sm text-gray-600">{{ $ticket['address'] }} — {{ $ticket['city'] }}</native:text>
            <native:text class="text-sm mt-2">{{ $ticket['description'] }}</native:text>
        </native:column>

        <native:column class="w-full mt-3 p-3 rounded-xl bg-white">
            <native:text class="text-sm text-gray-500">Stato</native:text>
            <native:text>{{ $ticket['status_label'] }}</native:text>
        </native:column>

        @isset($ticket['resolution'])
            <native:column class="w-full mt-3 p-3 rounded-xl bg-white">
                <native:text class="text-sm text-gray-500">Risoluzione</native:text>
                <native:text>{{ $ticket['resolution'] }}</native:text>
            </native:column>
        @endisset

        @if ($canUpdate)
            <native:column class="w-full mt-3">
                @foreach (['open', 'in_progress', 'resolved', 'closed'] as $status)
                    <native:pressable
                        class="w-full mb-2 p-3 rounded-xl bg-white"
                        @tap="updateStatus('{{ $status }}')"
                    >
                        <native:text>{{ $status }}</native:text>
                    </native:pressable>
                @endforeach
            </native:column>
        @endif

        <native:column class="w-full mt-4">
            <native:text class="text-sm text-gray-500 font-semibold">Timeline</native:text>

            @foreach ($timeline as $entry)
                <native:column class="w-full mt-2 p-3 rounded-xl bg-white">
                    <native:text>{{ $entry['label'] }}</native:text>
                    <native:text class="text-xs text-gray-400">{{ $entry['user'] }} — {{ $entry['timestamp'] }}</native:text>
                </native:column>
            @endforeach
        </native:column>

        <native:column class="w-full mt-4 mb-8">
            <native:pressable class="w-full mb-2 p-3 rounded-xl bg-gray-100" @tap="subscribe">
                <native:text>{{ $isSubscribed ? 'Disiscriviti' : 'Sottoscrivi aggiornamenti' }}</native:text>
            </native:pressable>

            <native:pressable class="w-full mb-2 p-3 rounded-xl bg-gray-100" @tap="shareTicket">
                <native:text>Condividi</native:text>
            </native:pressable>

            <native:pressable class="w-full p-3 rounded-xl bg-gray-100" @tap="navigateToMap">
                <native:text>Vedi sulla mappa</native:text>
            </native:pressable>
        </native:column>
    </native:column>
</native:scroll-view>
