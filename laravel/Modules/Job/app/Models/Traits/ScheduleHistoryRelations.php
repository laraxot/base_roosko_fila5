<?php

declare(strict_types=1);

namespace Modules\Job\Models\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Modules\Job\Database\Factories\ScheduleHistoryFactory;
use Modules\Xot\Contracts\ProfileContract;
use Override;
use Modules\Job\Models\Schedule;

trait ScheduleHistoryRelations
{
<<<<<<< .merge_file_PEapJN
=======
    /**
     * @phpstan-return BelongsTo<Schedule, $this>
     */
>>>>>>> .merge_file_zBMS6L
    function command(): BelongsTo
        {
            return $this->belongsTo(Schedule::class, 'schedule_id', 'id');
        }

}
