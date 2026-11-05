<?php

declare(strict_types=1);

namespace Modules\Job\Models\Traits;

<<<<<<< HEAD
<<<<<<< .merge_file_ixhvuE
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Job\Database\Factories\TaskCommentFactory;
use Modules\User\Models\User;
use Modules\Xot\Contracts\ProfileContract;
use Modules\Job\Models\Task;

trait TaskCommentRelations
{
    function task(): BelongsTo
        {
            return $this->belongsTo(Task::class);
        }

    function user(): BelongsTo
        {
            return $this->belongsTo(User::class);
        }

=======
=======
>>>>>>> laraxot/dev
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Job\Models\Task;
use Modules\User\Models\User;

trait TaskCommentRelations
{
    /**
     * @phpstan-return BelongsTo<Task, $this>
     */
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    /**
     * @phpstan-return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
<<<<<<< HEAD
>>>>>>> .merge_file_29hnXB
=======
>>>>>>> laraxot/dev
}
