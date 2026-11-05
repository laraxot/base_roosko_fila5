# BMAD — Sistema di Auth Login per /it/auth/login
Stack: Folio + Volt + Filament + Tailwind + Alpine + Lit + DaisyUI + Flowbite (NO Bootstrap Italia)
DB: MariaDB (sqlite escluso)

File principale:
- laravel/Themes/Sixteen/resources/views/pages/auth/login.blade.php (Folio route /it/auth/login)

Elementi inclusi nel login:
1. Form email/password con POST route('login') → accesso con credenziali
2. Link registrazione → /auth/register
3. Link recupero password → /auth/password/forgot
4. Bottone Login con Google → route('social.redirect', ['provider' => 'google'])
5. Link accessibilità

Traduzioni:
- fixcity::auth.login.title
- fixcity::auth.login.heading
- fixcity::auth.login.subtitle
- fixcity::auth.login.email / .email_placeholder
- fixcity::auth.login.password / .password_placeholder
- fixcity::auth.login.button
- fixcity::auth.register
- fixcity::auth.forgot_password

BMAD + Second Brain aggiornati per il sistema di autenticazione.
