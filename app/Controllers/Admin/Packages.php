<?php

namespace App\Controllers\Admin;

use App\Models\CategoryModel;
use App\Models\DestinationModel;
use App\Models\PackageDepartureModel;
use App\Models\PackageInclusionModel;
use App\Models\PackageModel;
use App\Models\TourTypeModel;

class Packages extends AdminController
{
    private const PER_PAGE = 20;

    public function index(): string
    {
        return view('admin/packages/index', $this->layout($this->listData() + [
            'title'       => 'Packages',
            'heading'     => 'Packages',
            'subheading'  => 'The tours shown on the public site and returned by the filter.',
            'activeAdmin' => 'packages',
        ]));
    }

    public function list(): string
    {
        return view('admin/packages/_table', $this->listData());
    }

    private function listData(): array
    {
        $model = new PackageModel();

        $filters = [
            'q'        => trim((string) $this->request->getGet('q')),
            'category' => (string) $this->request->getGet('category'),
            'status'   => (string) $this->request->getGet('status'),
        ];

        $builder = $model->select('packages.*, categories.name AS category_name, destinations.name AS destination_name')
            ->join('categories', 'categories.id = packages.category_id', 'left')
            ->join('destinations', 'destinations.id = packages.destination_id', 'left')
            ->orderBy('packages.sort_order', 'ASC')
            ->orderBy('packages.id', 'ASC');

        if ($filters['q'] !== '') {
            $builder->like('packages.title', $filters['q']);
        }
        if ($filters['category'] !== '') {
            $builder->where('packages.category_id', (int) $filters['category']);
        }
        if ($filters['status'] === 'active') {
            $builder->where('packages.is_active', 1);
        } elseif ($filters['status'] === 'hidden') {
            $builder->where('packages.is_active', 0);
        } elseif ($filters['status'] === 'featured') {
            $builder->where('packages.is_featured', 1);
        }

        $rows = $builder->paginate(self::PER_PAGE, 'default', max(1, (int) $this->request->getGet('page')));

        return [
            'rows'       => $rows,
            'pager'      => $model->pager,
            'total'      => $model->pager->getTotal('default'),
            'filters'    => $filters,
            'categories' => (new CategoryModel())->active(),
        ];
    }

    public function new(): string
    {
        return view('admin/packages/form', $this->layout($this->formData() + [
            'title'       => 'New package',
            'heading'     => 'New package',
            'subheading'  => 'Add a tour to the site.',
            'activeAdmin' => 'packages',
            'package'     => null,
            'inclusions'  => [],
            'departures'  => [],
            'errors'      => [],
        ]));
    }

    public function edit(int $id): string
    {
        $package = (new PackageModel())->find($id);

        if ($package === null) {
            return redirect()->to(site_url('admin/packages'))->with('error', 'That package no longer exists.');
        }

        return view('admin/packages/form', $this->layout($this->formData() + [
            'title'       => 'Edit package',
            'heading'     => $package['title'],
            'subheading'  => 'Editing an existing package.',
            'activeAdmin' => 'packages',
            'package'     => $package,
            'inclusions'  => (new PackageInclusionModel())->forPackage($id),
            'departures'  => (new PackageDepartureModel())->forPackage($id),
            'errors'      => [],
        ]));
    }

    private function formData(): array
    {
        return [
            'categories'   => (new CategoryModel())->active(),
            'destinations' => (new DestinationModel())->active(),
            'tourTypes'    => (new TourTypeModel())->active(),
        ];
    }

    public function create()
    {
        return $this->persist(null);
    }

    public function update(int $id)
    {
        return $this->persist($id);
    }

    /** Shared create/update path — the form posts the same fields either way. */
    private function persist(?int $id)
    {
        $model    = new PackageModel();
        $existing = $id !== null ? $model->find($id) : null;

        if ($id !== null && $existing === null) {
            return redirect()->to(site_url('admin/packages'))->with('error', 'That package no longer exists.');
        }

        $rules = [
            'title' => 'required|max_length[180]',
            'price' => 'permit_empty|numeric|greater_than_equal_to[0]',
        ];

        if (! $this->validate($rules)) {
            return view('admin/packages/form', $this->layout($this->formData() + [
                'title'       => $id === null ? 'New package' : 'Edit package',
                'heading'     => $id === null ? 'New package' : (string) $this->request->getPost('title'),
                'activeAdmin' => 'packages',
                'package'     => array_merge($existing ?? [], $this->request->getPost()),
                'inclusions'  => $this->postedInclusions(),
                'departures'  => $this->postedDepartures(),
                'errors'      => $this->validator->getErrors(),
            ]));
        }

        $days   = (int) ($this->request->getPost('duration_days') ?: 1);
        $nights = (int) ($this->request->getPost('duration_nights') ?: 0);

        // Trim-or-null for text; number-or-null for the fee/rate money fields.
        $text = fn (string $k): ?string => ($v = trim((string) $this->request->getPost($k))) !== '' ? $v : null;
        $money = fn (string $k): ?float => ($v = trim((string) $this->request->getPost($k))) !== '' ? (float) $v : null;

        $data = [
            'title'           => $this->request->getPost('title'),
            'category_id'     => $this->request->getPost('category_id') ?: null,
            'destination_id'  => $this->request->getPost('destination_id') ?: null,
            'tour_type_id'    => $this->request->getPost('tour_type_id') ?: null,
            'summary'         => $this->request->getPost('summary'),
            'description'     => $this->request->getPost('description'),
            'image_alt'       => $this->request->getPost('image_alt'),
            'duration_days'   => $days,
            'duration_nights' => $nights,
            'duration_label'  => $this->request->getPost('duration_label') ?: $this->durationLabel($days, $nights),
            'price'           => (float) ($this->request->getPost('price') ?: 0),
            'currency'        => $this->request->getPost('currency') ?: 'KES',
            'group_size'      => $this->request->getPost('group_size'),
            'region'          => $text('region'),
            'county'          => $text('county'),
            'entrance_fee'    => $money('entrance_fee'),
            'nearby_hotel'    => $text('nearby_hotel'),
            'hotel_rate'      => $money('hotel_rate'),
            'nearby_cottage'  => $text('nearby_cottage'),
            'cottage_rate'    => $money('cottage_rate'),
            'availability'    => array_key_exists((string) $this->request->getPost('availability'), PackageModel::AVAILABILITY)
                ? $this->request->getPost('availability') : 'available',
            'spots_available' => ($s = trim((string) $this->request->getPost('spots_available'))) !== '' ? (int) $s : null,
            'deposit_amount'  => $money('deposit_amount'),
            'is_featured'     => $this->request->getPost('is_featured') ? 1 : 0,
            'is_active'       => $this->request->getPost('is_active') ? 1 : 0,
            'sort_order'      => (int) ($this->request->getPost('sort_order') ?: 0),
        ];

        // A blank slug field means "derive it from the title".
        $slugSource   = trim((string) $this->request->getPost('slug')) ?: $data['title'];
        $data['slug'] = $this->uniqueSlug($slugSource, 'packages', $id);

        try {
            $uploaded = $this->handleUpload('image_file', 'packages');
        } catch (\RuntimeException $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }

        if ($uploaded !== null) {
            $this->deleteUpload($existing['image'] ?? null);
            $data['image'] = $uploaded;
        } elseif (($url = trim((string) $this->request->getPost('image_url'))) !== '') {
            $data['image'] = $url;
        }

        if ($id === null) {
            if (($id = $this->insertRow($model, $data)) === null) {
                return redirect()->back()->withInput()->with('error', implode(' ', $model->errors()));
            }
            $message = 'Package created.';
        } else {
            if (! $this->saveRow($model, $id, $data)) {
                return redirect()->back()->withInput()->with('error', implode(' ', $model->errors()));
            }
            $message = 'Package saved.';
        }

        $this->saveInclusions($id);
        $this->saveDepartures($id);

        return redirect()->to(site_url('admin/packages'))->with('message', $message);
    }

    /** Reads the repeatable departure rows off the posted form. */
    private function postedDepartures(): array
    {
        $dates   = (array) $this->request->getPost('departure_date');
        $returns = (array) $this->request->getPost('departure_return');
        $spots   = (array) $this->request->getPost('departure_spots');
        $notes   = (array) $this->request->getPost('departure_note');
        $rows    = [];

        foreach ($dates as $i => $date) {
            $date = trim((string) $date);
            if ($date === '') {
                continue;
            }
            $rows[] = [
                'depart_date' => $date,
                'return_date' => trim((string) ($returns[$i] ?? '')) ?: null,
                'spots'       => ($s = trim((string) ($spots[$i] ?? ''))) !== '' ? (int) $s : null,
                'note'        => trim((string) ($notes[$i] ?? '')) ?: null,
            ];
        }

        return $rows;
    }

    /** Departures are replaced wholesale, like inclusions. */
    private function saveDepartures(int $packageId): void
    {
        $model = new PackageDepartureModel();
        $model->where('package_id', $packageId)->delete();

        $rows = [];
        foreach ($this->postedDepartures() as $i => $row) {
            $rows[] = $row + ['package_id' => $packageId, 'sort_order' => $i + 1];
        }

        if ($rows !== []) {
            $model->insertBatch($rows);
        }
    }

    /** Reads the repeatable inclusion rows off the posted form. */
    private function postedInclusions(): array
    {
        $items    = (array) $this->request->getPost('inclusion_item');
        $included = (array) $this->request->getPost('inclusion_included');
        $rows     = [];

        foreach ($items as $i => $item) {
            $item = trim((string) $item);
            if ($item !== '') {
                $rows[] = ['item' => $item, 'is_included' => (int) ($included[$i] ?? 1)];
            }
        }

        return $rows;
    }

    /** Inclusions are replaced wholesale — simpler than diffing, and the list is short. */
    private function saveInclusions(int $packageId): void
    {
        $model = new PackageInclusionModel();
        $model->where('package_id', $packageId)->delete();

        $rows = [];
        foreach ($this->postedInclusions() as $i => $row) {
            $rows[] = $row + ['package_id' => $packageId, 'sort_order' => $i + 1];
        }

        if ($rows !== []) {
            $model->insertBatch($rows);
        }
    }

    private function durationLabel(int $days, int $nights): string
    {
        $label = $days . ' ' . ($days === 1 ? 'Day' : 'Days');

        if ($nights > 0) {
            $label .= ' · ' . $nights . ' ' . ($nights === 1 ? 'Night' : 'Nights');
        }

        return $label;
    }

    /** Flips is_active straight from the table row. */
    public function toggle(int $id)
    {
        $model   = new PackageModel();
        $package = $model->find($id);

        if ($package !== null) {
            $model->update($id, ['is_active' => $package['is_active'] ? 0 : 1]);
            $this->toast($package['is_active'] ? 'Package hidden.' : 'Package published.');
        }

        return view('admin/packages/_table', $this->listData());
    }

    public function delete(int $id)
    {
        $model   = new PackageModel();
        $package = $model->find($id);

        if ($package !== null) {
            // Soft delete, so any enquiry that references it keeps its link.
            $model->delete($id);
            $this->toast('Package deleted.');
        }

        return view('admin/packages/_table', $this->listData());
    }
}
