<?php

declare(strict_types=1);

namespace Modules\Xot\Console\Commands;

use Modules\Xot\Actions\File\CreateRelativeSymlinkAction;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

use function Safe\readlink;
use function Safe\realpath;
use function Safe\symlink;

class ThemeLinkCommand extends Command
{
    protected $signature = 'xot:theme-link
                            {--theme= : Nome del tema specifico da linkare}
                            {--dry-run : Mostra le operazioni senza eseguirle}
                            {--force : Ricrea il link se la destinazione esiste gia\'}';

    protected $description = 'Crea i symlink public_html/themes/{Theme} verso Themes/{Theme}/public';

    public function handle(): int
    {
        $themesPath = base_path('Themes');
        $docroot = base_path('../public_html/themes');
        $filter = $this->option('theme');
        $dryRun = (bool) $this->option('dry-run');
        $force = (bool) $this->option('force');

        if (! File::isDirectory($themesPath)) {
            $this->error("Directory temi non trovata: {$themesPath}");

            return self::FAILURE;
        }

        File::ensureDirectoryExists($docroot);

        $created = 0;
        $skipped = 0;

        foreach (File::directories($themesPath) as $themeDir) {
            if (! is_string($themeDir)) {
                continue;
            }
            $theme = basename($themeDir);
            if (is_string($filter) && $filter !== '' && strcasecmp($theme, $filter) !== 0) {
                continue;
            }

            $source = $themeDir.DIRECTORY_SEPARATOR.'public';
            if (! File::isDirectory($source)) {
                $this->line("- {$theme}: nessuna cartella public/, saltato");
                $skipped++;

                continue;
            }

            $link = $docroot.DIRECTORY_SEPARATOR.$theme;
            $result = $this->link($link, $source, $dryRun, $force);
            if ($result === 'linked') {
                $created++;
            } else {
                $skipped++;
            }
        }

        $this->info(($dryRun ? '[dry-run] ' : '')."Completato: {$created} link creati, {$skipped} saltati.");

        return self::SUCCESS;
    }

    private function link(string $link, string $source, bool $dryRun, bool $force): string
    {
        if (is_link($link)) {
            $target = (string) readlink($link);
            // \realpath nativo: false (non eccezione) se il link e' rotto.
            $resolved = str_starts_with($target, '/')
                ? \realpath($target)
                : \realpath(dirname($link).'/'.$target);
            if (app(CreateRelativeSymlinkAction::class)->isRelativeLinkTo($link, $source)) {
                $this->line('- '.basename($link).": link gia' corretto");

                return 'skipped';
            }
            // Stessa destinazione ma percorso assoluto: si riscrive relativo, senza bisogno di --force.
            $sameTarget = $resolved !== false && $resolved === \realpath($source);
            if (! $sameTarget && ! $force) {
                $this->warn('- '.basename($link).": link esistente punta a {$target}, usa --force per sostituirlo");

                return 'skipped';
            }
            if (! $dryRun) {
                File::delete($link);
            }
        } elseif (File::exists($link)) {
            if (! $force) {
                $this->warn('- '.basename($link).': esiste una cartella reale, usa --force per sostituirla');

                return 'skipped';
            }
            if (! $dryRun) {
                File::moveDirectory($link, $link.'.bak-'.date('YmdHis'));
            }
        }

        $relative = app(CreateRelativeSymlinkAction::class)->relativeTarget($source, $link);
        $this->info(($dryRun ? '[dry-run] ' : '').'ln -s '.$relative.' '.\Str::after($link, base_path('../')));
        if (! $dryRun) {
            symlink($relative, $link);
        }

        return 'linked';
    }
}
