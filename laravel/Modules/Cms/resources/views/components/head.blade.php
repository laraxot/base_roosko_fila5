{{--
  Contratto Filament 5.x — Configuring your layout:
  https://filamentphp.com/docs/5.x/introduction/installation#configuring-your-layout

  OBBLIGATORIO in <head>: @filamentStyles (prima del CSS Vite del tema).
  Senza di esso i componenti Filament/Livewire fuori pannello non hanno CSS.
  @livewireStyles non si ripete: gli asset Livewire sono già coperti da Filament + inject_assets.
--}}
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @head
    <style>[x-cloak] { display: none !important; }</style>
    <script>
        if (typeof Storage !== 'undefined' && localStorage.getItem('dark_mode') === 'true') {
            document.documentElement.classList.add('dark');
        }
    </script>
    @filamentStyles
    @vite(['resources/css/app.css'], 'themes/'.$pubTheme)
</head>
