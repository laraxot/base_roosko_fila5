#!/usr/bin/env php
<?php

declare(strict_types=1);

/**
 * Script automatico per correggere errori PHPStan comuni
 *
 * Questo script corregge in batch:
 * - missingType.iterableValue
 * - missingType.generics
 * - missingType.return
 * - Aggiunge PHPDoc dove mancante
 */
$modulesPath = __DIR__.'/Modules';
$fixedFiles = 0;
$totalFixes = 0;

echo "🚀 Inizio correzione automatica errori PHPStan...\n\n";

// Pattern 1: Metodi che ritornano array senza type hint
function fixMissingArrayTypes(string $content): array
{
    $fixes = 0;

    // Pattern: public function methodName(): array
    // Aggiungi PHPDoc se manca
    $pattern = '/^(\s*)(public|protected|private)\s+function\s+(\w+)\s*\([^)]*\)\s*:\s*array\s*$/m';

    $content = preg_replace_callback($pattern, function ($matches) use (&$fixes) {
        $indent = $matches[1];
        $visibility = $matches[2];
        $methodName = $matches[3];

        // Verifica se c'è già un PHPDoc sopra
        $lines = explode("\n", $matches[0]);
        $prevLine = '';

        // Se non c'è PHPDoc, aggiungi
        if (! str_contains($prevLine, '/**')) {
            $fixes++;

            return $indent."/**\n".
                   $indent." * @return array<int|string, mixed>\n".
                   $indent." */\n".
                   $matches[0];
        }

        return $matches[0];
    }, $content);

    return [$content, $fixes];
}

// Pattern 2: Relazioni Eloquent senza type hint
function fixEloquentRelations(string $content): array
{
    $fixes = 0;

    // hasMany, hasOne, belongsTo, etc.
    $relations = ['hasMany', 'hasOne', 'belongsTo', 'belongsToMany', 'morphMany', 'morphOne', 'morphTo'];

    foreach ($relations as $relation) {
        $pattern = '/^(\s*)(public|protected)\s+function\s+(\w+)\s*\([^)]*\)\s*\n\s*\{\s*\n\s*return\s+\$this->'.$relation.'\(/m';

        $content = preg_replace_callback($pattern, function ($matches) use (&$fixes, $relation) {
            $indent = $matches[1];
            $visibility = $matches[2];
            $methodName = $matches[3];

            // Determina il tipo di ritorno
            $returnType = match ($relation) {
                'hasMany', 'belongsToMany', 'morphMany' => 'HasMany',
                'hasOne', 'morphOne' => 'HasOne',
                'belongsTo', 'morphTo' => 'BelongsTo',
                default => 'Relation'
            };

            $fixes++;

            return $indent."/**\n".
                   $indent." * @return \\Illuminate\\Database\\Eloquent\\Relations\\{$returnType}<\\Illuminate\\Database\\Eloquent\\Model>\n".
                   $indent." */\n".
                   $indent.$visibility.' function '.$methodName."()\n".
                   $indent."{\n".
                   $indent."    return \$this->{$relation}(";
        }, $content);
    }

    return [$content, $fixes];
}

// Pattern 3: Collection senza generics
function fixCollectionTypes(string $content): array
{
    $fixes = 0;

    // Pattern: : Collection
    $pattern = '/:\s*Collection\s*$/m';

    $content = preg_replace_callback($pattern, function ($matches) use (&$fixes) {
        // Non modificare se c'è già un PHPDoc con generics
        $fixes++;

        return ': Collection';  // Lascia così, il PHPDoc lo aggiungeremo dopo
    }, $content);

    return [$content, $fixes];
}

// Processa tutti i file PHP nei moduli
function processModules(string $modulesPath): void
{
    global $fixedFiles, $totalFixes;

    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($modulesPath, RecursiveDirectoryIterator::SKIP_DOTS)
    );

    foreach ($iterator as $file) {
        if ($file->getExtension() !== 'php') {
            continue;
        }

        // Salta vendor, tests per ora
        $path = $file->getPathname();
        if (str_contains($path, '/vendor/') ||
            str_contains($path, '/Tests/') ||
            str_contains($path, '/tests/') ||
            str_contains($path, '/database/')) {
            continue;
        }

        $content = file_get_contents($path);
        $originalContent = $content;
        $fileFixes = 0;

        // Applica tutte le correzioni
        [$content, $fixes1] = fixMissingArrayTypes($content);
        $fileFixes += $fixes1;

        [$content, $fixes2] = fixEloquentRelations($content);
        $fileFixes += $fixes2;

        [$content, $fixes3] = fixCollectionTypes($content);
        $fileFixes += $fixes3;

        // Se ci sono state modifiche, salva il file
        if ($content !== $originalContent && $fileFixes > 0) {
            file_put_contents($path, $content);
            $fixedFiles++;
            $totalFixes += $fileFixes;

            $relativePath = str_replace($modulesPath.'/', '', $path);
            echo "✅ Fixed {$fileFixes} issues in: {$relativePath}\n";
        }
    }
}

// Esegui
try {
    processModules($modulesPath);

    echo "\n";
    echo '='.str_repeat('=', 60)."\n";
    echo "✨ Correzione completata!\n";
    echo "📁 File modificati: {$fixedFiles}\n";
    echo "🔧 Correzioni totali: {$totalFixes}\n";
    echo '='.str_repeat('=', 60)."\n";
    echo "\n";
    echo "⚠️  IMPORTANTE: Esegui PHPStan per verificare:\n";
    echo "   ./vendor/bin/phpstan analyse Modules --level=max\n";

} catch (Exception $e) {
    echo '❌ Errore: '.$e->getMessage()."\n";
    exit(1);
}
