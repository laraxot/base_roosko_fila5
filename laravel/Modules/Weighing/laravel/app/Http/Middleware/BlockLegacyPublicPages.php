<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Keeps internal showcase pages out of the public guest surface.
 *
 * The source views remain available to tests and documentation, but they must
 * not be reachable as civic-public pages or indexed as demo content.
 */
final class BlockLegacyPublicPages
{
    /**
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $segments = $request->segments();
        $supportedLocales = array_keys((array) config('laravellocalization.supportedLocales', []));

        if ($segments !== [] && in_array($segments[0], $supportedLocales, true)) {
            array_shift($segments);
        }

        $path = implode('/', $segments);
        $blocked = [
            'prova',
            'prova01',
            'counter',
            'show',
            'bootstrap-italia-showcase',
            'homepage',
            'auth/login1',
            'auth/login2',
            'auth/login3',
            'auth/login4',
            'auth/login5',
        ];

        foreach ($blocked as $prefix) {
            if ($path === $prefix || str_starts_with($path, $prefix.'/')) {
                abort(Response::HTTP_NOT_FOUND);
            }
        }

        return $next($request);
    }
}
