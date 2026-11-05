# Product

## Register

product

## Platform

web (admin panels) and mobile web (operator page on handheld scanners and phones)

## Users

Roosko serves the staff of a waste treatment and sorting plant:

- **Yard operators** work outdoors with gloves, on rugged handhelds (Zebra, Honeywell, Chainway) or phones. They scan QR codes and RFID tags, move containers, start treatments and load shipments. They need large targets, one action per screen and immediate feedback.
- **Weighbridge and acceptance office** registers incoming loads with their FIR, records gross and tare weights, accepts or rejects them.
- **Technical manager** owns legal compliance: authorizations, the loading/unloading register, FIR, RENTRI transmissions, mass balances.
- **Administrator** configures the plant: authorizations, warehouses and areas, scales, label printers, RENTRI credentials.

## Product Purpose

Roosko tracks every unit of hazardous and non-hazardous waste from arrival to exit: identification by label, QR or RFID; mapped storage with authorization checks; treatment (e.g. shredding) that produces new labelled units linked to their origin; re-storage with weight, provenance and a new status for sale (End of Waste) or remediation; automatic register entries and RENTRI submissions. Success means the plant always knows where each container is, what it contains, where it came from and whether the stock is within the authorized limits.

## Positioning

Roosko's distinguishing claim is that compliance is a by-product of the yard work: scanning and moving containers writes the register, the FIR acceptance and the RENTRI transmissions, instead of being re-typed in the office afterwards.

Secondary differentiators:
- **Mapped storage with authorization guards**: a container cannot be put where the plant's authorization does not allow that EWC code or quantity.
- **Lineage across treatments**: every output unit carries the share of each input it came from, through several treatments.
- **Reusable modules**: labeling, weighing, warehouse and processing know nothing about waste and can serve other industries.

## Brand Personality

Practical, reliable, field-ready.

## Anti-references

- Office-only ERP screens that assume a desk, a mouse and a big monitor.
- Dashboards full of decorative charts that hide which container is where.
- Free-text fields where a scan or a constrained choice prevents errors.
- Interfaces that let the user do something the authorization or the law forbids and complain later.

## Design Principles

- Scan first: every flow on the yard starts from a scanned code, never from a search form.
- One clear next action per state; buttons appear only when the state allows them.
- Errors explain the rule that was violated and what to do (other area, other operation, rectify).
- Immutable history: register entries, movements and lineage are never edited, only rectified.
- Labels come from translation files; Italian is the primary language.

## Accessibility & Inclusion

- Touch targets sized for gloved hands; high contrast for outdoor light.
- Operator page usable with a hardware scanner alone, without touching the screen.
- Status always conveyed by text as well as color.
