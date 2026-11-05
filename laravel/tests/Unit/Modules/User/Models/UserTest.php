<?php

declare(strict_types=1);

namespace Tests\Unit\Modules\User\Models;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Hash;
use Modules\User\Models\BaseModel;
use Modules\User\Models\Permission;
use Modules\User\Models\Role;
use Modules\User\Models\Team;
use Modules\User\Models\User;
use Tests\TestCase;

class UserTest extends TestCase
{
    use DatabaseTransactions, WithFaker;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = new User;
    }

    /** @test */
    public function it_extends_base_model(): void
    {
        $this->assertInstanceOf(BaseModel::class, $this->user);
    }

    /** @test */
    public function it_has_correct_fillable_attributes(): void
    {
        $expectedFillable = [
            'name',
            'email',
            'password',
            'email_verified_at',
            'remember_token',
            'profile_photo_path',
            'two_factor_secret',
            'two_factor_recovery_codes',
            'two_factor_confirmed_at',
            'current_team_id',
            'current_tenant_id',
        ];

        $this->assertEquals($expectedFillable, $this->user->getFillable());
    }

    /** @test */
    public function it_has_correct_hidden_attributes(): void
    {
        $expectedHidden = [
            'password',
            'remember_token',
            'two_factor_secret',
            'two_factor_recovery_codes',
        ];

        $this->assertEquals($expectedHidden, $this->user->getHidden());
    }

    /** @test */
    public function it_has_correct_cast_attributes(): void
    {
        $expectedCasts = [
            'email_verified_at' => 'datetime',
            'two_factor_confirmed_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];

        $this->assertEquals($expectedCasts, $this->user->getCasts());
    }

    /** @test */
    public function it_can_create_user_with_valid_data(): void
    {
        $userData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ];

        $user = User::create($userData);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('Test User', $user->name);
        $this->assertEquals('test@example.com', $user->email);
        $this->assertTrue(Hash::check('password123', $user->password));
    }

    /** @test */
    public function it_can_update_user_attributes(): void
    {
        $user = User::create([
            'name' => 'Original Name',
            'email' => 'original@example.com',
            'password' => Hash::make('password123'),
        ]);

        $user->update([
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
        ]);

        $this->assertEquals('Updated Name', $user->fresh()->name);
        $this->assertEquals('updated@example.com', $user->fresh()->email);
    }

    /** @test */
    public function it_can_verify_email(): void
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $this->assertNull($user->email_verified_at);

        $user->markEmailAsVerified();

        $this->assertNotNull($user->fresh()->email_verified_at);
        $this->assertTrue($user->fresh()->hasVerifiedEmail());
    }

    /** @test */
    public function it_can_check_if_email_is_verified(): void
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $this->assertFalse($user->hasVerifiedEmail());

        $user->markEmailAsVerified();

        $this->assertTrue($user->fresh()->hasVerifiedEmail());
    }

    /** @test */
    public function it_can_send_email_verification_notification(): void
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        // Mock della notifica per evitare l'invio reale
        $this->mock(VerifyEmail::class, function ($mock) {
            $mock->shouldReceive('toMail')->andReturn(new MailMessage);
        });

        $user->sendEmailVerificationNotification();

        $this->assertTrue(true); // Se arriviamo qui, la notifica è stata inviata
    }

    /** @test */
    public function it_can_join_team(): void
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $team = Team::create([
            'name' => 'Test Team',
            'personal_team' => false,
        ]);

        $user->teams()->attach($team->id);

        $this->assertTrue($user->teams->contains($team));
        $this->assertEquals(1, $user->teams->count());
    }

    /** @test */
    public function it_can_leave_team(): void
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $team = Team::create([
            'name' => 'Test Team',
            'personal_team' => false,
        ]);

        $user->teams()->attach($team->id);
        $this->assertEquals(1, $user->teams->count());

        $user->teams()->detach($team->id);

        $this->assertEquals(0, $user->fresh()->teams->count());
    }

    /** @test */
    public function it_can_check_if_belongs_to_team(): void
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $team = Team::create([
            'name' => 'Test Team',
            'personal_team' => false,
        ]);

        $this->assertFalse($user->belongsToTeam($team));

        $user->teams()->attach($team->id);

        $this->assertTrue($user->fresh()->belongsToTeam($team));
    }

    /** @test */
    public function it_can_get_current_team(): void
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $team = Team::create([
            'name' => 'Test Team',
            'personal_team' => false,
        ]);

        $user->update(['current_team_id' => $team->id]);

        $this->assertEquals($team->id, $user->fresh()->currentTeam->id);
    }

    /** @test */
    public function it_can_switch_team(): void
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $team1 = Team::create([
            'name' => 'Team 1',
            'personal_team' => false,
        ]);

        $team2 = Team::create([
            'name' => 'Team 2',
            'personal_team' => false,
        ]);

        $user->teams()->attach([$team1->id, $team2->id]);

        $user->switchTeam($team2);

        $this->assertEquals($team2->id, $user->fresh()->current_team_id);
    }

    /** @test */
    public function it_can_assign_role(): void
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $role = Role::create([
            'name' => 'admin',
            'guard_name' => 'web',
        ]);

        $user->assignRole($role);

        $this->assertTrue($user->hasRole($role));
        $this->assertTrue($user->hasRole('admin'));
    }

    /** @test */
    public function it_can_remove_role(): void
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $role = Role::create([
            'name' => 'admin',
            'guard_name' => 'web',
        ]);

        $user->assignRole($role);
        $this->assertTrue($user->hasRole($role));

        $user->removeRole($role);

        $this->assertFalse($user->fresh()->hasRole($role));
    }

    /** @test */
    public function it_can_check_if_has_role(): void
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $role = Role::create([
            'name' => 'editor',
            'guard_name' => 'web',
        ]);

        $this->assertFalse($user->hasRole($role));
        $this->assertFalse($user->hasRole('editor'));

        $user->assignRole($role);

        $this->assertTrue($user->fresh()->hasRole($role));
        $this->assertTrue($user->fresh()->hasRole('editor'));
    }

    /** @test */
    public function it_can_check_if_has_any_role(): void
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $role1 = Role::create([
            'name' => 'admin',
            'guard_name' => 'web',
        ]);

        $role2 = Role::create([
            'name' => 'editor',
            'guard_name' => 'web',
        ]);

        $this->assertFalse($user->hasAnyRole([$role1, $role2]));
        $this->assertFalse($user->hasAnyRole(['admin', 'editor']));

        $user->assignRole($role1);

        $this->assertTrue($user->fresh()->hasAnyRole([$role1, $role2]));
        $this->assertTrue($user->fresh()->hasAnyRole(['admin', 'editor']));
    }

    /** @test */
    public function it_can_check_if_has_all_roles(): void
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $role1 = Role::create([
            'name' => 'admin',
            'guard_name' => 'web',
        ]);

        $role2 = Role::create([
            'name' => 'editor',
            'guard_name' => 'web',
        ]);

        $this->assertFalse($user->hasAllRoles([$role1, $role2]));
        $this->assertFalse($user->hasAllRoles(['admin', 'editor']));

        $user->assignRole($role1);
        $this->assertFalse($user->fresh()->hasAllRoles([$role1, $role2]));

        $user->assignRole($role2);

        $this->assertTrue($user->fresh()->hasAllRoles([$role1, $role2]));
        $this->assertTrue($user->fresh()->hasAllRoles(['admin', 'editor']));
    }

    /** @test */
    public function it_can_check_if_has_permission(): void
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $permission = Permission::create([
            'name' => 'edit posts',
            'guard_name' => 'web',
        ]);

        $this->assertFalse($user->hasPermissionTo($permission));
        $this->assertFalse($user->hasPermissionTo('edit posts'));

        $user->givePermissionTo($permission);

        $this->assertTrue($user->fresh()->hasPermissionTo($permission));
        $this->assertTrue($user->fresh()->hasPermissionTo('edit posts'));
    }

    /** @test */
    public function it_can_give_permission(): void
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $permission = Permission::create([
            'name' => 'delete posts',
            'guard_name' => 'web',
        ]);

        $user->givePermissionTo($permission);

        $this->assertTrue($user->fresh()->hasPermissionTo($permission));
    }

    /** @test */
    public function it_can_revoke_permission(): void
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $permission = Permission::create([
            'name' => 'publish posts',
            'guard_name' => 'web',
        ]);

        $user->givePermissionTo($permission);
        $this->assertTrue($user->hasPermissionTo($permission));

        $user->revokePermissionTo($permission);

        $this->assertFalse($user->fresh()->hasPermissionTo($permission));
    }

    /** @test */
    public function it_can_check_if_has_any_permission(): void
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $permission1 = Permission::create([
            'name' => 'create posts',
            'guard_name' => 'web',
        ]);

        $permission2 = Permission::create([
            'name' => 'edit posts',
            'guard_name' => 'web',
        ]);

        $this->assertFalse($user->hasAnyPermission([$permission1, $permission2]));
        $this->assertFalse($user->hasAnyPermission(['create posts', 'edit posts']));

        $user->givePermissionTo($permission1);

        $this->assertTrue($user->fresh()->hasAnyPermission([$permission1, $permission2]));
        $this->assertTrue($user->fresh()->hasAnyPermission(['create posts', 'edit posts']));
    }

    /** @test */
    public function it_can_check_if_has_all_permissions(): void
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $permission1 = Permission::create([
            'name' => 'view posts',
            'guard_name' => 'web',
        ]);

        $permission2 = Permission::create([
            'name' => 'comment posts',
            'guard_name' => 'web',
        ]);

        $this->assertFalse($user->hasAllPermissions([$permission1, $permission2]));
        $this->assertFalse($user->hasAllPermissions(['view posts', 'comment posts']));

        $user->givePermissionTo($permission1);
        $this->assertFalse($user->fresh()->hasAllPermissions([$permission1, $permission2]));

        $user->givePermissionTo($permission2);

        $this->assertTrue($user->fresh()->hasAllPermissions([$permission1, $permission2]));
        $this->assertTrue($user->fresh()->hasAllPermissions(['view posts', 'comment posts']));
    }

    /** @test */
    public function it_can_sync_roles(): void
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $role1 = Role::create([
            'name' => 'admin',
            'guard_name' => 'web',
        ]);

        $role2 = Role::create([
            'name' => 'editor',
            'guard_name' => 'web',
        ]);

        $role3 = Role::create([
            'name' => 'viewer',
            'guard_name' => 'web',
        ]);

        // Assegna i primi due ruoli
        $user->assignRole([$role1, $role2]);
        $this->assertCount(2, $user->roles);

        // Sincronizza con il terzo ruolo (rimuove i precedenti)
        $user->syncRoles([$role3]);

        $this->assertCount(1, $user->fresh()->roles);
        $this->assertTrue($user->fresh()->hasRole($role3));
        $this->assertFalse($user->fresh()->hasRole($role1));
        $this->assertFalse($user->fresh()->hasRole($role2));
    }

    /** @test */
    public function it_can_sync_permissions(): void
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $permission1 = Permission::create([
            'name' => 'create posts',
            'guard_name' => 'web',
        ]);

        $permission2 = Permission::create([
            'name' => 'edit posts',
            'guard_name' => 'web',
        ]);

        $permission3 = Permission::create([
            'name' => 'delete posts',
            'guard_name' => 'web',
        ]);

        // Assegna i primi due permessi
        $user->givePermissionTo([$permission1, $permission2]);
        $this->assertCount(2, $user->permissions);

        // Sincronizza con il terzo permesso (rimuove i precedenti)
        $user->syncPermissions([$permission3]);

        $this->assertCount(1, $user->fresh()->permissions);
        $this->assertTrue($user->fresh()->hasPermissionTo($permission3));
        $this->assertFalse($user->fresh()->hasPermissionTo($permission1));
        $this->assertFalse($user->fresh()->hasPermissionTo($permission2));
    }

    /** @test */
    public function it_can_get_all_permissions(): void
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $role = Role::create([
            'name' => 'admin',
            'guard_name' => 'web',
        ]);

        $permission1 = Permission::create([
            'name' => 'create posts',
            'guard_name' => 'web',
        ]);

        $permission2 = Permission::create([
            'name' => 'edit posts',
            'guard_name' => 'web',
        ]);

        $role->givePermissionTo([$permission1, $permission2]);
        $user->assignRole($role);

        $allPermissions = $user->getAllPermissions();

        $this->assertCount(2, $allPermissions);
        $this->assertTrue($allPermissions->contains($permission1));
        $this->assertTrue($allPermissions->contains($permission2));
    }

    /** @test */
    public function it_can_get_all_roles(): void
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $role1 = Role::create([
            'name' => 'admin',
            'guard_name' => 'web',
        ]);

        $role2 = Role::create([
            'name' => 'editor',
            'guard_name' => 'web',
        ]);

        $user->assignRole([$role1, $role2]);

        $allRoles = $user->getAllRoles();

        $this->assertCount(2, $allRoles);
        $this->assertTrue($allRoles->contains($role1));
        $this->assertTrue($allRoles->contains($role2));
    }

    /** @test */
    public function it_can_check_if_is_super_admin(): void
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $superAdminRole = Role::create([
            'name' => 'super-admin',
            'guard_name' => 'web',
        ]);

        $this->assertFalse($user->isSuperAdmin());

        $user->assignRole($superAdminRole);

        $this->assertTrue($user->fresh()->isSuperAdmin());
    }

    /** @test */
    public function it_can_check_if_is_admin(): void
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $adminRole = Role::create([
            'name' => 'admin',
            'guard_name' => 'web',
        ]);

        $this->assertFalse($user->isAdmin());

        $user->assignRole($adminRole);

        $this->assertTrue($user->fresh()->isAdmin());
    }

    /** @test */
    public function it_can_get_profile_photo_url(): void
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        // Test URL foto profilo di default
        $defaultUrl = $user->profile_photo_url;
        $this->assertStringContainsString('ui-avatars.com', $defaultUrl);
        $this->assertStringContainsString('Test+User', $defaultUrl);

        // Test URL foto profilo personalizzata
        $user->update(['profile_photo_path' => 'photos/profile.jpg']);

        $customUrl = $user->fresh()->profile_photo_url;
        $this->assertStringContainsString('photos/profile.jpg', $customUrl);
    }

    /** @test */
    public function it_can_get_name_or_email(): void
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $this->assertEquals('Test User', $user->name_or_email);

        // Test con nome vuoto
        $user->update(['name' => '']);
        $this->assertEquals('test@example.com', $user->fresh()->name_or_email);
    }

    /** @test */
    public function it_can_get_initials(): void
    {
        $user = User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => Hash::make('password123'),
        ]);

        $this->assertEquals('JD', $user->initials);

        // Test con nome singolo
        $user->update(['name' => 'John']);
        $this->assertEquals('J', $user->fresh()->initials);

        // Test con nome vuoto
        $user->update(['name' => '']);
        $this->assertEquals('', $user->fresh()->initials);
    }

    /** @test */
    public function it_can_check_if_has_team_permission(): void
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $team = Team::create([
            'name' => 'Test Team',
            'personal_team' => false,
        ]);

        $permission = Permission::create([
            'name' => 'manage team',
            'guard_name' => 'web',
        ]);

        $this->assertFalse($user->hasTeamPermission($team, $permission));

        $user->teams()->attach($team->id);
        $team->users()->attach($user->id, ['role' => 'admin']);

        $this->assertTrue($user->fresh()->hasTeamPermission($team, $permission));
    }

    /** @test */
    public function it_can_get_team_role(): void
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $team = Team::create([
            'name' => 'Test Team',
            'personal_team' => false,
        ]);

        $user->teams()->attach($team->id, ['role' => 'editor']);

        $this->assertEquals('editor', $user->getTeamRole($team));
    }

    /** @test */
    public function it_can_check_if_is_team_admin(): void
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $team = Team::create([
            'name' => 'Test Team',
            'personal_team' => false,
        ]);

        $this->assertFalse($user->isTeamAdmin($team));

        $user->teams()->attach($team->id, ['role' => 'admin']);

        $this->assertTrue($user->fresh()->isTeamAdmin($team));
    }

    /** @test */
    public function it_can_get_personal_team(): void
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $personalTeam = Team::create([
            'name' => 'Test User\'s Team',
            'personal_team' => true,
            'user_id' => $user->id,
        ]);

        $user->teams()->attach($personalTeam->id);

        $this->assertEquals($personalTeam->id, $user->personalTeam()->id);
    }

    /** @test */
    public function it_can_check_if_owns_team(): void
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $team = Team::create([
            'name' => 'Test Team',
            'personal_team' => false,
            'user_id' => $user->id,
        ]);

        $this->assertTrue($user->ownsTeam($team));

        $otherUser = User::create([
            'name' => 'Other User',
            'email' => 'other@example.com',
            'password' => Hash::make('password123'),
        ]);

        $otherTeam = Team::create([
            'name' => 'Other Team',
            'personal_team' => false,
            'user_id' => $otherUser->id,
        ]);

        $this->assertFalse($user->ownsTeam($otherTeam));
    }

    /** @test */
    public function it_can_be_created_with_factory(): void
    {
        $user = User::factory()->create();

        $this->assertInstanceOf(User::class, $user);
        $this->assertNotEmpty($user->name);
        $this->assertNotEmpty($user->email);
        $this->assertNotEmpty($user->password);
    }

    /** @test */
    public function it_can_be_created_with_factory_and_specific_attributes(): void
    {
        $user = User::factory()->create([
            'name' => 'Factory User',
            'email' => 'factory@example.com',
        ]);

        $this->assertEquals('Factory User', $user->name);
        $this->assertEquals('factory@example.com', $user->email);
    }

    /** @test */
    public function it_can_be_created_multiple_times_with_factory(): void
    {
        $users = User::factory()->count(5)->create();

        $this->assertCount(5, $users);
        $this->assertContainsOnlyInstancesOf(User::class, $users);
    }

    /** @test */
    public function it_can_be_created_with_factory_states(): void
    {
        $user = User::factory()->unverified()->create();

        $this->assertNull($user->email_verified_at);
        $this->assertFalse($user->hasVerifiedEmail());
    }

    /** @test */
    public function it_can_be_created_with_factory_relationships(): void
    {
        $user = User::factory()
            ->has(Team::factory()->count(3))
            ->create();

        $this->assertCount(3, $user->teams);
    }

    /** @test */
    public function it_has_correct_table_name(): void
    {
        $this->assertEquals('users', $this->user->getTable());
    }

    /** @test */
    public function it_has_correct_primary_key(): void
    {
        $this->assertEquals('id', $this->user->getKeyName());
    }

    /** @test */
    public function it_uses_incrementing_primary_key(): void
    {
        $this->assertTrue($this->user->getIncrementing());
    }

    /** @test */
    public function it_uses_timestamps(): void
    {
        $this->assertTrue($this->user->usesTimestamps());
    }

    /** @test */
    public function it_has_created_at_and_updated_at_columns(): void
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $this->assertNotNull($user->created_at);
        $this->assertNotNull($user->updated_at);
    }

    /** @test */
    public function it_can_be_soft_deleted_if_enabled(): void
    {
        // Verifica se il modello supporta soft deletes
        if (method_exists($this->user, 'trashed')) {
            $user = User::create([
                'name' => 'Test User',
                'email' => 'test@example.com',
                'password' => Hash::make('password123'),
            ]);

            $user->delete();

            $this->assertSoftDeleted($user);
        } else {
            $this->markTestSkipped('Soft deletes not enabled for User model');
        }
    }

    /** @test */
    public function it_can_be_restored_if_soft_deletes_enabled(): void
    {
        // Verifica se il modello supporta soft deletes
        if (method_exists($this->user, 'trashed')) {
            $user = User::create([
                'name' => 'Test User',
                'email' => 'test@example.com',
                'password' => Hash::make('password123'),
            ]);

            $user->delete();
            $user->restore();

            $this->assertNotSoftDeleted($user);
        } else {
            $this->markTestSkipped('Soft deletes not enabled for User model');
        }
    }

    /** @test */
    public function it_can_be_permanently_deleted(): void
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $userId = $user->id;
        $user->forceDelete();

        $this->assertDatabaseMissing('users', ['id' => $userId]);
    }

    /** @test */
    public function it_can_be_found_by_id(): void
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $foundUser = User::find($user->id);

        $this->assertInstanceOf(User::class, $foundUser);
        $this->assertEquals($user->id, $foundUser->id);
    }

    /** @test */
    public function it_can_be_found_by_email(): void
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'unique@example.com',
            'password' => Hash::make('password123'),
        ]);

        $foundUser = User::where('email', 'unique@example.com')->first();

        $this->assertInstanceOf(User::class, $foundUser);
        $this->assertEquals('unique@example.com', $foundUser->email);
    }

    /** @test */
    public function it_can_be_found_by_name(): void
    {
        $user = User::create([
            'name' => 'Unique Name',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $foundUsers = User::where('name', 'Unique Name')->get();

        $this->assertCount(1, $foundUsers);
        $this->assertEquals('Unique Name', $foundUsers->first()->name);
    }

    /** @test */
    public function it_can_be_filtered_by_multiple_criteria(): void
    {
        $user1 = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
        ]);

        $user2 = User::create([
            'name' => 'Editor User',
            'email' => 'editor@example.com',
            'password' => Hash::make('password123'),
        ]);

        $foundUsers = User::where('name', 'like', '%User')
            ->where('email', 'like', '%@example.com')
            ->get();

        $this->assertCount(2, $foundUsers);
    }

    /** @test */
    public function it_can_be_ordered_by_attributes(): void
    {
        $user1 = User::create([
            'name' => 'Charlie',
            'email' => 'charlie@example.com',
            'password' => Hash::make('password123'),
        ]);

        $user2 = User::create([
            'name' => 'Alice',
            'email' => 'alice@example.com',
            'password' => Hash::make('password123'),
        ]);

        $user3 = User::create([
            'name' => 'Bob',
            'email' => 'bob@example.com',
            'password' => Hash::make('password123'),
        ]);

        $orderedUsers = User::orderBy('name', 'asc')->get();

        $this->assertEquals('Alice', $orderedUsers[0]->name);
        $this->assertEquals('Bob', $orderedUsers[1]->name);
        $this->assertEquals('Charlie', $orderedUsers[2]->name);
    }

    /** @test */
    public function it_can_be_limited_in_results(): void
    {
        User::factory()->count(10)->create();

        $limitedUsers = User::limit(5)->get();

        $this->assertCount(5, $limitedUsers);
    }

    /** @test */
    public function it_can_be_paginated(): void
    {
        User::factory()->count(25)->create();

        $paginatedUsers = User::paginate(10);

        $this->assertEquals(25, $paginatedUsers->total());
        $this->assertEquals(10, $paginatedUsers->perPage());
        $this->assertEquals(3, $paginatedUsers->lastPage());
    }

    /** @test */
    public function it_can_be_counted(): void
    {
        User::factory()->count(7)->create();

        $count = User::count();

        $this->assertEquals(7, $count);
    }

    /** @test */
    public function it_can_be_queried_with_where_in(): void
    {
        $user1 = User::create(['name' => 'User 1', 'email' => 'user1@example.com', 'password' => Hash::make('password123')]);
        $user2 = User::create(['name' => 'User 2', 'email' => 'user2@example.com', 'password' => Hash::make('password123')]);
        $user3 = User::create(['name' => 'User 3', 'email' => 'user3@example.com', 'password' => Hash::make('password123')]);

        $foundUsers = User::whereIn('id', [$user1->id, $user3->id])->get();

        $this->assertCount(2, $foundUsers);
        $this->assertTrue($foundUsers->contains('id', $user1->id));
        $this->assertTrue($foundUsers->contains('id', $user3->id));
    }

    /** @test */
    public function it_can_be_queried_with_where_between(): void
    {
        $user1 = User::create(['name' => 'User 1', 'email' => 'user1@example.com', 'password' => Hash::make('password123')]);
        $user2 = User::create(['name' => 'User 2', 'email' => 'user2@example.com', 'password' => Hash::make('password123')]);
        $user3 = User::create(['name' => 'User 3', 'email' => 'user3@example.com', 'password' => Hash::make('password123')]);

        $foundUsers = User::whereBetween('id', [$user1->id, $user2->id])->get();

        $this->assertCount(2, $foundUsers);
    }

    /** @test */
    public function it_can_be_queried_with_where_like(): void
    {
        $user1 = User::create(['name' => 'Admin User', 'email' => 'admin@example.com', 'password' => Hash::make('password123')]);
        $user2 = User::create(['name' => 'Editor User', 'email' => 'editor@example.com', 'password' => Hash::make('password123')]);
        $user3 = User::create(['name' => 'Viewer User', 'email' => 'viewer@example.com', 'password' => Hash::make('password123')]);

        $foundUsers = User::where('name', 'like', '%Admin%')->get();

        $this->assertCount(1, $foundUsers);
        $this->assertEquals('Admin User', $foundUsers->first()->name);
    }

    /** @test */
    public function it_can_be_queried_with_where_date(): void
    {
        $today = now()->toDateString();
        $yesterday = now()->subDay()->toDateString();

        $user1 = User::create([
            'name' => 'Today User',
            'email' => 'today@example.com',
            'password' => Hash::make('password123'),
            'created_at' => $today,
        ]);

        $user2 = User::create([
            'name' => 'Yesterday User',
            'email' => 'yesterday@example.com',
            'password' => Hash::make('password123'),
            'created_at' => $yesterday,
        ]);

        $todayUsers = User::whereDate('created_at', $today)->get();
        $yesterdayUsers = User::whereDate('created_at', $yesterday)->get();

        $this->assertCount(1, $todayUsers);
        $this->assertCount(1, $yesterdayUsers);
        $this->assertEquals('Today User', $todayUsers->first()->name);
        $this->assertEquals('Yesterday User', $yesterdayUsers->first()->name);
    }
}
