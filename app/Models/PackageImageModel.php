<?php

namespace App\Models;

use CodeIgniter\Model;

class PackageImageModel extends Model
{
    protected $table         = 'package_images';
    protected $primaryKey    = 'id';
    protected $useTimestamps = true;
    protected $returnType    = 'array';
    protected $allowedFields = ['package_id', 'path', 'alt', 'sort_order'];

    public function forPackage(int $packageId): array
    {
        return $this->where('package_id', $packageId)
            ->orderBy('sort_order', 'ASC')
            ->orderBy('id', 'ASC')
            ->findAll();
    }
}
