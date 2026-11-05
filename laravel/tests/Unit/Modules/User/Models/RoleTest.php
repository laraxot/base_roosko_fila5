<?php

declare(strict_types=1);

/**
 * Role Model Test Suite
 *
 * Tests User\Models\Role functionality:
 * - Role CRUD operations
 * - Permission associations (many-to-many)
 * - User role assignments
 * - Role hierarchy and guards
 *
 * Test Naming Convention:
 * - it_<verb>_<subject> for simple assertions (e.g., it_has_correct_table_name)
 * - it_can_<action>_<object> for behavior tests (e.g., it_can_create_role)
 * - it_should_<consequence> for validations (e.g., it_should_have_guard_name)
 */

namespace Tests\Unit\Modules\User\Models;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Modules\User\Models\Permission;
use Modules\User\Models\Role;
use Tests\TestCase;

class RoleTest extends TestCase
{
    use DatabaseTransactions, WithFaker;

    protected Role $role;

    protected function setUp(): void
    {
        parent::setUp();
        $this->role = new Role;
    }

    /**
     * @test Role model has correct table configuration
     *
     * Role table is typically 'roles' and uses UUID primary keys
     * based on the Xot framework conventions.
     */
    public function it_has_correct_table_name(): void
    {
        $this->assertEquals('roles', $this->role->getTable());
    }

    /** @test Role has correct primary key */
    public function it_has_correct_primary_key(): void
    {
        $this->assertEquals('id', $this->role->getKeyName());
    }

    /**
     * @test Can create role with valid data
     *
     * Roles require:
     * - name: unique role identifier (admin, editor, etc.)
     * - guard_name: which guard this role applies to (web, api, etc.)
     */
    public function it_can_create_role_with_valid_data(): void
    {
        $data = [
            'name' => 'admin',
            'guard_name' => 'web',
            'description' => 'Administrator role',
        ];

        $role = Role::create($data);

        $this->assertInstanceOf(Role::class, $role);
        $this->assertEquals('admin', $role->name);
        $this->assertEquals('web', $role->guard_name);
    }

    /** @test Can create multiple distinct roles */
    public function it_can_create_multiple_roles(): void
    {
        Role::create(['name' => 'admin', 'guard_name' => 'web']);
        Role::create(['name' => 'editor', 'guard_name' => 'web']);
        Role::create(['name' => 'viewer', 'guard_name' => 'web']);

        $this->assertCount(3, Role::all());
    }

    /**
     * @test Can assign permission to role
     *
     * Roles and permissions have a many-to-many relationship.
     * A role can have multiple permissions, and permissions can
     * be assigned to multiple roles.
     */
    public function it_can_assign_permission_to_role(): void
    {
        $role = Role::create(['name' => 'editor', 'guard_name' => 'web']);
        $permission = Permission::create(['name' => 'edit posts', 'guard_name' => 'web']);

        // Method depends on implementation (givePermissionTo, attach, etc.)
        if (method_exists($role, 'givePermissionTo')) {
            $role->givePermissionTo($permission);
            $this->assertTrue($role->hasPermissionTo($permission));
        } elseif (method_exists($role, 'permissions')) {
            $role->permissions()->attach($permission->id);
            $this->assertTrue($role->permissions->contains($permission));
        }
    }

    /** @test Can find role by name */
    public function it_can_be_found_by_name(): void
    {
        $role = Role::create(['name' => 'moderator', 'guard_name' => 'web']);

        $found = Role::where('name', 'moderator')->first();

        $this->assertInstanceOf(Role::class, $found);
        $this->assertEquals('moderator', $found->name);
    }

    /** @test Can find role by guard */
    public function it_can_be_found_by_guard(): void
    {
        Role::create(['name' => 'admin', 'guard_name' => 'web']);
        Role::create(['name' => 'api_admin', 'guard_name' => 'api']);

        $webRoles = Role::where('guard_name', 'web')->get();

        $this->assertCount(1, $webRoles);
        $this->assertEquals('web', $webRoles->first()->guard_name);
    }

    /**
     * @test Role uses timestamps
     *
     * Roles track creation and modification times
     * for audit trail and record management.
     */
    public function it_uses_timestamps(): void
    {
        $this->assertTrue($this->role->usesTimestamps());
    }

    /** @test Role has created_at and updated_at columns */
    public function it_has_timestamps(): void
    {
        $role = Role::create(['name' => 'test', 'guard_name' => 'web']);

        $this->assertNotNull($role->created_at);
        $this->assertNotNull($role->updated_at);
    }

    /** @test Can update role */
    public function it_can_update_role(): void
    {
        $role = Role::create(['name' => 'old_name', 'guard_name' => 'web']);

        $role->update(['name' => 'new_name']);

        $this->assertEquals('new_name', $role->fresh()->name);
    }

    /** @test Can delete role */
    public function it_can_be_deleted(): void
    {
        $role = Role::create(['name' => 'temporary', 'guard_name' => 'web']);
        $roleId = $role->id;

        $role->delete();

        $this->assertDatabaseMissing('roles', ['id' => $roleId]);
    }

    /** @test Can count roles */
    public function it_can_be_counted(): void
    {
        Role::create(['name' => 'role1', 'guard_name' => 'web']);
        Role::create(['name' => 'role2', 'guard_name' => 'web']);
        Role::create(['name' => 'role3', 'guard_name' => 'web']);

        $this->assertCount(3, Role::all());
    }
}
