<?php

namespace App\Controllers\Admin;

use App\Models\CategoryModel;
use App\Models\DestinationModel;
use App\Models\PostCategoryModel;
use App\Models\TourTypeModel;
use CodeIgniter\Exceptions\PageNotFoundException;

/**
 * One screen for all four lookup tables. The {type} segment selects which model
 * and which extra columns the form shows.
 */
class Taxonomy extends AdminController
{
    private const TYPES = [
        'categories' => [
            'model'    => CategoryModel::class,
            'table'    => 'categories',
            'label'    => 'Categories',
            'singular' => 'category',
            'nav'      => 'tax-categories',
            'blurb'    => 'The trip categories used by the filter and the homepage tiles.',
            'fields'   => ['icon', 'description'],
        ],
        'destinations' => [
            'model'    => DestinationModel::class,
            'table'    => 'destinations',
            'label'    => 'Destinations',
            'singular' => 'destination',
            'nav'      => 'tax-destinations',
            'blurb'    => 'Places a package can be assigned to. Used by the destination filter.',
            'fields'   => ['region', 'description'],
        ],
        'tour-types' => [
            'model'    => TourTypeModel::class,
            'table'    => 'tour_types',
            'label'    => 'Tour types',
            'singular' => 'tour type',
            'nav'      => 'tax-tour-types',
            'blurb'    => 'How a trip runs — group, private, day trip and so on.',
            'fields'   => ['description'],
        ],
        'post-categories' => [
            'model'    => PostCategoryModel::class,
            'table'    => 'post_categories',
            'label'    => 'Post categories',
            'singular' => 'post category',
            'nav'      => 'tax-post-categories',
            'blurb'    => 'Sections of the journal.',
            'fields'   => [],
        ],
    ];

    private array $type;
    private string $typeKey;

    /** Resolves the {type} segment, or 404s. */
    private function resolve(string $type): void
    {
        if (! isset(self::TYPES[$type])) {
            throw PageNotFoundException::forPageNotFound('Unknown taxonomy "' . $type . '".');
        }

        $this->typeKey = $type;
        $this->type    = self::TYPES[$type];
    }

    private function model()
    {
        return new ($this->type['model'])();
    }

    private function rows(): array
    {
        return $this->model()
            ->orderBy('sort_order', 'ASC')
            ->orderBy('name', 'ASC')
            ->findAll();
    }

    /** Rows plus a count of how many packages/posts reference each one. */
    private function rowsWithUsage(): array
    {
        $rows = $this->rows();
        $db   = db_connect();

        [$table, $column] = match ($this->typeKey) {
            'categories'      => ['packages', 'category_id'],
            'destinations'    => ['packages', 'destination_id'],
            'tour-types'      => ['packages', 'tour_type_id'],
            'post-categories' => ['posts', 'post_category_id'],
        };

        $counts = [];
        foreach ($db->table($table)
            ->select($column . ' AS id, COUNT(*) AS n')
            ->where('deleted_at', null)
            ->groupBy($column)
            ->get()
            ->getResultArray() as $row) {
            $counts[(int) $row['id']] = (int) $row['n'];
        }

        foreach ($rows as $i => $row) {
            $rows[$i]['usage'] = $counts[(int) $row['id']] ?? 0;
        }

        return $rows;
    }

    public function index(string $type): string
    {
        $this->resolve($type);

        return view('admin/taxonomy/index', $this->layout([
            'title'       => $this->type['label'],
            'heading'     => $this->type['label'],
            'subheading'  => $this->type['blurb'],
            'activeAdmin' => $this->type['nav'],
            'typeKey'     => $type,
            'meta'        => $this->type,
            'rows'        => $this->rowsWithUsage(),
        ]));
    }

    public function create(string $type)
    {
        $this->resolve($type);

        $name = trim((string) $this->request->getPost('name'));

        if ($name === '') {
            $this->toast('A name is required.', 'error');

            return $this->listView();
        }

        $data = [
            'name'       => $name,
            'slug'       => $this->uniqueSlug(
                trim((string) $this->request->getPost('slug')) ?: $name,
                $this->type['table']
            ),
            'sort_order' => $this->model()->countAllResults() + 1,
            'is_active'  => 1,
        ];

        foreach ($this->type['fields'] as $field) {
            $data[$field] = $this->request->getPost($field);
        }

        $this->model()->insert($data);
        $this->toast(ucfirst($this->type['singular']) . ' added.');

        return $this->listView();
    }

    public function update(string $type, int $id)
    {
        $this->resolve($type);

        $data = [
            'name'       => $this->request->getPost('name'),
            'slug'       => $this->uniqueSlug(
                trim((string) $this->request->getPost('slug')) ?: (string) $this->request->getPost('name'),
                $this->type['table'],
                $id
            ),
            'sort_order' => (int) $this->request->getPost('sort_order'),
            'is_active'  => $this->request->getPost('is_active') ? 1 : 0,
        ];

        foreach ($this->type['fields'] as $field) {
            $data[$field] = $this->request->getPost($field);
        }

        if ($this->saveRow($this->model(), $id, $data)) {
            $this->toast('Saved.');
        }

        return $this->listView();
    }

    public function delete(string $type, int $id)
    {
        $this->resolve($type);

        // The FK is ON DELETE SET NULL, so referencing rows survive with an
        // empty taxonomy rather than disappearing.
        $this->model()->delete($id);
        $this->toast(ucfirst($this->type['singular']) . ' deleted.');

        return $this->listView();
    }

    private function listView(): string
    {
        return view('admin/taxonomy/_list', [
            'rows'    => $this->rowsWithUsage(),
            'typeKey' => $this->typeKey,
            'meta'    => $this->type,
        ]);
    }
}
