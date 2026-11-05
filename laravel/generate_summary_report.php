#!/usr/bin/env php
<?php

declare(strict_types=1);

/**
 * Script per generare un report riassuntivo della complessità ciclomatica
 * di tutti i moduli
 */
$modules = [
    'AI', 'Activity', 'Blog', 'Cms', 'Comment', 'Fixcity',
    'Gdpr', 'Geo', 'Job', 'Lang', 'Media', 'Notify',
    'Rating', 'Seo', 'Tenant', 'UI', 'User', 'Xot',
];

$baseDir = __DIR__;
$modulesDir = $baseDir.'/Modules';
$docsDir = $baseDir.'/docs';

if (! is_dir($docsDir)) {
    mkdir($docsDir, 0755, true);
}

echo "🐮 Super Mucca - Generating Summary Report\n";
echo '='.str_repeat('=', 70)."\n\n";

$summaryData = [];

foreach ($modules as $module) {
    $reportPath = $modulesDir.'/'.$module.'/docs/cyclomatic-complexity-report.md';

    if (! file_exists($reportPath)) {
        echo "⚠️  Report for $module not found, skipping...\n";

        continue;
    }

    echo "📖 Reading report: $module\n";

    $content = file_get_contents($reportPath);
    $data = parseReport($content, $module);
    $summaryData[$module] = $data;
}

echo "\n✍️  Generating summary report...\n";

$summaryReport = generateSummaryReport($summaryData);
$summaryPath = $docsDir.'/cyclomatic-complexity-summary.md';
file_put_contents($summaryPath, $summaryReport);

echo "✅ Summary report saved: $summaryPath\n";
echo "🎉 Done!\n";

/**
 * Estrae i dati dal report markdown
 */
function parseReport(string $content, string $module): array
{
    $data = [
        'module' => $module,
        'files' => 0,
        'lines' => 0,
        'classes' => 0,
        'methods' => 0,
        'high_complexity' => 0,
        'avg_complexity' => 0.0,
        'max_complexity' => 0,
        'low_count' => 0,
        'moderate_count' => 0,
        'high_count' => 0,
        'very_high_count' => 0,
    ];

    // Estrai statistiche
    if (preg_match('/Total PHP Files \| (\d+)/', $content, $matches)) {
        $data['files'] = (int) $matches[1];
    }
    if (preg_match('/Total Lines of Code \| (\d+)/', $content, $matches)) {
        $data['lines'] = (int) $matches[1];
    }
    if (preg_match('/Total Classes\/Traits\/Interfaces \| (\d+)/', $content, $matches)) {
        $data['classes'] = (int) $matches[1];
    }
    if (preg_match('/Total Methods \| (\d+)/', $content, $matches)) {
        $data['methods'] = (int) $matches[1];
    }
    if (preg_match('/High Complexity Methods \(>10\) \| (\d+)/', $content, $matches)) {
        $data['high_complexity'] = (int) $matches[1];
    }
    if (preg_match('/Average Complexity \| ([\d.]+)/', $content, $matches)) {
        $data['avg_complexity'] = (float) $matches[1];
    }
    if (preg_match('/Maximum Complexity \| (\d+)/', $content, $matches)) {
        $data['max_complexity'] = (int) $matches[1];
    }

    // Estrai distribuzione
    if (preg_match('/Low \(1-10\) \| (\d+)/', $content, $matches)) {
        $data['low_count'] = (int) $matches[1];
    }
    if (preg_match('/Moderate \(11-20\) \| (\d+)/', $content, $matches)) {
        $data['moderate_count'] = (int) $matches[1];
    }
    if (preg_match('/High \(21-50\) \| (\d+)/', $content, $matches)) {
        $data['high_count'] = (int) $matches[1];
    }
    if (preg_match('/Very High \(>50\) \| (\d+)/', $content, $matches)) {
        $data['very_high_count'] = (int) $matches[1];
    }

    return $data;
}

/**
 * Genera il report riassuntivo
 */
function generateSummaryReport(array $summaryData): string
{
    $date = date('Y-m-d H:i:s');

    $report = "# Cyclomatic Complexity - Project Summary Report\n\n";
    $report .= "**Generated:** $date  \n";
    $report .= "**Analyzer:** Super Mucca 🐮\n\n";
    $report .= "---\n\n";

    // Statistiche globali
    $totalFiles = 0;
    $totalLines = 0;
    $totalClasses = 0;
    $totalMethods = 0;
    $totalHighComplexity = 0;
    $totalLow = 0;
    $totalModerate = 0;
    $totalHigh = 0;
    $totalVeryHigh = 0;

    foreach ($summaryData as $data) {
        $totalFiles += $data['files'];
        $totalLines += $data['lines'];
        $totalClasses += $data['classes'];
        $totalMethods += $data['methods'];
        $totalHighComplexity += $data['high_complexity'];
        $totalLow += $data['low_count'];
        $totalModerate += $data['moderate_count'];
        $totalHigh += $data['high_count'];
        $totalVeryHigh += $data['very_high_count'];
    }

    $report .= "## 📊 Global Statistics\n\n";
    $report .= "| Metric | Value |\n";
    $report .= "|--------|-------|\n";
    $report .= '| Total Modules | '.count($summaryData)." |\n";
    $report .= '| Total PHP Files | '.number_format($totalFiles)." |\n";
    $report .= '| Total Lines of Code | '.number_format($totalLines)." |\n";
    $report .= '| Total Classes/Traits/Interfaces | '.number_format($totalClasses)." |\n";
    $report .= '| Total Methods | '.number_format($totalMethods)." |\n";
    $report .= '| High Complexity Methods (>10) | '.number_format($totalHighComplexity)." |\n\n";
    $report .= "---\n\n";

    // Distribuzione globale
    $totalAll = $totalLow + $totalModerate + $totalHigh + $totalVeryHigh;

    $report .= "## 📈 Global Complexity Distribution\n\n";
    $report .= "| Risk Level | Count | Percentage |\n";
    $report .= "|------------|-------|------------|\n";
    $report .= sprintf("| ✅ Low (1-10) | %s | %.1f%% |\n",
        number_format($totalLow),
        $totalAll > 0 ? ($totalLow / $totalAll) * 100 : 0
    );
    $report .= sprintf("| ⚠️ Moderate (11-20) | %s | %.1f%% |\n",
        number_format($totalModerate),
        $totalAll > 0 ? ($totalModerate / $totalAll) * 100 : 0
    );
    $report .= sprintf("| 🔴 High (21-50) | %s | %.1f%% |\n",
        number_format($totalHigh),
        $totalAll > 0 ? ($totalHigh / $totalAll) * 100 : 0
    );
    $report .= sprintf("| 💀 Very High (>50) | %s | %.1f%% |\n",
        number_format($totalVeryHigh),
        $totalAll > 0 ? ($totalVeryHigh / $totalAll) * 100 : 0
    );
    $report .= "\n---\n\n";

    // Tabella per modulo
    $report .= "## 📦 Module-by-Module Analysis\n\n";
    $report .= "| Module | Files | LOC | Methods | High Complexity | Avg | Max |\n";
    $report .= "|--------|-------|-----|---------|-----------------|-----|-----|\n";

    // Ordina per numero di metodi ad alta complessità (decrescente)
    uasort($summaryData, function ($a, $b) {
        return $b['high_complexity'] - $a['high_complexity'];
    });

    foreach ($summaryData as $data) {
        $emoji = $data['high_complexity'] > 0 ? '⚠️' : '✅';
        $report .= sprintf("| %s %s | %s | %s | %s | %s %d | %.2f | %d |\n",
            $emoji,
            $data['module'],
            number_format($data['files']),
            number_format($data['lines']),
            number_format($data['methods']),
            $data['high_complexity'] > 0 ? '🔴' : '',
            $data['high_complexity'],
            $data['avg_complexity'],
            $data['max_complexity']
        );
    }

    $report .= "\n---\n\n";

    // Top 5 moduli con più metodi ad alta complessità
    $report .= "## 🔴 Top 5 Modules with High Complexity\n\n";

    $topModules = array_slice($summaryData, 0, 5, true);
    $hasHighComplexity = false;

    foreach ($topModules as $data) {
        if ($data['high_complexity'] > 0) {
            $hasHighComplexity = true;
            break;
        }
    }

    if ($hasHighComplexity) {
        $report .= "These modules require immediate attention:\n\n";

        $position = 1;
        foreach ($topModules as $data) {
            if ($data['high_complexity'] > 0) {
                $report .= sprintf("**%d. %s**\n", $position, $data['module']);
                $report .= sprintf("- High complexity methods: %d\n", $data['high_complexity']);
                $report .= sprintf("- Average complexity: %.2f\n", $data['avg_complexity']);
                $report .= sprintf("- Maximum complexity: %d\n", $data['max_complexity']);
                $report .= sprintf("- Report: `Modules/%s/docs/cyclomatic-complexity-report.md`\n\n", $data['module']);
                $position++;
            }
        }
    } else {
        $report .= "✅ **Excellent!** No modules have high-complexity methods.\n\n";
    }

    $report .= "---\n\n";

    // Raccomandazioni generali
    $report .= "## 💡 General Recommendations\n\n";

    $percentageHighComplexity = $totalMethods > 0 ? ($totalHighComplexity / $totalMethods) * 100 : 0;

    if ($percentageHighComplexity > 5) {
        $report .= "🔴 **Critical**: More than 5% of methods have high complexity.\n\n";
        $report .= "**Immediate Actions:**\n";
        $report .= "1. Review all methods with complexity > 20\n";
        $report .= "2. Create refactoring plan for critical methods\n";
        $report .= "3. Set up complexity limits in CI/CD pipeline\n";
        $report .= "4. Schedule regular code reviews\n\n";
    } elseif ($percentageHighComplexity > 2) {
        $report .= "⚠️ **Warning**: Between 2-5% of methods have high complexity.\n\n";
        $report .= "**Recommended Actions:**\n";
        $report .= "1. Prioritize refactoring of methods with complexity > 20\n";
        $report .= "2. Review and improve testing coverage\n";
        $report .= "3. Consider pair programming for complex features\n\n";
    } elseif ($percentageHighComplexity > 0) {
        $report .= "✅ **Good**: Less than 2% of methods have high complexity.\n\n";
        $report .= "**Maintenance Actions:**\n";
        $report .= "1. Address remaining high-complexity methods\n";
        $report .= "2. Maintain current code quality standards\n";
        $report .= "3. Continue regular code reviews\n\n";
    } else {
        $report .= "🎉 **Excellent**: No high-complexity methods found!\n\n";
        $report .= "**Keep it up:**\n";
        $report .= "1. Maintain current development practices\n";
        $report .= "2. Continue code reviews and testing\n";
        $report .= "3. Share best practices with the team\n\n";
    }

    $report .= "---\n\n";

    // Best practices
    $report .= "## 📚 Best Practices for Reducing Complexity\n\n";
    $report .= "### 1. Extract Method\n";
    $report .= "Break down complex methods into smaller, focused methods.\n\n";
    $report .= "### 2. Early Returns\n";
    $report .= "Use guard clauses to reduce nesting levels.\n\n";
    $report .= "### 3. Strategy Pattern\n";
    $report .= "Replace complex conditionals with polymorphic behavior.\n\n";
    $report .= "### 4. Single Responsibility\n";
    $report .= "Ensure each method has one clear purpose.\n\n";
    $report .= "### 5. Limit Parameters\n";
    $report .= "Use parameter objects for methods with many parameters.\n\n";
    $report .= "### 6. Avoid Deep Nesting\n";
    $report .= "Keep nesting levels below 3-4 levels.\n\n";
    $report .= "---\n\n";

    // Links ai report individuali
    $report .= "## 📄 Individual Module Reports\n\n";

    foreach ($summaryData as $data) {
        $report .= sprintf("- [%s](../Modules/%s/docs/cyclomatic-complexity-report.md)\n",
            $data['module'],
            $data['module']
        );
    }

    $report .= "\n---\n\n";
    $report .= "## 🔗 References\n\n";
    $report .= "- [Cyclomatic Complexity - Wikipedia](https://en.wikipedia.org/wiki/Cyclomatic_complexity)\n";
    $report .= "- [Refactoring Guru](https://refactoring.guru/)\n";
    $report .= "- [Clean Code by Robert C. Martin](https://www.amazon.com/Clean-Code-Handbook-Software-Craftsmanship/dp/0132350882)\n";
    $report .= "- [Code Complete by Steve McConnell](https://www.amazon.com/Code-Complete-Practical-Handbook-Construction/dp/0735619670)\n";
    $report .= "\n---\n\n";
    $report .= "*Report generated by Super Mucca Analyzer 🐮*\n";

    return $report;
}
