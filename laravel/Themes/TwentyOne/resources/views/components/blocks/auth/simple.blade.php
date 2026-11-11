@guest
<div class="hidden lg:block">
	<div class="flex space-x-4">
		<a href="{{route('register')}}" class="grid px-4 py-2 text-sm font-semibold transition rounded-lg text-nowrap ring-2 ring-inset ring-blue-500 place-items-center hover:bg-gray-50 hover:no-underline">
			<span>{{ __('user::auth.sign-up') }}</span>
		</a>
		<a href="{{route('login')}}" class="grid px-4 py-2 text-sm font-semibold text-white transition bg-blue-600 rounded-lg text-nowrap place-items-center hover:bg-blue-700 hover:no-underline">
			<span>{{ __('user::auth.login-in.text') }}</span>
		</a>
	</div>
</div>
@endguest
<div>
	<button type="button" data-dropdown-toggle="dropdown-about" aria-haspopup="true" aria-controls="dropdown-about" aria-label="{{ __('pub_theme::headernav.user_menu') }}" class="relative grid bg-gray-100 rounded-full size-10 overflow-clip hover:bg-gray-50 place-items-center">
		@auth
		@php
			// Senza foto profilo: iniziali del nome (l'immagine predefinita non è pubblicata
			// in tutti gli ambienti e compariva come immagine rotta con il testo "Menu").
			$avatarPhoto = method_exists($profile, 'getFirstMediaUrl') ? $profile->getFirstMediaUrl('photo_profile') : '';
			$avatarName = trim((string) (auth()->user()?->name ?? ''));
			$avatarInitials = mb_strtoupper(collect(preg_split('/\s+/', $avatarName) ?: [])->filter()->take(2)->map(static fn (string $part): string => mb_substr($part, 0, 1))->implode(''));
		@endphp
		@if ($avatarPhoto !== '')
		<img class="absolute inset-0 object-cover w-full h-full shrink-0" src="{{ $avatarPhoto }}" alt="{{ __('pub_theme::headernav.user_menu') }}">
		@else
		<span class="text-sm font-semibold text-blue-700" aria-hidden="true">{{ $avatarInitials !== '' ? $avatarInitials : '?' }}</span>
		@endif
		@else
		<x-heroicon-o-ellipsis-vertical class="size-6 z-10" />
		@endauth
	</button>
	<div id="dropdown-about" class="absolute z-20 hidden p-2 w-[160px] overflow-hidden text-sm border border-white rounded-lg bg-gray-50/85 backdrop-blur">
		<ul>
			@auth
			<li>
				<a href="{{ url(app()->getLocale().'/pages/wallet') }}" class="flex items-center p-2 space-x-2 transition-colors rounded hover:text-blue-500 hover:bg-white">
					<x-heroicon-o-wallet class="size-6" />
					<span>{{ __('pub_theme::headernav.wallet') }}</span>
				</a>
			</li>
			<li>
				<a href="{{ url(app()->getLocale().'/pages/profile') }}" class="flex items-center p-2 space-x-2 transition-colors rounded hover:text-blue-500 hover:bg-white">
					<x-heroicon-o-user-circle class="size-6" />
					<span>{{ __('pub_theme::headernav.profile') }}</span>
				</a>
			</li>
			<li>
				<a href="{{ url(app()->getLocale().'/profile/edit') }}" class="flex items-center p-2 space-x-2 transition-colors rounded hover:text-blue-500 hover:bg-white">
					<x-heroicon-o-cog-6-tooth class="size-6" />
					<span>{{ __('pub_theme::headernav.my_data') }}</span>
				</a>
			</li>
			@else
			<li class="block lg:hidden">
				<a href="{{route('register')}}" class="flex items-center p-2 space-x-2 text-blue-500 transition-colors rounded hover:text-blue-600 hover:bg-white">
					<span>{{ __('pub_theme::headernav.sign_up') }}</span>
				</a>
			</li>
			<li class="block lg:hidden">
				<a href="{{route('login')}}" class="flex items-center p-2 space-x-2 transition-colors rounded hover:text-blue-500 hover:bg-white">
					<span>{{ __('pub_theme::headernav.login') }}</span>
				</a>
			</li>
			@endauth

			@foreach($_theme->getMenu('user_menu') as $menu)
			<li>
				<a href="{{ $_theme->getMenuUrl($menu) }}"
					class="flex items-center p-2 space-x-2 transition-colors rounded hover:text-blue-500 hover:bg-white">
					@svg($menu['icon'], 'size-6')
					<span>{{ $menu['title'] }}</span>
				</a>
			</li>
			@endforeach

			@auth
			<li>
				<form action="{{ route('logout') }}" method="post"> @csrf
					<button type="submit" class="flex items-center w-full p-2 space-x-2 text-red-500 rounded hover:text-red-600 hover:bg-white">
						<x-heroicon-o-power class="size-6" />
						<span>{{ __('pub_theme::headernav.logout') }}</span>
					</button>
				</form>
			</li>
			@endauth
		</ul>
	</div>
</div>
