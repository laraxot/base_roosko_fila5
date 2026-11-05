<?php

declare(strict_types=1);

namespace Modules\User\Models\Policies;

use Modules\User\Models\Role as Post;
use Modules\Xot\Contracts\UserContract;
<<<<<<< HEAD

class RolePolicy extends UserBasePolicy
{
=======
use Modules\Xot\Models\Policies\Concerns\DeniesUndeclaredFilamentAbilities;

/**
 * Ruoli: gestione riservata al super-admin (passa da XotBasePolicy::before()).
 *
 * Concedere create/update/delete o il collegamento dei ruoli a chi amministra gli utenti
 * permette di rinominare o assegnarsi il ruolo super-admin (escalation di privilegi).
 */
class RolePolicy extends UserBasePolicy
{
    use DeniesUndeclaredFilamentAbilities;

>>>>>>> laraxot/dev
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(UserContract $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(UserContract $_user, Post $_post): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(UserContract $_user): bool
    {
<<<<<<< HEAD
        return true;
=======
        return false;
>>>>>>> laraxot/dev
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(UserContract $_user, Post $_post): bool
    {
<<<<<<< HEAD
        return true;
=======
        return false;
>>>>>>> laraxot/dev
    }

    /**
     * Determine whether the user can add team members.
     */
    public function addTeamMember(UserContract $user, Post $post): bool
    {
<<<<<<< HEAD
        return true;
=======
        return false;
>>>>>>> laraxot/dev
    }

    /**
     * Determine whether the user can update team member permissions.
     */
    public function updateTeamMember(UserContract $user, Post $post): bool
    {
<<<<<<< HEAD
        return true;
=======
        return false;
>>>>>>> laraxot/dev
    }

    /**
     * Determine whether the user can remove team members.
     */
    public function removeTeamMember(UserContract $user, Post $post): bool
    {
<<<<<<< HEAD
        return true;
=======
        return false;
>>>>>>> laraxot/dev
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(UserContract $_user, Post $_post): bool
    {
<<<<<<< HEAD
        return true;
=======
        return false;
>>>>>>> laraxot/dev
    }
}
