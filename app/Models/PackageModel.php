<?php

namespace App\Models;

use CodeIgniter\Model;

class PackageModel extends Model
{
    protected $table            = 'packages';
    protected $primaryKey       = 'id';
    protected $useSoftDeletes   = true;
    protected $useTimestamps    = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'title', 'slug', 'category_id', 'destination_id', 'tour_type_id',
        'summary', 'description', 'image', 'image_alt',
        'duration_days', 'duration_nights', 'duration_label',
        'price', 'currency', 'group_size',
        'is_featured', 'is_active', 'sort_order',
    ];

    protected $validationRules = [
        // Required so the {id} placeholder in the is_unique rule below resolves.
        'id' => 'permit_empty|is_natural_no_zero',
        'title'      => 'required|max_length[180]',
        'slug'       => 'required|max_length[200]|is_unique[packages.slug,id,{id}]',
        'price'      => 'permit_empty|decimal|greater_than_equal_to[0]',
        'duration_days' => 'permit_empty|is_natural',
    ];

    /**
     * Price bands offered in the filter dropdown. Keyed by the value that
     * appears in the query string; `null` on either bound means open-ended.
     */
    public const PRICE_RANGES = [
        'under-10000' => ['label' => 'Under KES 10,000',        'min' => null,  'max' => 10000],
        '10000-30000' => ['label' => 'KES 10,000 – 30,000',     'min' => 10000, 'max' => 30000],
        '30000-60000' => ['label' => 'KES 30,000 – 60,000',     'min' => 30000, 'max' => 60000],
        'over-60000'  => ['label' => 'KES 60,000+',             'min' => 60000, 'max' => null],
    ];

    /** Duration bands, measured in `duration_days`. */
    public const DURATION_RANGES = [
        'day-trip' => ['label' => 'Day trip (1 day)', 'min' => 1, 'max' => 1],
        'short'    => ['label' => '2 – 3 days',       'min' => 2, 'max' => 3],
        'medium'   => ['label' => '4 – 6 days',       'min' => 4, 'max' => 6],
        'long'     => ['label' => '7+ days',          'min' => 7, 'max' => null],
    ];

    /** Sort options exposed to the visitor. */
    public const SORTS = [
        'recommended' => 'Recommended',
        'price-asc'   => 'Price: low to high',
        'price-desc'  => 'Price: high to low',
        'duration'    => 'Duration: shortest first',
        'newest'      => 'Newest first',
    ];

    /**
     * Base query joining the three taxonomies so cards can show their names and
     * the filter can match on slug as well as id.
     */
    protected function withTaxonomies()
    {
        return $this->select('packages.*,
                categories.name AS category_name, categories.slug AS category_slug, categories.icon AS category_icon,
                destinations.name AS destination_name, destinations.slug AS destination_slug,
                tour_types.name AS tour_type_name, tour_types.slug AS tour_type_slug')
            ->join('categories', 'categories.id = packages.category_id', 'left')
            ->join('destinations', 'destinations.id = packages.destination_id', 'left')
            ->join('tour_types', 'tour_types.id = packages.tour_type_id', 'left');
    }

    /**
     * Applies the packages-page filter. Every key in $filters is optional; an
     * empty string is treated as "any", which is what the dropdowns submit when
     * left on their placeholder option.
     *
     * Accepts either an id or a slug for category/destination/tour type, so the
     * homepage category tiles (?category=safari) and the admin's numeric links
     * both work.
     */
    public function filtered(array $filters = [])
    {
        $builder = $this->withTaxonomies()->where('packages.is_active', 1);

        foreach ([
            'category'    => ['categories', 'category_id'],
            'destination' => ['destinations', 'destination_id'],
            'tour_type'   => ['tour_types', 'tour_type_id'],
        ] as $key => [$table, $column]) {
            $value = trim((string) ($filters[$key] ?? ''));
            if ($value === '') {
                continue;
            }

            if (ctype_digit($value)) {
                $builder->where("packages.{$column}", (int) $value);
            } else {
                $builder->where("{$table}.slug", $value);
            }
        }

        $price = $filters['price'] ?? '';
        if (isset(self::PRICE_RANGES[$price])) {
            ['min' => $min, 'max' => $max] = self::PRICE_RANGES[$price];
            if ($min !== null) {
                $builder->where('packages.price >=', $min);
            }
            if ($max !== null) {
                $builder->where('packages.price <=', $max);
            }
        }

        $duration = $filters['duration'] ?? '';
        if (isset(self::DURATION_RANGES[$duration])) {
            ['min' => $min, 'max' => $max] = self::DURATION_RANGES[$duration];
            if ($min !== null) {
                $builder->where('packages.duration_days >=', $min);
            }
            if ($max !== null) {
                $builder->where('packages.duration_days <=', $max);
            }
        }

        $keyword = trim((string) ($filters['q'] ?? ''));
        if ($keyword !== '') {
            $builder->groupStart()
                ->like('packages.title', $keyword)
                ->orLike('packages.summary', $keyword)
                ->orLike('packages.description', $keyword)
                ->orLike('destinations.name', $keyword)
                ->orLike('categories.name', $keyword)
                ->groupEnd();
        }

        return $this->applySort($builder, $filters['sort'] ?? 'recommended');
    }

    protected function applySort($builder, string $sort)
    {
        return match ($sort) {
            'price-asc'  => $builder->orderBy('packages.price', 'ASC'),
            'price-desc' => $builder->orderBy('packages.price', 'DESC'),
            'duration'   => $builder->orderBy('packages.duration_days', 'ASC'),
            'newest'     => $builder->orderBy('packages.created_at', 'DESC'),
            default      => $builder->orderBy('packages.is_featured', 'DESC')
                                    ->orderBy('packages.sort_order', 'ASC')
                                    ->orderBy('packages.id', 'ASC'),
        };
    }

    public function featured(int $limit = 6): array
    {
        return $this->withTaxonomies()
            ->where('packages.is_active', 1)
            ->where('packages.is_featured', 1)
            ->orderBy('packages.sort_order', 'ASC')
            ->findAll($limit);
    }

    public function findBySlug(string $slug): ?array
    {
        return $this->withTaxonomies()
            ->where('packages.slug', $slug)
            ->where('packages.is_active', 1)
            ->first();
    }

    /** Other packages in the same category, for the "you may also like" row. */
    public function related(array $package, int $limit = 3): array
    {
        return $this->withTaxonomies()
            ->where('packages.is_active', 1)
            ->where('packages.id !=', $package['id'])
            ->groupStart()
                ->where('packages.category_id', $package['category_id'])
                ->orWhere('packages.destination_id', $package['destination_id'])
            ->groupEnd()
            ->orderBy('packages.is_featured', 'DESC')
            ->findAll($limit);
    }
}
