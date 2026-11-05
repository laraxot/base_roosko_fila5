<?php

declare(strict_types=1);

namespace Modules\Blog\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Cms\Models\Menu;
use Modules\Cms\Models\Page;
use Webmozart\Assert\Assert;

class SiteSeeder extends Seeder
{
    public function run(): void
    {
        $factory = Page::factory();
        if (is_object($factory) && method_exists($factory, 'create')) {
            $aboutPage = $factory->create([
                'slug' => 'about',
                'title' => 'About Us',
            ]);
            Assert::isInstanceOf($aboutPage, Page::class);
        }

        $factory2 = Page::factory();
        if (is_object($factory2) && method_exists($factory2, 'create')) {
            $termsPage = $factory2->create([
                'slug' => 'terms',
                'title' => 'Terms & Conditions',
            ]);
            Assert::isInstanceOf($termsPage, Page::class);
        }

        $menu = Menu::create([
            'name' => 'main',
            'items' => [
                [
                    'title' => 'Blog',
                    'url' => '/blog',
                    'type' => 'internal',
                ],
                [
                    'title' => 'About',
                    'url' => '/about',
                    'type' => 'internal',
                ],
                [
                    'title' => 'Contact',
                    'url' => '/contact',
                    'type' => 'internal',
                ],
            ],
        ]);
        Assert::isInstanceOf($menu, Menu::class);

        $footerMenu = Menu::create([
            'name' => 'footer',
            'items' => [
                [
                    'title' => 'Terms & Conditions',
                    'url' => '/terms',
                    'type' => 'internal',
                ],
            ],
        ]);
        Assert::isInstanceOf($footerMenu, Menu::class);
    }
}
