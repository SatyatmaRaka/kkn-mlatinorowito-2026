<?php

namespace App\Support;

use HTMLPurifier;
use HTMLPurifier_Config;

class HtmlSanitizer
{
    /**
     * Sanitize rich HTML from the editor using HTMLPurifier library.
     */
    public static function sanitize(?string $html): ?string
    {
        if ($html === null || trim($html) === '') {
            return $html;
        }

        $config = HTMLPurifier_Config::createDefault();
        
        // Konfigurasi tag dan atribut yang diizinkan (mirip dengan ALLOWED_TAGS sebelumnya)
        $config->set('HTML.Allowed', 'p,br,strong,em,ul,ol,li,h3,h4,a[href|title],img[src|alt|width|height]');
        
        // Keamanan tambahan: cegah loading resource eksternal jika diinginkan atau skema URI yang tidak aman
        $config->set('URI.AllowedSchemes', ['http' => true, 'https' => true, 'mailto' => true]);

        $purifier = new HTMLPurifier($config);

        return $purifier->purify($html);
    }
}
