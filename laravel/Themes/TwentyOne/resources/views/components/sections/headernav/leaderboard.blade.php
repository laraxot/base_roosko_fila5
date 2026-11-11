<a class="flex items-center py-1.5 space-x-1 text-sm font-semibold text-gray-600 hover:text-blue-600" href="{{ route('page_slug.view', ['lang'=>$lang, 'slug' => 'leaderboard' ]) }}">
	<x-heroicon-o-trophy class="size-6" />
	{{-- <span>Leaderboard</span> --}}
	<span>{{ __('pub_theme::headernav.leaderboard') }}</span>
</a>