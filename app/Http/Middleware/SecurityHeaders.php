<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Attach strict security headers to all HTTP responses.
     */
    public function handle(Request $request, Closure $next): Response
    {
        /** @var Response $response */
        $response = $next($request);

        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');

        $cspDirectives = [
            "default-src 'self'",
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://fonts.googleapis.com https://www.googletagmanager.com https://challenges.cloudflare.com https://www.google.com/recaptcha/ https://www.gstatic.com/recaptcha/",
            "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com",
            "font-src 'self' https://fonts.gstatic.com data:",
            "img-src 'self' data: https: http: blob:",
            "frame-src 'self' https://www.youtube.com https://www.youtube-nocookie.com https://player.vimeo.com https://www.google.com https://maps.google.com https://challenges.cloudflare.com https://www.google.com/recaptcha/",
            "connect-src 'self' https: ws: wss:",
            "object-src 'none'",
            "base-uri 'self'",
            "form-action 'self'",
        ];

        $response->headers->set('Content-Security-Policy', implode('; ', $cspDirectives));

        return $response;
    }
}
