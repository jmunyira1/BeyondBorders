<?php

/**
 * SEO / AEO helper — Open Graph, canonical and schema.org JSON-LD builders.
 *
 * The JSON-LD blocks are what makes the site legible to both search engines and
 * AI assistants (Google, Bing, ChatGPT, Perplexity, Claude): they describe the
 * business, its trips and its content as structured data.
 */

if (! function_exists('abs_url')) {
    /** Turn a possibly-relative path (e.g. media_url output) into an absolute URL. */
    function abs_url(?string $url): string
    {
        $url = (string) $url;
        if ($url === '') {
            return base_url();
        }
        if (preg_match('~^https?://~i', $url)) {
            return $url;
        }

        return rtrim(base_url(), '/') . '/' . ltrim($url, '/');
    }
}

if (! function_exists('json_ld')) {
    /**
     * Render one schema.org array (or a list of them) as a JSON-LD <script>.
     * HEX flags prevent a "</script>" inside any value from breaking out.
     */
    function json_ld($schema): string
    {
        if (empty($schema)) {
            return '';
        }

        return '<script type="application/ld+json">'
            . json_encode(
                $schema,
                JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT
            )
            . '</script>';
    }
}

if (! function_exists('seo_logo_url')) {
    function seo_logo_url(): string
    {
        return abs_url(media_url(site('logo'), 'assets/img/logo-nav.png'));
    }
}

if (! function_exists('seo_org_schema')) {
    /** The business itself — TravelAgency (a LocalBusiness subtype). */
    function seo_org_schema(): array
    {
        $sameAs = array_values(array_filter(
            [site('instagram'), site('facebook'), site('tiktok'), site('twitter')],
            static fn ($u): bool => is_string($u) && $u !== '' && $u !== '#'
        ));

        $schema = [
            '@context'    => 'https://schema.org',
            '@type'       => 'TravelAgency',
            '@id'         => base_url() . '#organization',
            'name'        => site('companyName'),
            'url'         => base_url(),
            'logo'        => seo_logo_url(),
            'image'       => seo_logo_url(),
            'description' => 'Kenyan travel and adventure company offering safaris, beach holidays, mountain treks, cultural experiences and tailor-made trips for local and international travellers.',
            'email'       => site('email'),
            'telephone'   => site('phoneLink'),
            'priceRange'  => 'KES',
            'areaServed'  => ['@type' => 'Country', 'name' => 'Kenya'],
            'address'     => [
                '@type'           => 'PostalAddress',
                'addressLocality' => site('address', 'Nairobi'),
                'addressCountry'  => 'KE',
            ],
        ];

        if ($sameAs !== []) {
            $schema['sameAs'] = $sameAs;
        }

        return $schema;
    }
}

if (! function_exists('seo_website_schema')) {
    /** The website, with a SearchAction so engines can offer a direct search box. */
    function seo_website_schema(): array
    {
        return [
            '@context'        => 'https://schema.org',
            '@type'           => 'WebSite',
            '@id'             => base_url() . '#website',
            'name'            => site('companyName'),
            'url'             => base_url(),
            'publisher'       => ['@id' => base_url() . '#organization'],
            'inLanguage'      => 'en',
            'potentialAction' => [
                '@type'       => 'SearchAction',
                'target'      => [
                    '@type'       => 'EntryPoint',
                    'urlTemplate' => base_url('packages') . '?q={search_term_string}',
                ],
                'query-input' => 'required name=search_term_string',
            ],
        ];
    }
}

if (! function_exists('seo_breadcrumb_schema')) {
    /**
     * BreadcrumbList from an ordered [label => absoluteUrl] map.
     */
    function seo_breadcrumb_schema(array $crumbs): array
    {
        $items = [];
        $i     = 1;
        foreach ($crumbs as $name => $url) {
            $items[] = [
                '@type'    => 'ListItem',
                'position' => $i++,
                'name'     => $name,
                'item'     => $url,
            ];
        }

        return [
            '@context'        => 'https://schema.org',
            '@type'           => 'BreadcrumbList',
            'itemListElement' => $items,
        ];
    }
}
