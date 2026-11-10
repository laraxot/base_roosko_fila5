<?php

declare(strict_types=1);

namespace Modules\Weighing\Models\Policies;

use Modules\User\Models\Policies\UserBasePolicy;
use Modules\Xot\Contracts\UserContract;
use Modules\Xot\Models\Policies\Concerns\DeniesUndeclaredFilamentAbilities;

/**
 * `readScale` copre "Leggi bilancia" (apre una connessione TCP verso l'indicatore): permesso dedicato,
 * distinto da `view`. Le abilita' Filament non dichiarate sono negate ({@see DeniesUndeclaredFilamentAbilities}).
 */
class ScalePolicy extends UserBasePolicy
{
    use DeniesUndeclaredFilamentAbilities;

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

    public function readScale(UserContract $user): bool
    {
        return $user->hasPermissionTo('scale.readScale');
    }
}
