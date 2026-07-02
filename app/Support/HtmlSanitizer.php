<?php

namespace App\Support;

class HtmlSanitizer
{
    private const ALLOWED_TAGS = '<p><br><strong><em><ul><ol><li><h3><h4><a><img>';

    /**
     * Sanitize rich HTML from the editor: whitelist safe tags, strip scripts,
     * and remove dangerous attributes (event handlers, javascript:/data: URLs).
     */
    public static function sanitize(?string $html): ?string
    {
        if ($html === null || trim($html) === '') {
            return $html;
        }

        $clean = strip_tags($html, self::ALLOWED_TAGS);

        $clean = preg_replace('/\s*on\w+\s*=\s*("[^"]*"|\'[^\']*\'|[^\s>]+)/i', '', $clean) ?? $clean;
        $clean = preg_replace('/\s*(href|src)\s*=\s*"(javascript:|data:)[^"]*"/i', '', $clean) ?? $clean;
        $clean = preg_replace("/\s*(href|src)\s*=\s*'(javascript:|data:)[^']*'/i", '', $clean) ?? $clean;

        return $clean;
    }
}
