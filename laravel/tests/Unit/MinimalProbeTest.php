<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Modules\Tenant\Models\Domain;
use Modules\Tenant\Models\Tenant;
use Tests\TestCase;

uses(TestCase::class, DatabaseTransactions::class, WithFaker::class);

describe('Minimal Probe', function () {
    beforeEach(function () {
        $this->tenant = Tenant::factory()->create();
    });

    it('creates domain', function () {
        $domain = Domain::create([
            'tenant_id' => $this->tenant->id,
            'domain' => 'tenant.example.com',
            'is_primary' => true,
        ]);

        expect($domain)->toBeInstanceOf(Domain::class);
    });
});
