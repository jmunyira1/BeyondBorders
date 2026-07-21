<?php

namespace App\Models;

class TourTypeModel extends TaxonomyModel
{
    protected $table         = 'tour_types';
    protected $allowedFields = ['name', 'slug', 'description', 'sort_order', 'is_active'];

    protected $validationRules = [
        // Required so the {id} placeholder in the is_unique rule below resolves.
        'id' => 'permit_empty|is_natural_no_zero',
        'name' => 'required|max_length[120]',
        'slug' => 'required|max_length[140]|is_unique[tour_types.slug,id,{id}]',
    ];
}
