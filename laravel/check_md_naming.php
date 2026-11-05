#!/usr/bin/env php
<?php

/**
 * Script per identificare file .md che violano le naming convention:
 * - NO date nel nome
 * - NO maiuscole (tranne README.md)
 */
$baseDirs = [
    __DIR__.'/../docs',
    __DIR__.'/Modules',
];

$violations = [];

foreach ($baseDirs as $baseDir) {
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($baseDir, RecursiveDirectoryIterator::SKIP_DOTS)
    );

    foreach ($iterator as $file) {
        if ($file->getExtension() !== 'md') {
            continue;
        }

        $filename = $file->getFilename();
        $filepath = $file->getPathname();

        // Skip README.md (eccezione consentita)
        if ($filename === 'README.md') {
            continue;
        }

        $hasViolation = false;
        $reasons = [];

        // Check 1: Contiene date (pattern: YYYY-MM-DD, YYYY_MM_DD, YYYYMMDD)
        if (preg_match('/\d{4}[-_]?\d{2}[-_]?\d{2}/', $filename)) {
            $hasViolation = true;
            $reasons[] = 'contiene data';
        }

        // Check 2: Contiene maiuscole
        if (preg_match('/[A-Z]/', $filename)) {
            $hasViolation = true;
            $reasons[] = 'contiene maiuscole';
        }

        if ($hasViolation) {
            $suggestedName = strtolower($filename);
            // Rimuovi date nel formato YYYY-MM-DD o YYYY_MM_DD
            $suggestedName = preg_replace('/-?\d{4}[-_]\d{2}[-_]\d{2}/', '', $suggestedName);
            // Rimuovi date nel formato YYYYMMDD
            $suggestedName = preg_replace('/-?\d{8}/', '', $suggestedName);
            // Pulisci doppi trattini
            $suggestedName = preg_replace('/-+/', '-', $suggestedName);
            // Rimuovi trattini all'inizio/fine
            $suggestedName = trim($suggestedName, '-');

            $violations[] = [
                'path' => $filepath,
                'filename' => $filename,
                'reasons' => $reasons,
                'suggested' => $suggestedName,
                'dir' => dirname($filepath),
            ];
        }
    }
}

if (empty($violations)) {
    echo "✅ Nessuna violazione trovata! Tutti i file .md rispettano le naming convention.\n";
    exit(0);
}

echo "🚨 VIOLAZIONI NAMING CONVENTION\n";
echo str_repeat('=', 100)."\n";
echo sprintf("Trovate %d violazioni\n\n", count($violations));

// Raggruppa per directory
$byDir = [];
foreach ($violations as $v) {
    $relDir = str_replace(__DIR__.'/../', '', $v['dir']);
    if (! isset($byDir[$relDir])) {
        $byDir[$relDir] = [];
    }
    $byDir[$relDir][] = $v;
}

ksort($byDir);

foreach ($byDir as $dir => $files) {
    echo "📁 {$dir}/\n";
    echo str_repeat('-', 100)."\n";

    foreach ($files as $file) {
        echo sprintf("  ❌ %s\n", $file['filename']);
        echo sprintf("     Problemi: %s\n", implode(', ', $file['reasons']));
        echo sprintf("     Suggerito: %s\n", $file['suggested']);
        echo "\n";
    }
    echo "\n";
}

echo str_repeat('=', 100)."\n";
echo "📊 STATISTICHE\n";
echo str_repeat('=', 100)."\n";
echo sprintf("Totale violazioni: %d\n", count($violations));

$reasonCounts = [];
foreach ($violations as $v) {
    foreach ($v['reasons'] as $reason) {
        if (! isset($reasonCounts[$reason])) {
            $reasonCounts[$reason] = 0;
        }
        $reasonCounts[$reason]++;
    }
}

echo "\nTipi di violazioni:\n";
foreach ($reasonCounts as $reason => $count) {
    echo sprintf("  - %s: %d file\n", $reason, $count);
}

echo "\n";

// Genera script bash per rinominare
$scriptFile = __DIR__.'/rename_md_files.sh';
$bashScript = "#!/bin/bash\n\n";
$bashScript .= "# Script generato automaticamente per rinominare file .md\n";
$bashScript .= "# con naming convention non conformi\n\n";
$bashScript .= "set -e\n\n";

foreach ($violations as $v) {
    $oldPath = $v['path'];
    $newPath = $v['dir'].'/'.$v['suggested'];
    $bashScript .= sprintf("# %s\n", implode(', ', $v['reasons']));
    $bashScript .= sprintf("mv \"%s\" \"%s\"\n", $oldPath, $newPath);
    $bashScript .= sprintf("git add \"%s\" \"%s\"\n\n", $oldPath, $newPath);
}

$bashScript .= "echo \"✅ Rinominazione completata\"\n";

file_put_contents($scriptFile, $bashScript);
chmod($scriptFile, 0755);

echo "✅ Script di rinominazione generato: {$scriptFile}\n";
echo "\n";
echo "Per applicare le rinominazioni, esegui:\n";
echo "  ./rename_md_files.sh\n";
echo "\n";
