# Timeline chiusura condizionata — Second Brain

- **Status:** Open
- **Initiative:** kp-servizi-design-comuni-timeline-ticket
- **Created:** 2026-10-16
- **Related docs:** `bmad-output/ticket-timeline-bmad.md`, `bmad-output/ticket-timeline-closure-bmad.md`

## Regole di chiusura (NON chiude automaticamente alla risposta)

Il ticket si chiude SOLO con un passo esplicito `closed_*`. La risposta del Comune (`response_*`) determina il tipo di chiusura ma NON la chiude automaticamente. Il moderatore deve confermare.

### Tipi chiusura:
- `closed_resolved` — problema risolto (risposta positiva con esito "risolto")
- `closed_rejected` — scartato dal moderatore o dal dipartimento (risposta negativa con motivo)
- `closed_delegated` — non è compito nostro, delegato a ULSS/Vigili (risposta delegata)
- `closed_not_resolved` — non risolto (risposta negativa o scadenza senza risposta + sollecito)

### Sollecito (`email_reminder`):
- Creato automaticamente se `requires_followup = true` e `followup_at` scaduta.
- Non chiude il ticket, solo notifica il dipartimento.
- Dopo sollecito, il moderatore può chiudere con `closed_not_resolved` se non arriva risposta.

### Allegati foto (`photo_added`):
- Ogni foto aggiunta è un nuovo passo nella timeline (`TimelineStep::type = 'photo_added'` con `attachment_id`).
- Il cittadino può aggiungere foto in qualsiasi momento (se il ticket non è chiuso).
- Il moderatore può aggiungere foto con nota privata (`private_note = true`).
- Ogni allegato ha `original_name`, `mime_type`, `uploaded_by`, `uploaded_at`.

### Visibilità (`private_note`):
- `false` (default): visibile a cittadino, moderatore, dipartimento, admin.
- `true`: visibile solo a moderatore, dipartimento assegnato, admin. Il cittadino NON vede il contenuto ma vede che esiste un passo (tipo `photo_added` senza contenuto della nota).

### Passaggi sequenziali della timeline (ordine cronologico):
1. `created` → `moderated_approved` / `moderated_rejected` → `assigned` → `email_sent` → (`email_reminder` se scaduto) → (`photo_added` se allegato) → `response_received` (o `response_negative` / `response_delegated` / `response_soon`) → `closed_*` (conferma moderatore).

### Stato badge finale:
- `risolto` (verde) — `closed_resolved`
- `scartato` (grigio) — `closed_rejected`
- `delegato` (blu) — `closed_delegated`
- `non risolto` (arancione) — `closed_not_resolved`
- `in attesa` (arancione chiaro) — `moderated_approved` o `assigned` o `email_sent` con `requires_followup = true`

### Regole di ruolo:
- Cittadino: vede solo i suoi passi (`created`, `moderated_approved`, `assigned`, `email_sent`, `email_reminder`, `photo_added` [solo se suo], `response_received`, `response_soon`, `closed_resolved`/`closed_delegated`/`closed_not_resolved`). NON vede `moderated_rejected`, `response_negative`, note private.
- Moderatore: tutto, inclusi allegati, note private, `moderated_rejected`, `response_negative`.
- Dipartimento: i suoi passi (`assigned`, `email_sent`, `email_reminder`, `response_received`, `photo_added` collegati), può aggiungere `response_received`.
- Admin: tutto, inclusi dati grezzi.

### Integrazione Filament:
- Widget `TimelineStepper` nel `ViewTicket` (colonna 2/3).
- Widget `CommentWidget` nel `ViewTicket` (colonna 1/3, per moderatore con allegati e note private).
- Componente `StatusBadge` per lo stato corrente del ticket.
- Pagina `TicketShowPage` aggiornata con grid 2/3 + 1/3.

### Documenti collegati:
- `bmad-output/ticket-timeline-bmad.md` (epic base)
- `bmad-output/ticket-timeline-closure-bmad.md` (epic chiusura condizionata)
- `comment-pure-filament-widget.md`
- Design Comunication: graduatoria-conferma, permessi-conferma, messaggio-area-personale
