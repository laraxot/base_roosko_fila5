# BMAD — Modelli vs Migrazioni — Modulo Fixcity + User

Regola (dati sacri): mai `migrate:fresh`, mai `database:refresh`. DB MariaDB (`fixcity_data`).

Modelli Fixcity (`app/Models/`):
- Activity, BaseModel, BasePivot, Category, Profile, Report, Ticket, TicketActivity, TicketComment, TicketHour, TicketRelation, TicketSubscriber + Concerns/Scopes/Policies

Migrazioni Fixcity (`database/migrations/`):
- profiles, tickets, activities, comments, hours, relations, subscribers, categories, reports

Modulo User (`app/Models/`): Authentication, Profile, BaseProfile, BaseTeam, ecc.
Migrazione User (`database/migrations/`): `2024_01_01_000001_create_users_table.php` (tabella `users` esistente).

Aggiunta: `2026_01_01_000001_create_users_table.php` in `Modules/Fixcity/database/migrations/` — crea la tabella solo se **non esiste** (`Schema::hasTable('users')` guard), proteggendo i dati sacri.
