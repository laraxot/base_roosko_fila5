<?php

/**
 * ---.
 */

declare(strict_types=1);

namespace App;

class Application extends \Illuminate\Foundation\Application
{
    public function publicPath($path = ''): string
    {
        // The web deployment keeps the document root beside Laravel as
        // `public_html`; NativePHP bundles Laravel inside the device sandbox
        // and has no sibling document root. Keep both runtimes valid.
        $webPublicPath = $this->basePath.'/../public_html/'.$path;
        $webPublicPath = str_replace(['/', '\\'], [DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR], $webPublicPath);
        $webPublicRoot = realpath($this->basePath.'/../public_html');
        if ($webPublicRoot !== false) {
            return realpath($webPublicPath) ?: $webPublicRoot.DIRECTORY_SEPARATOR.$path;
        }

        // NativePHP bundles theme assets under the theme-owned public folder.
        // Laravel's Vite helper still asks for public_path('themes/Sixteen/*'),
        // so resolve that virtual web path to the bundled asset root.
        $nativeThemePrefix = 'themes/Sixteen';
        $requestedPath = ltrim((string) $path, DIRECTORY_SEPARATOR);
        if (str_starts_with($requestedPath, $nativeThemePrefix)) {
            $themeRelativePath = ltrim(substr($requestedPath, strlen($nativeThemePrefix)), DIRECTORY_SEPARATOR);
            $themePublicPath = $this->basePath.'/Themes/Sixteen/public';

            return rtrim($themePublicPath, DIRECTORY_SEPARATOR)
                .($themeRelativePath !== '' ? DIRECTORY_SEPARATOR.$themeRelativePath : '');
        }

        return rtrim($this->basePath.'/public', DIRECTORY_SEPARATOR).($path !== '' ? DIRECTORY_SEPARATOR.ltrim((string) $path, DIRECTORY_SEPARATOR) : '');
    }
}
