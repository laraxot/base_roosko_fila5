# BMAD — Architettura /it/auth/login (Folio + Filament + Volt)
Vincoli: NO controllers, NO web.php, NO api.php; DB MariaDB; NO SQLite; NO Bootstrap Italia.
Ragionamento naming `oauth.redirect` vs `socialite.oauth.redirect`:
- Socialite è una libreria (concreta, cambiabile); OAuth è un protocollo (astratto, stabile).
- Dependency Inversion (SOLID): componenti dipendono da `oauth.redirect`, non da `socialite`.
- Se in futuro si passa a un altro provider (Azure, Auth0, SAML), i componenti non cambiano.
- Second Brain traccia `memory/second-brain/oauth-redirect-naming.json` con principio e file coinvolti.
Struttura Folio (https://laravel.com/docs/13.x/folio):
- Pagina dinamica: `pages/auth/[provider]/index.blade.php` (provider = google, spid, cie).
- Route generata automaticamente: `GET /it/auth/{provider}`
- Middleware Folio: SetFolioLocale + BlockLegacyPublicPages
- Layout: `themes::Sixteen.layouts.guest`
File creati/aggiornati:
- pages/auth/[provider]/index.blade.php (Folio dynamic, usa `oauth.redirect` in widget)
- components/utilities/google-button.blade.php (Alpine + Filament)
- components/pages/auth-login.blade.php (SocialLoginWidget con `redirectRoute` => 'oauth.redirect')
- auth/login.blade.php (Livewire widget + Google button integrato)
- bmad-output/auth-login-architettura.md, bmad-output/oauth-redirect-naming.md
DB: MariaDB (`DB_CONNECTION=mariadb`); zero SQLite.
Stack: Tailwind + Alpine + Lit + DaisyUI + Flowbite; no Bootstrap Italia.
Filament Blueprint: SocialLoginWidget configurabile (`redirectRoute` custom), widget login integrato.
