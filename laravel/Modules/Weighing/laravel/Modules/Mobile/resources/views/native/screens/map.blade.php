<native:column class="w-full h-full">
    <native:top-bar title="{{ $title }}" />

    <native:scroll-view class="w-full h-full">
        <native:column class="w-full p-4">
            @if ($isLoading)
                <native:text class="text-sm text-gray-500">Caricamento mappa…</native:text>
            @else
                <native:text class="text-sm text-gray-600">{{ $markerCount }} segnalazioni visualizzate</native:text>
            @endif

            <native:column class="w-full mt-4 p-3 rounded-xl bg-white">
                <native:text class="text-sm text-gray-500 font-semibold">Filtri</native:text>

                <native:column class="w-full mt-2">
                    <native:text class="text-xs text-gray-400">Tipi selezionati</native:text>
                    <native:text>{{ $filters['types'] === [] ? 'tutti' : implode(', ', $filters['types']) }}</native:text>
                </native:column>

                <native:column class="w-full mt-2">
                    <native:text class="text-xs text-gray-400">Stati selezionati</native:text>
                    <native:text>{{ $filters['statuses'] === [] ? 'tutti' : implode(', ', $filters['statuses']) }}</native:text>
                </native:column>

                <native:column class="w-full mt-2">
                    <native:text class="text-xs text-gray-400">Intervallo date</native:text>
                    <native:text>{{ $filters['date_range'] ?? 'non specificato' }}</native:text>
                </native:column>
            </native:column>

            <native:pressable class="w-full mt-3 p-3 rounded-xl bg-gray-100" @tap="clearFilters">
                <native:text>Azzera filtri</native:text>
            </native:pressable>

            @if ($error !== '')
                <native:text class="text-sm text-red-600 mt-3">{{ $error }}</native:text>
            @endif
        </native:column>
    </native:scroll-view>
</native:column>
