# BMAD — Visual Parity Design Comuni /it/domande-frequenti + /it/blog + /it/events
Stack: Tailwind v4 + Alpine.js + Lit + DaisyUI + Flowbite (NO Bootstrap Italia)
DB: MariaDB (sqlite escluso)
Second Brain: memory/second-brain/design-comuni.json
Blocchi riconosciuti (design-comuni-pagine-statiche):
- hero/header con breadcrumb + titolo sezione
- griglia card argomenti/risorse/eventi (grid-cols-1 md:grid-cols-2 lg:grid-cols-3)
- filtri/categorie (chips DaisyUI)
- lista paginata con meta (data, categoria, tag)
- CTA con link a dettagli (route locale, non pages.view ma pages.show)
Obiettivo: alta visual parity con design-comuni-pagine-statiche; migliorare blog/events con stessa struttura.
