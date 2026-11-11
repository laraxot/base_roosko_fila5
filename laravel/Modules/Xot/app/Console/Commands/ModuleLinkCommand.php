<?php

declare(strict_types=1);

namespace Modules\Xot\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

use function Safe\readlink;
use function Safe\realpath;
use function Safe\symlink;

class ModuleLinkCommand extends Command
{
    protected $signature = 'xot:module-link
                            {--module= : Nome del modulo specifico da linkare}
                            {--dry-run : Mostra le operazioni senza eseguirle}
                            {--force : Ricrea il link se la destinazione esiste gia\'}';

    protected $description = 'Crea i symlink public_html/assets/{modulo} verso Modules/{Modulo}/public';

    public function handle(): int
    {
        $modulesPath = base_path('Modules');
        $docroot = base_path('../public_html/assets');
        $filter = $this->option('module');
        $dryRun = (bool) $this->option('dry-run');
        $force = (bool) $this->option('force');

        if (! File::isDirectory($modulesPath)) {
            $this->error("Directory moduli non trovata: {$modulesPath}");

            return self::FAILURE;
        }

        File::ensureDirectoryExists($docroot);

        $created = 0;
        $skipped = 0;

        foreach (File::directories($modulesPath) as $moduleDir) {
            if (! is_string($moduleDir)) {
                continue;
            }
            $module = basename($moduleDir);
            if (is_string($filter) && $filter !== '' && strcasecmp($module, $filter) !== 0) {
                continue;
            }

            $source = $moduleDir.DIRECTORY_SEPARATOR.'public';
            if (! File::isDirectory($source)) {
                $this->line("- {$module}: nessuna cartella public/, saltato");
                $skipped++;

                continue;
            }

            $link = $docroot.DIRECTORY_SEPARATOR.Str::lower($module);
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
            $resolved = str_starts_with($target, '/')
                ? realpath($target)
                : realpath(dirname($link).'/'.$target);
            if ($resolved === realpath($source) || $target === $source) {
                $this->line('- '.basename($link).": link gia' corretto");

                return 'skipped';
            }
            if (! $force) {
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

        $this->info(($dryRun ? '[dry-run] ' : '').'ln -s '.\Str::after($source, base_path()).' '.\Str::after($link, base_path('../')));
        if (! $dryRun) {
            symlink($source, $link);
        }

        return 'linked';
    }
}
