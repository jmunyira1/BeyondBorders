<?php

namespace App\Models;

use CodeIgniter\Model;

class FaqModel extends Model
{
    protected $table         = 'faqs';
    protected $primaryKey    = 'id';
    protected $useTimestamps = true;
    protected $returnType    = 'array';
    protected $allowedFields = ['question', 'answer', 'sort_order', 'is_active'];

    protected $validationRules = [
        'question' => 'required|max_length[255]',
        'answer'   => 'required',
    ];

    public function active(): array
    {
        return $this->where('is_active', 1)
            ->orderBy('sort_order', 'ASC')
            ->orderBy('id', 'ASC')
            ->findAll();
    }
}
