<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Modules\Tenant\Models\Tenant;
use Tests\TestCase;

uses(TestCase::class, DatabaseTransactions::class, WithFaker::class);

describe('Tenant Model', function () {
    it('creates tenant with required fields', function () {
        $tenant = Tenant::create([
            'name' => 'test-tenant',
            'domain' => 'test.example.com',
            'is_active' => true,
        ]);

        expect($tenant)->toBeInstanceOf(Tenant::class);
        expect($tenant->name)->toBe('test-tenant');
        expect($tenant->domain)->toBe('test.example.com');
        expect($tenant->is_active)->toBeTrue();
    });

    it('has fillable fields', function () {
        $fillable = (new Tenant)->getFillable();

        expect($fillable)->toContain('name');
        expect($fillable)->toContain('domain');
        expect($fillable)->toContain('is_active');
        expect($fillable)->toContain('settings');
    });

    it('casts settings to array', function () {
        $tenant = Tenant::create([
            'name' => 'test',
            'domain' => 'test.com',
            'settings' => ['theme' => 'dark', 'language' => 'en'],
        ]);

        expect($tenant->settings)->toBeArray();
        expect($tenant->settings['theme'])->toBe('dark');
    });

    it('has unique name', function () {
        Tenant::factory()->create(['name' => 'unique-tenant']);

        expect(fn () => Tenant::factory()->create(['name' => 'unique-tenant']))
            ->toThrow(QueryException::class);
    });

    it('has unique domain', function () {
        Tenant::factory()->create(['domain' => 'unique.example.com']);

        expect(fn () => Tenant::factory()->create(['domain' => 'unique.example.com']))
            ->toThrow(QueryException::class);
    });

    it('has many domains', function () {
        $tenant = Tenant::factory()->create();

        expect($tenant->domains())->toBeInstanceOf(HasMany::class);
    });

    it('has many settings', function () {
        $tenant = Tenant::factory()->create();

        expect($tenant->settingsRelation())->toBeInstanceOf(HasMany::class);
    });

    it('has many subscriptions', function () {
        $tenant = Tenant::factory()->create();

        expect($tenant->subscriptions())->toBeInstanceOf(HasMany::class);
    });

    it('scopes active tenants', function () {
        Tenant::factory()->create(['name' => 'active-1', 'is_active' => true]);
        Tenant::factory()->create(['name' => 'active-2', 'is_active' => true]);
        Tenant::factory()->create(['name' => 'inactive', 'is_active' => false]);

        $active = Tenant::active()->get();

        expect($active)->toHaveCount(2);
        expect($active->pluck('name'))->toContain('active-1', 'active-2');
    });

    it('generates config path', function () {
        $tenant = Tenant::factory()->create(['name' => 'path-test']);

        $path = $tenant->configPath();

        expect($path)->toContain('tenants/path-test/config');
    });

    it('generates lang path', function () {
        $tenant = Tenant::factory()->create(['name' => 'lang-test']);

        $path = $tenant->langPath();

        expect($path)->toContain('tenants/lang-test/lang');
    });
});
