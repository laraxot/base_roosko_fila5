---
type: bmad-epic-story
title: Commenti FO per Blog/Eventi/Ticket — loggato può commentare
status: open
created: 2026-10-16
related:
  - module: Comment
  - initiative_id: kp-spatie-removal-comment
scope_in: [Comment, Blog, Fixcity (Ticket)]
---
# STORY: Commenti pubblici per utenti loggati su Blog/Eventi/Ticket

## Perché (da studio dei 3 siti)
AFC Formazione / Aferrari / Assodella Vendita mostrano commenti threadati con avatar, data, corpo; possibilità di rispondere; moderazione leggera; nessun JS pesante.

## Cosa serve per funzionare (con Comment nativo post-Spatie)
1. `HasComments` su `Article`, evento, `Ticket` — già presente.
2. Widget Livewire (`Comments`) in pagina FO — collegare tramite `@livewire('comment::comments', ['model' => $article])`.
3. Policy `CommentPolicy::create` — vero se `$user` autenticato.
4. Route/azione `CommentController@store` — native, non Spatie.

## Note tecnica
- Dopo rimozione `packages/spatie/`, il widget Livewire deve puntare a `Modules\Comment\Http\Livewire\Comments` (da creare copiando il componente vendor).
- `SanitizeCommentTextAction` (già native con Symfony) viene chiamato dal controller di creazione.
- Il blueprint Filament (`bmad-output/comment-filament-blueprint.md`) definisce il widget `Commentable` per pannello admin; il widget FO è separato ma condivide il modello `Comment`.

## Riferimenti
- `bmad-output/comment-spatie-removal-bmad.md`
- `bmad-output/comment-system-second-brain.md`
- `bmad-output/comment-filament-blueprint.md`
- Siti: https://www.afcformazione.it/blog/, https://www.aferrari.it/articoli/, https://www.assodellavendita.it/blog/
