<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\File;
use Modules\Tenant\Actions\Config\GetTenantConfigArrayAction;
use Modules\Tenant\Models\Tenant;
use Tests\TestCase;

uses(TestCase::class, DatabaseTransactions::class, WithFaker::class);

beforeEach(function () {
    $this->action = app(GetTenantConfigArrayAction::class);
    $this->tenant = Tenant::factory()->create(['name' => 'test-tenant']);
    // Create tenant config file
    $configDir = base_path("tenants/{$this->tenant->name}/config");
    File::makeDirectory($configDir, 0755, true);
    File::put(
        $configDir.'/app.php',
        '<?php return ['.PHP_EOL.
        "    'name' => 'Tenant App',".PHP_EOL.
        "    'debug' => true,".PHP_EOL.
        '];'
    );
});

afterEach(function () {
    $configDir = base_path("tenants/{$this->tenant->name}/config");
    if (File::exists($configDir)) {
        File::deleteDirectory($configDir);
    }
});

describe('GetTenantConfigArrayAction', function () {
    it('loads tenant config array', function () {
        $config = $this->action->execute('app', $this->tenant->name);

        expect($config)->toBeArray()
            ->toHaveKeys(['name', 'debug']);
        expect($config['name'])->toBe('Tenant App');
        expect($config['debug'])->toBeTrue();
    });

    it('returns empty array for non-existent config', function () {
        $config = $this->action->execute('non-existent', $this->tenant->name);

        expect($config)->toBeArray()->toBeEmpty();
    });

    it('returns empty array for non-existent tenant', function () {
        $config = $this->action->execute('app', 'non-existent-tenant');

        expect($config)->toBeArray()->toBeEmpty();
    });

    it('loads nested config values', function () {
        $configDir = base_path("tenants/{$this->tenant->name}/config");
        File::put(
            $configDir.'/database.php',
            '<?php return ['.PHP_EOL.
            "    'connections' => [".PHP_EOL.
            "        'tenant' => [".PHP_EOL.
            "            'driver' => 'mysql',".PHP_EOL.
            "            'host' => 'localhost',".PHP_EOL.
            '        ],'.PHP_EOL.
            '    ],'.PHP_EOL.
            '];'
        );

        $config = $this->action->execute('database', $this->tenant->name);

        expect($config)->toHaveKey('connections');
        expect($config['connections']['tenant']['driver'])->toBe('mysql');
    });

    it('handles PHP array syntax variations', function () {
        $configDir = base_path("tenants/{$this->tenant->name}/config");
        File::put(
            $configDir.'/short.php',
            '<?php return [\'key\' => \'value\'];'
        );

        $config = $this->action->execute('short', $this->tenant->name);

        expect($config['key'])->toBe('value');
    });
});
