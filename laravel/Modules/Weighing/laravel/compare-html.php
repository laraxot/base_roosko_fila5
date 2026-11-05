#!/usr/bin/env php
<?php

declare(strict_types=1);

/**
 * HTML Body Comparison Tool
 * Compares HTML structure between two URLs (excluding scripts)
 *
 * Usage: php compare-html.php <url1> <url2>
 */
$url1 = $argv[1] ?? 'https://italia.github.io/design-comuni-pagine-statiche/sito/homepage.html';
$url2 = $argv[2] ?? 'http://127.0.0.1:8000/it/tests/homepage';

// Fetch HTML using curl for better compatibility
function fetchHtml($url): void
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    $html = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode !== 200 || ! $html) {
        return;
    }

    return $html;
}

echo "🔍 HTML Body Comparison Tool\n";
echo "Originale: {$url1}\n";
echo "Replica: {$url2}\n\n";
echo "⏳ Download pagine...\n";

// Fetch HTML
$html1 = fetchHtml($url1);
$html2 = fetchHtml($url2);

if (! $html1) {
    echo '❌ Errore: Impossibile scaricare originale (HTTP '.(curl_init($url1) ? 'timeout' : 'error').")\n";
    exit(1);
}

if (! $html2) {
    echo "❌ Errore: Impossibile scaricare replica (HTTP timeout o errore)\n";
    exit(1);
}

echo "✅ Pagine scaricate\n";
echo 'Originale: '.strlen($html1)." bytes\n";
echo 'Replica: '.strlen($html2)." bytes\n\n";

// Extract body content
preg_match('/<body[^>]*>(.*?)<\/body>/s', $html1, $matches1);
preg_match('/<body[^>]*>(.*?)<\/body>/s', $html2, $matches2);

$body1 = $matches1[1] ?? '';
$body2 = $matches2[1] ?? '';

// Remove scripts
$body1 = preg_replace('/<script\b[^>]*>.*?<\/script>/is', '', $body1);
$body2 = preg_replace('/<script\b[^>]*>.*?<\/script>/is', '', $body2);

// Extract structure (tags with classes/IDs)
function extractStructure($html)
{
    preg_match_all('/<(\/?)([a-z0-9]+)([^>]*)(\/?)>/i', $html, $matches, PREG_SET_ORDER);
    $structure = [];
    foreach ($matches as $match) {
        $tag = $match[2];
        $attrs = $match[3];

        // Extract class
        preg_match('/class=["\']([^"\']*)["\']/i', $attrs, $classMatch);
        $class = $classMatch[1] ?? '';

        // Extract id
        preg_match('/id=["\']([^"\']*)["\']/i', $attrs, $idMatch);
        $id = $idMatch[1] ?? '';

        $structure[] = [
            'close' => $match[1] === '/',
            'tag' => $tag,
            'class' => $class,
            'id' => $id,
        ];
    }

    return $structure;
}

$struct1 = extractStructure($body1);
$struct2 = extractStructure($body2);

// Compare
echo "📊 Confronto Struttura:\n";
echo 'Originale: '.count($struct1)." elementi\n";
echo 'Replica: '.count($struct2)." elementi\n\n";

$differences = [];
$maxLen = max(count($struct1), count($struct2));

for ($i = 0; $i < $maxLen; $i++) {
    $elem1 = $struct1[$i] ?? null;
    $elem2 = $struct2[$i] ?? null;

    if (! $elem1 && $elem2) {
        $differences[] = "➕ EXTRA in replica: <{$elem2['tag']} class=\"{$elem2['class']}\" id=\"{$elem2['id']}\">";
    } elseif ($elem1 && ! $elem2) {
        $differences[] = "➖ MANCA in replica: <{$elem1['tag']} class=\"{$elem1['class']}\" id=\"{$elem1['id']}\">";
    } elseif ($elem1 && $elem2) {
        if ($elem1['tag'] !== $elem2['tag']) {
            $differences[] = "🏷️ TAG DIVERSO: {$elem1['tag']} vs {$elem2['tag']}";
        }
        if ($elem1['class'] !== $elem2['class']) {
            $differences[] = "🎨 CLASSE DIVERSA: {$elem1['class']} vs {$elem2['class']}";
        }
        if ($elem1['id'] !== $elem2['id']) {
            $differences[] = "🆔 ID DIVERSO: {$elem1['id']} vs {$elem2['id']}";
        }
    }
}

if (empty($differences)) {
    echo "✅ STRUTTURA IDENTICA!\n";
} else {
    echo "❌ Differenze trovate:\n\n";
    foreach (array_slice($differences, 0, 50) as $diff) {
        echo "{$diff}\n";
    }
    if (count($differences) > 50) {
        echo "\n... e altre ".(count($differences) - 50)." differenze\n";
    }
}

echo "\n📈 Statistiche:\n";
echo 'Differenze totali: '.count($differences)."\n";
$matchPercent = 100 - (count($differences) / max(count($struct1), count($struct2)) * 100);
echo 'Corrispondenza: '.round($matchPercent, 2)."%\n";
