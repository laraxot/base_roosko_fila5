<?php

declare(strict_types=1);

/**
 * PHPUnit → Pest DSL Converter
 * Converts class-based PHPUnit tests to Pest function-based DSL
 */
$testFiles = [];
foreach (new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator(__DIR__.'/laravel/Modules'),
    RecursiveIteratorIterator::LEAVES_ONLY
) as $file) {
    if ($file->getExtension() === 'php' &&
        strpos($file->getPathname(), 'tests') !== false &&
        strpos($file->getFilename(), 'Test.php') !== false &&
        strpos($file->getFilename(), '.pest.php') === false) {
        $testFiles[] = $file;
    }
}

$converted = 0;
$failed = [];

foreach ($testFiles as $file) {
    $filePath = $file->getPathname();
    $content = file_get_contents($filePath);

    // Skip if already in Pest format
    if (strpos($content, 'uses(') !== false && strpos($content, "it('") !== false) {
        continue;
    }

    try {
        $converted_content = convertToPest($content, $filePath);

        if ($converted_content !== $content) {
            file_put_contents($filePath, $converted_content);
            $converted++;
            echo '[✓] Converted: '.basename($filePath)."\n";
        }
    } catch (Exception $e) {
        $failed[] = basename($filePath).': '.$e->getMessage();
    }
}

echo "\n=== CONVERSION SUMMARY ===\n";
echo "Converted: $converted files\n";
if (! empty($failed)) {
    echo 'Failed: '.count($failed)." files\n";
    foreach ($failed as $fail) {
        echo "  - $fail\n";
    }
}

function convertToPest(string $content, string $filePath): string
{
    // Extract namespace and class name
    if (! preg_match('/namespace\s+([^;]+);/', $content, $nsMatch)) {
        return $content;
    }

    if (! preg_match('/class\s+(\w+)\s+extends\s+(\w+)/', $content, $classMatch)) {
        return $content;
    }

    $namespace = $nsMatch[1];
    $className = $classMatch[1];
    $baseClass = $classMatch[2];

    // Extract imports
    $imports = extractImports($content);
    $uses = "uses($baseClass::class);";

    // Extract test methods
    $testMethods = extractTestMethods($content);

    if (empty($testMethods)) {
        return $content;
    }

    // Build Pest content
    $header = "<?php\n\ndeclare(strict_types=1);\n\nnamespace $namespace;\n\n";

    $pest_content = $header.$imports."\n$uses\n\n";

    foreach ($testMethods as $test) {
        $pest_content .= $test."\n\n";
    }

    return $pest_content;
}

function extractImports(string $content): string
{
    $lines = explode("\n", $content);
    $imports = [];
    $in_header = true;

    foreach ($lines as $line) {
        if ($in_header && preg_match('/^use\s+/', $line)) {
            $imports[] = $line;
        } elseif ($in_header && preg_match('/^class\s+/', $line)) {
            $in_header = false;
            break;
        }
    }

    return implode("\n", $imports);
}

function extractTestMethods(string $content): array
{
    $methods = [];

    // Match both #[Test] attribute and public function test_* patterns
    $pattern = '/(?:#\[Test\]\s*)?public\s+function\s+(test_\w+)\s*\(\s*\)\s*:\s*void\s*\{(.*?)\n\s*\}/s';

    if (preg_match_all($pattern, $content, $matches, PREG_SET_ORDER)) {
        foreach ($matches as $match) {
            $methodName = $match[1];
            $methodBody = $match[2];

            // Convert test_method_name to "Method Name"
            $testName = ucwords(str_replace('_', ' ', substr($methodName, 5)));

            $pest_test = sprintf(
                "it('%s', function (): void {%s\n});",
                $testName,
                $methodBody
            );

            $methods[] = $pest_test;
        }
    }

    return $methods;
}

echo "✓ Conversion script started for Activity module...\n";
