<?php

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\MultipleRecordsFoundException;
use Illuminate\Support\Facades\Redirect;
use Modules\Blog\Models\Article;
use function Laravel\Folio\{withTrashed, render};

withTrashed();

render(function (string $Post_slug) {
    try {
        $article = Article::query()
            ->whereIn('type', ['event', 'article'])
            ->where('slug', $Post_slug)
            ->sole();
    } catch (ModelNotFoundException|MultipleRecordsFoundException) {
        $article = null;
    }

    if ($article === null) {
        return Redirect::route('articles.index');
    }

    return redirect()->route('article.view', ['slug' => $article->slug]);
});
