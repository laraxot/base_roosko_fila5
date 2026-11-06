<?php

declare(strict_types=1);

use Filament\Forms\Components\MarkdownEditor;
use Livewire\Livewire;
use Modules\Blog\Models\Article;
use Modules\Comment\Filament\Widgets\Comment\CommentWidget;
use Modules\Comment\Filament\Widgets\Commentable\CommentsWidget;
use Modules\Comment\Filament\Widgets\Schemas\CommentTextForm;
use Modules\Comment\Models\Comment;
use Modules\Comment\Tests\TestCase;
use Modules\Comment\Tests\Support\CommentatorUser;
use Webmozart\Assert\Assert;

uses(TestCase::class);

/**
 * @return array{0: CommentatorUser, 1: Article}
 */
function ownedArticleWithCommentator(): array
{
    $user = CommentatorUser::createOne();
    Assert::isInstanceOf($user, CommentatorUser::class);
    $article = Article::factory()->createOne();

    return [$user, $article];
}

it('exposes the markdown editor from CommentTextForm for comments and replies', function (): void {
    expect(CommentTextForm::getCommentFormSchema()['text'])->toBeInstanceOf(MarkdownEditor::class);
});

it('stores the comment a logged-in user submits', function (): void {
    [$user, $article] = ownedArticleWithCommentator();
    $this->actingAs($user);

    Livewire::test(CommentsWidget::class, [
        'commentableType' => $article::class,
        'commentableKey' => $article->getKey(),
    ])
        ->set('data.text', 'La buca **è ancora lì**')
        ->call('comment')
        ->assertHasNoErrors();

    $comment = Comment::query()->where('commentable_id', $article->getKey())->latest('id')->first();

    expect($comment)->toBeInstanceOf(Comment::class)
        ->and($comment?->original_text)->toBe('La buca **è ancora lì**')
        ->and((string) $comment?->text)->toContain('<strong>è ancora lì</strong>');
});

it('lets a logged-in user reply to a comment', function (): void {
    [$user, $article] = ownedArticleWithCommentator();
    $this->actingAs($user);
    $parent = $article->comment('Prima segnalazione', $user);

    Livewire::test(CommentWidget::class, ['comment' => $parent, 'showReplies' => true])
        ->call('startReplying')
        ->set('data.text', 'Risposta al commento')
        ->call('reply')
        ->assertHasNoErrors();

    expect($parent->nestedComments()->pluck('original_text')->all())->toContain('Risposta al commento');
});

it('renders an existing thread with the commentator avatar and a reply button', function (): void {
    [$user, $article] = ownedArticleWithCommentator();
    $this->actingAs($user);
    $parent = $article->comment('Commento con risposta', $user);
    $parent->comment('Risposta annidata', $user);

    Livewire::test(CommentsWidget::class, [
        'commentableType' => $article::class,
        'commentableKey' => $article->getKey(),
        'noReplies' => false,
    ])
        ->assertSee('Commento con risposta')
        ->assertSee('Risposta annidata')
        ->assertSee('gravatar.com/avatar/', false)
        ->assertSee(__('comment::txt.reply'));
});
