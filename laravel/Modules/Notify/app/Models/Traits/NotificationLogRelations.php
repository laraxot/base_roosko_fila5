<?php

declare(strict_types=1);

namespace Modules\Notify\Models\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;
use Modules\Media\Models\Media;
use Modules\Notify\Enums\NotificationLogStatusEnum;
use Modules\Xot\Contracts\ProfileContract;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;
use Modules\Notify\Models\NotificationTemplate;

trait NotificationLogRelations
{
<<<<<<< HEAD
    function notifiable(): MorphTo
        {
            return $this->morphTo();
        }

    function template(): BelongsTo
        {
            return $this->belongsTo(NotificationTemplate::class, 'template_id');
        }
=======
    /**
     * @phpstan-return MorphTo<\Illuminate\Database\Eloquent\Model, $this>
     */
    function notifiable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * @phpstan-return BelongsTo<NotificationTemplate, $this>
     */
    function template(): BelongsTo
    {
        return $this->belongsTo(NotificationTemplate::class, 'template_id');
    }
>>>>>>> laraxot/dev

}
