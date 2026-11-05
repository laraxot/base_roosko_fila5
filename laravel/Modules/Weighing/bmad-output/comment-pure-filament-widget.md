---
type: filament-blueprint
tags: [comment, filament-widget-only, no-livewire, widget, resource, blueprint]
status: open
created: 2026-10-16
updated: 2026-10-16
related:
  - module: Comment
  - initiative_id: kp-spatie-removal-comment
scope_in: [Comment, Blog, Fixcity (Ticket), Eventi]
scope_out: [Livewire, livewire-components, <livewire:...>, @livewire]
---
# BLUEPRINT: Comment pure Filament widget (NO Livewire)

## Regola architettura
Non si usano più componenti Livewire (`Spatie\LivewireComments\Comments`, `<livewire:...>`, `@livewire`). Tutto il rendering commenti pubblici avviene tramite widget Filament (`Filament\Widgets\Widget`) che usa solo Blade + componenti Filament, con azioni server via Livewire interno di Filament (gestito dal framework, non componente custom).

## Widget `Commentable` (Filament native)
- File: `laravel/Modules/Comment/app/Filament/Widgets/Commentable.php`
- Estende: `Filament\Widgets\Widget` (non Livewire custom)
- Stato: `public $model;` (Eloquent con `HasComments`), `public ?string $newCommentBody = null;`
- Metodi widget:
  - `getViewData()`: carica `commentRoot` (paginati, con eager `replies`, `author`, `reactions`), `formState`
  - `createComment()`: chiama `SanitizeCommentTextAction`, crea record tramite `HasComments` trait, emette evento `CommentCreated`
  - `deleteComment($id)`: verifica `CommentPolicy::delete`, elimina
- Blade: `resources/views/filament/widgets/commentable.blade.php` — card Filament con header, lista commenti (avatar, nome, data, corpo, azione "rispondi"), form `Textarea` (`newCommentBody`), bottone "Invia" (Filament `Action`). Nessun tag `<livewire>`.

## Widget `CommentThread` (risposte annidate)
- File: `laravel/Modules/Comment/app/Filament/Widgets/CommentThread.php`
- Per ogni commento radice, renderizza ricorsivamente (max 2 livelli) le risposte tramite componente Blade `comment-reply-item.blade.php`. Nessun componente Livewire separato.

## Policy `CommentPolicy` (aggiornata per widget)
- File: `laravel/Modules/Comment/app/Policies/CommentPolicy.php`
- Metodi richiesti dal widget:
  - `create(User $user, Model $commentableModel): bool` — `auth()->check()` e `method_exists($commentableModel, 'comments')`
  - `view(User $user, Comment $comment): bool` — sempre vero per commenti pubblici approvati; `pending` solo proprietario
  - `delete(User $user, Comment $comment): bool`
  - `moderate(User $user, Comment $comment): bool` — ruolo `comment_moderator`

## Integrazione FO (Blog / Eventi / Ticket)
Non si inserisce `@livewire(...)`. Si inserisce il widget tramite un controller/page Filament o tramite un componente Blade che renderizza il widget nativamente. Due opzioni:

### Opzione A: Page Filament pubblica (`CommentPage`)
- File: `laravel/Modules/Comment/app/Filament/Pages/CommentPage.php`
- Route FO: `GET /blog/{article}/commenti` → `CommentPage` con parametro `model`
- La pagina usa `getHeaderWidgets()` per restituire `Commentable::class`

### Opzione B: Componente Blade con widget Filament inline (senza Livewire custom)
- File: `resources/views/components/comment-widget.blade.php`
- Renderizza il widget tramite il componente Filament: `{{ \Filament\View::render('filament.widgets.commentable', ['model' => $model]) }}`
- Inserimento in `resources/views/blog/article/show.blade.php`: `@include('components.comment-widget', ['model' => $article])`

Nota: Filament utilizza internamente Livewire per il proprio framework; questo non richiede componenti Livewire custom (`Spatie\LivewireComments`). Il widget `Commentable` è un `Filament\Widgets\Widget` standard.

## Eliminazione tutti i riferimenti Livewire custom dal modulo
- Cancellare/convertire:
  - `docs/concepts/comments-widget-livewire-mount.md` → sostituire con `filament-widget-mount.md`
  - `docs/concepts/no-livewire-filament-widgets-only.md` → aggiornare per riflettere la regola "pure Filament"
  - Qualsiasi import `Spatie\LivewireComments\*` o componente Livewire custom nel codice `Comment` → rimosso (già eliminato con `packages/spatie/`)
- Non si crea `Modules\Comment\View\Livewire\Comments` (previsto in versione precedente): non serve.

## Allineamento siti di riferimento (FO pattern)
- AFC Formazione / Aferrari / Assodella Vendita → commenti pubblici, threadati, moderazione visibile, nessun componente JS pesante.
- Il widget Filament `Commentable` produce lo stesso output: card con lista commenti, form testo, azioni inline, stato server-rendered con refresh via Filament (che usa Livewire interno, non componente custom).

## Note implementazione Ponytail
- Nessun nuovo vendor (solo Filament, già in `composer.json`)
- `symfony/html-sanitizer` per sanitizzazione (già presente)
- Nessun componente Livewire custom → riduzione complessità
- Il widget è un componente standard Filament, riutilizzabile in qualsiasi pagina del pannello o FO (tramite Page Filament o componente Blade con widget inline)