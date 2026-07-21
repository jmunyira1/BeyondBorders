<?php

namespace App\Models;

use CodeIgniter\Model;

class GalleryImageModel extends Model
{
    protected $table         = 'gallery_images';
    protected $primaryKey    = 'id';
    protected $useTimestamps = true;
    protected $returnType    = 'array';
    protected $allowedFields = ['path', 'caption', 'alt', 'sort_order', 'is_active'];

    protected $validationRules = [
        'path' => 'required|max_length[255]',
    ];

    public function active(int $limit = 0): array
    {
        return $this->where('is_active', 1)
            ->orderBy('sort_order', 'ASC')
            ->orderBy('id', 'ASC')
            ->findAll($limit);
    }
}
