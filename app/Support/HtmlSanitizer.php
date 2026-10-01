<?php

declare(strict_types=1);

namespace App\Support;

class HtmlSanitizer
{
    /**
     * Allowed HTML tags for rich text editorial content.
     */
    public const ALLOWED_TAGS = '<p><br><strong><b><em><i><u><s><a><ul><ol><li><blockquote><code><pre><hr><img><table><thead><tbody><tr><th><td><span><div>';

    /**
     * Sanitize rich text HTML to prevent XSS while preserving editorial formatting.
     */
    public static function clean(?string $html): string
    {
        if (blank($html)) {
            return '';
        }

        // 1. Strip disallowed tags
        $clean = strip_tags($html, static::ALLOWED_TAGS);

        // 2. Remove script / event handler attributes (e.g. onclick, onerror, onload)
        $clean = (string) preg_replace('/\s+on[a-zA-Z]+\s*=\s*(["\']).*?\1/i', '', $clean);
        $clean = (string) preg_replace('/\s+on[a-zA-Z]+\s*=\s*[^"\'>\s]+/i', '', $clean);

        // 3. Remove javascript: and vbscript: URIs
        $clean = (string) preg_replace('/href\s*=\s*(["\'])\s*(javascript|vbscript|data):.*?\1/i', 'href="#"', $clean);
        $clean = (string) preg_replace('/src\s*=\s*(["\'])\s*(javascript|vbscript):.*?\1/i', '', $clean);

        return trim($clean);
    }
}
