<?php

declare(strict_types=1);

namespace App\Support;

use Symfony\Component\HtmlSanitizer\HtmlSanitizer as SymfonySanitizer;
use Symfony\Component\HtmlSanitizer\HtmlSanitizerConfig;
use Throwable;

class HtmlSanitizer
{
    private static ?SymfonySanitizer $sanitizer = null;

    /**
     * Allowed HTML tags for rich text editorial content (regex fallback).
     */
    public const ALLOWED_TAGS = '<p><br><strong><b><em><i><u><s><a><ul><ol><li><blockquote><code><pre><hr><img><table><thead><tbody><tr><th><td><span><div><h1><h2><h3><h4><h5><h6>';

    /**
     * Sanitize rich text HTML to neutralize XSS vectors while preserving editorial formatting.
     */
    public static function clean(?string $html): string
    {
        if (blank($html)) {
            return '';
        }

        try {
            if (self::$sanitizer === null) {
                $config = (new HtmlSanitizerConfig)
                    ->allowSafeElements()
                    ->allowRelativeLinks()
                    ->allowRelativeMedias()
                    ->allowAttribute('class', ['*'])
                    ->allowAttribute('id', ['*'])
                    ->allowAttribute('target', ['a'])
                    ->allowAttribute('rel', ['a'])
                    ->allowAttribute('loading', ['img'])
                    ->allowAttribute('alt', ['img'])
                    ->allowAttribute('title', ['img', 'a'])
                    ->allowAttribute('width', ['img'])
                    ->allowAttribute('height', ['img'])
                    ->forceAttribute('a', 'rel', 'noopener noreferrer');

                self::$sanitizer = new SymfonySanitizer($config);
            }

            $sanitized = self::$sanitizer->sanitize($html);
        } catch (Throwable) {
            // Robust fallback if sanitizer component is not available or throws
            $sanitized = strip_tags($html, static::ALLOWED_TAGS);
        }

        // Secondary defense in depth against malformed attributes and javascript URI schemes
        $sanitized = (string) preg_replace('/\s+on[a-zA-Z]+\s*=\s*(["\']).*?\1/i', '', $sanitized);
        $sanitized = (string) preg_replace('/\s+on[a-zA-Z]+\s*=\s*[^"\'>\s]+/i', '', $sanitized);
        $sanitized = (string) preg_replace('/href\s*=\s*(["\'])\s*(javascript|vbscript|data):.*?\1/i', 'href="#"', $sanitized);
        $sanitized = (string) preg_replace('/src\s*=\s*(["\'])\s*(javascript|vbscript):.*?\1/i', '', $sanitized);

        return trim($sanitized);
    }
}
