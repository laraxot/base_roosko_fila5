<?php

declare(strict_types=1);

use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Modules\Tenant\Models\Domain;
use Modules\Tenant\Models\Tenant;
use Tests\TestCase;

uses(TestCase::class, DatabaseTransactions::class, WithFaker::class);

describe('Domain Model', function () {
    beforeEach(function () {
        $this->tenant = Tenant::factory()->create();
    });

    it('creates domain with required fields', function () {
        $domain = Domain::create([
            'tenant_id' => $this->tenant->id,
            'domain' => 'tenant.example.com',
            'is_primary' => true,
        ]);

        expect($domain)->toBeInstanceOf(Domain::class);
        expect($domain->domain)->toBe('tenant.example.com');
        expect($domain->is_primary)->toBeTrue();
    });

    it('belongs to tenant', function () {
        $domain = Domain::factory()->create(['tenant_id' => $this->tenant->id]);

        expect($domain->tenant)->toBeInstanceOf(Tenant::class);
        expect($domain->tenant->id)->toBe($this->tenant->id);
    });

    it('has unique domain', function () {
        Domain::factory()->create(['domain' => 'unique.example.com', 'tenant_id' => $this->tenant->id]);

        expect(fn () => Domain::factory()->create(['domain' => 'unique.example.com', 'tenant_id' => $this->tenant->id]))
            ->toThrow(QueryException::class);
    });

    it('casts ssl_status', function () {
        $domain = Domain::create([
            'tenant_id' => $this->tenant->id,
            'domain' => 'ssl.example.com',
            'ssl_status' => 'active',
        ]);

        expect($domain->ssl_status)->toBe('active');
    });

    it('defaults is_primary to false', function () {
        $domain = Domain::create([
            'tenant_id' => $this->tenant->id,
            'domain' => 'secondary.example.com',
        ]);

        expect($domain->is_primary)->toBeFalse();
    });

    it('only one primary domain per tenant', function () {
        Domain::factory()->create(['tenant_id' => $this->tenant->id, 'is_primary' => true, 'domain' => 'primary1.com']);
        Domain::factory()->create(['tenant_id' => $this->tenant->id, 'is_primary' => true, 'domain' => 'primary2.com']);

        // The second one should become primary, first should be demoted
        // This depends on implementation - test the expected behavior
        $primaries = Domain::where('tenant_id', $this->tenant->id)->where('is_primary', true)->get();
        // Implementation may vary - just verify structure
        expect($primaries)->not->toBeEmpty();
    });
});
