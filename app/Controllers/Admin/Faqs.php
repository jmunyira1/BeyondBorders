<?php

namespace App\Controllers\Admin;

use App\Models\FaqModel;

class Faqs extends AdminController
{
    public function index(): string
    {
        return view('admin/faqs/index', $this->layout([
            'title'       => 'FAQs',
            'heading'     => 'FAQs',
            'subheading'  => 'The accordion on the contact page.',
            'activeAdmin' => 'faqs',
            'rows'        => $this->rows(),
        ]));
    }

    private function rows(): array
    {
        return (new FaqModel())
            ->orderBy('sort_order', 'ASC')
            ->orderBy('id', 'ASC')
            ->findAll();
    }

    public function create()
    {
        $model = new FaqModel();

        $question = trim((string) $this->request->getPost('question'));
        $answer   = trim((string) $this->request->getPost('answer'));

        if ($question === '' || $answer === '') {
            $this->toast('A question and an answer are both required.', 'error');

            return view('admin/faqs/_list', ['rows' => $this->rows()]);
        }

        $model->insert([
            'question'   => $question,
            'answer'     => $answer,
            'sort_order' => $model->countAllResults() + 1,
            'is_active'  => 1,
        ]);

        $this->toast('FAQ added.');

        return view('admin/faqs/_list', ['rows' => $this->rows()]);
    }

    public function update(int $id)
    {
        (new FaqModel())->update($id, [
            'question'   => $this->request->getPost('question'),
            'answer'     => $this->request->getPost('answer'),
            'sort_order' => (int) $this->request->getPost('sort_order'),
            'is_active'  => $this->request->getPost('is_active') ? 1 : 0,
        ]);

        $this->toast('FAQ updated.');

        return view('admin/faqs/_list', ['rows' => $this->rows()]);
    }

    public function delete(int $id)
    {
        (new FaqModel())->delete($id);
        $this->toast('FAQ deleted.');

        return view('admin/faqs/_list', ['rows' => $this->rows()]);
    }
}
