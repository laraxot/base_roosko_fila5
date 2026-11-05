---
type: bmad-epic-story
tags: [ticket, timeline, chiusura, sollecito, foto-allegati, moderazione, design-comuni, second-brain, filament-blueprint]
status: open
created: 2026-10-16
updated: 2026-10-16
related:
  - module: Fixcity
  - module: Comment
  - initiative_id: kp-servizi-design-comuni-timeline-ticket
  - refs: [ticket-timeline-bmad.md, design-comuni-graduatoria-confirm, design-comuni-permessi-confirm]
title: "Ticket: timeline con chiusura condizionata, solleciti, allegati foto e risposte negative del Comune"
summary: |
  Un ticket si chiude SOLO quando il problema è risolto (non solo risposto). Il Comune può rispondere con esiti diversi: "non vogliamo risolvere", "non è compito nostro (delegato a ULSS/Vigili)", "lo sistemeremo presto", "richiediamo altre informazioni", "risolto". Ogni risposta è un PASSO della timeline. Si possono mandare mail di sollecito dopo un certo tempo (scadenza). Si possono caricare nuove foto (commento con allegato) che entrano nella timeline come passi aggiuntivi. Il moderatore può scartare (foto non pertinenti, bestemmie) in qualsiasi momento; dopo approvazione il flusso va: segnalato → moderato (approvato/scartato) → assegnato (dipartimento) → mail inviata → risposta ricevuta → chiuso (risolto) / chiuso (scartato) / chiuso (non risolto, delegato) / chiuso (risolto con sollecito intermedio). Tutti i passaggi visibili per ruolo in base alla privacy.
scope:
  in:
    - Schema TimelineStep con tipi: created, moderated_approved, moderated_rejected, assigned, email_sent, email_reminder, response_received, response_negative, response_delegated, response_soon, photo_added, closed_resolved, closed_rejected, closed_delegated, closed_not_resolved
    - Scadenza per sollecito: calcolo basato su `email_sent_at` + `reminder_after_days` (default 7) con stato `pending_reminder`
    - Allegato foto nel commento del moderatore o cittadino: collegato a TimelineStep come `attachment_id` (tabella `timeline_attachments` con `path`, `original_name`, `mime_type`, `uploaded_by`)
    - Risposta del Comune con tipo di esito (positiva/negativa/delegata/in attesa) che determina il passo successivo e il badge finale
    - Badge di chiusura: `risolto` (verde), `scartato` (grigio), `delegato` (blu), `non risolto` (arancione), `in attesa` (arancione chiaro)
    - Widget Filament Comment integrato nel ViewTicket per moderatore con allegati e note private
  out:
    - TimelineStepper mostra tutti i passaggi inclusi allegati, solleciti, risposte
    - Ogni passo mostra: data, responsabile, azione, allegato (se presente come thumbnail), stato, nota (visibile o privata in base al ruolo)
    - Stato chiusura mostra il MOTIVO (non solo "chiuso") con riferimento al passo di risposta
    - Il cittadino vede solo i suoi passi e le risposte pubbliche; il moderatore vede tutto inclusi allegati e note private; il dipartimento vede i suoi passi e può aggiornare stato
  links:
    design_comuni:
      - "graduatoria-conferma" (step di conferma + prossimi passi)
      - "permessi-conferma" (step di conferma + prossimi passi)
      - "messaggio-area-personale" (messaggio in area personale con codice riferimento)
    bmad_docs:
      - "ticket-timeline-bmad.md"
      - "comment-pure-filament-widget.md"
      - "comment-fo-user-story.md"
---

# EPIC: Timeline servizio completo — chiusura condizionata, solleciti, allegati, risposte Comune

## Contesto Design Comunication
I servizi Design Comunication (graduatoria, permessi, vantaggi, messaggi) mostrano:
- **Passaggi sequenziali** con stato visibile (attivo, completato, in attesa)
- **Conferma** con codice riferimento e prossimi passi
- **Messaggio in area personale** con risposta del servizio
- **Tempi e scadenze** (quando scade il termine, quando viene inviato il sollecito)

## Scopo del progetto (ripetuto e approfondito)
Il cittadino segnala un problema (es. marciapiede rotto, buca stradale, luce spenta, rifiuti). Il **moderatore** controlla (scarta se non pertinente: foto dei piedi, bestemmie). Dopo approvazione, il ticket viene **assegnato** a un dipartimento (ULSS, Comune, Vigili, Supporto). Viene inviata una **mail** con codice riferimento. Il dipartimento **risponde** con un esito:
- Positivo: "risolto" o "lo sistemeremo presto" o "delegato a X"
- Negativo: "non vogliamo risolvere" o "non è compito nostro (delegato a ULSS/Vigili)"
- Intermedio: "richiediamo altre informazioni" o "in attesa di documenti"
Se non arriva risposta, viene inviato un **sollecito** dopo una scadenza. Il cittadino può aggiungere **altre foto** (commento con allegato) che diventano nuovi passaggi della timeline. Il ticket si **chiude solo quando risolto** (o esplicitamente chiuso con motivo). La chiusura mostra il **motivo** (risolto/delegato/non risolto/scartato) e il passo di riferimento.

## Timeline completa (tutti i passaggi possibili)

1. `created` — Segnalato dal cittadino (data/ora, descrizione, allegato iniziale se presente)
2. `moderated_approved` o `moderated_rejected` — Moderatore approva o scarta (nota con motivo)
3. `assigned` — Assegnato al dipartimento (nome, data)
4. `email_sent` — Mail inviata al dipartimento (oggetto, destinatario, data/ora)
5. `email_reminder` — Sollecito inviato (se `pending_reminder` attivo dopo scadenza)
6. `photo_added` — Nuove foto caricate dal cittadino o moderatore (allegato con nota)
7. `response_received` — Risposta ricevuta dal dipartimento (contenuto, tipo esito)
8. `response_negative` — Esito negativo: "non vogliamo risolvere", "non è compito nostro", "scartato dal dipartimento"
9. `response_delegated` — Delegato ad altro ente (es. "non è compito nostro, delegato a ULSS")
10. `response_soon` — In attesa: "lo sistemeremo presto", "richiediamo documenti aggiuntivi"
11. `closed_resolved` — Chiuso, risolto (riferimento al passo di risposta)
12. `closed_rejected` — Chiuso, scartato (riferimento al passo di moderazione o risposta negativa)
13. `closed_delegated` — Chiuso, delegato (riferimento al passo di risposta delegata)
14. `closed_not_resolved` — Chiuso, non risolto (motivo esplicito)

## Allegati (foto) nella timeline
Ogni allegato è un passo `photo_added` con:
- `attachment_path` (storage/app/public/timeline_attachments/...)
- `original_name` (nome originale)
- `uploaded_by` (cittadino o moderatore)
- `uploaded_at`
- `note` (testo del commento associato)
Il moderatore può aggiungere note private (`private_note = true`) visibili solo a moderatori e dipartimento, non al cittadino.

## Visibilità per ruolo
- **Cittadino**: vede `created`, `moderated_approved`, `assigned`, `email_sent`, `email_reminder`, `photo_added` (solo i suoi allegati), `response_received`, `response_soon`, `closed_resolved`/`closed_delegated`/`closed_not_resolved`. NON vede `moderated_rejected`, `response_negative`, note private.
- **Moderatore**: vede tutto, inclusi `moderated_rejected`, `response_negative`, note private, allegati completi.
- **Dipartimento (ULSS, Comune, Vigili)**: vede i passi assegnati a lui, `email_sent`, `email_reminder`, `response_received`, `photo_added` (se allegato collegato alla sua richiesta), `closed_*`. Può aggiungere `response_received` o aggiornare stato.
- **Admin**: tutto, inclusi dati grezzi.

## Componenti Filament
- `TimelineStepper.php` (widget) — mostra la linea verticale con nodi, allegati come thumbnail, stato corrente, scadenze, prossimi passi.
- `StatusBadge.php` — badge colorato con icona, tipo stato (`in corso`, `risolto`, `scartato`, `delegato`, `in attesa`), data.
- `CommentWidget.php` — widget Filament per moderatore con testo + allegato + flag `private_note`.
- `TicketShowPage.php` — pagina dettaglio con grid 2/3 (timeline) + 1/3 (commenti, stato, azioni).

## Prossimi passi
1. Creare tabella `timeline_attachments` (migration)
2. Aggiornare `TimelineStep` con `attachment_id` nullable e `private_note`
3. Implementare `CommentWidget` con upload file
4. Creare `StatusBadge` con tutti i tipi di chiusura
5. Aggiornare `ViewTicket` con widget timeline e commenti
6. Aggiungere seed dati con sollecito, allegati, risposte diverse
7. Documentare regole di visibilità nel `docs/index.md` del modulo Fixcity
