<?php

namespace App\Models;

use CodeIgniter\Model;

class PostModel extends Model
{
    protected $table          = 'posts';
    protected $primaryKey     = 'id';
    protected $useSoftDeletes = true;
    protected $useTimestamps  = true;
    protected $returnType     = 'array';
    protected $allowedFields  = [
        'title', 'slug', 'post_category_id', 'excerpt', 'body',
        'image', 'image_alt', 'author', 'read_minutes',
        'is_published', 'published_at',
    ];

    protected $validationRules = [
        // Required so the {id} placeholder in the is_unique rule below resolves.
        'id' => 'permit_empty|is_natural_no_zero',
        'title' => 'required|max_length[200]',
        'slug'  => 'required|max_length[220]|is_unique[posts.slug,id,{id}]',
    ];

    protected function withCategory()
    {
        return $this->select('posts.*, post_categories.name AS category_name, post_categories.slug AS category_slug')
            ->join('post_categories', 'post_categories.id = posts.post_category_id', 'left');
    }

    /** Published posts only, newest first. Scheduled future posts stay hidden. */
    public function published(?string $categorySlug = null)
    {
        $builder = $this->withCategory()
            ->where('posts.is_published', 1)
            ->where('posts.published_at <=', date('Y-m-d H:i:s'))
            ->orderBy('posts.published_at', 'DESC');

        if ($categorySlug !== null && $categorySlug !== '') {
            $builder->where('post_categories.slug', $categorySlug);
        }

        return $builder;
    }

    public function findBySlug(string $slug): ?array
    {
        return $this->withCategory()
            ->where('posts.slug', $slug)
            ->where('posts.is_published', 1)
            ->where('posts.published_at <=', date('Y-m-d H:i:s'))
            ->first();
    }

    public function recent(int $limit = 3): array
    {
        return $this->published()->findAll($limit);
    }
}
