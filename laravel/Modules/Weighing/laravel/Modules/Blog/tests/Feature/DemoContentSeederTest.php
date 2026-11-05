<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Blog;

use Illuminate\Support\Facades\App;
use Modules\Blog\Database\Seeders\DemoContentSeeder;
use Modules\Blog\Models\Article;
use Modules\Comment\Models\Comment;
use Tests\TestCase;

/**
 * Demo marketing-ready: post + eventi + commenti morph + media locali.
 * Parental STI `type` = post|event (Post, Event).
 */
class DemoContentSeederTest extends TestCase
{
    /** @var list<string> */
    protected array $connectionsToTransact = ['blog', 'comment'];

    public function test_demo_content_seeder_sets_post_and_event_content_types(): void
    {
        app(DemoContentSeeder::class)->run();

        $this->assertSame(8, Article::query()->where('type', 'post')->count());
        $this->assertSame(6, Article::query()->where('type', 'event')->count());
        $this->assertSame(0, Article::query()->whereNull('type')->count());
    }

    public function test_demo_content_has_media_credits_and_no_lorem(): void
    {
        app(DemoContentSeeder::class)->run();

        $sample = Article::query()->where('slug', 'piano-asfaltature-autunno')->first();
        $this->assertNotNull($sample);
        $this->assertNotEmpty($sample->main_image_url);
        $blocks = $sample->content_blocks;
        $this->assertIsArray($blocks);
        $json = json_encode($blocks, JSON_THROW_ON_ERROR);
        $this->assertStringContainsString('credit', $json);
        $this->assertStringContainsString('Asset Design Comuni', $json);
        $this->assertDoesNotMatchRegularExpression('/lorem\s+ipsum/i', $json.' '.$sample->title.' '.$sample->description);
    }

    public function test_demo_content_seeder_attaches_comments_via_comment_module(): void
    {
        app(DemoContentSeeder::class)->run();

        $article = Article::query()->where('slug', 'piano-asfaltature-autunno')->firstOrFail();
        $comments = Comment::query()
            ->where('commentable_type', $article->getMorphClass())
            ->where('commentable_id', $article->getKey())
            ->get();

        $this->assertGreaterThanOrEqual(2, $comments->count());
        $this->assertTrue($comments->contains(static fn (Comment $c): bool => $c->approved_at !== null));
        $this->assertTrue(
            Comment::query()->whereNotNull('parent_id')->whereNotNull('approved_at')->exists(),
            'Serve almeno una risposta editoriale (parent_id) approvata');
        $this->assertTrue(
            Comment::query()->whereNull('approved_at')->exists(),
            'Serve almeno un commento pending non esposto');
    }

    public function test_demo_content_seeder_is_idempotent(): void
    {
        app(DemoContentSeeder::class)->run();

        $articleCount = Article::query()->count();
        $commentCount = Comment::query()->count();
        $articleSlugs = Article::query()->pluck('id', 'slug')->all();

        app(DemoContentSeeder::class)->run();

        $this->assertSame($articleCount, Article::query()->count());
        $this->assertSame($commentCount, Comment::query()->count());
        $this->assertSame($articleSlugs, Article::query()->pluck('id', 'slug')->all());
    }

    public function test_demo_content_seeder_skips_outside_allowed_environments(): void
    {
        $previous = App::environment();
        App::detectEnvironment(static fn (): string => 'production');

        try {
            app(DemoContentSeeder::class)->run();
            $this->assertSame(0, Article::query()->where('slug', 'piano-asfaltature-autunno')->count());
        } finally {
            App::detectEnvironment(static fn () => $previous);
        }
    }
}
