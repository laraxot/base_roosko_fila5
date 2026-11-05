<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Modules\Tenant\Actions\Domains\GetDomainsArrayAction;
use Modules\Tenant\Models\Domain;
use Modules\Tenant\Models\Tenant;
use Tests\TestCase;

uses(TestCase::class, DatabaseTransactions::class, WithFaker::class);

beforeEach(function () {
    $this->action = app(GetDomainsArrayAction::class);
    $this->tenant = Tenant::factory()->create(['name' => 'test-tenant']);
});

describe('GetDomainsArrayAction', function () {
    it('returns domains for tenant', function () {
        Domain::factory()->create(['tenant_id' => $this->tenant->id, 'domain' => 'tenant1.example.com', 'is_primary' => true]);
        Domain::factory()->create(['tenant_id' => $this->tenant->id, 'domain' => 'alias.example.com', 'is_primary' => false]);

        $domains = $this->action->execute($this->tenant->id);

        expect($domains)->toBeArray()->toHaveCount(2);
        expect($domains[0]['domain'])->toBe('tenant1.example.com');
        expect($domains[0]['is_primary'])->toBeTrue();
        expect($domains[1]['is_primary'])->toBeFalse();
    });

    it('returns empty array for tenant without domains', function () {
        $tenant2 = Tenant::factory()->create(['name' => 'no-domains']);

        $domains = $this->action->execute($tenant2->id);

        expect($domains)->toBeArray()->toBeEmpty();
    });

    it('returns empty array for non-existent tenant', function () {
        $domains = $this->action->execute(99999);

        expect($domains)->toBeArray()->toBeEmpty();
    });

    it('includes SSL status', function () {
        Domain::factory()->create([
            'tenant_id' => $this->tenant->id,
            'domain' => 'secure.example.com',
            'is_primary' => true,
            'ssl_status' => 'active',
        ]);

        $domains = $this->action->execute($this->tenant->id);

        expect($domains[0])->toHaveKey('ssl_status');
        expect($domains[0]['ssl_status'])->toBe('active');
    });
});
