@php
    $locales = [
        'en' => ['label' => 'English', 'flag' => 'us'],
        'it' => ['label' => 'Italiano', 'flag' => 'it'],
        'es' => ['label' => 'Español', 'flag' => 'es'],
        'de' => ['label' => 'Deutsch', 'flag' => 'de'],
    ];
    $currentLocale = app()->getLocale();
@endphp
<div class="relative">
	<button type="button" data-dropdown-toggle="dropdown-language" aria-haspopup="true" aria-expanded="false" aria-label="{{ __('pub_theme::ui.language') }}" class="grid py-3 text-sm font-semibold transition rounded-lg place-items-center">
		<div class="flex items-center space-x-1">
			<x-heroicon-o-language class="size-5" aria-hidden="true"/>
			<span class="hidden sm:block">{{ $locales[$currentLocale]['label'] ?? strtoupper($currentLocale) }}</span>
			<x-heroicon-o-chevron-down class="hidden size-4 sm:block" aria-hidden="true"/>
		</div>
	</button>
	<div id="dropdown-language" class="absolute z-[45] hidden p-2 overflow-hidden text-sm border border-white rounded-lg bg-gray-50/85 backdrop-blur w-[240px] max-w-sm">
		<ul>
			@foreach($locales as $code => $locale)
			<li>
				<a href="{{ \Mcamara\LaravelLocalization\Facades\LaravelLocalization::getLocalizedURL($code) }}" hreflang="{{ $code }}" @if($code === $currentLocale) aria-current="true" @endif class="flex items-center w-full px-2 py-3 space-x-2 transition rounded hover:bg-white {{ $code === $currentLocale ? 'font-bold' : '' }}">
					<div>{{ $locale['label'] }}</div>
				</a>
			</li>
			@endforeach
		</ul>
	</div>
</div>
