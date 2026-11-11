@props(['title' => null, 'description' => null])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
	{{-- title/description: facoltativi, per le pagine di un oggetto (es. mercato) → <title>, og:*, twitter:*. --}}
	<x-cms::head :title="$title" :description="$description" />
	<body class="antialiased">
        <a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:z-[100] focus:bg-white focus:px-4 focus:py-2 focus:text-sm focus:font-semibold focus:text-gray-900 dark:focus:bg-gray-900 dark:focus:text-white">
            {{ __('pub_theme::ui.skip_to_content') }}
        </a>

		{{-- Bonus del giorno prima dell'intestazione: così il saldo in alto include già i crediti. --}}
		@auth
			<x-predict::daily-bonus />
		@endauth

		<x-section slug="header" />

        <main id="main-content" tabindex="-1">
        {{ $slot }}
        </main>
        
		<x-section slug="footer" />

        {{-- Coriandoli e messaggio quando un mercato su cui avevi puntato si risolve a tuo favore. --}}
        @auth
            <x-predict::win-celebration />
        @endauth


		<!-- mobile tabs -->
		@include('pub_theme::layouts.mobile_tabs')


		
		<x-cms::foot />

    </body>
</html>
