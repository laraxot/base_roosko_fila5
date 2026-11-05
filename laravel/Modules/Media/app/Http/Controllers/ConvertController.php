<?php

declare(strict_types=1);

namespace Modules\Media\Http\Controllers;

use Illuminate\Contracts\View\View;

class ConvertController extends BaseController
{
    /**
     * Show the conversion page for the given media.
     */
    public function __invoke(string|int $mediaId): View
    {
        /** @var view-string $view */
        $view = 'media::convert';

        return view($view, []);
    }
}
