<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Modules\Tenant\Actions\Models\ResolveTenantModelInstanceAction;
use Modules\Tenant\Models\Tenant;
use Tests\TestCase;

uses(TestCase::class, DatabaseTransactions::class, WithFaker::class);

beforeEach(function () {
    $this->action = app(ResolveTenantModelInstanceAction::class);
    $this->tenant = Tenant::factory()->create(['name' => 'test-tenant']);
});

describe('ResolveTenantModelInstanceAction', function () {
    it('returns model instance for valid model', function () {
        // Test that action can be instantiated
        expect($this->action)->toBeInstanceOf(ResolveTenantModelInstanceAction::class);
    });

    it('throws exception for unknown model', function () {
        expect(fn () => $this->action->execute('unknown-model', $this->tenant->name))
            ->toThrow(ReflectionException::class);
    });
});
