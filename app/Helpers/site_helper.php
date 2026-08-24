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

if (! function_exists('wordmark')) {
    /**
     * The branded logotype, built from the editable company name so changing
     * it in Admin → Settings updates every instance (nav, footer, admin).
     *
     * With no suffix, the last word is set small — "Beyond Borders <small>Adventures</small>".
     * Pass a suffix to append a fixed small label instead — wordmark('Admin')
     * gives the full name plus a small "Admin".
     *
     * Returns safe HTML (dynamic parts escaped); echo it raw.
     */
    function wordmark(?string $suffix = null): string
    {
        $name = trim((string) site('companyName', 'MOROP GAA Tours and Travel'));
        if ($name === '') {
            $name = 'MOROP GAA Tours and Travel';
        }

        if ($suffix !== null && $suffix !== '') {
            return esc($name) . '<small>' . esc($suffix) . '</small>';
        }

        // An admin-defined subtitle sets the small line explicitly. The big
        // line is the company name with that subtitle trimmed off the end when
        // it appears there — so companyName "MOROP GAA Tours and Travel" with
        // subtitle "Tours and Travel" renders "MOROP GAA" over "Tours and Travel".
        $subtitle = trim((string) site('wordmarkSubtitle', ''));
        if ($subtitle !== '') {
            $main = $name;
            $tail = mb_substr($name, -mb_strlen($subtitle));
            if (mb_strtolower($tail) === mb_strtolower($subtitle)) {
                $main = rtrim(mb_substr($name, 0, mb_strlen($name) - mb_strlen($subtitle)));
            }
            if ($main === '') {
                $main = $name;
            }

            return esc($main) . '<small>' . esc($subtitle) . '</small>';
        }

        // Default: set the last word small.
        $words = preg_split('/\s+/', $name);
        if (count($words) < 2) {
            return esc($name);
        }

        $small = array_pop($words);

        return esc(implode(' ', $words)) . '<small>' . esc($small) . '</small>';
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
