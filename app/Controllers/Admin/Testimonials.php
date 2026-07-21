<?php

namespace App\Controllers\Admin;

use App\Models\TestimonialModel;

class Testimonials extends AdminController
{
    public function index(): string
    {
        return view('admin/testimonials/index', $this->layout([
            'title'       => 'Testimonials',
            'heading'     => 'Testimonials',
            'subheading'  => 'Guest quotes shown on the homepage and the about page.',
            'activeAdmin' => 'testimonials',
            'rows'        => $this->rows(),
        ]));
    }

    private function rows(): array
    {
        return (new TestimonialModel())
            ->orderBy('sort_order', 'ASC')
            ->orderBy('id', 'ASC')
            ->findAll();
    }

    public function create()
    {
        $model = new TestimonialModel();

        $data = [
            'quote'           => trim((string) $this->request->getPost('quote')),
            'author_name'     => trim((string) $this->request->getPost('author_name')),
            'author_location' => $this->request->getPost('author_location'),
            'rating'          => (int) ($this->request->getPost('rating') ?: 5),
            'sort_order'      => $model->countAllResults() + 1,
            'is_active'       => 1,
        ];

        if ($data['quote'] === '' || $data['author_name'] === '') {
            $this->toast('A quote and an author name are both required.', 'error');

            return view('admin/testimonials/_list', ['rows' => $this->rows()]);
        }

        $model->insert($data);
        $this->toast('Testimonial added.');

        return view('admin/testimonials/_list', ['rows' => $this->rows()]);
    }

    public function update(int $id)
    {
        (new TestimonialModel())->update($id, [
            'quote'           => $this->request->getPost('quote'),
            'author_name'     => $this->request->getPost('author_name'),
            'author_location' => $this->request->getPost('author_location'),
            'rating'          => (int) ($this->request->getPost('rating') ?: 5),
            'sort_order'      => (int) $this->request->getPost('sort_order'),
            'is_active'       => $this->request->getPost('is_active') ? 1 : 0,
        ]);

        $this->toast('Testimonial updated.');

        return view('admin/testimonials/_list', ['rows' => $this->rows()]);
    }

    public function delete(int $id)
    {
        (new TestimonialModel())->delete($id);
        $this->toast('Testimonial deleted.');

        return view('admin/testimonials/_list', ['rows' => $this->rows()]);
    }
}
