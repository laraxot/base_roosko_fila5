<?php

declare(strict_types=1);

namespace Modules\Weighing\Models\Policies;

use Modules\User\Models\Policies\UserBasePolicy;
use Modules\Xot\Contracts\UserContract;
use Modules\Xot\Models\Policies\Concerns\DeniesUndeclaredFilamentAbilities;

/**
 * `create` = aprire una pesata o registrare una pesata singola, `update` = chiuderla (seconda pesata),
 * `void` = annullarla (permesso distinto: cambia il valore legale del ticket). I ticket non si cancellano.
 * Le abilita' Filament non dichiarate sono negate ({@see DeniesUndeclaredFilamentAbilities}).
 */
class WeighingPolicy extends UserBasePolicy
{
    use DeniesUndeclaredFilamentAbilities;

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

    public function void(UserContract $user): bool
    {
        return $user->hasPermissionTo('weighing.void');
    }

    public function delete(UserContract $user): bool
    {
        return false;
    }
}
