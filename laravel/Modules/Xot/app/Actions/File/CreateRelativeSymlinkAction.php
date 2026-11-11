<?php

declare(strict_types=1);

namespace Modules\Xot\Actions\File;

use Webmozart\Assert\Assert;

/**
 * Crea un symlink con destinazione RELATIVA alla cartella del link, per esempio
 * `public_html/themes/TwentyOne` → `../../laravel/Themes/TwentyOne/public`.
 *
 * Un link assoluto (`/home/utente/progetto/laravel/...`) si rompe appena il progetto cambia
 * percorso: altro utente o home, container, cartella di release, copia da sviluppo a produzione.
 * Quello relativo dipende solo dalla struttura del repository, quindi funziona ovunque e si può
 * anche versionare.
 */
class CreateRelativeSymlinkAction
{
    /**
     * @return string la destinazione relativa scritta nel link
     */
    public function execute(string $source, string $link): string
    {
        $relative = $this->relativeTarget($source, $link);
        symlink($relative, $link);

        return $relative;
    }

    /**
     * Percorso di `$source` visto dalla cartella che contiene `$link`.
     */
    public function relativeTarget(string $source, string $link): string
    {
        $from = realpath(\dirname($link));
        $to = realpath($source);
        Assert::string($from, 'Cartella del link inesistente: '.\dirname($link));
        Assert::string($to, 'Destinazione del link inesistente: '.$source);

        $fromParts = array_values(array_filter(explode(DIRECTORY_SEPARATOR, $from), static fn (string $part): bool => $part !== ''));
        $toParts = array_values(array_filter(explode(DIRECTORY_SEPARATOR, $to), static fn (string $part): bool => $part !== ''));

        $common = 0;
        $max = min(\count($fromParts), \count($toParts));
        while ($common < $max && $fromParts[$common] === $toParts[$common]) {
            $common++;
        }

        $up = array_fill(0, \count($fromParts) - $common, '..');
        $down = \array_slice($toParts, $common);
        $parts = [...$up, ...$down];

        return $parts === [] ? '.' : implode(DIRECTORY_SEPARATOR, $parts);
    }

    /**
     * Vero se il link esiste, punta a `$source` ed è già relativo.
     */
    public function isRelativeLinkTo(string $link, string $source): bool
    {
        if (! is_link($link)) {
            return false;
        }
        $target = readlink($link);
        if (! \is_string($target) || str_starts_with($target, DIRECTORY_SEPARATOR)) {
            return false;
        }

        return realpath(\dirname($link).DIRECTORY_SEPARATOR.$target) === realpath($source);
    }
}
