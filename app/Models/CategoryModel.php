<?php

namespace App\Models;

class CategoryModel extends TaxonomyModel
{
    protected $table         = 'categories';
    protected $allowedFields = ['name', 'slug', 'icon', 'description', 'sort_order', 'is_active'];

    protected $validationRules = [
        // Required so the {id} placeholder in the is_unique rule below resolves.
        'id' => 'permit_empty|is_natural_no_zero',
        'name' => 'required|max_length[120]',
        'slug' => 'required|max_length[140]|is_unique[categories.slug,id,{id}]',
    ];

    /**
     * Active categories with a count of their live packages — used for the
     * category tiles so an empty category can be hidden or greyed out.
     */
    public function withPackageCounts(): array
    {
        return $this->select('categories.*, COUNT(packages.id) AS package_count')
            ->join('packages', 'packages.category_id = categories.id AND packages.is_active = 1 AND packages.deleted_at IS NULL', 'left')
            ->where('categories.is_active', 1)
            ->groupBy('categories.id')
            ->orderBy('categories.sort_order', 'ASC')
            ->findAll();
    }
}
