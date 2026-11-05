<?php

declare(strict_types=1);

namespace Tests\Integration\Modules\CrossModule;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Modules\Chart\Models\Chart;
use Modules\User\Models\Permission;
use Modules\User\Models\Role;
use Modules\User\Models\Team;
use Modules\User\Models\User;
use Tests\Support\Traits\ModuleTestTrait;
use Tests\TestCase;

class ChartUserIntegrationTest extends TestCase
{
    use DatabaseTransactions, ModuleTestTrait, WithFaker;

    protected User $user;

    protected User $adminUser;

    protected Team $team;

    protected Role $editorRole;

    protected Permission $editChartsPermission;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpModuleTest();

        // Crea team e ruoli
        $this->team = Team::create(['name' => 'Test Team']);
        $this->editorRole = Role::create(['name' => 'editor', 'guard_name' => 'web']);
        $this->editChartsPermission = Permission::create(['name' => 'edit-charts', 'guard_name' => 'web']);

        // Assegna permesso al ruolo
        $this->editorRole->givePermissionTo($this->editChartsPermission);

        // Crea utenti
        $this->user = $this->createAuthenticatedUser();
        $this->adminUser = $this->createUserWithRole('admin');

        // Aggiungi utenti al team
        $this->user->teams()->attach($this->team->id);
        $this->adminUser->teams()->attach($this->team->id);

        // Assegna ruolo editor all'utente
        $this->user->assignRole($this->editorRole);
    }

    /** @test */
    public function it_can_create_chart_with_user_and_team(): void
    {
        $chartData = [
            'post_id' => 1,
            'post_type' => 'post',
            'type' => 'bar',
            'width' => 800,
            'height' => 600,
            'user_id' => $this->user->id,
            'team_id' => $this->team->id,
        ];

        $response = $this->actingAs($this->user)
            ->postJson('/api/charts', $chartData);

        $response->assertStatus(201);

        $this->assertDatabaseHas('charts', [
            'post_id' => 1,
            'post_type' => 'post',
            'type' => 'bar',
            'user_id' => $this->user->id,
        ]);

        // Verifica relazioni
        $chart = Chart::where('post_id', 1)->first();
        $this->assertEquals($this->user->id, $chart->user_id);
        $this->assertEquals($this->team->id, $chart->team_id);
    }

    /** @test */
    public function it_can_list_charts_by_user(): void
    {
        // Crea chart per l'utente corrente
        Chart::factory()->create([
            'user_id' => $this->user->id,
            'team_id' => $this->team->id,
        ]);

        // Crea chart per altro utente
        Chart::factory()->create([
            'user_id' => $this->adminUser->id,
            'team_id' => $this->team->id,
        ]);

        $response = $this->actingAs($this->user)
            ->getJson('/api/users/'.$this->user->id.'/charts');

        $response->assertStatus(200);
        $data = $response->json('data');

        $this->assertCount(1, $data);
        $this->assertEquals($this->user->id, $data[0]['user_id']);
    }

    /** @test */
    public function it_can_list_charts_by_team(): void
    {
        // Crea chart per il team corrente
        Chart::factory()->create([
            'user_id' => $this->user->id,
            'team_id' => $this->team->id,
        ]);

        // Crea altro team e chart
        $otherTeam = Team::create(['name' => 'Other Team']);
        Chart::factory()->create([
            'user_id' => $this->user->id,
            'team_id' => $otherTeam->id,
        ]);

        $response = $this->actingAs($this->user)
            ->getJson('/api/teams/'.$this->team->id.'/charts');

        $response->assertStatus(200);
        $data = $response->json('data');

        $this->assertCount(1, $data);
        $this->assertEquals($this->team->id, $data[0]['team_id']);
    }

    /** @test */
    public function it_can_update_chart_with_user_permission(): void
    {
        $chart = Chart::factory()->create([
            'user_id' => $this->user->id,
            'team_id' => $this->team->id,
        ]);

        $updateData = [
            'type' => 'line',
            'width' => 1000,
            'height' => 800,
        ];

        $response = $this->actingAs($this->user)
            ->putJson("/api/charts/{$chart->id}", $updateData);

        $response->assertStatus(200);

        $this->assertDatabaseHas('charts', [
            'id' => $chart->id,
            'type' => 'line',
            'width' => 1000,
            'height' => 800,
        ]);
    }

    /** @test */
    public function it_cannot_update_chart_without_permission(): void
    {
        $chart = Chart::factory()->create([
            'user_id' => $this->adminUser->id,
            'team_id' => $this->team->id,
        ]);

        $updateData = [
            'type' => 'line',
            'width' => 1000,
            'height' => 800,
        ];

        $response = $this->actingAs($this->user)
            ->putJson("/api/charts/{$chart->id}", $updateData);

        $response->assertStatus(403);
    }

    /** @test */
    public function it_can_delete_chart_with_user_permission(): void
    {
        $chart = Chart::factory()->create([
            'user_id' => $this->user->id,
            'team_id' => $this->team->id,
        ]);

        $response = $this->actingAs($this->user)
            ->deleteJson("/api/charts/{$chart->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('charts', [
            'id' => $chart->id,
        ]);
    }

    /** @test */
    public function it_cannot_delete_chart_without_permission(): void
    {
        $chart = Chart::factory()->create([
            'user_id' => $this->adminUser->id,
            'team_id' => $this->team->id,
        ]);

        $response = $this->actingAs($this->user)
            ->deleteJson("/api/charts/{$chart->id}");

        $response->assertStatus(403);
    }

    /** @test */
    public function it_can_share_chart_with_team_members(): void
    {
        $chart = Chart::factory()->create([
            'user_id' => $this->user->id,
            'team_id' => $this->team->id,
        ]);

        $response = $this->actingAs($this->user)
            ->postJson("/api/charts/{$chart->id}/share", [
                'team_id' => $this->team->id,
                'permission' => 'view',
            ]);

        $response->assertStatus(200);

        // Verifica che i membri del team possano vedere il chart
        $response = $this->actingAs($this->adminUser)
            ->getJson("/api/charts/{$chart->id}");

        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_get_chart_collaborators(): void
    {
        $chart = Chart::factory()->create([
            'user_id' => $this->user->id,
            'team_id' => $this->team->id,
        ]);

        // Aggiungi collaboratori
        $collaborator = User::factory()->create();
        $collaborator->teams()->attach($this->team->id);

        // Simula collaborazione (qui dovresti avere una tabella pivot per i collaboratori)
        // $chart->collaborators()->attach($collaborator->id);

        $response = $this->actingAs($this->user)
            ->getJson("/api/charts/{$chart->id}/collaborators");

        $response->assertStatus(200);
        // Verifica struttura risposta
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'email',
                    'role',
                ],
            ],
        ]);
    }

    /** @test */
    public function it_can_get_user_chart_statistics(): void
    {
        // Crea chart di diversi tipi per l'utente
        Chart::factory()->count(3)->create([
            'user_id' => $this->user->id,
            'team_id' => $this->team->id,
            'type' => 'bar',
        ]);

        Chart::factory()->count(2)->create([
            'user_id' => $this->user->id,
            'team_id' => $this->team->id,
            'type' => 'line',
        ]);

        Chart::factory()->count(1)->create([
            'user_id' => $this->user->id,
            'team_id' => $this->team->id,
            'type' => 'pie',
        ]);

        $response = $this->actingAs($this->user)
            ->getJson("/api/users/{$this->user->id}/chart-statistics");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'total_charts',
                    'charts_by_type',
                    'charts_by_team',
                    'average_dimensions',
                    'recent_activity',
                ],
            ]);

        $data = $response->json('data');
        $this->assertEquals(6, $data['total_charts']);
        $this->assertEquals(3, $data['charts_by_type']['bar']);
        $this->assertEquals(2, $data['charts_by_type']['line']);
        $this->assertEquals(1, $data['charts_by_type']['pie']);
    }

    /** @test */
    public function it_can_get_team_chart_statistics(): void
    {
        // Crea chart per diversi utenti nel team
        Chart::factory()->count(2)->create([
            'user_id' => $this->user->id,
            'team_id' => $this->team->id,
        ]);

        Chart::factory()->count(3)->create([
            'user_id' => $this->adminUser->id,
            'team_id' => $this->team->id,
        ]);

        $response = $this->actingAs($this->user)
            ->getJson("/api/teams/{$this->team->id}/chart-statistics");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'total_charts',
                    'charts_by_user',
                    'charts_by_type',
                    'collaboration_metrics',
                ],
            ]);

        $data = $response->json('data');
        $this->assertEquals(5, $data['total_charts']);
        $this->assertEquals(2, $data['charts_by_user'][$this->user->id]);
        $this->assertEquals(3, $data['charts_by_user'][$this->adminUser->id]);
    }

    /** @test */
    public function it_can_search_charts_across_team(): void
    {
        // Crea chart con contenuti diversi
        Chart::factory()->create([
            'user_id' => $this->user->id,
            'team_id' => $this->team->id,
            'type' => 'bar',
            'post_type' => 'post',
        ]);

        Chart::factory()->create([
            'user_id' => $this->adminUser->id,
            'team_id' => $this->team->id,
            'type' => 'line',
            'post_type' => 'page',
        ]);

        $response = $this->actingAs($this->user)
            ->getJson('/api/teams/'.$this->team->id.'/charts/search?q=bar&type=bar');

        $response->assertStatus(200);
        $data = $response->json('data');

        $this->assertCount(1, $data);
        $this->assertEquals('bar', $data[0]['type']);
    }

    /** @test */
    public function it_can_export_team_charts(): void
    {
        Chart::factory()->count(5)->create([
            'team_id' => $this->team->id,
        ]);

        $response = $this->actingAs($this->user)
            ->getJson('/api/teams/'.$this->team->id.'/charts/export?format=csv');

        $response->assertStatus(200)
            ->assertHeader('Content-Type', 'text/csv; charset=UTF-8')
            ->assertHeader('Content-Disposition', 'attachment; filename="team-charts.csv"');
    }

    /** @test */
    public function it_can_bulk_share_charts_with_team(): void
    {
        $charts = Chart::factory()->count(3)->create([
            'user_id' => $this->user->id,
            'team_id' => $this->team->id,
        ]);

        $chartIds = $charts->pluck('id')->toArray();

        $response = $this->actingAs($this->user)
            ->postJson('/api/charts/bulk-share', [
                'chart_ids' => $chartIds,
                'team_id' => $this->team->id,
                'permission' => 'edit',
            ]);

        $response->assertStatus(200);

        // Verifica che tutti i chart siano condivisi
        foreach ($chartIds as $id) {
            $this->assertDatabaseHas('chart_shares', [
                'chart_id' => $id,
                'team_id' => $this->team->id,
                'permission' => 'edit',
            ]);
        }
    }

    /** @test */
    public function it_can_get_chart_activity_log(): void
    {
        $chart = Chart::factory()->create([
            'user_id' => $this->user->id,
            'team_id' => $this->team->id,
        ]);

        // Simula attività sul chart (creazione, modifica, condivisione)
        // In un'implementazione reale, avresti una tabella activity_log

        $response = $this->actingAs($this->user)
            ->getJson("/api/charts/{$chart->id}/activity");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'action',
                        'user_id',
                        'timestamp',
                        'details',
                    ],
                ],
            ]);
    }

    /** @test */
    public function it_can_get_user_dashboard_with_charts(): void
    {
        // Crea chart per l'utente
        Chart::factory()->count(5)->create([
            'user_id' => $this->user->id,
            'team_id' => $this->team->id,
        ]);

        $response = $this->actingAs($this->user)
            ->getJson('/api/users/'.$this->user->id.'/dashboard');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'user_info',
                    'chart_summary',
                    'recent_charts',
                    'team_collaboration',
                    'activity_feed',
                ],
            ]);

        $data = $response->json('data');
        $this->assertEquals(5, $data['chart_summary']['total_charts']);
    }

    /** @test */
    public function it_can_get_team_dashboard_with_charts(): void
    {
        // Crea chart per diversi utenti nel team
        Chart::factory()->count(3)->create([
            'user_id' => $this->user->id,
            'team_id' => $this->team->id,
        ]);

        Chart::factory()->count(2)->create([
            'user_id' => $this->adminUser->id,
            'team_id' => $this->team->id,
        ]);

        $response = $this->actingAs($this->user)
            ->getJson('/api/teams/'.$this->team->id.'/dashboard');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'team_info',
                    'chart_overview',
                    'member_contributions',
                    'recent_activity',
                    'collaboration_metrics',
                ],
            ]);

        $data = $response->json('data');
        $this->assertEquals(5, $data['chart_overview']['total_charts']);
        $this->assertEquals(2, $data['member_contributions']['active_members']);
    }

    /** @test */
    public function it_can_handle_chart_permissions_correctly(): void
    {
        $chart = Chart::factory()->create([
            'user_id' => $this->user->id,
            'team_id' => $this->team->id,
        ]);

        // Testa diversi livelli di permesso
        $permissions = ['view', 'edit', 'delete', 'share'];

        foreach ($permissions as $permission) {
            $response = $this->actingAs($this->user)
                ->getJson("/api/charts/{$chart->id}/check-permission?permission={$permission}");

            $response->assertStatus(200);
            $data = $response->json('data');

            // L'utente dovrebbe avere tutti i permessi sui propri chart
            $this->assertTrue($data['has_permission']);
        }
    }

    /** @test */
    public function it_can_handle_team_chart_quota(): void
    {
        // Simula limite di chart per team
        $maxCharts = 10;

        // Crea chart fino al limite
        for ($i = 0; $i < $maxCharts; $i++) {
            Chart::factory()->create([
                'user_id' => $this->user->id,
                'team_id' => $this->team->id,
            ]);
        }

        // Prova a creare un chart in più
        $chartData = [
            'post_id' => 999,
            'post_type' => 'post',
            'type' => 'bar',
            'width' => 800,
            'height' => 600,
            'user_id' => $this->user->id,
            'team_id' => $this->team->id,
        ];

        $response = $this->actingAs($this->user)
            ->postJson('/api/charts', $chartData);

        $response->assertStatus(422)
            ->assertJson([
                'message' => 'Team chart quota exceeded',
            ]);
    }

    /** @test */
    public function it_can_handle_chart_versioning(): void
    {
        $chart = Chart::factory()->create([
            'user_id' => $this->user->id,
            'team_id' => $this->team->id,
        ]);

        // Crea prima versione
        $response = $this->actingAs($this->user)
            ->postJson("/api/charts/{$chart->id}/versions", [
                'version' => '1.0',
                'changes' => 'Initial version',
            ]);

        $response->assertStatus(201);

        // Aggiorna chart
        $updateData = [
            'type' => 'line',
            'width' => 1000,
            'height' => 800,
        ];

        $response = $this->actingAs($this->user)
            ->putJson("/api/charts/{$chart->id}", $updateData);

        $response->assertStatus(200);

        // Crea seconda versione
        $response = $this->actingAs($this->user)
            ->postJson("/api/charts/{$chart->id}/versions", [
                'version' => '1.1',
                'changes' => 'Updated to line chart with new dimensions',
            ]);

        $response->assertStatus(201);

        // Verifica versioni
        $response = $this->actingAs($this->user)
            ->getJson("/api/charts/{$chart->id}/versions");

        $response->assertStatus(200);
        $data = $response->json('data');

        $this->assertCount(2, $data);
        $this->assertEquals('1.0', $data[0]['version']);
        $this->assertEquals('1.1', $data[1]['version']);
    }
}
