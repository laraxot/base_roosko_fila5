<?php

declare(strict_types=1);

namespace Modules\Weighing\Models\Policies;

use Modules\User\Models\Policies\UserBasePolicy;
use Modules\Xot\Contracts\UserContract;

/**
 * `view` copre anche la lettura della bilancia ("Leggi bilancia").
 */
class ScalePolicy extends UserBasePolicy
{
    public function viewAny(UserContract $user): bool
    {
        return $user->hasPermissionTo('scale.viewAny');
    }

    public function view(UserContract $user): bool
    {
        return $user->hasPermissionTo('scale.view');
    }

    public function create(UserContract $user): bool
    {
        return $user->hasPermissionTo('scale.create');
    }

    public function update(UserContract $user): bool
    {
        return $user->hasPermissionTo('scale.update');
    }

    public function delete(UserContract $user): bool
    {
        return $user->hasPermissionTo('scale.delete');
    }
}
