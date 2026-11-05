<?php

declare(strict_types=1);

namespace Modules\Notify\Models\Traits;

use Illuminate\Database\Eloquent\Builder;
use Modules\Media\Models\Media;
use Modules\Notify\Actions\RenderNotificationTemplateStringAction;
use Modules\Notify\Enums\NotificationTypeEnum;
use Modules\Xot\Contracts\ProfileContract;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;
use Spatie\Translatable\HasTranslations;

trait NotificationTemplateMutators
{
    function getChannelsLabelAttribute(): string
        {
            return $this->getChannelsLabel();
        }

    function getChannelsLabel(): string
        {
            /** @var array<int, string> $channels */
            $channels = $this->channels;
    
            return collect($channels)
                ->map(fn (string $channel): string => (string) __('notify::template.fields.channel.options.'.$channel.'.label'))
                ->implode(', ');
        }

}
