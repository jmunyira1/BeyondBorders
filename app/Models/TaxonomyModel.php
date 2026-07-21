<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * Shared behaviour for the four simple name/slug lookup tables. Subclasses only
 * need to set $table and $allowedFields.
 */
abstract class TaxonomyModel extends Model
{
    protected $primaryKey    = 'id';
    protected $useTimestamps = true;
    protected $returnType    = 'array';

    /** Active rows in display order — what the filter dropdowns are built from. */
    public function active(): array
    {
        return $this->where('is_active', 1)
            ->orderBy('sort_order', 'ASC')
            ->orderBy('name', 'ASC')
            ->findAll();
    }

    public function findBySlug(string $slug): ?array
    {
        return $this->where('slug', $slug)->first();
    }

    /** id => name map, for admin <select> menus. */
    public function options(): array
    {
        return array_column($this->active(), 'name', 'id');
    }
}
