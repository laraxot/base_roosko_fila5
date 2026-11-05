<?php

declare(strict_types=1);

namespace Modules\Notify\Models\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Media\Models\Media;
use Modules\Xot\Contracts\ProfileContract;
use Modules\Xot\Traits\Updater;
use RuntimeException;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;
use Modules\Notify\Models\NotificationTemplate;

trait NotificationTemplateVersionRelations
{
<<<<<<< HEAD
    function template(): BelongsTo
        {
            return $this->belongsTo(NotificationTemplate::class, 'template_id');
        }
=======
    /**
     * @phpstan-return BelongsTo<NotificationTemplate, $this>
     */
    function template(): BelongsTo
    {
        return $this->belongsTo(NotificationTemplate::class, 'template_id');
    }
>>>>>>> laraxot/dev

}
