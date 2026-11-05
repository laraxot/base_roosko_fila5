# BMAD — Final System Design /it/auth/login
Folio route: /auth/{provider} via pages/auth/[provider]/index.blade.php (Folio dynamic)
Route abstract: 'oauth.redirect' (not 'socialite.oauth.redirect') — Dependency Inversion
DB: MariaDB (sqlite excluded)
Stack: Tailwind + Alpine + Lit + DaisyUI + Flowbite (NO Bootstrap Italia)
Component: components/utilities/google-button.blade.php (Alpine + Filament route)
Widget: SocialLoginWidget (redirectRoute => 'oauth.redirect')
Memory: memory/second-brain/auth-login-reason.json
Architecture: Folio + Volt + Filament, zero controllers, zero web.php/api.php edits
Visual parity: 95%+ with design-comuni-pagine-statiche (griglia, breadcrumb, card, filtri, CTA)
