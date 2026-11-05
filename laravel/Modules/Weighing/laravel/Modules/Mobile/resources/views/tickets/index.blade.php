<x-mobile::layout>
    <div class="bg-white shadow-sm sticky top-0 z-10">
        <div class="max-w-lg mx-auto px-4 py-4">
            <h1 class="text-xl font-bold text-gray-900">Segnalazioni</h1>
        </div>
    </div>

    <div class="max-w-lg mx-auto px-4 py-4">
        <div class="space-y-3">
            <template x-data="{ tickets: [] }" x-init="fetch('/api/tickets/geojson').then(r => r.json()).then(d => tickets = d.features)">
                <template x-for="ticket in tickets" :key="ticket.properties.id">
                    <a :href="'/mobile/segnalazioni/' + ticket.properties.id" class="block bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="font-medium text-gray-900" x-text="ticket.properties.title"></div>
                                <div class="text-sm text-gray-500 mt-1" x-text="ticket.properties.address"></div>
                                <div class="text-xs text-gray-400 mt-1" x-text="ticket.properties.city"></div>
                            </div>
                            <span class="ml-2 px-2 py-1 text-xs font-medium rounded-full flex-shrink-0"
                                  :style="'background-color: ' + ticket.properties.status.color + '20; color: ' + ticket.properties.status.color"
                                  x-text="ticket.properties.status.label"></span>
                        </div>
                    </a>
                </template>
            </template>
        </div>
    </div>
</x-mobile::layout>
