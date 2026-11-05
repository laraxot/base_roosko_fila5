<?php

declare(strict_types=1);

namespace Modules\Job\Models\Traits;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\Xot\Contracts\ProfileContract;
use Override;
use Modules\Job\Models\Task;

trait ResultRelations
{
<<<<<<< .merge_file_dNWMCf
=======
    /**
     * @phpstan-return BelongsTo<Task, $this>
     */
>>>>>>> .merge_file_zBTNSh
    function task(): BelongsTo
        {
            return $this->belongsTo(Task::class);
        }

}
