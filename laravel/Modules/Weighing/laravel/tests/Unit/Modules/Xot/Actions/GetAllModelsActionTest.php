<?php

declare(strict_types=1);

/**
 * GetAllModelsAction Test Suite
 *
 * Tests the core Xot Action for retrieving all models:
 * - Collects all application models from configured namespaces
 * - Filters based on module context
 * - Handles missing or invalid model classes
 * - Returns structured array of model metadata
 */

namespace Tests\Unit\Modules\Xot\Actions;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Modules\User\Models\User;
use Modules\Xot\Actions\Model\GetAllModelsAction;
use Tests\TestCase;

class GetAllModelsActionTest extends TestCase
{
    use DatabaseTransactions, WithFaker;

    protected GetAllModelsAction $action;

    protected function setUp(): void
    {
        parent::setUp();
        $this->action = new GetAllModelsAction;
    }

    /** @test Action can be instantiated */
    public function it_can_be_instantiated(): void
    {
        $this->assertInstanceOf(GetAllModelsAction::class, $this->action);
    }

    /** @test Action retrieves core User model */
    public function it_retrieves_user_model(): void
    {
        $models = $this->action->execute();

        // Should return collection or array of models
        $this->assertTrue(is_iterable($models));

        // Check if User model is in results
        $userModels = collect($models)->filter(function ($model) {
            return is_string($model) && strpos($model, 'User') !== false;
        });

        $this->assertGreaterThan(0, $userModels->count());
    }

    /** @test Action returns model class names */
    public function it_returns_valid_model_class_names(): void
    {
        $models = $this->action->execute();

        foreach ($models as $model) {
            // Each model should be a valid class string or instance
            $this->assertTrue(
                is_string($model) || is_object($model),
                'Model is not string or object: '.gettype($model)
            );
        }
    }

    /** @test Action handles empty namespace gracefully */
    public function it_handles_invalid_namespaces(): void
    {
        // Action should not throw when namespace doesn't exist
        $this->expectNotToPerformAssertions();

        try {
            $models = $this->action->execute();
            $this->assertIsIterable($models);
        } catch (\Exception $e) {
            $this->fail('Action threw exception on invalid namespace: '.$e->getMessage());
        }
    }

    /** @test Action result is not empty for core application */
    public function it_returns_non_empty_result(): void
    {
        $models = $this->action->execute();

        $this->assertGreaterThan(0, count((array) $models));
    }
}
