#!/usr/bin/env php
<?php

/**
 * Script per analizzare gli errori PHPStan e categorizzarli per modulo e tipo
 */

$modulesDir = __DIR__ . '/Modules';
$modules = array_filter(glob($modulesDir . '/*'), 'is_dir');
$results = [];
$totalErrors = 0;

echo "🧪 Analisi PHPStan SOLO per cartelle TESTS\n";
echo str_repeat('=', 80) . "\n\n";

foreach ($modules as $modulePath) {
    $moduleName = basename($modulePath);
    $testsPath = $modulePath . '/tests';

    // Skip se non esiste la cartella tests
    if (!is_dir($testsPath)) {
        echo "📦 {$moduleName}: ⏭️  Nessuna cartella tests\n\n";
        continue;
    }

    echo "📦 Analizzando test del modulo: {$moduleName}...\n";

    $output = [];
    $returnCode = 0;
    exec("./vendor/bin/phpstan analyse {$testsPath} --error-format=table --no-progress 2>&1", $output, $returnCode);

    $errorCount = 0;
    $errorTypes = [];

    foreach ($output as $line) {
        // Cerca la riga con il conteggio errori
        if (preg_match('/Found (\d+) errors?/', $line, $matches)) {
            $errorCount = (int) $matches[1];
        }

        // Categorizza per tipo di errore (cerca gli identifier 🪪)
        if (preg_match('/🪪\s+(\S+)/', $line, $matches)) {
            $errorType = $matches[1];
            if (!isset($errorTypes[$errorType])) {
                $errorTypes[$errorType] = 0;
            }
            $errorTypes[$errorType]++;
        }
    }

    $results[$moduleName] = [
        'errors' => $errorCount,
        'types' => $errorTypes,
        'status' => $errorCount === 0 ? '✅' : '❌'
    ];

    $totalErrors += $errorCount;

    echo "  Errori trovati: {$errorCount}\n";
    if ($errorCount > 0 && !empty($errorTypes)) {
        echo "  Tipi di errori:\n";
        arsort($errorTypes);
        foreach (array_slice($errorTypes, 0, 5) as $type => $count) {
            echo "    - {$type}: {$count}\n";
        }
    }
    echo "\n";
}

echo str_repeat('=', 80) . "\n";
echo "📊 RIEPILOGO GENERALE\n";
echo str_repeat('=', 80) . "\n\n";

// Ordina per numero di errori
uasort($results, function($a, $b) {
    return $b['errors'] <=> $a['errors'];
});

// Tabella riepilogativa
printf("%-20s | %-10s | %-6s\n", "MODULO", "ERRORI", "STATUS");
echo str_repeat('-', 80) . "\n";

foreach ($results as $module => $data) {
    printf("%-20s | %10d | %-6s\n", $module, $data['errors'], $data['status']);
}

echo str_repeat('=', 80) . "\n";
echo "TOTALE ERRORI: {$totalErrors}\n";
echo str_repeat('=', 80) . "\n\n";

// Analisi per tipo di errore globale
$globalErrorTypes = [];
foreach ($results as $module => $data) {
    foreach ($data['types'] as $type => $count) {
        if (!isset($globalErrorTypes[$type])) {
            $globalErrorTypes[$type] = 0;
        }
        $globalErrorTypes[$type] += $count;
    }
}

if (!empty($globalErrorTypes)) {
    echo "🏷️  TOP 10 TIPI DI ERRORI\n";
    echo str_repeat('=', 80) . "\n";
    arsort($globalErrorTypes);
    $i = 1;
    foreach (array_slice($globalErrorTypes, 0, 10) as $type => $count) {
        printf("%2d. %-40s : %5d occorrenze\n", $i++, $type, $count);
    }
    echo "\n";
}

// Salva risultati in JSON
$reportFile = __DIR__ . '/../docs/phpstan/tests-analysis-' . date('Y-m-d') . '.json';
@mkdir(dirname($reportFile), 0755, true);
file_put_contents($reportFile, json_encode([
    'date' => date('Y-m-d H:i:s'),
    'total_errors' => $totalErrors,
    'scope' => 'tests_only',
    'modules' => $results,
    'error_types' => $globalErrorTypes
], JSON_PRETTY_PRINT));

echo "✅ Report TEST salvato in: {$reportFile}\n";
