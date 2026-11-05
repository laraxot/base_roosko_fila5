{{--
    View: user::filament.widgets.login
    Scopo: Widget di login Filament conforme a Windsurf/Xot
    Modifica liberamente questa struttura per UX custom
--}}
<div class="filament-widget-login space-y-6">
    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative text-sm">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif
    <form wire:submit.prevent="login" class="space-y-4">
        {{ $this->form }}
        <button type="submit" wire:loading.attr="disabled" class="w-full py-3 rounded bg-[#FF5F7E] text-white font-bold hover:bg-[#e8556e] transition-colors flex items-center justify-center gap-2">
            <span wire:loading.remove wire:target="login">{{ __('Accedi') }}</span>
            <span wire:loading wire:target="login" class="flex items-center gap-2">
                <svg class="animate-spin h-5 w-5 text-white" role="status" aria-live="polite" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                <span class="sr-only">{{ __('user::auth.login.button.loading') }}</span>
            </span>
        </button>
    </form>
    <div class="text-center text-sm text-gray-500 mt-2">
        @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}" class="underline hover:text-blue-700">{{ __('Password dimenticata?') }}</a>
        @endif
    </div>
</div>
