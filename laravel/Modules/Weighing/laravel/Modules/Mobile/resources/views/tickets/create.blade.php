<x-mobile::layout>
    <div class="bg-white shadow-sm sticky top-0 z-10">
        <div class="max-w-lg mx-auto px-4 py-4">
            <a href="/mobile" class="text-blue-600 text-sm">← Home</a>
            <h1 class="text-xl font-bold text-gray-900 mt-2">Nuova segnalazione</h1>
        </div>
    </div>

    <div class="max-w-lg mx-auto px-4 py-4">
        <form action="#" method="POST" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Titolo</label>
                <input type="text" name="title" placeholder="Descrivi il problema" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Categoria</label>
                <select name="category" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Seleziona categoria</option>
                    <option value="road_maintenance">Manutenzione stradale</option>
                    <option value="street_lighting">Illuminazione</option>
                    <option value="waste">Rifiuti</option>
                    <option value="public_green">Verde pubblico</option>
                    <option value="vandalism">Vandalismo</option>
                    <option value="signage">Segnaletica</option>
                    <option value="transport">Trasporti</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Foto</label>
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
                    <div class="text-4xl mb-2">📷</div>
                    <p class="text-sm text-gray-500">Tocca per scattare una foto</p>
                    <button type="button" class="mt-2 text-sm text-blue-600 font-medium">Apri fotocamera</button>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Posizione</label>
                <div class="border border-gray-300 rounded-lg p-3 flex items-center gap-2">
                    <div class="text-lg">📍</div>
                    <input type="text" name="location" placeholder="Posizione automatica GPS" class="flex-1 border-0 p-0 text-sm focus:ring-0" readonly>
                    <button type="button" class="text-blue-600 text-sm font-medium">Rileva</button>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Descrizione</label>
                <textarea name="description" rows="4" placeholder="Descrivi il problema in dettaglio" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white font-semibold py-3 rounded-lg text-sm">Invia segnalazione</button>
        </form>
    </div>
</x-mobile::layout>
