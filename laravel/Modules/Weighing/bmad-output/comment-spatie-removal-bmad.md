---
type: bmad-epic-story
tags: [comment, spatie-removal, ponytail, second-brain, filament-blueprint]
status: open
created: 2026-10-16
updated: 2026-10-16
related:
  - module: Comment
  - initiative_id: kp-spatie-removal-comment
scope:
  in:
    - rimuovere packages/spatie/ (fork vendor)
    - sostituire import Spatie con native engine (symfony/html-sanitizer)
    - mantenere Livewire + Filament + QueueableAction
  out:
    - non riscrivere logica business (commenti, moderazione, notifiche)
    - non introdurre nuovi vendor in packages/
---
# EPIC: Internalizzare Spatie — rimuovere packages/

Perché: il fork `packages/spatie/` viola Ponytail (YAGNI → stdlib/nativo; nessuna dipendenza esterna venduta dentro il repo). Lo scopo NON è riscrivere il motore commenti, ma tagliare il vendor fork e usare `symfony/html-sanitizer` (già richiesto) per la sanitizzazione.

Passi Ponytail:
1. Cancella packages/ (fatto)
2. Rimuovi autoload Spatie (fatto)
3. Sostituisci `Spatie\Comments\Support\CommentSanitizer` in `SanitizeCommentTextAction` con `Symfony\Component\HtmlSanitizer\HtmlSanitizer` (minimo: una classe wrapper native)
4. Mantieni `CommentEngineServiceProvider`, `CommentPolicy`, widget Filament — non toccare.

> 🧠 **From Hindsight memory (Component map)** — Comment usa Spatie come vendor interno; i documenti `STORY-158` dichiarano completata l'internalizzazione ma `packages/` resta presente e `SanitizeCommentTextAction` importa ancora `Spatie\Comments\...`.
