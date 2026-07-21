<?php

namespace App\Controllers;

use App\Models\PostCategoryModel;
use App\Models\PostModel;

class Blog extends BaseController
{
    private const PER_PAGE = 9;

    public function index(): string
    {
        $model    = new PostModel();
        $category = trim((string) $this->request->getGet('category'));
        $page     = max(1, (int) $this->request->getGet('page'));

        $posts = $model->published($category)->paginate(self::PER_PAGE, 'default', $page);

        return view('pages/blog', [
            'title'           => 'The Journal — ' . site('companyName'),
            'metaDescription' => 'Notes from the road — safari timing, packing, planning and the Kenyan coast, from the Beyond Borders Adventures team.',
            'activeNav'       => 'blog',
            'posts'           => $posts,
            'pager'           => $model->pager,
            'categories'      => (new PostCategoryModel())->active(),
            'activeCategory'  => $category,
        ]);
    }

    public function show(string $slug): string
    {
        $model = new PostModel();
        $post  = $model->findBySlug($slug);

        if ($post === null) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound(
                'No post found with the slug "' . $slug . '".'
            );
        }

        // Three most recent other posts, for the "keep reading" row.
        $more = array_values(array_filter(
            $model->recent(4),
            static fn ($p) => $p['id'] !== $post['id']
        ));

        return view('pages/post_detail', [
            'title'           => $post['title'] . ' — ' . site('companyName'),
            'metaDescription' => excerpt_of($post['excerpt'] ?: $post['body'], 155),
            'activeNav'       => 'blog',
            'post'            => $post,
            'more'            => array_slice($more, 0, 3),
        ]);
    }
}
