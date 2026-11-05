<?php

declare(strict_types=1);

namespace Modules\Notify\Models\Traits;

trait NotificationTemplateMutators
{
    public function getChannelsLabelAttribute(): string
    {
        return $this->getChannelsLabel();
    }

    public function getChannelsLabel(): string
    {
        /** @var array<int, string> $channels */
        $channels = $this->channels;

        return collect($channels)
            ->map(fn (string $channel): string => (string) __('notify::template.fields.channel.options.'.$channel.'.label'))
            ->implode(', ');
    }
}
