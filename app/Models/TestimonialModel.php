<?php

namespace App\Models;

use CodeIgniter\Model;

class TestimonialModel extends Model
{
    protected $table         = 'testimonials';
    protected $primaryKey    = 'id';
    protected $useTimestamps = true;
    protected $returnType    = 'array';
    protected $allowedFields = ['quote', 'author_name', 'author_location', 'rating', 'sort_order', 'is_active'];

    protected $validationRules = [
        'quote'       => 'required',
        'author_name' => 'required|max_length[120]',
        'rating'      => 'permit_empty|is_natural|less_than_equal_to[5]',
    ];

    public function active(int $limit = 0): array
    {
        return $this->where('is_active', 1)
            ->orderBy('sort_order', 'ASC')
            ->orderBy('id', 'ASC')
            ->findAll($limit);
    }
}
