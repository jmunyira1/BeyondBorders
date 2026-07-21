<?php

namespace App\Controllers\Admin;

use App\Models\PostCategoryModel;
use App\Models\PostModel;

class Posts extends AdminController
{
    private const PER_PAGE = 20;

    public function index(): string
    {
        return view('admin/posts/index', $this->layout($this->listData() + [
            'title'       => 'Blog posts',
            'heading'     => 'Blog posts',
            'subheading'  => 'The journal shown at /blog.',
            'activeAdmin' => 'posts',
        ]));
    }

    public function list(): string
    {
        return view('admin/posts/_table', $this->listData());
    }

    private function listData(): array
    {
        $model   = new PostModel();
        $filters = [
            'q'      => trim((string) $this->request->getGet('q')),
            'status' => (string) $this->request->getGet('status'),
        ];

        $builder = $model->select('posts.*, post_categories.name AS category_name')
            ->join('post_categories', 'post_categories.id = posts.post_category_id', 'left')
            ->orderBy('posts.published_at', 'DESC');

        if ($filters['q'] !== '') {
            $builder->like('posts.title', $filters['q']);
        }
        if ($filters['status'] === 'published') {
            $builder->where('posts.is_published', 1);
        } elseif ($filters['status'] === 'draft') {
            $builder->where('posts.is_published', 0);
        }

        $rows = $builder->paginate(self::PER_PAGE, 'default', max(1, (int) $this->request->getGet('page')));

        return [
            'rows'       => $rows,
            'pager'      => $model->pager,
            'total'      => $model->pager->getTotal('default'),
            'filters'    => $filters,
            'categories' => (new PostCategoryModel())->active(),
        ];
    }

    public function new(): string
    {
        return view('admin/posts/form', $this->layout([
            'title'       => 'New post',
            'heading'     => 'New post',
            'subheading'  => 'Write something for the journal.',
            'activeAdmin' => 'posts',
            'post'        => null,
            'categories'  => (new PostCategoryModel())->active(),
            'errors'      => [],
        ]));
    }

    public function edit(int $id): string
    {
        $post = (new PostModel())->find($id);

        if ($post === null) {
            return redirect()->to(site_url('admin/posts'))->with('error', 'That post no longer exists.');
        }

        return view('admin/posts/form', $this->layout([
            'title'       => 'Edit post',
            'heading'     => $post['title'],
            'subheading'  => 'Editing an existing post.',
            'activeAdmin' => 'posts',
            'post'        => $post,
            'categories'  => (new PostCategoryModel())->active(),
            'errors'      => [],
        ]));
    }

    public function create()
    {
        return $this->persist(null);
    }

    public function update(int $id)
    {
        return $this->persist($id);
    }

    private function persist(?int $id)
    {
        $model    = new PostModel();
        $existing = $id !== null ? $model->find($id) : null;

        if ($id !== null && $existing === null) {
            return redirect()->to(site_url('admin/posts'))->with('error', 'That post no longer exists.');
        }

        if (! $this->validate(['title' => 'required|max_length[200]'])) {
            return view('admin/posts/form', $this->layout([
                'title'       => $id === null ? 'New post' : 'Edit post',
                'heading'     => $id === null ? 'New post' : (string) $this->request->getPost('title'),
                'activeAdmin' => 'posts',
                'post'        => array_merge($existing ?? [], $this->request->getPost()),
                'categories'  => (new PostCategoryModel())->active(),
                'errors'      => $this->validator->getErrors(),
            ]));
        }

        $publishedAt = trim((string) $this->request->getPost('published_at'));

        $data = [
            'title'            => $this->request->getPost('title'),
            'post_category_id' => $this->request->getPost('post_category_id') ?: null,
            'excerpt'          => $this->request->getPost('excerpt'),
            'body'             => $this->request->getPost('body'),
            'image_alt'        => $this->request->getPost('image_alt'),
            'author'           => $this->request->getPost('author') ?: site('companyName'),
            'read_minutes'     => (int) ($this->request->getPost('read_minutes') ?: $this->estimateReadTime((string) $this->request->getPost('body'))),
            'is_published'     => $this->request->getPost('is_published') ? 1 : 0,
            'published_at'     => $publishedAt !== ''
                ? date('Y-m-d H:i:s', strtotime($publishedAt))
                : ($existing['published_at'] ?? date('Y-m-d H:i:s')),
        ];

        $slugSource   = trim((string) $this->request->getPost('slug')) ?: $data['title'];
        $data['slug'] = $this->uniqueSlug($slugSource, 'posts', $id);

        try {
            $uploaded = $this->handleUpload('image_file', 'blog');
        } catch (\RuntimeException $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }

        if ($uploaded !== null) {
            $this->deleteUpload($existing['image'] ?? null);
            $data['image'] = $uploaded;
        } elseif (($url = trim((string) $this->request->getPost('image_url'))) !== '') {
            $data['image'] = $url;
        }

        if ($id === null) {
            if ($this->insertRow($model, $data) === null) {
                return redirect()->back()->withInput()->with('error', implode(' ', $model->errors()));
            }
            $message = 'Post created.';
        } else {
            if (! $this->saveRow($model, $id, $data)) {
                return redirect()->back()->withInput()->with('error', implode(' ', $model->errors()));
            }
            $message = 'Post saved.';
        }

        return redirect()->to(site_url('admin/posts'))->with('message', $message);
    }

    /** ~200 words a minute, rounded up, minimum one. */
    private function estimateReadTime(string $body): int
    {
        return max(1, (int) ceil(str_word_count(strip_tags($body)) / 200));
    }

    public function delete(int $id)
    {
        $model = new PostModel();

        if (($post = $model->find($id)) !== null) {
            $this->deleteUpload($post['image']);
            $model->delete($id);
            $this->toast('Post deleted.');
        }

        return view('admin/posts/_table', $this->listData());
    }
}
