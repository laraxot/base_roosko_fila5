<?php

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Vite;

require_once __DIR__.'/vendor/autoload.php';

$app = require __DIR__.'/bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

// Previeni il caricamento di Vite durante l'analisi PHPStan
if (! defined('PHPSTAN_RUNNING')) {
    define('PHPSTAN_RUNNING', true);
}

// Mock della classe Vite per PHPStan
if (! class_exists('MockVite')) {
    class MockVite extends Vite
    {
        public function __construct()
        {
            // Constructor vuoto per evitare errori
        }

        public function manifest(): array
        {
            return [
                'resources/js/app.js' => [
                    'file' => 'assets/app.js',
                    'src' => 'resources/js/app.js',
                    'isEntry' => true,
                ],
            ];
        }
    }
}

// Registra il mock di Vite nel container
$app->singleton('Illuminate\Foundation\Vite', function () {
    return new MockVite;
});
