# 📧 Email Deliverability Best Practices – TwentyOne Theme

**Theme**: TwentyOne (owner)  
**Status**: ✅ CONFIGURATO E OPERATIVO  
**Data**: 2026-11-12  

> **Obiettivo**: Template email minimali e performanti per evitare lo Spam nel tema TwentyOne.

---

## 📌 Panoramica

Il tema TwentyOne segue il design Zen e applica gli stessi principi alle email:
- Design pulito e minimalista
- Alto contrasto per accessibilità
- Struttura semantica corretta

---

## 📋 Best Practices

### 1. Design Email

- Colori a alto contrasto (WCAG 2.1 AA)
- Fonti web sicuri o sistema
- Layout single-column per mobile

### 2. Contenuto Anti-Spam

Evitare parole chiave che triggerano i filtri spam:
- ❌ "Gratis", "Gratuito", "Offerta"
- ❌ "CLICCA QUI", "Ottieni subito"
- ❌ Molti punti esclamativi!!!
- ❌ Testo tutto in maiuscolo

### 3. Autenticazione

Tutte le email del tema TwentyOne usano:
- `mailtrap-sdk` per sviluppo/test
- `sendmail` per produzione locale
- `smtp` per provider esterni (SendGrid, Mailgun)

---

*BMAD + ECC + Second Brain + Filament Blueprint – Version 1.0*