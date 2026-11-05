<x-mobile::layout>
    <x-slot:title>FixCity Mobile</x-slot:title>
    <x-ui.marketing.page-header
        title="FixCity Mobile"
        description="App cittadina — segnalazioni civiche"
    />
    <div>
        <a href="{{ route('mobile.map') }}">📍 Mappa</a><br>
        <a href="{{ route('mobile.create') }}">✏️ Segnala</a>
    </div>
</x-mobile::layout>
