<?php

namespace App\Controllers\Admin;

use App\Models\EnquiryModel;

class Enquiries extends AdminController
{
    private const PER_PAGE = 20;

    public function index(): string
    {
        return view('admin/enquiries/index', $this->layout($this->listData() + [
            'title'       => 'Enquiries',
            'heading'     => 'Enquiries',
            'subheading'  => 'Everything submitted through the contact, custom-trip and booking forms.',
            'activeAdmin' => 'enquiries',
        ]));
    }

    /** The table on its own, for htmx filtering. */
    public function list(): string
    {
        return view('admin/enquiries/_table', $this->listData());
    }

    private function listData(): array
    {
        $model   = new EnquiryModel();
        $filters = [
            'type'   => (string) $this->request->getGet('type'),
            'status' => (string) $this->request->getGet('status'),
            'q'      => trim((string) $this->request->getGet('q')),
        ];

        $rows = $model->inbox($filters)->paginate(self::PER_PAGE, 'default', max(1, (int) $this->request->getGet('page')));

        return [
            'rows'    => $rows,
            'pager'   => $model->pager,
            'total'   => $model->pager->getTotal('default'),
            'filters' => $filters,
        ];
    }

    /** Detail panel, loaded into the shared modal. Marks 'new' as read. */
    public function show(int $id): string
    {
        $model = new EnquiryModel();
        $row   = $model->select('enquiries.*, packages.title AS package_title, packages.slug AS package_slug')
            ->join('packages', 'packages.id = enquiries.package_id', 'left')
            ->find($id);

        if ($row === null) {
            return '<div class="p-4">That enquiry no longer exists.</div>';
        }

        if ($row['status'] === 'new') {
            $model->update($id, ['status' => 'read']);
            $row['status'] = 'read';
        }

        return view('admin/enquiries/_detail', ['row' => $row]);
    }

    public function status(int $id)
    {
        $status = (string) $this->request->getPost('status');

        if (! in_array($status, ['new', 'read', 'replied', 'closed'], true)) {
            return $this->response->setStatusCode(400)->setBody('Unknown status.');
        }

        (new EnquiryModel())->update($id, ['status' => $status]);

        $this->toast('Marked as ' . $status . '.');

        return view('admin/enquiries/_table', $this->listData());
    }

    public function notes(int $id)
    {
        (new EnquiryModel())->update($id, ['admin_notes' => $this->request->getPost('admin_notes')]);

        return $this->toastAndClose('Note saved.')->setBody('');
    }

    public function delete(int $id)
    {
        (new EnquiryModel())->delete($id);

        $this->toast('Enquiry deleted.');

        return view('admin/enquiries/_table', $this->listData());
    }
}
