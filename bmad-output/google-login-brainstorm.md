# BMAD — Brainstorm: Login con Google su /it/auth/login
Vincoli: niente web.php / api.php / controllers; stack Folio + Volt + Filament; DB MariaDB.
Approccio:
1. Filament Socialite plugin (filament/socialite) → provider Socialite + Filament AccountPage
2. Volt component: <x-filament::social-login ... /> → bottone "Accedi con Google"
3. Folio route /it/auth/login (volt action login, redirect a Filament OAuth)
4. Niente controller custom.
5. MCP: mcp__laravel_boost + mcp__qmd; EEC; Filament Blueprint.
6. MariaDB: tabella oAuth (oauth_identities) con engine=Aria/InnoDB, charset utf8mb4.
7. Privacy cookie + GDPR banner su redirect.
Verifica: parity 375/768/1440; WCAG 2.1 AA.
