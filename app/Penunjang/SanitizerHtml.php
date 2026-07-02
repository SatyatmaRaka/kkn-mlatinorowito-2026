<?php

namespace App\Penunjang;

use HTMLPurifier;
use HTMLPurifier_Config;

/**
 * Sanitasi konten HTML rich-text kegiatan menggunakan HTMLPurifier.
 * Mencegah XSS dari editor konten di panel CMS.
 */
class SanitizerHtml
{
    public static function sanitize(?string $html): ?string
    {
        if ($html === null || trim($html) === '') {
            return $html;
        }

        $config = HTMLPurifier_Config::createDefault();
        $config->set('HTML.Allowed', 'p,br,strong,em,ul,ol,li,h3,h4,a[href|title],img[src|alt|width|height]');
        $config->set('URI.AllowedSchemes', ['http' => true, 'https' => true, 'mailto' => true]);

        $purifier = new HTMLPurifier($config);

        return $purifier->purify($html);
    }
}
