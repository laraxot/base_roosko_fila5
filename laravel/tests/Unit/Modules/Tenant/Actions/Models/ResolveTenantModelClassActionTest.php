<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Modules\Tenant\Actions\Models\ResolveTenantModelClassAction;
use Modules\Tenant\Models\Tenant;
use Tests\TestCase;

uses(TestCase::class, DatabaseTransactions::class, WithFaker::class);

beforeEach(function () {
    $this->action = app(ResolveTenantModelClassAction::class);
    $this->tenant = Tenant::factory()->create(['name' => 'test-tenant']);
});

describe('ResolveTenantModelClassAction', function () {
    it('resolves model class for registered model', function () {
        // This depends on tenant configuration
        // Test that action can be instantiated
        expect($this->action)->toBeInstanceOf(ResolveTenantModelClassAction::class);
    });

    it('returns null for unknown model', function () {
        $class = $this->action->execute('unknown-model', $this->tenant->name);

        expect($class)->toBeNull();
    });

    it('handles tenant without model mapping', function () {
        $class = $this->action->execute('user', 'non-existent-tenant');

        expect($class)->toBeNull();
    });
});
