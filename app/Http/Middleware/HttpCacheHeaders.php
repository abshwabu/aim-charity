<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HttpCacheHeaders
{
    /**
     * Handle an incoming request and apply HTTP caching headers for public GET responses.
     */
    public function handle(Request $request, Closure $next): Response
    {
        /** @var Response $response */
        $response = $next($request);

        // Only cache successful public GET and HEAD responses outside admin
        if (! $request->isMethodSafe() || $request->is('admin*') || $request->is('livewire*')) {
            return $response;
        }

        if ($response->getStatusCode() === 200) {
            $content = $response->getContent();

            if (is_string($content) && $content !== '') {
                $etag = '"'.md5($content).'"';
                $response->headers->set('ETag', $etag);

                // Conditional GET (304 Not Modified)
                $ifNoneMatch = $request->header('If-None-Match') ?? $request->headers->get('If-None-Match');
                if (filled($ifNoneMatch)) {
                    $cleanIfNoneMatch = trim((string) $ifNoneMatch, " \t\n\r\0\x0B\"W/");
                    $cleanEtag = trim($etag, '"');

                    if ($cleanIfNoneMatch === $cleanEtag) {
                        $response->setStatusCode(304);
                        $response->setContent('');

                        return $response;
                    }
                }
            }

            // Public caching headers (5 minutes browser cache, 10 min stale-while-revalidate)
            $response->headers->set('Cache-Control', 'public, max-age=300, stale-while-revalidate=600');
        }

        return $response;
    }
}
