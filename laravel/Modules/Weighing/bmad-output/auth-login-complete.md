# BMAD — Auth Login /it/auth/login — Completamento 2026-10-18

## Stato finale (tutto verificato)
- Folio page: laravel/Themes/Sixteen/resources/views/pages/auth/login.blade.php
- Layout: x-layouts.app (stesso pattern di pages/index.blade.php)
- Stack: Tailwind + Alpine + Lit + DaisyUI + Flowbite (NO Bootstrap Italia)
- Vite: @vite(['resources/css/app.css', 'resources/js/app.js'], 'themes/Sixteen')
- DB: MariaDB fixcity_data (users table esiste, dati sacri protetti)
- Form: email + password (POST route('login'))
- Link: registrazione (/auth/register), recupero password (/auth/password/forgot)
- Social Login: widget SocialLoginWidget + bottone Google direct
- Naming: oauth.redirect / oauth.callback (astratto, non Socialite)
- Traduzioni: 5 elementi (namespace::context.collection.key.type)
- Componenti: components/utilities/google-button.blade.php (route oauth.redirect)
- Second Brain: auth-login-architettura.json, design-comuni.json, google-callback-folio.json, dati-sacri-rules.json
- Docs: bmad-output/auth-login-architettura.md, auth-login-system.md, vite-theme-resolution.md, translation-keys-convention.md
- Regola dati sacri: memory/second-brain/dati-sacri-rules.json (mai migrate:fresh / force)
