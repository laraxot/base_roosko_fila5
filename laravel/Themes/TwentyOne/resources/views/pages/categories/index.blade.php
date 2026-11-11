<?php

use Modules\Blog\Models\Category;
use Illuminate\Support\Arr;
use Illuminate\View\View;
use function Laravel\Folio\{withTrashed,middleware, name,render};

withTrashed();
name('categories.index');
//middleware(['auth', 'verified']);

render(function (View $view) {
    $categories = Category::tree()->get()->toTree();
    return $view->with('categories', $categories);
});


?>
<x-layouts.app>
    <div class="container mx-auto max-w-6xl px-6 pt-8">
        <h1 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white">{{ __('pub_theme::categories.title') }}</h1>
        <p class="mt-2 text-gray-600 dark:text-gray-400">{{ __('pub_theme::categories.subtitle') }}</p>
    </div>
    @foreach($categories as $category)
        <div class="container max-w-6xl p-6 mx-auto space-y-4">
            {{-- <p class="flex items-center gap-3 mb-4 text-sm text-gray-400">
                <span>All</span>
                @foreach($category->ancestors()->breadthFirst()->get() as $ancestor)
                    <span>
                        <x-heroicon-o-chevron-right width="15px"/>
                    </span>
                    <a class="text-blue-500" href="{{ url('categories/'.$ancestor->slug) }}">{{ $ancestor->title }}</a>
                @endforeach
            </p> --}}
            {{-- <h2 class="flex items-center space-x-2 text-2xl font-semibold">
                {{ $category->title }}
            </h2> --}}


            <h2><a class="inline-flex min-h-11 items-center text-2xl font-semibold text-gray-900 hover:text-blue-700 hover:underline dark:text-white"
                href="{{ route('category.view', ['lang'=>$lang,'slug' => $category->slug ]) }}"
                >
                {{ $category->title }}
            </a></h2>


            <ul class="flex flex-wrap gap-2">
                @foreach($category->descendants()->get() as $descendant)
                <li>
                    <a href="{{ route('category.view', ['lang'=>$lang,'slug' => $descendant->slug ]) }}"
                        class="inline-flex min-h-11 items-center rounded-full border border-gray-300 px-4 text-sm font-medium text-gray-800 transition-colors duration-150 hover:border-blue-600 hover:text-blue-700 dark:border-gray-600 dark:text-gray-200">
                        <span>{{ $descendant->title }}</span>
                    </a>
                </li>
                @endforeach
            </ul>
        </div>
    @endforeach
</x-layouts.app>