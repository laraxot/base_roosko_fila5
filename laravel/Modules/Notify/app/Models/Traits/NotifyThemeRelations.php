<?php

declare(strict_types=1);

namespace Modules\Notify\Models\Traits;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;
use Modules\Media\Models\Media;
use Modules\Notify\Database\Factories\NotifyThemeFactory;
use Modules\Xot\Contracts\ProfileContract;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;

trait NotifyThemeRelations
{
    /**
<<<<<<< HEAD
<<<<<<< HEAD
     * @return MorphTo<Media, static>
=======
     * @phpstan-return MorphTo<\Illuminate\Database\Eloquent\Model, $this>
>>>>>>> laraxot/dev
=======
     * @phpstan-return MorphTo<\Illuminate\Database\Eloquent\Model, $this>
>>>>>>> laraxot/dev
     */
    function linkable(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'post_type', 'post_id');
    }

}
