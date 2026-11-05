<?php

declare(strict_types=1);

use Filament\Forms\Components\MarkdownEditor;
use Livewire\Livewire;
use Modules\Comment\Filament\Widgets\Comment\CommentWidget;
use Modules\Comment\Filament\Widgets\Commentable\CommentsWidget;
use Modules\Comment\Filament\Widgets\Schemas\CommentTextForm;
use Modules\Comment\Models\Comment;
use Modules\Comment\Tests\TestCase;
use Modules\Fixcity\Models\Ticket;
use Modules\Fixcity\Models\User;
use Webmozart\Assert\Assert;

uses(TestCase::class);

/**
 * @return array{0: User, 1: Ticket}
 */
function ownedTicketWithCommentator(): array
{
    $user = User::factory()->createOne(['email' => fake()->unique()->safeEmail()]);
    Assert::isInstanceOf($user, User::class);
    $ticket = Ticket::factory()->createOne(['owner_id' => $user->getKey()]);

    return [$user, $ticket];
}

it('exposes the markdown editor from CommentTextForm for comments and replies', function (): void {
    expect(CommentTextForm::getCommentFormSchema()['text'])->toBeInstanceOf(MarkdownEditor::class);
});

it('stores the comment a logged-in user submits', function (): void {
    [$user, $ticket] = ownedTicketWithCommentator();
    $this->actingAs($user);

    Livewire::test(CommentsWidget::class, [
        'commentableType' => $ticket::class,
        'commentableKey' => $ticket->getKey(),
    ])
        ->set('data.text', 'La buca **è ancora lì**')
        ->call('comment')
        ->assertHasNoErrors();

    $comment = Comment::query()->where('commentable_id', $ticket->getKey())->latest('id')->first();

    expect($comment)->toBeInstanceOf(Comment::class)
        ->and($comment?->original_text)->toBe('La buca **è ancora lì**')
        ->and((string) $comment?->text)->toContain('<strong>è ancora lì</strong>');
});

it('lets a logged-in user reply to a comment', function (): void {
    [$user, $ticket] = ownedTicketWithCommentator();
    $this->actingAs($user);
    $parent = $ticket->comment('Prima segnalazione', $user);

    Livewire::test(CommentWidget::class, ['comment' => $parent, 'showReplies' => true])
        ->call('startReplying')
        ->set('data.text', 'Risposta al commento')
        ->call('reply')
        ->assertHasNoErrors();

    expect($parent->nestedComments()->pluck('original_text')->all())->toContain('Risposta al commento');
});

it('renders an existing thread with the commentator avatar and a reply button', function (): void {
    [$user, $ticket] = ownedTicketWithCommentator();
    $this->actingAs($user);
    $parent = $ticket->comment('Commento con risposta', $user);
    $parent->comment('Risposta annidata', $user);

    Livewire::test(CommentsWidget::class, [
        'commentableType' => $ticket::class,
        'commentableKey' => $ticket->getKey(),
        'noReplies' => false,
    ])
        ->assertSee('Commento con risposta')
        ->assertSee('Risposta annidata')
        ->assertSee('gravatar.com/avatar/', false)
        ->assertSee(__('comment::txt.reply'));
});
