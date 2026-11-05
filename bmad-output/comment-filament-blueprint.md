# Filament Blueprint — Timeline + Chiusura condizionata

- **Moduli:** Fixcity (TimelineStep, TimelineAttachment), Comment (widget con allegati e note private)
- **Componenti:** TimelineStepper widget, StatusBadge component, CommentWidget con upload
- **Pagine:** `ViewTicket` aggiornata con grid 2/3 (timeline) + 1/3 (commenti + stato + azioni)
- **Pattern:** Design Comunication — step sequenziali, stato visibile, conferma con codice riferimento, messaggio in area personale, prossimi passi, scadenze
- **Visibilità:** cittadino (passi pubblici), moderatore (tutti + allegati + note private), dipartimento (i suoi passi + risposta), admin (tutto)
- **Chiusura:** condizionata (`closed_resolved`, `closed_rejected`, `closed_delegated`, `closed_not_resolved`) — NON automatica alla risposta, richiede conferma moderatore
- **Sollecito:** `email_reminder` con scadenza automatica (`followup_at`)
- **Allegati:** ogni foto aggiunta è un nuovo `TimelineStep::photo_added` con `TimelineAttachment`
- **Note private:** `private_note = true` — visibili solo a moderatore/dipartimento/admin, non al cittadino
- **Documenti BMAD:** `ticket-timeline-bmad.md`, `ticket-timeline-closure-bmad.md`
- **Second Brain:** `timeline-closure-second-brain.md`
