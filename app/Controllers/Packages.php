<?php

namespace App\Controllers;

use App\Models\CategoryModel;
use App\Models\DestinationModel;
use App\Models\EnquiryModel;
use App\Models\PackageImageModel;
use App\Models\PackageInclusionModel;
use App\Models\PackageModel;
use App\Models\TourTypeModel;

class Packages extends BaseController
{
    private const PER_PAGE = 9;

    /** Query-string keys the filter understands. */
    private const FILTER_KEYS = ['category', 'destination', 'tour_type', 'price', 'duration', 'q', 'sort'];

    /**
     * Full packages page. The results region is also reachable on its own at
     * /packages/filter for htmx swaps — see filter() below.
     */
    public function index(): string
    {
        return view('pages/packages', $this->buildData());
    }

    /**
     * The results region only. htmx swaps this into #packages-results, so the
     * response is a fragment rather than a full document.
     *
     * Falls back to rendering the whole page for a direct (non-htmx) hit, so
     * the URLs pushed into history stay shareable and crawlable.
     */
    public function filter(): string
    {
        $data = $this->buildData();

        if (! is_htmx()) {
            return view('pages/packages', $data);
        }

        // htmx would otherwise push this endpoint's own URL — including the
        // empty params the form serialises. Push the canonical /packages?…
        // instead, so what lands in the address bar is what a visitor would
        // share, and reloading it renders the full page.
        $this->response->setHeader(
            'HX-Push-Url',
            url_to('packages') . $this->queryString($data['filters'])
        );

        return view('packages/_results', $data);
    }

    /** Collects filter input, runs the query and assembles everything the views need. */
    private function buildData(): array
    {
        $filters = [];
        foreach (self::FILTER_KEYS as $key) {
            $filters[$key] = trim((string) $this->request->getGet($key));
        }

        $model = new PackageModel();
        $page  = max(1, (int) $this->request->getGet('page'));

        $packages = $model->filtered($filters)->paginate(self::PER_PAGE, 'default', $page);
        $pager    = $model->pager;

        $categories   = (new CategoryModel())->active();
        $destinations = (new DestinationModel())->active();
        $tourTypes    = (new TourTypeModel())->active();

        return [
            'title'           => 'Tours & Packages — ' . site('companyName'),
            'metaDescription' => "Browse Beyond Borders Adventures' Kenya tours — safaris, beach holidays, adventures, cultural experiences and corporate retreats, all clearly priced.",
            'activeNav'       => 'packages',
            'packages'        => $packages,
            'pager'           => $pager,
            'total'           => $pager->getTotal('default'),
            'filters'         => $filters,
            'activeFilters'   => $this->describeFilters($filters, $categories, $destinations, $tourTypes),
            'categories'      => $categories,
            'destinations'    => $destinations,
            'tourTypes'       => $tourTypes,
            'priceRanges'     => PackageModel::PRICE_RANGES,
            'durationRanges'  => PackageModel::DURATION_RANGES,
            'sorts'           => PackageModel::SORTS,
        ];
    }

    /**
     * Turns the raw filter values into the labelled chips shown above the
     * results, each with the URL that removes just that one filter.
     */
    private function describeFilters(array $filters, array $categories, array $destinations, array $tourTypes): array
    {
        $lookup = static function (array $rows, string $value): ?string {
            foreach ($rows as $row) {
                if ($row['slug'] === $value || (string) $row['id'] === $value) {
                    return $row['name'];
                }
            }

            return null;
        };

        $chips = [];

        foreach ([
            'category'    => $categories,
            'destination' => $destinations,
            'tour_type'   => $tourTypes,
        ] as $key => $rows) {
            if ($filters[$key] !== '' && ($name = $lookup($rows, $filters[$key])) !== null) {
                $chips[] = ['key' => $key, 'label' => $name];
            }
        }

        if (isset(PackageModel::PRICE_RANGES[$filters['price']])) {
            $chips[] = ['key' => 'price', 'label' => PackageModel::PRICE_RANGES[$filters['price']]['label']];
        }

        if (isset(PackageModel::DURATION_RANGES[$filters['duration']])) {
            $chips[] = ['key' => 'duration', 'label' => PackageModel::DURATION_RANGES[$filters['duration']]['label']];
        }

        if ($filters['q'] !== '') {
            $chips[] = ['key' => 'q', 'label' => '“' . $filters['q'] . '”'];
        }

        // Attach the "remove this one" URL now that we know the full set.
        foreach ($chips as $i => $chip) {
            $remaining = $filters;
            $remaining[$chip['key']] = '';
            $chips[$i]['removeUrl'] = url_to('packages') . $this->queryString($remaining);
        }

        return $chips;
    }

    /** Builds "?a=1&b=2" from the filter array, dropping empty values. */
    private function queryString(array $filters): string
    {
        $params = array_filter($filters, static fn ($v) => $v !== '' && $v !== null);

        return $params === [] ? '' : '?' . http_build_query($params);
    }

    /**
     * Search-as-you-type suggestions. Returns a fragment for the combobox
     * dropdown: matching trips first, then destination / category / tour-type
     * shortcuts. An empty response collapses the dropdown client-side.
     *
     * A direct (non-htmx) hit is bounced to the packages page with the same
     * keyword, so the endpoint never renders a bare fragment to a visitor.
     */
    public function suggest()
    {
        $q = trim((string) $this->request->getGet('q'));

        if (! is_htmx()) {
            return redirect()->to(url_to('packages') . ($q !== '' ? '?q=' . urlencode($q) : ''));
        }

        if (mb_strlen($q) < 2) {
            return '';
        }

        $packages = (new PackageModel())->filtered(['q' => $q])->findAll(5);

        // The taxonomies are small; match them in PHP rather than three queries.
        $match = static fn (array $rows): array => array_values(array_filter(
            $rows,
            static fn (array $row): bool => mb_stripos($row['name'], $q) !== false
        ));

        return view('packages/_suggest', [
            'q'            => $q,
            'packages'     => $packages,
            'destinations' => $match((new DestinationModel())->active()),
            'categories'   => $match((new CategoryModel())->active()),
            'tourTypes'    => $match((new TourTypeModel())->active()),
        ]);
    }

    /** Single package detail page. */
    public function show(string $slug): string
    {
        $package = (new PackageModel())->findBySlug($slug);

        if ($package === null) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound(
                'No package found with the slug "' . $slug . '".'
            );
        }

        return view('pages/package_detail', [
            'title'           => $package['title'] . ' — ' . site('companyName'),
            'metaDescription' => excerpt_of($package['summary'], 155),
            'activeNav'       => 'packages',
            'package'         => $package,
            'inclusions'      => (new PackageInclusionModel())->grouped((int) $package['id']),
            'images'          => (new PackageImageModel())->forPackage((int) $package['id']),
            'related'         => (new PackageModel())->related($package, 3),
        ]);
    }

    /**
     * Booking enquiry from the package detail page. Always returns a fragment —
     * the form posts through htmx and swaps itself for the result.
     */
    public function enquire(string $slug): string
    {
        $package = (new PackageModel())->findBySlug($slug);

        if ($package === null) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Unknown package.');
        }

        // Honeypot: a real browser leaves this empty because it is off-screen.
        if (trim((string) $this->request->getPost('website')) !== '') {
            return view('partials/form_success', [
                'heading' => 'Thank you',
                'message' => 'Your enquiry has been received.',
            ]);
        }

        $rules = EnquiryModel::RULES['booking'];

        if (! $this->validate($rules)) {
            return view('packages/_booking_form', [
                'package' => $package,
                'errors'  => $this->validator->getErrors(),
                'old'     => $this->request->getPost(),
            ]);
        }

        (new EnquiryModel())->insert([
            'type'         => 'booking',
            'package_id'   => $package['id'],
            'name'         => $this->request->getPost('name'),
            'email'        => $this->request->getPost('email'),
            'phone'        => $this->request->getPost('phone'),
            'subject'      => 'Booking enquiry: ' . $package['title'],
            'message'      => $this->request->getPost('message'),
            'people'       => $this->request->getPost('people') ?: null,
            'travel_dates' => $this->request->getPost('travel_dates'),
            'status'       => 'new',
            'ip_address'   => $this->request->getIPAddress(),
            'user_agent'   => substr((string) $this->request->getUserAgent(), 0, 255),
        ]);

        return view('partials/form_success', [
            'heading' => 'Enquiry sent',
            'message' => 'Thank you — we have your enquiry for <strong>' . esc($package['title'])
                . '</strong> and will come back to you with availability and a written quote within 24 hours.',
            'raw'     => true,
        ]);
    }
}
