<?php

/**
 * Small view-layer helpers shared by the public site and the admin.
 */

if (! function_exists('media_url')) {
    /**
     * Resolves an image column to a usable src.
     *
     * Rows seeded from the original design hold absolute picsum URLs; anything
     * uploaded through the admin is stored as a path relative to public/, e.g.
     * "uploads/packages/mara.jpg". This lets both live side by side.
     */
    function media_url(?string $path, ?string $fallback = null): string
    {
        $path = trim((string) $path);

        if ($path === '') {
            return $fallback !== null ? media_url($fallback) : base_url('assets/img/placeholder.svg');
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://') || str_starts_with($path, '//')) {
            return $path;
        }

        return base_url(ltrim($path, '/'));
    }
}

if (! function_exists('money')) {
    /** "KES 25,000" — no decimals, since every price on the site is a whole shilling. */
    function money($amount, string $currency = 'KES'): string
    {
        return $currency . ' ' . number_format((float) $amount, 0);
    }
}

if (! function_exists('site')) {
    /** Shorthand for setting('Site.x') with a fallback. */
    function site(string $key, $default = '')
    {
        $value = setting('Site.' . $key);

        return ($value === null || $value === '') ? $default : $value;
    }
}

if (! function_exists('whatsapp_link')) {
    /**
     * Builds a wa.me deep link. Strips everything but digits from the number,
     * because wa.me rejects "+" and spaces.
     */
    function whatsapp_link(?string $message = null, ?string $number = null): string
    {
        $number = preg_replace('/\D+/', '', $number ?? (string) site('whatsappNumber', '254700000000'));
        $url    = 'https://wa.me/' . $number;

        if ($message !== null && $message !== '') {
            $url .= '?text=' . rawurlencode($message);
        }

        return $url;
    }
}

if (! function_exists('excerpt_of')) {
    /** Trims to a word boundary near $length and appends an ellipsis. */
    function excerpt_of(?string $text, int $length = 160): string
    {
        $text = trim(strip_tags((string) $text));

        if ($text === '' || mb_strlen($text) <= $length) {
            return $text;
        }

        $cut = mb_substr($text, 0, $length);
        $pos = mb_strrpos($cut, ' ');

        return rtrim($pos === false ? $cut : mb_substr($cut, 0, $pos), " .,;:") . '…';
    }
}

if (! function_exists('is_htmx')) {
    /** True when the current request came from htmx rather than a full page load. */
    function is_htmx(): bool
    {
        return service('request')->getHeaderLine('HX-Request') === 'true';
    }
}

if (! function_exists('nl2paras')) {
    /** Renders a plain-text block (double-newline separated) as <p> elements. */
    function nl2paras(?string $text, string $class = ''): string
    {
        $text = trim((string) $text);
        if ($text === '') {
            return '';
        }

        $attr = $class !== '' ? ' class="' . esc($class, 'attr') . '"' : '';
        $out  = '';

        foreach (preg_split('/\n\s*\n/', $text) as $para) {
            $para = trim($para);
            if ($para !== '') {
                $out .= '<p' . $attr . '>' . nl2br(esc($para)) . '</p>';
            }
        }

        return $out;
    }
}
