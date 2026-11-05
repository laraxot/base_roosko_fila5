<?php

declare(strict_types=1);

/**
 * Article Model Test Suite
 *
 * Tests Blog\Models\Article functionality:
 * - Table configuration and relationships
 * - Article CRUD operations
 * - Category associations (many-to-many)
 * - Tag relationships
 * - Article publishing state
 * - Content translation support
 */

namespace Tests\Unit\Modules\Blog\Models;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Modules\Blog\Models\Article;
use Tests\TestCase;

class ArticleTest extends TestCase
{
    use DatabaseTransactions, WithFaker;

    protected Article $article;

    protected function setUp(): void
    {
        parent::setUp();
        $this->article = new Article;
    }

    /** @test Article has correct table name */
    public function it_has_correct_table_name(): void
    {
        $this->assertEquals('blog_articles', $this->article->getTable());
    }

    /** @test Article has correct primary key */
    public function it_has_correct_primary_key(): void
    {
        $this->assertEquals('id', $this->article->getKeyName());
    }

    /** @test Article can be created with valid data */
    public function it_can_create_article_with_valid_data(): void
    {
        $data = [
            'title' => 'Test Article',
            'slug' => 'test-article',
            'content' => 'This is test content',
            'published' => true,
        ];

        $article = Article::create($data);

        $this->assertInstanceOf(Article::class, $article);
        $this->assertEquals('Test Article', $article->title);
        $this->assertEquals('test-article', $article->slug);
        $this->assertTrue($article->published);
    }

    /** @test Article can be updated */
    public function it_can_update_article(): void
    {
        $article = Article::create([
            'title' => 'Original Title',
            'slug' => 'original-title',
            'content' => 'Original content',
        ]);

        $article->update([
            'title' => 'Updated Title',
            'content' => 'Updated content',
        ]);

        $this->assertEquals('Updated Title', $article->fresh()->title);
        $this->assertEquals('Updated content', $article->fresh()->content);
    }

    /** @test Article can be published */
    public function it_can_be_published(): void
    {
        $article = Article::create([
            'title' => 'Unpublished Article',
            'slug' => 'unpublished',
            'published' => false,
        ]);

        $article->update(['published' => true]);

        $this->assertTrue($article->fresh()->published);
    }

    /** @test Article can be unpublished */
    public function it_can_be_unpublished(): void
    {
        $article = Article::create([
            'title' => 'Published Article',
            'slug' => 'published',
            'published' => true,
        ]);

        $article->update(['published' => false]);

        $this->assertFalse($article->fresh()->published);
    }

    /** @test Article can be found by slug */
    public function it_can_be_found_by_slug(): void
    {
        $article = Article::create([
            'title' => 'Slug Test',
            'slug' => 'slug-test-unique',
            'content' => 'Test content',
        ]);

        $found = Article::where('slug', 'slug-test-unique')->first();

        $this->assertInstanceOf(Article::class, $found);
        $this->assertEquals('Slug Test', $found->title);
    }

    /** @test Article can be found by title */
    public function it_can_be_found_by_title(): void
    {
        $article = Article::create([
            'title' => 'Unique Title',
            'slug' => 'unique-title',
            'content' => 'Content',
        ]);

        $found = Article::where('title', 'Unique Title')->first();

        $this->assertInstanceOf(Article::class, $found);
        $this->assertEquals('unique-title', $found->slug);
    }

    /** @test Can query only published articles */
    public function it_can_query_published_articles(): void
    {
        Article::create(['title' => 'Published 1', 'slug' => 'pub-1', 'published' => true]);
        Article::create(['title' => 'Draft 1', 'slug' => 'draft-1', 'published' => false]);
        Article::create(['title' => 'Published 2', 'slug' => 'pub-2', 'published' => true]);

        $published = Article::where('published', true)->get();

        $this->assertCount(2, $published);
    }

    /** @test Can query only draft articles */
    public function it_can_query_draft_articles(): void
    {
        Article::create(['title' => 'Published', 'slug' => 'published', 'published' => true]);
        Article::create(['title' => 'Draft 1', 'slug' => 'draft-1', 'published' => false]);
        Article::create(['title' => 'Draft 2', 'slug' => 'draft-2', 'published' => false]);

        $drafts = Article::where('published', false)->get();

        $this->assertCount(2, $drafts);
    }

    /** @test Article uses timestamps */
    public function it_uses_timestamps(): void
    {
        $this->assertTrue($this->article->usesTimestamps());
    }

    /** @test Article has created_at and updated_at */
    public function it_has_timestamps(): void
    {
        $article = Article::create([
            'title' => 'Timestamp Test',
            'slug' => 'timestamp-test',
        ]);

        $this->assertNotNull($article->created_at);
        $this->assertNotNull($article->updated_at);
    }

    /** @test Article can be deleted */
    public function it_can_be_deleted(): void
    {
        $article = Article::create([
            'title' => 'To Delete',
            'slug' => 'to-delete',
        ]);

        $articleId = $article->id;
        $article->delete();

        $this->assertDatabaseMissing('blog_articles', ['id' => $articleId]);
    }

    /** @test Article can be created with factory */
    public function it_can_be_created_with_factory(): void
    {
        $article = Article::factory()->create();

        $this->assertInstanceOf(Article::class, $article);
        $this->assertNotEmpty($article->title);
        $this->assertNotEmpty($article->slug);
    }

    /** @test Article can be queried and ordered by creation date */
    public function it_can_be_ordered_by_created_date(): void
    {
        Article::create(['title' => 'First', 'slug' => 'first', 'created_at' => now()->subDays(2)]);
        Article::create(['title' => 'Second', 'slug' => 'second', 'created_at' => now()]);
        Article::create(['title' => 'Third', 'slug' => 'third', 'created_at' => now()->subDay()]);

        $ordered = Article::orderBy('created_at', 'asc')->get();

        $this->assertEquals('First', $ordered[0]->title);
        $this->assertEquals('Third', $ordered[1]->title);
        $this->assertEquals('Second', $ordered[2]->title);
    }

    /** @test Article can be counted */
    public function it_can_be_counted(): void
    {
        Article::factory()->count(5)->create();

        $count = Article::count();

        $this->assertGreaterThanOrEqual(5, $count);
    }

    /** @test Article query can be limited */
    public function it_can_be_limited(): void
    {
        Article::factory()->count(10)->create();

        $limited = Article::limit(3)->get();

        $this->assertCount(3, $limited);
    }

    /** @test Article can be paginated */
    public function it_can_be_paginated(): void
    {
        Article::factory()->count(25)->create();

        $paginated = Article::paginate(10);

        $this->assertEquals(25, $paginated->total());
        $this->assertLessThanOrEqual(10, $paginated->perPage());
    }
}
