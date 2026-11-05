<?php

declare(strict_types=1);

/**
 * Module Model Test Suite
 *
 * Tests the core Xot\Models\Module functionality:
 * - Table configuration and relationships
 * - Module state management (enabled/disabled)
 * - Module configuration retrieval
 * - Database persistence and querying
 */

namespace Tests\Unit\Modules\Xot\Models;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Modules\Xot\Models\Module;
use Tests\TestCase;

class ModuleTest extends TestCase
{
    use DatabaseTransactions, WithFaker;

    protected Module $module;

    protected function setUp(): void
    {
        parent::setUp();
        $this->module = new Module;
    }

    /** @test Module has correct table name */
    public function it_has_correct_table_name(): void
    {
        $this->assertEquals('modules', $this->module->getTable());
    }

    /** @test Module has correct primary key */
    public function it_has_correct_primary_key(): void
    {
        $this->assertEquals('id', $this->module->getKeyName());
    }

    /** @test Module can be created with valid data */
    public function it_can_create_module_with_valid_data(): void
    {
        $data = [
            'name' => 'TestModule',
            'alias' => 'test-module',
            'description' => 'Test module description',
            'enabled' => true,
        ];

        $module = Module::create($data);

        $this->assertInstanceOf(Module::class, $module);
        $this->assertEquals('TestModule', $module->name);
        $this->assertEquals('test-module', $module->alias);
        $this->assertTrue($module->enabled);
    }

    /** @test Module can be updated */
    public function it_can_update_module_attributes(): void
    {
        $module = Module::create([
            'name' => 'OriginalName',
            'alias' => 'original-alias',
            'enabled' => true,
        ]);

        $module->update([
            'name' => 'UpdatedName',
            'enabled' => false,
        ]);

        $this->assertEquals('UpdatedName', $module->fresh()->name);
        $this->assertFalse($module->fresh()->enabled);
    }

    /** @test Module can be enabled */
    public function it_can_enable_module(): void
    {
        $module = Module::create([
            'name' => 'TestModule',
            'alias' => 'test-module',
            'enabled' => false,
        ]);

        $module->update(['enabled' => true]);

        $this->assertTrue($module->fresh()->enabled);
    }

    /** @test Module can be disabled */
    public function it_can_disable_module(): void
    {
        $module = Module::create([
            'name' => 'TestModule',
            'alias' => 'test-module',
            'enabled' => true,
        ]);

        $module->update(['enabled' => false]);

        $this->assertFalse($module->fresh()->enabled);
    }

    /** @test Module can be found by name */
    public function it_can_be_found_by_name(): void
    {
        $module = Module::create([
            'name' => 'BlogModule',
            'alias' => 'blog',
        ]);

        $found = Module::where('name', 'BlogModule')->first();

        $this->assertInstanceOf(Module::class, $found);
        $this->assertEquals('BlogModule', $found->name);
    }

    /** @test Module can be found by alias */
    public function it_can_be_found_by_alias(): void
    {
        $module = Module::create([
            'name' => 'UserModule',
            'alias' => 'user-management',
        ]);

        $found = Module::where('alias', 'user-management')->first();

        $this->assertInstanceOf(Module::class, $found);
        $this->assertEquals('UserModule', $found->name);
    }

    /** @test Can query only enabled modules */
    public function it_can_query_only_enabled_modules(): void
    {
        Module::create(['name' => 'Module1', 'alias' => 'mod1', 'enabled' => true]);
        Module::create(['name' => 'Module2', 'alias' => 'mod2', 'enabled' => false]);
        Module::create(['name' => 'Module3', 'alias' => 'mod3', 'enabled' => true]);

        $enabled = Module::where('enabled', true)->get();

        $this->assertCount(2, $enabled);
    }

    /** @test Module uses timestamps */
    public function it_uses_timestamps(): void
    {
        $this->assertTrue($this->module->usesTimestamps());
    }

    /** @test Module has created_at and updated_at timestamps */
    public function it_has_timestamps(): void
    {
        $module = Module::create([
            'name' => 'TestModule',
            'alias' => 'test',
        ]);

        $this->assertNotNull($module->created_at);
        $this->assertNotNull($module->updated_at);
    }

    /** @test Module can be deleted */
    public function it_can_be_deleted(): void
    {
        $module = Module::create([
            'name' => 'ToDelete',
            'alias' => 'to-delete',
        ]);

        $moduleId = $module->id;
        $module->delete();

        $this->assertDatabaseMissing('modules', ['id' => $moduleId]);
    }

    /** @test Module can be counted */
    public function it_can_be_counted(): void
    {
        Module::factory()->count(5)->create();

        $count = Module::count();

        $this->assertGreaterThanOrEqual(5, $count);
    }

    /** @test Module can be retrieved with limit */
    public function it_can_be_limited(): void
    {
        Module::factory()->count(10)->create();

        $limited = Module::limit(3)->get();

        $this->assertCount(3, $limited);
    }
}
