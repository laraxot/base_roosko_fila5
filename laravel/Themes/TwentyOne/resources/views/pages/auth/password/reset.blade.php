<?php

use Illuminate\Support\Facades\Password;
use function Laravel\Folio\name;
use Livewire\Volt\Component;
use Livewire\Attributes\Validate;

name('password.request');

new class extends Component
{
    #[Validate('required|email')]
    public $email = null;

    public $emailSentMessage = false;

    public function sendResetPasswordLink()
    {
        $this->validate();

        $response = Password::broker()->sendResetLink(['email' => $this->email]);

        if ($response == Password::RESET_LINK_SENT) {
            $this->emailSentMessage = trans($response);

            return;
        }

        $this->addError('email', trans($response));
    }
};

?>

<x-layouts.app>
    <div class="flex min-h-[70vh] items-center justify-center px-4 py-12">
        <div class="w-full max-w-md">
            <div class="mb-8 text-center">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('pub_theme::auth.reset.title') }}</h1>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">{{ __('pub_theme::auth.reset.subtitle') }}</p>
            </div>

            @volt('auth.password.reset')
            <div class="rounded-2xl border border-gray-200 bg-white p-8 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                @if ($emailSentMessage)
                    <p class="rounded-lg bg-green-50 p-4 text-sm font-medium text-green-800 dark:bg-green-900/30 dark:text-green-200" role="status">{{ $emailSentMessage }}</p>
                @else
                    <form wire:submit="sendResetPasswordLink" class="space-y-5">
                        <div>
                            <label for="email" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('pub_theme::auth.reset.email') }}</label>
                            <input id="email" type="email" wire:model="email" autocomplete="email" required
                                   class="w-full rounded-lg border border-gray-300 px-3 py-2 text-gray-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-900 dark:text-white" />
                            @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <button type="submit" class="w-full rounded-lg bg-blue-600 px-4 py-2.5 font-semibold text-white hover:bg-blue-700" wire:loading.attr="disabled">
                            {{ __('pub_theme::auth.reset.submit') }}
                        </button>
                    </form>
                @endif
            </div>
            @endvolt

            <p class="mt-6 text-center text-sm">
                <a href="{{ route('login') }}" class="font-medium text-blue-600 hover:underline">{{ __('pub_theme::auth.reset.back') }}</a>
            </p>
        </div>
    </div>
</x-layouts.app>
