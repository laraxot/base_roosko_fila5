#!/usr/bin/env php
<?php

declare(strict_types=1);

/**
 * Script per analizzare la complessità ciclomatica dei moduli
 * Genera report dettagliati per ogni modulo
 */
$modules = [
    'AI', 'Activity', 'Blog', 'Cms', 'Comment', 'Fixcity',
    'Gdpr', 'Geo', 'Job', 'Lang', 'Media', 'Notify',
    'Rating', 'Seo', 'Tenant', 'UI', 'User', 'Xot',
];

$baseDir = __DIR__;
$modulesDir = $baseDir.'/Modules';

echo "🐮 Super Mucca Analysis - Cyclomatic Complexity Report\n";
echo '='.str_repeat('=', 70)."\n\n";

foreach ($modules as $module) {
    $modulePath = $modulesDir.'/'.$module;
    $docsPath = $modulePath.'/docs';

    if (! is_dir($modulePath)) {
        echo "⚠️  Module $module not found, skipping...\n";

        continue;
    }

    // Crea la cartella docs se non esiste
    if (! is_dir($docsPath)) {
        mkdir($docsPath, 0755, true);
    }

    echo "📊 Analyzing module: $module\n";

    // Analizza il modulo
    $report = analyzeModule($modulePath, $module);

    // Salva il report
    $reportPath = $docsPath.'/cyclomatic-complexity-report.md';
    file_put_contents($reportPath, $report);

    echo "✅ Report saved: $reportPath\n\n";
}

echo "🎉 Analysis completed!\n";

/**
 * Analizza un modulo e genera il report
 */
function analyzeModule(string $modulePath, string $moduleName): string
{
    $stats = [
        'total_files' => 0,
        'total_lines' => 0,
        'total_classes' => 0,
        'total_methods' => 0,
        'complexity_data' => [],
        'high_complexity_methods' => [],
    ];

    // Analizza tutti i file PHP del modulo
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($modulePath, RecursiveDirectoryIterator::SKIP_DOTS)
    );

    foreach ($iterator as $file) {
        if ($file->getExtension() !== 'php') {
            continue;
        }

        $stats['total_files']++;
        $filePath = $file->getPathname();
        $relativePath = str_replace($modulePath.'/', '', $filePath);

        // Analizza il file
        $fileStats = analyzeFile($filePath, $relativePath);
        $stats['total_lines'] += $fileStats['lines'];
        $stats['total_classes'] += $fileStats['classes'];
        $stats['total_methods'] += $fileStats['methods'];

        if (! empty($fileStats['complexity_data'])) {
            $stats['complexity_data'][$relativePath] = $fileStats['complexity_data'];
        }

        if (! empty($fileStats['high_complexity'])) {
            $stats['high_complexity_methods'] = array_merge(
                $stats['high_complexity_methods'],
                $fileStats['high_complexity']
            );
        }
    }

    // Genera il report markdown
    return generateMarkdownReport($moduleName, $stats);
}

/**
 * Analizza un singolo file PHP
 */
function analyzeFile(string $filePath, string $relativePath): array
{
    $content = file_get_contents($filePath);
    $lines = count(file($filePath));

    $stats = [
        'lines' => $lines,
        'classes' => 0,
        'methods' => 0,
        'complexity_data' => [],
        'high_complexity' => [],
    ];

    // Conta classi
    preg_match_all('/\b(class|interface|trait|enum)\s+\w+/i', $content, $classMatches);
    $stats['classes'] = count($classMatches[0]);

    // Analizza metodi e complessità
    $tokens = token_get_all($content);
    $currentClass = null;
    $currentMethod = null;
    $braceLevel = 0;
    $methodStartBrace = 0;
    $complexity = 1;

    for ($i = 0; $i < count($tokens); $i++) {
        $token = $tokens[$i];

        if (is_array($token)) {
            [$id, $text] = $token;

            // Rileva classe corrente
            if ($id === T_CLASS || $id === T_TRAIT || $id === T_INTERFACE) {
                for ($j = $i + 1; $j < count($tokens); $j++) {
                    if (is_array($tokens[$j]) && $tokens[$j][0] === T_STRING) {
                        $currentClass = $tokens[$j][1];
                        break;
                    }
                }
            }

            // Rileva metodo corrente
            if ($id === T_FUNCTION) {
                for ($j = $i + 1; $j < count($tokens); $j++) {
                    if (is_array($tokens[$j]) && $tokens[$j][0] === T_STRING) {
                        $currentMethod = $tokens[$j][1];
                        $stats['methods']++;
                        $complexity = 1;
                        $methodStartBrace = 0;
                        break;
                    }
                }
            }

            // Calcola complessità ciclomatica
            if ($currentMethod && $methodStartBrace > 0) {
                if (in_array($id, [T_IF, T_ELSEIF, T_FOR, T_FOREACH, T_WHILE, T_CASE, T_CATCH, T_BOOLEAN_AND, T_BOOLEAN_OR, T_LOGICAL_AND, T_LOGICAL_OR])) {
                    $complexity++;
                }

                // Gestisci operatore ternario
                if ($text === '?') {
                    $complexity++;
                }
            }
        } else {
            // Gestisci parentesi graffe
            if ($token === '{') {
                $braceLevel++;
                if ($currentMethod && $methodStartBrace === 0) {
                    $methodStartBrace = $braceLevel;
                }
            } elseif ($token === '}') {
                if ($currentMethod && $braceLevel === $methodStartBrace) {
                    // Fine del metodo
                    $methodName = $currentClass ? "$currentClass::$currentMethod" : $currentMethod;
                    $stats['complexity_data'][$methodName] = $complexity;

                    // Segna metodi con alta complessità (>10)
                    if ($complexity > 10) {
                        $stats['high_complexity'][] = [
                            'file' => $relativePath,
                            'method' => $methodName,
                            'complexity' => $complexity,
                        ];
                    }

                    $currentMethod = null;
                    $methodStartBrace = 0;
                }
                $braceLevel--;
            }
        }
    }

    return $stats;
}

/**
 * Genera il report in formato Markdown
 */
function generateMarkdownReport(string $moduleName, array $stats): string
{
    $date = date('Y-m-d H:i:s');
    $highComplexityCount = count($stats['high_complexity_methods']);

    $report = "# Cyclomatic Complexity Report - Module: $moduleName\n\n";
    $report .= "**Generated:** $date  \n";
    $report .= "**Analyzer:** Super Mucca 🐮\n\n";
    $report .= "---\n\n";
    $report .= "## 📊 Summary Statistics\n\n";
    $report .= "| Metric | Value |\n";
    $report .= "|--------|-------|\n";
    $report .= "| Total PHP Files | {$stats['total_files']} |\n";
    $report .= "| Total Lines of Code | {$stats['total_lines']} |\n";
    $report .= "| Total Classes/Traits/Interfaces | {$stats['total_classes']} |\n";
    $report .= "| Total Methods | {$stats['total_methods']} |\n";
    $report .= "| High Complexity Methods (>10) | $highComplexityCount |\n\n";
    $report .= "---\n\n";
    $report .= "## 🎯 Cyclomatic Complexity Overview\n\n";
    $report .= "### What is Cyclomatic Complexity?\n\n";
    $report .= "La complessità ciclomatica è una metrica del software che misura la complessità di un programma. \n";
    $report .= "Viene calcolata contando il numero di percorsi di esecuzione indipendenti attraverso il codice sorgente.\n\n";
    $report .= "### Interpretation Guidelines\n\n";
    $report .= "| Complexity | Risk Level | Recommendation |\n";
    $report .= "|------------|------------|----------------|\n";
    $report .= "| 1-10 | ✅ Low | Simple method, low risk |\n";
    $report .= "| 11-20 | ⚠️ Moderate | Consider refactoring |\n";
    $report .= "| 21-50 | 🔴 High | Should refactor |\n";
    $report .= "| >50 | 💀 Very High | Must refactor immediately |\n\n";
    $report .= "---\n\n";

    // Aggiungi sezione metodi ad alta complessità
    if (! empty($stats['high_complexity_methods'])) {
        $report .= "## 🔴 High Complexity Methods (>10)\n\n";
        $report .= "These methods should be considered for refactoring:\n\n";
        $report .= "| File | Method | Complexity |\n";
        $report .= "|------|--------|------------|\n";

        // Ordina per complessità decrescente
        usort($stats['high_complexity_methods'], function ($a, $b) {
            return $b['complexity'] - $a['complexity'];
        });

        foreach ($stats['high_complexity_methods'] as $item) {
            $emoji = $item['complexity'] > 50 ? '💀' : ($item['complexity'] > 20 ? '🔴' : '⚠️');
            $report .= "| `{$item['file']}` | `{$item['method']}` | $emoji {$item['complexity']} |\n";
        }

        $report .= "\n---\n\n";
    } else {
        $report .= "## ✅ Excellent!\n\n";
        $report .= "No methods with high complexity found. All methods have complexity ≤ 10.\n\n";
        $report .= "---\n\n";
    }

    // Aggiungi distribuzione complessità
    $report .= "## 📈 Complexity Distribution\n\n";

    $distribution = [
        'low' => 0,      // 1-10
        'moderate' => 0, // 11-20
        'high' => 0,     // 21-50
        'very_high' => 0, // >50
    ];

    // Appiattisci l'array di complessità
    $allComplexities = [];
    foreach ($stats['complexity_data'] as $fileData) {
        if (is_array($fileData)) {
            foreach ($fileData as $complexity) {
                $allComplexities[] = $complexity;
            }
        } else {
            $allComplexities[] = $fileData;
        }
    }

    foreach ($allComplexities as $complexity) {
        if ($complexity <= 10) {
            $distribution['low']++;
        } elseif ($complexity <= 20) {
            $distribution['moderate']++;
        } elseif ($complexity <= 50) {
            $distribution['high']++;
        } else {
            $distribution['very_high']++;
        }
    }

    $total = array_sum($distribution);

    if ($total > 0) {
        $report .= "| Risk Level | Count | Percentage |\n";
        $report .= "|------------|-------|------------|\n";
        $report .= sprintf("| ✅ Low (1-10) | %d | %.1f%% |\n",
            $distribution['low'],
            ($distribution['low'] / $total) * 100
        );
        $report .= sprintf("| ⚠️ Moderate (11-20) | %d | %.1f%% |\n",
            $distribution['moderate'],
            ($distribution['moderate'] / $total) * 100
        );
        $report .= sprintf("| 🔴 High (21-50) | %d | %.1f%% |\n",
            $distribution['high'],
            ($distribution['high'] / $total) * 100
        );
        $report .= sprintf("| 💀 Very High (>50) | %d | %.1f%% |\n",
            $distribution['very_high'],
            ($distribution['very_high'] / $total) * 100
        );
    }

    $report .= "\n---\n\n";

    // Calcola media e mediana
    if (! empty($allComplexities)) {
        $avg = array_sum($allComplexities) / count($allComplexities);
        sort($allComplexities);
        $median = $allComplexities[floor(count($allComplexities) / 2)];
        $max = max($allComplexities);

        $report .= "## 📐 Statistical Analysis\n\n";
        $report .= "| Metric | Value |\n";
        $report .= "|--------|-------|\n";
        $report .= sprintf("| Average Complexity | %.2f |\n", $avg);
        $report .= sprintf("| Median Complexity | %d |\n", $median);
        $report .= sprintf("| Maximum Complexity | %d |\n", $max);
        $report .= "\n---\n\n";
    }

    // Aggiungi raccomandazioni
    $report .= "## 💡 Recommendations\n\n";

    if (count($stats['high_complexity_methods']) === 0) {
        $report .= "✅ **Great job!** This module has excellent code quality with no high-complexity methods.\n\n";
        $report .= "Continue following best practices:\n";
        $report .= "- Keep methods focused on a single responsibility\n";
        $report .= "- Extract complex logic into separate methods\n";
        $report .= "- Use early returns to reduce nesting\n";
    } else {
        $report .= "⚠️ **Action Required:** This module has methods with high cyclomatic complexity.\n\n";
        $report .= "### Refactoring Strategies:\n\n";
        $report .= "1. **Extract Method**: Break down complex methods into smaller, focused methods\n";
        $report .= "2. **Replace Conditional with Polymorphism**: Use inheritance/interfaces instead of complex conditionals\n";
        $report .= "3. **Introduce Parameter Object**: Group related parameters into objects\n";
        $report .= "4. **Use Guard Clauses**: Replace nested conditionals with early returns\n";
        $report .= "5. **Strategy Pattern**: Replace complex switch statements with strategy objects\n";
        $report .= "6. **State Pattern**: For complex state-dependent behavior\n\n";

        $report .= "### Priority:\n\n";
        $veryHigh = count(array_filter($stats['high_complexity_methods'], fn ($m) => $m['complexity'] > 50));
        $high = count(array_filter($stats['high_complexity_methods'], fn ($m) => $m['complexity'] > 20 && $m['complexity'] <= 50));

        if ($veryHigh > 0) {
            $report .= "🔴 **URGENT**: $veryHigh method(s) with very high complexity (>50) - refactor immediately\n";
        }
        if ($high > 0) {
            $report .= "⚠️ **HIGH**: $high method(s) with high complexity (21-50) - plan refactoring\n";
        }
    }

    $report .= "\n---\n\n";
    $report .= "## 📚 References\n\n";
    $report .= "- [Cyclomatic Complexity - Wikipedia](https://en.wikipedia.org/wiki/Cyclomatic_complexity)\n";
    $report .= "- [Refactoring Guru - Code Smells](https://refactoring.guru/refactoring/smells)\n";
    $report .= "- [Martin Fowler - Refactoring](https://refactoring.com/)\n";
    $report .= "\n---\n\n";
    $report .= "*Report generated by Super Mucca Analyzer 🐮*\n";

    return $report;
}
