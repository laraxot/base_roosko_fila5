<?php

declare(strict_types=1);

namespace Modules\Weighing\Models\Policies;

use Modules\User\Models\Policies\UserBasePolicy;
use Modules\Xot\Contracts\UserContract;

/**
 * `create` = aprire/registrare una pesata, `update` = chiuderla o annullarla. I ticket non si cancellano.
 */
class WeighingPolicy extends UserBasePolicy
{
    public function viewAny(UserContract $user): bool
    {
        return $user->hasPermissionTo('weighing.viewAny');
    }

    public function view(UserContract $user): bool
    {
        return $user->hasPermissionTo('weighing.view');
    }

    public function create(UserContract $user): bool
    {
        return $user->hasPermissionTo('weighing.create');
    }

    public function update(UserContract $user): bool
    {
        return $user->hasPermissionTo('weighing.update');
    }

    public function delete(UserContract $user): bool
    {
        return false;
    }
}
