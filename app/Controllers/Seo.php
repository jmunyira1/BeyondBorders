<?php

namespace App\Controllers;

use App\Models\PackageModel;
use App\Models\PostModel;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Crawlable helper files: robots.txt, sitemap.xml and llms.txt.
 *
 * All three are generated so they always reflect the live catalogue and the
 * current base URL (which changes between local and production). The static
 * public/robots.txt was removed so these routes take over.
 */
class Seo extends BaseController
{
    /** Search + AI crawlers welcome; admin blocked; points at the sitemap. */
    public function robots(): ResponseInterface
    {
        $lines = [
            'User-agent: *',
            'Allow: /',
            'Disallow: /admin',
            'Disallow: /login',
            'Disallow: /logout',
            '',
            '# AI assistants are welcome to read and cite our content',
        ];

        foreach ([
            'GPTBot', 'ChatGPT-User', 'OAI-SearchBot', 'ClaudeBot', 'Claude-Web',
            'anthropic-ai', 'PerplexityBot', 'Perplexity-User', 'Google-Extended',
            'Applebot-Extended', 'CCBot', 'Amazonbot',
        ] as $bot) {
            $lines[] = 'User-agent: ' . $bot;
            $lines[] = 'Allow: /';
            $lines[] = '';
        }

        $lines[] = 'Sitemap: ' . base_url('sitemap.xml');

        return $this->response
            ->setContentType('text/plain; charset=UTF-8')
            ->setBody(implode("\n", $lines) . "\n");
    }

    /** XML sitemap of every public page, package and post. */
    public function sitemap(): ResponseInterface
    {
        $urls = [];
        $add  = static function (string $loc, ?string $lastmod, string $freq, string $priority) use (&$urls): void {
            $urls[] = [
                'loc'      => $loc,
                'lastmod'  => $lastmod ? date('Y-m-d', strtotime($lastmod)) : null,
                'freq'     => $freq,
                'priority' => $priority,
            ];
        };

        $add(base_url(), null, 'daily', '1.0');
        $add(url_to('packages'), null, 'daily', '0.9');
        $add(url_to('custom-trips'), null, 'monthly', '0.8');
        $add(url_to('about'), null, 'monthly', '0.5');
        $add(url_to('gallery'), null, 'monthly', '0.5');
        $add(url_to('blog'), null, 'weekly', '0.6');
        $add(url_to('contact'), null, 'monthly', '0.5');
        $add(url_to('terms'), null, 'yearly', '0.2');
        $add(url_to('privacy'), null, 'yearly', '0.2');

        foreach ((new PackageModel())->where('is_active', 1)->orderBy('sort_order', 'ASC')->findAll() as $p) {
            $add(url_to('package', $p['slug']), $p['updated_at'] ?? null, 'weekly', '0.8');
        }

        foreach ((new PostModel())
            ->where('is_published', 1)
            ->where('published_at <=', date('Y-m-d H:i:s'))
            ->orderBy('published_at', 'DESC')
            ->findAll() as $post) {
            $add(url_to('post', $post['slug']), $post['updated_at'] ?? $post['published_at'], 'monthly', '0.6');
        }

        $xml  = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        foreach ($urls as $u) {
            $xml .= '  <url>' . "\n";
            $xml .= '    <loc>' . htmlspecialchars($u['loc'], ENT_XML1) . '</loc>' . "\n";
            if ($u['lastmod'] !== null) {
                $xml .= '    <lastmod>' . $u['lastmod'] . '</lastmod>' . "\n";
            }
            $xml .= '    <changefreq>' . $u['freq'] . '</changefreq>' . "\n";
            $xml .= '    <priority>' . $u['priority'] . '</priority>' . "\n";
            $xml .= '  </url>' . "\n";
        }
        $xml .= '</urlset>' . "\n";

        return $this->response
            ->setContentType('application/xml; charset=UTF-8')
            ->setBody($xml);
    }

    /**
     * llms.txt — a curated, markdown map of the site for AI assistants.
     * See https://llmstxt.org/. Kept factual and current so models cite us right.
     */
    public function llms(): ResponseInterface
    {
        $name = site('companyName');
        $out  = [];
        $out[] = '# ' . $name;
        $out[] = '';
        $out[] = '> Kenyan travel and adventure company offering safaris, beach holidays, mountain treks, cultural experiences and tailor-made trips for local and international travellers. Every trip is clearly priced and organised end to end.';
        $out[] = '';
        $out[] = '- Based in ' . site('address', 'Nairobi, Kenya') . ', serving all of Kenya.';
        $out[] = '- Contact: ' . site('email') . ' · ' . site('phone') . ' (phone / WhatsApp).';
        $out[] = '- Booking is enquiry-based: request a trip, receive an itinerary and quote within 24 hours, then pay by M-Pesa or bank transfer.';
        $out[] = '';

        $out[] = '## Main pages';
        $out[] = '- [Tours & Packages](' . url_to('packages') . '): browse every ready-to-book trip, filterable by type, budget and duration.';
        $out[] = '- [Custom Trips](' . url_to('custom-trips') . '): tell us the occasion, group and budget and we plan the whole journey.';
        $out[] = '- [About](' . url_to('about') . '): who we are, our vision and mission.';
        $out[] = '- [Gallery](' . url_to('gallery') . '): photographs from past trips.';
        $out[] = '- [Journal](' . url_to('blog') . '): travel notes and planning guides.';
        $out[] = '- [Contact](' . url_to('contact') . '): phone, email, WhatsApp and FAQs.';
        $out[] = '';

        $packages = (new PackageModel())->where('is_active', 1)->orderBy('is_featured', 'DESC')->orderBy('sort_order', 'ASC')->findAll();
        if ($packages !== []) {
            $out[] = '## Tours & packages';
            foreach ($packages as $p) {
                $meta = array_filter([
                    $p['duration_label'] ?? null,
                    ($p['price'] ?? null) !== null && (float) $p['price'] > 0 ? 'from ' . money($p['price'], $p['currency'] ?: 'KES') : null,
                ]);
                $line = '- [' . $p['title'] . '](' . url_to('package', $p['slug']) . ')';
                if ($meta !== []) {
                    $line .= ' — ' . implode(', ', $meta);
                }
                if (! empty($p['summary'])) {
                    $line .= ': ' . excerpt_of($p['summary'], 140);
                }
                $out[] = $line;
            }
            $out[] = '';
        }

        $posts = (new PostModel())
            ->where('is_published', 1)
            ->where('published_at <=', date('Y-m-d H:i:s'))
            ->orderBy('published_at', 'DESC')
            ->findAll(20);
        if ($posts !== []) {
            $out[] = '## Journal articles';
            foreach ($posts as $post) {
                $line = '- [' . $post['title'] . '](' . url_to('post', $post['slug']) . ')';
                if (! empty($post['excerpt'])) {
                    $line .= ': ' . excerpt_of($post['excerpt'], 140);
                }
                $out[] = $line;
            }
            $out[] = '';
        }

        return $this->response
            ->setContentType('text/plain; charset=UTF-8')
            ->setBody(implode("\n", $out));
    }
}
