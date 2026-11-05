<?php

/**
 * Script per correggere automaticamente errori PHPStan nei test Pest.
 * 
 * Aggiunge @var inline per $this->property accessi nelle closure Pest.
 */

$modulesPath = __DIR__ . '/Modules';

// Trova tutti i file *Test.php
$iterator = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($modulesPath, RecursiveDirectoryIterator::SKIP_DOTS)
);

$testFiles = [];
foreach ($iterator as $file) {
    if ($file->isFile() && str_ends_with($file->getFilename(), 'Test.php')) {
        if (str_contains($file->getPath(), '/tests/')) {
            $testFiles[] = $file->getPathname();
        }
    }
}

echo "Trovati " . count($testFiles) . " file test\n";

$fixes = 0;
$filesFixed = 0;

foreach ($testFiles as $testFile) {
    $content = file_get_contents($testFile);
    $originalContent = $content;
    
    // Pattern: trova $this->nomeProperty e aggiungi @var se non esiste già
    $modified = false;
    
    // Fix 1: Aggiungi @var prima di expect($this->property)
    $pattern = '/(expect\(\$this->(\w+)\))/';
    if (preg_match_all($pattern, $content, $matches, PREG_OFFSET_CAPTURE)) {
        // Sostituzioni dall'ultimo al primo per mantenere offset
        foreach (array_reverse($matches[0]) as $i => $match) {
            $propName = array_reverse($matches[2])[$i];
            $fullMatch = $match[0];
            $offset = $match[1];
            
            // Cerca se c'è già @var prima
            $before = substr($content, max(0, $offset - 100), min(100, $offset));
            if (!str_contains($before, "/** @var") && !str_contains($before, "@var")) {
                $indent = '';
                $lineStart = strrpos(substr($content, 0, $offset), "\n");
                if ($lineStart !== false) {
                    $line = substr($content, $lineStart + 1, $offset - $lineStart - 1);
                    preg_match('/^(\s*)/', $line, $indentMatch);
                    $indent = $indentMatch[1] ?? '';
                }
                
                $replacement = "{$indent}/** @var mixed */\n{$indent}{$fullMatch}";
                $content = substr_replace($content, $replacement, $offset, strlen($fullMatch));
                $modified = true;
                $fixes++;
            }
        }
    }
    
    if ($modified && $content !== $originalContent) {
        file_put_contents($testFile, $content);
        $filesFixed++;
        echo ".";
        if ($filesFixed % 50 === 0) {
            echo " $filesFixed\n";
        }
    }
}

echo "\n\nCompletato!\n";
echo "File modificati: $filesFixed\n";
echo "Fix applicati: $fixes\n";

