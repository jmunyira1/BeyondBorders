<?php

namespace App\Controllers\Admin;

use App\Models\GalleryImageModel;

class Gallery extends AdminController
{
    public function index(): string
    {
        return view('admin/gallery/index', $this->layout([
            'title'       => 'Gallery',
            'heading'     => 'Gallery',
            'subheading'  => 'Photographs shown on the gallery page and the homepage teaser.',
            'activeAdmin' => 'gallery',
            'rows'        => $this->rows(),
        ]));
    }

    private function rows(): array
    {
        return (new GalleryImageModel())
            ->orderBy('sort_order', 'ASC')
            ->orderBy('id', 'ASC')
            ->findAll();
    }

    public function create()
    {
        $model = new GalleryImageModel();

        try {
            $uploaded = $this->handleUpload('image_file', 'gallery');
        } catch (\RuntimeException $e) {
            $this->toast($e->getMessage(), 'error');

            return view('admin/gallery/_grid', ['rows' => $this->rows()]);
        }

        $path = $uploaded ?? trim((string) $this->request->getPost('image_url'));

        if ($path === '') {
            $this->toast('Choose a file or paste an image URL.', 'error');

            return view('admin/gallery/_grid', ['rows' => $this->rows()]);
        }

        $model->insert([
            'path'       => $path,
            'caption'    => $this->request->getPost('caption'),
            'alt'        => $this->request->getPost('alt') ?: $this->request->getPost('caption'),
            'sort_order' => (int) ($this->request->getPost('sort_order') ?: ($model->countAllResults() + 1)),
            'is_active'  => 1,
        ]);

        $this->toast('Image added.');

        return view('admin/gallery/_grid', ['rows' => $this->rows()]);
    }

    public function update(int $id)
    {
        (new GalleryImageModel())->update($id, [
            'caption'    => $this->request->getPost('caption'),
            'alt'        => $this->request->getPost('alt'),
            'sort_order' => (int) $this->request->getPost('sort_order'),
            'is_active'  => $this->request->getPost('is_active') ? 1 : 0,
        ]);

        $this->toast('Image updated.');

        return view('admin/gallery/_grid', ['rows' => $this->rows()]);
    }

    public function delete(int $id)
    {
        $model = new GalleryImageModel();

        if (($row = $model->find($id)) !== null) {
            $this->deleteUpload($row['path']);
            $model->delete($id);
            $this->toast('Image removed.');
        }

        return view('admin/gallery/_grid', ['rows' => $this->rows()]);
    }
}
