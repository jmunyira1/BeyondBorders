<?php

namespace App\Models;

use CodeIgniter\Model;

class PackageInclusionModel extends Model
{
    protected $table         = 'package_inclusions';
    protected $primaryKey    = 'id';
    protected $useTimestamps = true;
    protected $returnType    = 'array';
    protected $allowedFields = ['package_id', 'item', 'is_included', 'sort_order'];

    public function forPackage(int $packageId): array
    {
        return $this->where('package_id', $packageId)
            ->orderBy('sort_order', 'ASC')
            ->orderBy('id', 'ASC')
            ->findAll();
    }

    /** Split into ['included' => [...], 'excluded' => [...]] for the detail page. */
    public function grouped(int $packageId): array
    {
        $rows = $this->forPackage($packageId);

        return [
            'included' => array_values(array_filter($rows, static fn ($r) => (int) $r['is_included'] === 1)),
            'excluded' => array_values(array_filter($rows, static fn ($r) => (int) $r['is_included'] === 0)),
        ];
    }
}
