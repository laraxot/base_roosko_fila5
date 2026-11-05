# BMAD — Convenzione Chiavi Traduzione (5-elementi)

## Regola rigida
Tutte le chiavi di traduzione devono avere **esattamente 5 elementi**:
`<namespace>::<contesto>.<collezione>.<chiave>.<tipo>`

Esempi corretti:
- `fixcity::auth.login.email.text` (namespace=fixcity, contesto=auth, collezione=login, chiave=email, tipo=text)
- `fixcity::auth.login.button.text`
- `fixcity::auth.register.action.text`
- `user::auth.login.google.button.text`
- `pub_theme::accessibility.title.text`

Esempi SBAGLIATI (da correggere):
- `fixcity::auth.email` → manca collezione, chiave, tipo
- `fixcity::auth.title` → manca collezione
- `fixcity::auth.register` → manca chiave/tipo
- `pub_theme::accessibility.title` → manca tipo

## Documentazione aggiornata nelle cartelle:
- `Topics/Sixteen/docs/`
- `Modules/User/docs/`
- `Module/UI/docs/`

## Second Brain
- `memory/second-brain/auth-login-final.json`
- `memory/second-brain/auth-login-architettura.json`
- `memory/second-brain/vite-theme-resolution.json`
- `memory/second-brain/dati-sacri-rules.json`
