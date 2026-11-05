<x-layouts.app>
    <div class="bg-white shadow-sm sticky top-0 z-10">
        <div class="max-w-lg mx-auto px-4 py-4">
            <h1 class="text-xl font-bold text-gray-900">Menu</h1>
        </div>
    </div>

    {{--
        Il catalogo non e' ancora disponibile lato server: va caricato dalla
        shell nativa/offline (service worker, vedi mobile::mobile.app) a partire
        dalla cache locale. Per popolarlo lato backend serve l'adapter di
        Modules\Mobile\Contracts\RestaurantGateway, che deve essere registrato
        dal modulo proprietario del dominio Restaurant.
    --}}
    <div class="max-w-lg mx-auto px-4 py-4">
        <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm text-sm text-gray-500">
            Menu non ancora disponibile offline.
        </div>
    </div>
</x-layouts.app>
