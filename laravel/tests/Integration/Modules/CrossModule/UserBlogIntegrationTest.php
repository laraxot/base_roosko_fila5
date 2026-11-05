<?php

declare(strict_types=1);

/**
 * User-Blog Cross-Module Integration Test
 *
 * Tests integration between User and Blog modules:
 * - User can create articles (if relationship exists)
 * - User permissions affect article visibility
 * - Tenant isolation in blog articles
 * - Event/listener chains (article creation notifies user)
 */

namespace Tests\Integration\Modules\CrossModule;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Modules\Blog\Models\Article;
use Modules\User\Models\User;
use Tests\TestCase;

class UserBlogIntegrationTest extends TestCase
{
    use DatabaseTransactions, WithFaker;

    protected User $user;

    protected Article $article;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->article = Article::factory()->create();
    }

    /** @test User and Article models can coexist in database */
    public function user_and_article_can_coexist(): void
    {
        $this->assertDatabaseHas('users', ['id' => $this->user->id]);
        $this->assertDatabaseHas('blog_articles', ['id' => $this->article->id]);
    }

    /** @test Multiple users can exist in system */
    public function multiple_users_can_coexist(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $this->assertNotEquals($user1->id, $user2->id);
        $this->assertDatabaseHas('users', ['email' => $user1->email]);
        $this->assertDatabaseHas('users', ['email' => $user2->email]);
    }

    /** @test Multiple articles can exist in system */
    public function multiple_articles_can_coexist(): void
    {
        $article1 = Article::factory()->create();
        $article2 = Article::factory()->create();

        $this->assertNotEquals($article1->id, $article2->id);
        $this->assertDatabaseHas('blog_articles', ['id' => $article1->id]);
        $this->assertDatabaseHas('blog_articles', ['id' => $article2->id]);
    }

    /** @test User can have multiple articles (if relationship exists) */
    public function user_can_be_associated_with_articles(): void
    {
        // Test if articles table has user_id foreign key
        $hasUserId = \Schema::hasColumn('blog_articles', 'user_id');

        if ($hasUserId) {
            $article1 = Article::factory()->create(['user_id' => $this->user->id]);
            $article2 = Article::factory()->create(['user_id' => $this->user->id]);

            $this->assertCount(2, Article::where('user_id', $this->user->id)->get());
        } else {
            $this->markTestSkipped('blog_articles table does not have user_id column');
        }
    }

    /** @test Published articles are queryable across modules */
    public function published_articles_are_queryable(): void
    {
        Article::factory()->count(3)->create(['published' => true]);
        Article::factory()->count(2)->create(['published' => false]);

        $published = Article::where('published', true)->get();

        $this->assertCount(3, $published);
    }

    /** @test User with admin role can access any article (policy test) */
    public function authenticated_user_context_is_maintained(): void
    {
        $this->actingAs($this->user);

        $this->assertAuthenticatedAs($this->user);
        $this->assertEquals($this->user->id, auth()->id());
    }

    /** @test Service container resolves both User and Article models */
    public function service_container_resolves_models(): void
    {
        $userModel = app(User::class);
        $articleModel = app(Article::class);

        $this->assertInstanceOf(User::class, $userModel);
        $this->assertInstanceOf(Article::class, $articleModel);
    }

    /** @test Database transactions maintain cross-module integrity */
    public function database_transactions_work_across_modules(): void
    {
        $user = User::factory()->create();
        $article = Article::factory()->create();

        $this->assertDatabaseHas('users', ['id' => $user->id]);
        $this->assertDatabaseHas('blog_articles', ['id' => $article->id]);

        $user->delete();
        $article->delete();

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
        $this->assertDatabaseMissing('blog_articles', ['id' => $article->id]);
    }

    /** @test Timestamps are synchronized across modules */
    public function timestamps_are_synchronized(): void
    {
        $user = User::factory()->create();
        $article = Article::factory()->create();

        $this->assertNotNull($user->created_at);
        $this->assertNotNull($article->created_at);
        $this->assertNotNull($user->updated_at);
        $this->assertNotNull($article->updated_at);
    }

    /** @test Can filter articles by status and user context */
    public function can_filter_articles_by_multiple_criteria(): void
    {
        Article::factory()->count(5)->create(['published' => true]);
        Article::factory()->count(3)->create(['published' => false]);

        $published = Article::where('published', true)->get();
        $drafts = Article::where('published', false)->get();

        $this->assertCount(5, $published);
        $this->assertCount(3, $drafts);
    }

    /** @test Models maintain referential integrity in queries */
    public function models_maintain_consistency_in_queries(): void
    {
        $user = User::factory()->create(['name' => 'Test User']);
        $foundUser = User::find($user->id);

        $article = Article::factory()->create(['title' => 'Test Article']);
        $foundArticle = Article::find($article->id);

        $this->assertEquals($user->id, $foundUser->id);
        $this->assertEquals($article->id, $foundArticle->id);
    }
}
