<?php

declare(strict_types=1);

namespace Modules\Job\Models\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Modules\Job\Database\Factories\TaskFactory;
use Modules\Job\Models\Traits\FrontendSortable;
use Modules\Xot\Actions\Cast\SafeStringCastAction;
use Modules\Xot\Contracts\ProfileContract;
use Modules\Xot\Models\Traits\HasXotFactory;
use Webmozart\Assert\Assert;
use function Safe\json_decode;
use Modules\Job\Models\Frequency;
use Modules\Job\Models\Result;

trait TaskRelations
{
    /**
<<<<<<< .merge_file_1tBgJ9
     * @phpstan-return HasMany<Frequency, static>
=======
     * @phpstan-return HasMany<Frequency, $this>
>>>>>>> .merge_file_0auQBI
     */
    function frequencies(): HasMany
    {
        return $this->hasMany(Frequency::class, 'task_id', 'id')->with('parameters');
    }

    /**
<<<<<<< .merge_file_1tBgJ9
     * @phpstan-return HasMany<Result, static>
=======
     * @phpstan-return HasMany<Result, $this>
>>>>>>> .merge_file_0auQBI
     */
    function results(): HasMany
    {
        return $this->hasMany(Result::class, 'task_id', 'id');
    }

}
