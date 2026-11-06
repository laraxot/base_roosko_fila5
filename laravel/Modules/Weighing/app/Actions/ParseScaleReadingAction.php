<?php

declare(strict_types=1);

namespace Modules\Weighing\Actions;

use Carbon\CarbonImmutable;
use Modules\Weighing\Datas\ScaleReadingData;
use Modules\Weighing\Exceptions\ScaleReadingParseException;
use Spatie\QueueableAction\QueueableAction;
use Throwable;

use function Safe\preg_match_all;
use function Safe\preg_replace;

/**
 * Interpreta una riga ASCII di un indicatore di peso. Funzione pura (testata in unit), parametri
 * in `config('weighing.tcp_ascii')`:
 *
 * - `weight_pattern`: regex con gruppo `weight`; vale l'ultima occorrenza della riga, cosi' un prefisso
 *   numerico (indirizzo dello strumento, "01ST,GS,+001234kg") non viene scambiato per il peso;
 * - segno e spazi tra segno e cifre ammessi ("+  1234.5"), virgola o punto come separatore decimale;
 * - `stable_marker` (default "ST"): la riga che lo contiene e' una lettura stabile;
 * - `unit_factor`: moltiplicatore verso i kg.
 *
 * @throws ScaleReadingParseException
 */
class ParseScaleReadingAction
{
    use QueueableAction;

    public const string DEFAULT_PATTERN = '/(?<weight>[+-]?\s*\d+(?:[.,]\d+)?)/';

    public function execute(string $raw, ?CarbonImmutable $readAt = null): ScaleReadingData
    {
        $pattern = config('weighing.tcp_ascii.weight_pattern');
        $pattern = is_string($pattern) && $pattern !== '' ? $pattern : self::DEFAULT_PATTERN;

        $line = trim(preg_replace('/[\x00-\x1F\x7F]+/', ' ', $raw));

        try {
            $found = preg_match_all($pattern, $line, $matches);
        } catch (Throwable) {
            throw ScaleReadingParseException::invalidPattern($pattern);
        }

        /** @var list<string> $weights */
        $weights = $matches['weight'] ?? $matches[1] ?? [];
        if ($found === 0 || $weights === []) {
            throw ScaleReadingParseException::for($raw);
        }

        $number = str_replace([' ', "\t", ','], ['', '', '.'], (string) end($weights));
        if (! is_numeric($number)) {
            throw ScaleReadingParseException::for($raw);
        }

        $marker = config('weighing.tcp_ascii.stable_marker');
        $stable = is_string($marker) && $marker !== '' && str_contains(strtoupper($line), strtoupper($marker));

        return new ScaleReadingData(
            weightKg: round((float) $number * config()->float('weighing.tcp_ascii.unit_factor', 1.0), 3) + 0.0,
            stable: $stable,
            raw: $raw,
            readAt: $readAt ?? now()->toImmutable(),
        );
    }
}
