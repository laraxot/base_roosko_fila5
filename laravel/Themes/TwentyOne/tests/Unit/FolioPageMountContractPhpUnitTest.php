<?php

declare(strict_types=1);

namespace Themes\TwentyOne\Tests\Unit;

use PHPUnit\Framework\TestCase;

use function Safe\file_get_contents;

/**
 * Contratto pagine Folio dinamiche TwentyOne (PHPUnit bridge per claude-audit static).
 *
 * @see docs/wiki/overviews/twentyone-theme.md
 */
final class FolioPageMountContractPhpUnitTest extends TestCase
{
    private string $themeRoot;

    protected function setUp(): void
    {
        parent::setUp();
        $this->themeRoot = dirname(__DIR__, 2);
    }

    public function test_container0_index_uses_filament_way_naming(): void
    {
        $indexPath = $this->themeRoot.'/resources/views/pages/[container0]/index.blade.php';
        $html = (string) file_get_contents($indexPath);

        self::assertStringContainsString("name('container0.index')", $html);
        self::assertStringContainsString("@volt('container0.index')", $html);
        self::assertStringContainsString("\$this->pageSlug = \$container0.'.index'", $html);
        self::assertStringNotContainsString('container0.list', $html);
        self::assertStringNotContainsString('CmsPage', $html);
    }

    public function test_container0_slug0_uses_container0_view(): void
    {
        $path = $this->themeRoot.'/resources/views/pages/[container0]/[slug0]/index.blade.php';
        $html = (string) file_get_contents($path);

        self::assertStringContainsString("name('container0.view')", $html);
        self::assertStringContainsString("@volt('container0.view')", $html);
        self::assertStringContainsString('ResolvePageAction', $html);
        self::assertStringNotContainsString('container0.detail', $html);
    }
}
