# BMAD — Ragionamento /it/auth/login (system reasoning)
Contesto: https://fixcity.eu/it/auth/login — server locale 0.0.0.0:8000 attivo ma HTTP 500.
Errori log: SecurityHeaders.php (line 25), FolioManager.php (line 92), Filament Notifications Middleware.
Componente aggiunto: components/utilities/google-button.blade.php + inserito in auth/login.blade.php.
Vincoli: NO controllers, NO web.php/api.php modifiche; stack Folio + Volt + Filament; DB MariaDB.
Second Brain: memory/second-brain/auth-login-reason.json.
Filament Blueprint: login con x-filament::social-login providers=[google] + @include google-button.
Azione consigliata:
1. Correggere SecurityHeaders middleware (probabile eccezione header/redirect).
2. Verificare route folio per /it/auth/login (FolioManager richiede view esistente).
3. Se route mancante, creare routes/views/auth/login.blade.php come page Folio oppure usare template esistente.
4. Mantenere componente Google Login (Alpine + Filament route 'filament.auth.login.social').
5. DB: MariaDB; social login OAuth salvi su DB se necessario (Socialite o Filament Socialite).
6. Verificare parity 375/768/1440 dopo fix.
