---
type: bmad-epic-story
tags: [ticket, timeline, moderazione, servizi, design-comuni, bmad, second-brain, filament-blueprint]
status: open
created: 2026-10-16
updated: 2026-10-16
related:
  - module: Fixcity
  - module: Comment
  - initiative_id: kp-servizi-design-comuni
title: 
  "Servizio Segnalazioni: Timeline e Tracciamento completo passaggi del ticket"
summary: |
  Progettare una timeline di servizio per il dettaglio del ticket FixCity che mostra passo-passo la progressione: segnalazione utente → revisione moderatore → assegnazione dipartimento → comunicazione email → risposta utente → chiusura. Basato sui pattern Design Comunication per servizi come "Graduatoria", "Permessi", "Messaggi", questo epic crea un pipeline di tracciamento realistico con scadenze e visibilità appropriate per ogni ruolo (cittadino, moderatore, dipartimento).
scope:
  in:
    - Schema dati "TimelineStep" con tipo, descrizione, responsabile, data, azione
    - Componenti UI "TimelineStepper" e "StatusBadge" (pattern design-comuni)
    - Endpoint API per creazione passo e aggiornamento stato
    - Integrazione con Comment widget per note moderator nei passaggi
    - Mock dati per timeline di esempio: "Segnalato il 15 ottobre 2026 ore 14:32" → "Rifiutato dal moderatore il 16 ottobre 2026 ore 09:15" → "Inviata email a ULSS il 16 ottobre 2026 ore 10:22" → ecc.
  out:
    - Pagina dettaglio ticket (ò/tickets/{id}) mostra timeline completa in stile Design Comunication
    - Ogni passo mostra avatar del responsabile, icona tipo, data/ora, azione, note (se autorizzate)
    - Cittadino vede solo passaggi visibili per il suo ruolo (in attesa, assegnato, chiuso)
    - Moderatore puo' aggiungere note e cambiare stato tramite pannello admin
    - Link a servizi correlati (servizi-design-comuni) nella sidebar
    - Stato corrente del ticket indicato con badge colorato (arancione: in corso, verde: completato, grigio: in attesa)
---

# EPIC: Timeline di servizio e tracciamento passaggi per il dettaglio del ticket

## Perché (basato su Design Comunication)
I servizi Design Comunication mostrano un pattern chiaro:
- **Schede di servizio** con step sequenziali (iscrizione, richiesta, conferma)
- **Griglie di stato** con date e Next steps
- **Badge attivi** che indicano lo stato corrente
- **Pagine di conferma** che riassumono l'azione svolta e mostrano i passi successivi

Per FixCity, un ticket non e' solo un registro: e' un **servizio** che i cittadini seguono. La timeline deve mostrare:
1. **Chi ha fatto cosa, quando e perche'** (tracciamento completo)
2. **Cosa cambia per il cittadino** (visibilita' basata sul ruolo)
3. **Prossimi passi** (chiari, come nei servizi Design Comunication)
4. **Scadenze** (bandi per follow-up)

## Cosa verra' costruito

### 1. Dati: Modello TimelineStep
- `type`: `created`, `moderated`, `assigned`, `email_sent`, `responded`, `closed`, etc.
- `description`: breve testo (es. "Segnalazione ricevuta dal moderatore")
- `responsible`: "cittadino", "moderatore X", "ULSS", "Comune", "Supporto"
- `performed_at`: Carbon nullable (quando compiuto)
- `action_note`: testo libero (visibile ai moderatori)
- `requires_followup`: bool (per calcolare prossime scadenze)

### 2. Componenti UI

#### TimelineStepper (`filament/widgets/TimelineStepper.php`)
- Mostra una linea verticale con nodi collegati
- Ogni nodo: icona (punto, mail, assegnazione, chiusura), avatar responsabile, badge stato, data/ora, descrizione, note (se autorizzate)
- Stato corrente evidenziato con colore (arancione), completato (verde), in attesa (grigio), scartato (rosso)

#### StatusBadge (riutilizzabile da Filament, ma custom per questo progetto)
- Supporta tipi: `active`, `completed`, `pending`, `rejected`
- Mostra icona + label + data/ora opzionale
- Mostra punto di connessione con il prossimo step se applicabile

### 3. Integrazione con Comment
- Il widget Comment (Filament) puo' essere usato per **note dei moderatori**:
  - Moderatore puo' aggiungere un commento alla timeline tramite il widget (pannello admin)
  - Il commento appare nel nodo piu' recente con `type: 'moderator_note'`
  - I cittadini vedono solo note che non violano la privacy (nessun dato sensibile)

### 4. Mock dati esempio
```json
"timeline": [
  {"type": "created", "description": "Segnalazione ricevuta", "responsible": "cittadino", "performed_at": "2026-10-15T14:32:00", "requires_followup": false},
  {"type": "moderated", "description": "Rifiutato dal moderatore (contenuto non appropriato)", "responsible": "moderatoreA", "performed_at": "2026-10-16T09:15:00", "action_note": "La foto dei piedi non e' pertinente. Richiesto motivo.", "requires_followup": true},
  {"type": "assigned", "description": "Inviato a ULSS per valutazione medica", "responsible": "ULSS_Brescia", "performed_at": "2026-10-16T10:22:00", "action_note": "Richiedere referto del pronto soccorso.", "requires_followup": true},
  {"type": "email_sent", "description": "Email inviata a mario.rossi@gmail.com", "responsible": "supporto", "performed_at": "2026-10-16T11:05:00", "action_note": "Oggetto: Richiamo in merito alla segnalazione ZPL-2026-10-15. Paziente contattato.", "requires_followup": true},
  {"type": "responded", "description": "Risposta del cittadino ricevuta", "responsible": "cittadino", "performed_at": "2026-10-16T14:48:00", "action_note": "Ho ricevuto la mail. Chiedo chiarimenti sui prossimi passi.", "requires_followup": false},
  {"type": "closed", "description": "Chiuso — archiviato", "responsible": "moderatoreB", "performed_at": "2026-10-17T09:00:00", "action_note": "Segnalazione chiusa dopo follow-up. Nessuna azione correttiva necessaria.", "requires_followup": false}
]
```

### 5. Layout pagina dettaglio ticket
- **Header**: Titolo ticket, stato corrente (badge), azioni rapida (chat, segnala)
- **Grid principale**:
  - **Columna sinistra** (2/3): Timeline completo con TimelineStepper
  - **Columna destra** (1/3): Riepilogo, servizi correlati (basati su Design Comunication pattern), azioni veloci (aggiungi commento moderatore, cambia stato)
- **Footer della pagina**: Link al servizio (es. "Leggi la guida per le segnalazioni"), contatti, prossimo passo previsto (se richiede follow-up)

### 6. Regole di visibilita'
- **Cittadino**: Vede solo passi assegnati a lui (`cittadino`, `moderatore`?) + data/ora delle azioni future (se programmate)
- **Moderatore**: Vede tutti i passi + puo' aggiungere note tramite Comment widget (se autorizzato)
- **Dipartimento** (ULSS, Comune, ecc.): Vede i suoi passi + puo' aggiornare stato e note
- **Admin**: Vede tutti i dati grezzi, puo' gestire timeline, aggiungere Note interne non visibili ai non-admin

### 7. Integrazione con i servizi Design Comunication
- La timeline serve come **visualizzazione del servizio** per la "scheda servizio" del ticket
- Nella sidebar, elenca **servizi correlati** (es. "Graduatoria iscrizioni", "Permessi ZTL", "Vantaggi sociali") con link agli endpoint esistenti
- Mostra **prossimi passi** con icone dei servizi e date, seguendo il pattern Design Comunication.

## Architecture Notes

- **Ponytail**: TimelineMinimal — solo i campi necessari per tracciare, niente extra.
- **Second Brain**: La timeline e' parte dell'iniziativa `kp-servizi-design-comuni` e viene memorizzata come事实记录 (non solo in memoria volatile).
- **Filament Blueprint**: Il widget TimelineStepper e il componente StatusBadge sono definiti in `bmad-output/ticket-timeline-bmad.md` e sono estensioni dei componenti Filament esistenti.
- **BMAD**: Epic story creato qui; ogni sotto-task (schema dati, componenti UI, mock dati) verra' catturato con ADR (Architectural Decision Record) come parte di questa epic.

## Prossimi passi per gli sviluppatori

1. **Creare il modello TimelineStep** (`Modules/Fixcity/Models/TimelineStep.php`)
2. **Generare le migrations** per `timeline_steps` e `timeline_step_actor` (table di joint per molti-a-molti responsabili)
3. **Implementare TimelineStepper widget** (`Filament/Widgets/TimelineStepper.php`)
4. **Creare StatusBadge component** (`Filament/Components/StatusBadge.php`)
5. **Aggiornare la rotta** `routes/web.php` per il dettaglio del ticket e embedder per TimelineStepper (se necessario)
6. **Creare seeder** per dati esempio nella pipeline del ticket
7. **Aggiornare la policy** `CommentPolicy` per consentire ai moderatori di aggiungere note tramite Comment widget
8. **Documentare** nell'indice dei docs del modulo Fixcity.

---

## Disclaimer
Questo epic segue il pattern Design Comunication di mostrare il servizio come una **paginazione di stato sequenziale**, rendendolo adatto ad un sistema che i cittadini possono comprendere intuitivamente.

## Riferimenti
- `bmad-output/comment-system-second-brain.md`
- `bmad-output/comment-pure-filament-widget.md`
- Design Comunication servizi (gradiuatoria, permessi, messaggi, vantaggi)
- Fixcity ticket module (models/Ticket.php, HasComments)
- Filament Widgets/Components patterns
