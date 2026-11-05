<?php

declare(strict_types=1);

namespace Modules\Blog\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Modules\Blog\Models\Article;
use Modules\Blog\Models\Category;
use Modules\Xot\Actions\Cast\SafeStringCastAction;
use Webmozart\Assert\Assert;

class ArticleSeeder extends Seeder
{
    /** @var list<array{name: string, image: string}> */
    private array $categories = [
        ['name' => 'Animals', 'image' => 'https://picsum.photos/id/219/800/600'],
        ['name' => 'Mountains', 'image' => 'https://picsum.photos/id/353/800/600'],
        ['name' => 'People', 'image' => 'https://picsum.photos/id/342/800/600'],
        ['name' => 'Things', 'image' => 'https://picsum.photos/id/252/800/600'],
    ];

    private Carbon $date;

    public function run(): void
    {
        $this->date = Carbon::now();

        foreach ($this->categories as $category) {
            Assert::isArray($category);
            Category::create([
                'title' => $category['name'],
                'slug' => Str::slug(SafeStringCastAction::cast($category['name'])),
            ]);
        }

        // Featured posts
        for ($i = 0; $i < 2; $i++) {
            $this->createArticle(['is_featured' => 1]);
        }

        // Published posts
        for ($i = 0; $i < 26; $i++) {
            $this->createArticle();
        }

        // Draft posts
        for ($i = 0; $i < 2; $i++) {
            $this->createArticle(['published_at' => null]);
        }
    }

    /**
     * @param  array<string, mixed>  $data
     * @return Collection<int, Article>
     */
    private function createArticle(array $data = []): Collection
    {
        $date = $this->date->subDay();

        /* @phpstan-ignore-next-line argument.type */
        $category_key = array_rand($this->categories);
        $category = $this->categories[$category_key] ?? [];
        $image = is_array($category) && isset($category['image']) ? $category['image'] : '';

        $defaults = [
            'created_at' => $date,
            'updated_at' => $date,
            'published_at' => $date,
            // 'category_id' => $category_key + 1,
            'main_image_url' => $image,
        ];

        $data = array_merge($defaults, $data);

        $factory = Article::factory();
        if (is_object($factory) && method_exists($factory, 'create')) {
            $result = $factory->create($data);
            if ($result instanceof Collection) {
                return $result;
            }
        }

        return new Collection;
    }
}
