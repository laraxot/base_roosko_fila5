<?php

declare(strict_types=1);

namespace Modules\Job\Models\Traits;

use Illuminate\Database\Eloquent\Builder;
<<<<<<< HEAD
<<<<<<< .merge_file_zRAdNj
use Illuminate\Database\Eloquent\Collection;
=======
>>>>>>> .merge_file_jvblPc
=======
>>>>>>> laraxot/dev
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Modules\Job\Database\Factories\FrequencyFactory;
use Modules\Xot\Contracts\ProfileContract;
use Modules\Job\Models\Parameter;
use Modules\Job\Models\Task;

trait FrequencyRelations
{
<<<<<<< HEAD
<<<<<<< .merge_file_zRAdNj
=======
    /**
     * @phpstan-return BelongsTo<Task, $this>
     */
>>>>>>> .merge_file_jvblPc
=======
    /**
     * @phpstan-return BelongsTo<Task, $this>
     */
>>>>>>> laraxot/dev
    function task(): BelongsTo
        {
            return $this->belongsTo(Task::class);
        }

<<<<<<< HEAD
<<<<<<< .merge_file_zRAdNj
=======
    /**
     * @phpstan-return HasMany<Parameter, $this>
     */
>>>>>>> .merge_file_jvblPc
=======
    /**
     * @phpstan-return HasMany<Parameter, $this>
     */
>>>>>>> laraxot/dev
    function parameters(): HasMany
        {
            return $this->hasMany(Parameter::class);
        }
<<<<<<< HEAD
<<<<<<< .merge_file_zRAdNj

=======
>>>>>>> .merge_file_jvblPc
=======
>>>>>>> laraxot/dev
}
