{{--
    Vestito TwentyOne (derivato da Themes/Two) del widget di registrazione con consensi (Modules\Gdpr\Filament\Widgets\Auth\RegisterWidget).
    Risolto da GetViewByClassAction: prima `pub_theme::filament.widgets.auth.register`, poi la vista del modulo.
    Logica (regole, limite di frequenza, creazione utente, registro consensi) nel widget; qui solo presentazione.
    Contratto: ogni campo ha label associata, errore collegato con aria-describedby e autocomplete; privacy, condizioni e marketing
    sono tre consensi separati, il marketing e' facoltativo e parte non spuntato. Testi: gdpr::register.* (it/en/de/es).
--}}
@php
    $privacyUrl = url(app()->getLocale().'/privacy-policy');
    $termsUrl = url(app()->getLocale().'/terms');
    $fields = [
        ['name' => 'first_name', 'type' => 'text', 'autocomplete' => 'given-name', 'hint' => false],
        ['name' => 'last_name', 'type' => 'text', 'autocomplete' => 'family-name', 'hint' => false],
        ['name' => 'email', 'type' => 'email', 'autocomplete' => 'email', 'hint' => true],
        ['name' => 'password', 'type' => 'password', 'autocomplete' => 'new-password', 'hint' => true],
        ['name' => 'password_confirmation', 'type' => 'password', 'autocomplete' => 'new-password', 'hint' => false],
    ];
    $consents = [
        ['name' => 'privacy_accepted', 'label' => 'privacy_policy_label', 'link' => 'privacy_policy_link', 'url' => $privacyUrl],
        ['name' => 'terms_accepted', 'label' => 'terms_label', 'link' => 'terms_link', 'url' => $termsUrl],
    ];
    $inputClass = 'block w-full min-h-11 rounded-lg border border-gray-300 bg-white px-4 py-2 text-base text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#2563eb] focus:border-[#2563eb]';
@endphp
<div>
    <form wire:submit="submit" novalidate class="space-y-6">
        @if ($errors->any())
            <div role="alert" class="rounded-lg border border-red-300 bg-red-50 p-4 text-sm text-red-700">
                <p class="font-semibold">{{ __('pub_theme::auth.register.errors_title') }}</p>
                <ul class="mt-2 space-y-1">
                    @foreach ($errors->keys() as $errorKey)
                        <li>
                            <a href="#register-{{ str_replace('_', '-', (string) $errorKey) }}" class="underline">{{ $errors->first($errorKey) }}</a>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        <fieldset class="space-y-4">
            <legend class="mb-2 text-lg font-semibold text-gray-900">{{ __('gdpr::register.sections.user_info') }}</legend>
            @foreach ($fields as $field)
                @php
                    $fieldId = 'register-'.str_replace('_', '-', $field['name']);
                    $hasError = $errors->has($field['name']);
                    $describedBy = trim(($field['hint'] ? $fieldId.'-hint ' : '').($hasError ? $fieldId.'-error' : ''));
                @endphp
                <div>
                    <label for="{{ $fieldId }}" class="mb-1 block text-sm font-medium text-gray-700">
                        {{ __('gdpr::register.fields.'.$field['name'].'.label') }}
                    </label>
                    <input
                        wire:model="{{ $field['name'] }}"
                        type="{{ $field['type'] }}"
                        id="{{ $fieldId }}"
                        name="{{ $field['name'] }}"
                        autocomplete="{{ $field['autocomplete'] }}"
                        required
                        aria-required="true"
                        @if ($hasError) aria-invalid="true" @endif
                        @if ($describedBy !== '') aria-describedby="{{ $describedBy }}" @endif
                        class="{{ $inputClass }}"
                    >
                    @if ($field['hint'])
                        <p id="{{ $fieldId }}-hint" class="mt-1 text-sm text-gray-600">{{ __('gdpr::register.fields.'.$field['name'].'.helper_text') }}</p>
                    @endif
                    @if ($hasError)
                        <p id="{{ $fieldId }}-error" class="mt-1 text-sm text-red-700">{{ $errors->first($field['name']) }}</p>
                    @endif
                </div>
            @endforeach
        </fieldset>

        <fieldset class="space-y-4">
            <legend class="mb-2 text-lg font-semibold text-gray-900">{{ __('gdpr::register.consents.title') }}</legend>
            @foreach ($consents as $consent)
                @php
                    $consentId = 'register-'.str_replace('_', '-', $consent['name']);
                    $consentHasError = $errors->has($consent['name']);
                @endphp
                <div>
                    <div class="flex items-start gap-3">
                        <input
                            wire:model="{{ $consent['name'] }}"
                            type="checkbox"
                            id="{{ $consentId }}"
                            name="{{ $consent['name'] }}"
                            required
                            aria-required="true"
                            @if ($consentHasError) aria-invalid="true" aria-describedby="{{ $consentId }}-error" @endif
                            class="mt-1 h-5 w-5 shrink-0 rounded border-gray-300 focus:outline-none focus:ring-2 focus:ring-[#2563eb]"
                        >
                        <label for="{{ $consentId }}" class="text-sm text-gray-700">
                            {{ __('gdpr::register.consents.'.$consent['label']) }}
                            <a href="{{ $consent['url'] }}" target="_blank" rel="noopener" class="font-medium text-[#2563eb] underline">
                                {{ __('gdpr::register.consents.'.$consent['link']) }}<span class="sr-only"> ({{ __('gdpr::register.consents.opens_new_tab') }})</span>
                            </a>
                        </label>
                    </div>
                    @if ($consentHasError)
                        <p id="{{ $consentId }}-error" class="mt-1 text-sm text-red-700">{{ $errors->first($consent['name']) }}</p>
                    @endif
                </div>
            @endforeach

            {{-- Marketing: consenso separato e facoltativo, mai preselezionato. --}}
            <div class="flex items-start gap-3">
                <input
                    wire:model="marketing_consent"
                    type="checkbox"
                    id="register-marketing-consent"
                    name="marketing_consent"
                    aria-describedby="register-marketing-consent-hint"
                    class="mt-1 h-5 w-5 shrink-0 rounded border-gray-300 focus:outline-none focus:ring-2 focus:ring-[#2563eb]"
                >
                <label for="register-marketing-consent" class="text-sm text-gray-700">
                    {{ __('gdpr::register.consents.marketing_label') }}
                    <span id="register-marketing-consent-hint" class="block text-gray-600">{{ __('gdpr::register.consents.marketing_hint') }}</span>
                </label>
            </div>
        </fieldset>

        <button
            type="submit"
            wire:loading.attr="disabled"
            wire:target="submit"
            class="block w-full min-h-11 rounded-lg bg-[#2563eb] px-5 py-3 font-semibold text-white focus:outline-none focus:ring-4 focus:ring-[#2563eb] disabled:opacity-50"
        >
            {{ __('gdpr::register.form.submit') }}
        </button>
    </form>

    <p class="mt-6 text-center text-sm text-gray-700">
        {{ __('gdpr::register.already_registered') }}
        <a href="{{ route('login') }}" class="font-semibold text-[#2563eb] underline">{{ __('gdpr::register.login') }}</a>
    </p>
</div>
