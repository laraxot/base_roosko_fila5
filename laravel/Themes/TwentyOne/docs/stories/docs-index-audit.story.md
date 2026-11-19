# Story: docs index audit — TwentyOne

Fase BMAD: Solutioning/Docs (audit + indicizzazione, no modifica contenuti esistenti).

Creato `Themes/TwentyOne/docs/index.md`: indice per argomento dei 138 file `.md` di root
di `docs/`, con link alle sottocartelle gia' indicizzate (`predict/README.md`,
`phpstan/README.md`, `wiki/index.md`, `raw/index.md`) e a quelle senza indice proprio
(kalshi-analysis, prompts, roadmap, memories, llm-wiki, i18n, concepts, root-txt-files).

Duplicati/superati raggruppati (non cancellati) in "Storico / da consolidare": cluster
filament login/auth widget (~20 file), doppia serie prodotto roadmap/strategy/sprint/
research/launch (13 file), audit qualita'/redundancy datati (8 file), coppie puntuali
(INDEX_BLADE_ERROR_FIX vs index-blade-errors-and-fixes, NO_PREDICT_SPECIFIC_PAGES vs
..._IN_THEME, antigravity-auth-success root vs memories/), e varianti hyphen/underscore
in `predict/` (futuur/prediki analysis).

Verifica: script bash di controllo copertura — ogni file `.md` di root e ogni file elencato
delle sottocartelle dirette compare almeno una volta in `index.md` (0 missing).

Nessun file `.md` esistente rinominato o cancellato. Nessun commit/push eseguito.
