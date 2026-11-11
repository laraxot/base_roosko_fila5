---
title: "Funzioni dei concorrenti: grafico, attività, mercati collegati, coriandoli, bonus del giorno"
type: story
status: done
created: 2026-11-10
updated: 2026-11-10
related:
  - ../../../../Modules/Predict/docs/competitor-analysis.md
  - ../../../../Modules/Predict/docs/manuale/11-cosa-succede.md
---

# Story: alla pari con Polymarket, Kalshi e Manifold

**Come** giocatore **voglio** vedere come cambiano le probabilità, chi sta scommettendo e
festeggiare quando vinco, **così che** il gioco sia chiaro e coinvolgente come sui siti migliori.

## Criteri di accettazione (verificati 2026-11-10)

- [x] Report `Modules/Predict/docs/competitor-analysis.md` (sostituisce la versione senza fonti).
- [x] Ogni ricalcolo delle percentuali salva un punto (`price_history` tipo `snapshot`).
- [x] Pagina mercato: grafico SVG (top 4 risposte, variazione 24h), barre volume giornaliero,
      ultime 8 scommesse (nome + iniziale), 3 mercati collegati. Vecchi punti `lmsr` Sì/No letti.
- [x] Coriandoli + "Hai vinto!" una volta per vincita; niente animazione con riduci movimento.
- [x] Bonus del giorno 50 + 10/giorno di fila (max 7), config `config/local/predict/predict_bonus.php`.
- [x] Manuale: giocatore 2.8-2.10, nuovo capitolo 11 "Cosa succede quando…", PDF front office e
      **nuovo PDF back office**.
- [x] Fuori story: 347 PNG del progetto ripristinati (corrotti CRLF→LF).

## Note per gli altri agenti

- La richiesta di usare "widget Pulse" per i grafici non è stata seguita: Laravel Pulse è il
  pannello di monitoraggio del server (accesso solo super-admin), non un componente per pagine
  pubbliche. Grafico SVG lato server, nessuna dipendenza JS.
- Da fare (report competitors, priorità 1-7): riquadro Regole, commenti pubblici, preferiti e
  notifiche, leghe mensili, vendita, immagini Open Graph.
