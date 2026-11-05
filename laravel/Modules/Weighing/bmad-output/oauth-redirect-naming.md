# BMAD — Naming: oauth.redirect vs socialite.oauth.redirect
Logica: `oauth.redirect` è un **contratto astratto** (Interface), non un'implementazione.
- `socialite.oauth.redirect` = coupling a Laravel Socialite (implementation detail)
- `oauth.redirect` = astrazione del provider OAuth (Google, GitHub, Azure, ecc.)
Principi:
1. **Dependency Inversion** — componenti dipendono da astrazioni, non concrete
2. **Open/Closed** — aggiungi provider senza toccare componenti
3. **Filament Blueprint** — widget `SocialLoginWidget` accetta `redirectRoute` custom; usando `oauth.redirect` rendi il widget provider-agnostico
4. **MariaDB** — tabella `oauth_identities` (non `socialite_identities`) coerente con il naming
5. **Second Brain** — memoria traccia decisioni architetturali, non librerie
Azione: sostituire TUTTI i riferimenti a `socialite.oauth.redirect` → `oauth.redirect`
