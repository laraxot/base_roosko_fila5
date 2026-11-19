<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Livewire\Volt\Component;
use Livewire\Attributes\Validate;
use function Laravel\Folio\{middleware, name};

middleware(['guest']);
name('register');

new class extends Component
{
    #[Validate('required')]
    public $name = '';

    #[Validate('required|email|unique:users')]
    public $email = '';

    #[Validate('required|min:8|same:passwordConfirmation')]
    public $password = '';

    #[Validate('required|min:8|same:password')]
    public $passwordConfirmation = '';

    public function register()
    {
        $this->validate();

        $user = User::create([
            'email' => $this->email,
            'name' => $this->name,
            'password' => Hash::make($this->password),
        ]);

        event(new Registered($user));

        Auth::login($user, true);

        return redirect()->intended('/');
    }
};

?>

<x-layouts.main>

    <div class="flex flex-col items-stretch justify-center w-screen min-h-screen py-10 sm:items-center">

        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <x-ui.link href="{{ route('home') }}">
                <x-ui.logo class="w-auto h-10 mx-auto text-gray-700 fill-current dark:text-gray-100" />
            </x-ui.link>
            <h2 class="mt-5 text-2xl font-extrabold leading-9 text-center text-gray-800 dark:text-gray-200">{{ __('pub_theme::auth_pages.register.heading.title') }}</h2>
            <div class="text-sm leading-5 text-center text-gray-600 dark:text-gray-400 space-x-0.5">
                <span>{{ __('pub_theme::auth_pages.common.or.label') }}</span>
                <x-ui.text-link href="{{ route('login') }}">{{ __('pub_theme::auth_pages.register.sign_in_link.label') }}</x-ui.text-link>
            </div>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="px-10 py-0 sm:py-8 sm:shadow-sm sm:bg-white dark:sm:bg-gray-950/50 dark:border-gray-200/10 sm:border sm:rounded-lg border-gray-200/60">
                @volt('auth.register')
                <form wire:submit="register" class="space-y-6">
                    <x-ui.input :label="__('pub_theme::auth_pages.fields.name.label')" type="text" id="name" name="name" wire:model="name" />
                    <x-ui.input :label="__('pub_theme::auth_pages.fields.email_address.label')" type="email" id="email" name="email" wire:model="email" />
                    <x-ui.input :label="__('pub_theme::auth_pages.fields.password.label')" type="password" id="password" name="password" wire:model="password" />
                    <x-ui.input :label="__('pub_theme::auth_pages.fields.password_confirmation.label')" type="password" id="password_confirmation" name="password_confirmation" wire:model="passwordConfirmation" />
                    <x-ui.button type="primary" rounded="md" submit="true">{{ __('pub_theme::auth_pages.actions.register.label') }}</x-ui.button>
                </form>
                @endvolt
            </div>
        </div>

    </div>

</x-layouts.main>