---
type: story
tags: [security, weighing, ssrf, network]
epic: security-audit
story: M2
slug: scale-address-allowlist
title: "Bilance TCP: solo indirizzi di LAN o consentiti, nessun contenuto del dispositivo nei messaggi (SSRF)"
status: review
phase: REVIEW
owner: Weighing
depends_on: []
related:
  - "../../../Labeling/docs/stories/printer-address-allowlist.story.md"
created: 2026-11-05
updated: 2026-11-05
---

# Bilance TCP: solo indirizzi di LAN o consentiti

## Story

Come **amministratore di sistema**
voglio **che il server legga solo indicatori di peso della rete di stabilimento**
così da **non poter usare "Leggi bilancia" per scandire la rete interna o leggere banner di servizi**.

## Perché / evidenze

- Audit di sicurezza **M2**: `TcpAsciiScaleDriver` apriva `tcp://{host}:{port}` dal record `Scale` senza vincoli;
  `ScaleForm` controllava solo `maxLength`.
- `ScaleReadingParseException::for` metteva i primi 80 caratteri della risposta nel messaggio mostrato all'utente;
  `ScaleConnectionException::unreachable` riportava l'errore del socket (distingue porte chiuse/filtrate).
- La notifica di lettura riuscita mostrava la riga grezza come body (una riga qualunque con un numero viene "letta").

## Soluzione

- `EnsureScaleAddressAllowedAction` (stessa regola di `Labeling\EnsurePrinterAddressAllowedAction`; i moduli non
  dipendono l'uno dall'altro): host non vuoto e valido, niente `localhost` né forme abbreviate di IP, porta in
  `weighing.scale_ports`, metadata cloud/link-local sempre vietati, `weighing.allowed_scale_hosts` vuota = solo LAN privata.
- Usata da `ScaleForm` (regola sul campo host) e dal driver prima del socket; il driver si collega all'IP controllato.
- Messaggi senza contenuto del dispositivo né errore del socket; i dettagli vanno nel log. Notifica di lettura senza riga grezza
  (resta salvata in `raw_readings` per l'audit della pesata).

## Acceptance criteria

1. Form bilancia con host `127.0.0.1`, `169.254.169.254`, `localhost`, `8.8.8.8` o porta `22` → errore di validazione tradotto.
2. Driver con host fuori lista → `ScaleAddressNotAllowedException` prima di `stream_socket_client`.
3. Una risposta illeggibile del dispositivo non compare nel messaggio d'errore.
4. L'errore di connessione non contiene il motivo tecnico del socket.

## Dev agent record

- File: `app/Actions/EnsureScaleAddressAllowedAction.php`, `app/Exceptions/ScaleAddressNotAllowedException.php`,
  `app/Exceptions/ScaleConnectionException.php`, `app/Exceptions/ScaleReadingParseException.php`,
  `app/Drivers/TcpAsciiScaleDriver.php`, `app/Filament/Actions/Concerns/InteractsWithScaleReading.php`,
  `app/Filament/Resources/ScaleResource/Schemas/ScaleForm.php`, `config/config.php`, `lang/{it,en}/exceptions.php`,
  `lang/{it,en}/scale_form.php`, `docs/README.md`.
- Test: `tests/Unit/EnsureScaleAddressAllowedActionTest.php`, `tests/Unit/TcpAsciiScaleDriverTest.php` (loopback in lista
  per i server di prova; indirizzi vietati; porta; nessun echo della risposta né dell'errore del socket).
