<?php

declare(strict_types=1);

namespace Modules\Job\Models\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Carbon;
use Modules\Job\Database\Factories\JobFactory;
use Modules\Xot\Contracts\ProfileContract;
use Override;
use Webmozart\Assert\Assert;
use function Safe\json_decode;

trait JobMutators
{
    function getDisplayNameAttribute(): ?string
        {
            return $this->getDisplayName();
        }

    function getDisplayName(): ?string
        {
            Assert::string($json = $this->attributes['payload'], __FILE__.':'.__LINE__.' - '.class_basename(self::class));
            $payload = json_decode($json, true);
            if (! is_array($payload)) {
                return null;
            }
    
            Assert::nullOrString($res = $payload['displayName'] ?? null);
    
            return $res;
        }

}
