<?php

declare(strict_types=1);

namespace Tests\Support\Traits;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Modules\User\Models\Permission;
use Modules\User\Models\Role;
use Modules\User\Models\User;

trait ModuleTestTrait
{
    use DatabaseTransactions, WithFaker;

    protected function setUpModuleTest(): void
    {
        $this->withoutExceptionHandling();
        $this->withoutMiddleware();
    }

    protected function createAuthenticatedUser(array $attributes = []): User
    {
        $user = User::factory()->create($attributes);
        $this->actingAs($user);

        return $user;
    }

    protected function assertDatabaseHasRecord(string $table, array $data): void
    {
        $this->assertDatabaseHas($table, $data);
    }

    protected function assertDatabaseMissingRecord(string $table, array $data): void
    {
        $this->assertDatabaseMissing($table, $data);
    }

    protected function createUserWithRole(string $roleName, array $attributes = []): User
    {
        $user = User::factory()->create($attributes);

        $role = Role::create([
            'name' => $roleName,
            'guard_name' => 'web',
        ]);

        $user->assignRole($role);

        return $user;
    }

    protected function createUserWithPermissions(array $permissions, array $attributes = []): User
    {
        $user = User::factory()->create($attributes);

        foreach ($permissions as $permissionName) {
            $permission = Permission::create([
                'name' => $permissionName,
                'guard_name' => 'web',
            ]);

            $user->givePermissionTo($permission);
        }

        return $user;
    }
}
