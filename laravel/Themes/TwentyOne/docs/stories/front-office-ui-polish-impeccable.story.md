---
title: "Front office: rifinitura UI/UX (impeccable + ui-ux-pro-max)"
type: story
status: done
created: 2026-11-10
updated: 2026-11-10
related:
  - ../../../../Modules/Predict/docs/manuale/README.md
  - ../../../../../PRODUCT.md
---

# Story: rifinitura del front office

**Come** visitatore o giocatore **voglio** schede, numeri e pulsanti chiari e coerenti
**così che** capisca un mercato a colpo d'occhio e scommetta senza errori.

## Metodo

- Regole di base di impeccable applicate: niente etichette "eyebrow" sopra i titoli, niente riquadri
  con le metriche dell'hero, niente emoji usate come icone, focus visibile, `::selection`,
  movimento ridotto, numeri tabellari, aree cliccabili ≥ 44px, contrasto ≥ 4.5:1.
- ui-ux-pro-max `--design-system` proponeva uno stile neon "3D gaming": **non adottato**,
  perché PRODUCT.md chiede chiarezza, nessun richiamo a soldi o premi e una rifinitura che
  conservi il prodotto, non un rifacimento.
- Il launcher di impeccable non è stato eseguito: sono state applicate a mano le sue regole.

## Fatto

- Scheda mercato (`filament/widgets/predict-table/item.blade.php`): barre di probabilità, data
  "Chiude il", un solo pulsante "Apri il mercato"; testi in `predict::predict_card`.
- Home (hero cinematic): numeri coerenti con `/predicts` (mercati aperti, crediti puntati),
  mercati in evidenza non più vuoti con i mercati Sì/No, percentuali = prezzo del mercato
  (prima la home diceva "No 71%" e il mercato "Sì 89%"), tolti il segnaposto foto e "Leader".
- Barra di fiducia con tre link verificabili; crediti in alto → portafoglio; pulsante di
  accesso blu (contrasto); stato "Chiuso" in arancione; podio 1°/2°/3°; categorie con titoli.
- Icone dei pulsanti secondari corrette (non più "aggiungi utente" su "Esplora i mercati").

## Manuale

Capitoli 1–2 e schermate aggiornati; PDF `Modules/Predict/docs/manuale/manuale-front-office.pdf`.
