<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\File;
use Modules\Tenant\Actions\Config\GetTenantConfigNamesAction;
use Modules\Tenant\Models\Tenant;
use Tests\TestCase;

uses(TestCase::class, DatabaseTransactions::class, WithFaker::class);

beforeEach(function () {
    $this->action = app(GetTenantConfigNamesAction::class);
    $this->tenant = Tenant::factory()->create(['name' => 'test-tenant']);
    $configDir = base_path("tenants/{$this->tenant->name}/config");
    File::makeDirectory($configDir, 0755, true);
    File::put($configDir.'/app.php', "<?php return ['name' => 'App'];");
    File::put($configDir.'/database.php', "<?php return ['driver' => 'mysql'];");
    File::put($configDir.'/mail.php', "<?php return ['driver' => 'smtp'];");
});

afterEach(function () {
    $configDir = base_path("tenants/{$this->tenant->name}/config");
    if (File::exists($configDir)) {
        File::deleteDirectory($configDir);
    }
});

describe('GetTenantConfigNamesAction', function () {
    it('returns list of config file names', function () {
        $names = $this->action->execute($this->tenant->name);

        expect($names)->toBeArray();
        expect($names)->toContain('app');
        expect($names)->toContain('database');
        expect($names)->toContain('mail');
    });

    it('returns empty array for tenant without config dir', function () {
        $tenant2 = Tenant::factory()->create(['name' => 'no-config-tenant']);
        $names = $this->action->execute($tenant2->name);

        expect($names)->toBeArray()->toBeEmpty();
    });

    it('returns empty array for non-existent tenant', function () {
        $names = $this->action->execute('non-existent');

        expect($names)->toBeArray()->toBeEmpty();
    });

    it('ignores non-PHP files', function () {
        $configDir = base_path("tenants/{$this->tenant->name}/config");
        File::put($configDir.'/readme.txt', 'Not a config');
        File::put($configDir.'/config.json', '{}');

        $names = $this->action->execute($this->tenant->name);

        expect($names)->not->toContain('readme');
        expect($names)->not->toContain('config');
    });
});
