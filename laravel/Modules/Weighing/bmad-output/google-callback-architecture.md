# BMAD — Google OAuth Callback Architecture (/it/auth/login/google/callback)
Vincoli: NO web.php/api.php modifications, NO nuovi controller, SOLO Folio + Volt + Filament.
DB: MariaDB (sqlite escluso).
Stack: Tailwind + Alpine + Lit + DaisyUI + Flowbite (NO Bootstrap Italia).
Analisi route esistenti (Modules/User/routes/socialite.php):
- `oauth.redirect` → `/auth/login/{provider}` → RedirectToProviderController
- `oauth.callback` → `/it/auth/login/{provider}/callback` → ProcessCallbackController
Problema: route hardcoded `/it/` e controller-based.
Soluzione Folio + Volt:
1. Pagina Folio per redirect: `pages/auth/login/[provider]/redirect.blade.php` → `/it/auth/login/google` (sostituisce controller)
2. Pagina Folio per callback: `pages/auth/login/[provider]/callback.blade.php` → `/it/auth/login/google/callback` (sostituisce controller)
3. Logica OAuth in componenti Volt (Livewire inline) usando `Laravel\Socialite\Facades\Socialite`.
4. Widget `SocialLoginWidget` usa `redirectRoute = 'oauth.redirect'` → ora punta a pagina Folio.
5. Naming `oauth.redirect` / `oauth.callback` mantenuto (astrazione protocollo, non libreria).
Docs da aggiornare:
- laravel/Themes/Sixteen/docs/auth-google-callback.md
- laravel/Modules/User/docs/oauth-folio.md
- laravel/Modules/User/docs/socialite-folio-migration.md
Second Brain: memory/second-brain/google-callback-folio.json
