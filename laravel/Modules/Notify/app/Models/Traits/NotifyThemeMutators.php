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

trait NotifyThemeMutators
{
    /**
     * @param array<string, int> $value
<<<<<<< HEAD
     * @return array<string, int>
=======
     * @return array<string, int|string>
>>>>>>> laraxot/dev
     */
    function getLogoAttribute(?array $value): array
    {
        return $this->getLogo();
    }

<<<<<<< HEAD
    function getLogo(): array
        {
            return [
                // 'path' => asset(strval($this->logo_src)),
                'path' => url($this->getFirstMediaUrl()),
                'width' => $this->logo_width ?? 50,
                'height' => $this->logo_height ?? 50];
        }
=======
    /**
     * @return array<string, int|string>
     */
    function getLogo(): array
    {
        return [
            // 'path' => asset(strval($this->logo_src)),
            'path' => url($this->getFirstMediaUrl()),
            'width' => (int) ($this->logo_width ?? 50),
            'height' => (int) ($this->logo_height ?? 50)];
    }
>>>>>>> laraxot/dev

}
