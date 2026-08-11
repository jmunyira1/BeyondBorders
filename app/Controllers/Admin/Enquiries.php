<?php

namespace App\Controllers\Admin;

use App\Models\EnquiryModel;
use App\Models\PackageDepartureModel;
use App\Models\PackageModel;

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

        if (! in_array($status, ['new', 'read', 'replied', 'booked', 'closed'], true)) {
            return $this->response->setStatusCode(400)->setBody('Unknown status.');
        }

        $model = new EnquiryModel();
        $enq   = $model->find($id);

        if ($enq === null) {
            return $this->response->setStatusCode(404)->setBody('Not found.');
        }

        $old = $enq['status'];
        $model->update($id, ['status' => $status]);

        // Booking a trip takes a spot; un-booking gives it back.
        if ($status === 'booked' && $old !== 'booked') {
            $this->adjustSpots($enq, -1);
        } elseif ($old === 'booked' && $status !== 'booked') {
            $this->adjustSpots($enq, +1);
        }

        $this->toast($status === 'booked' ? 'Marked as booked — a spot was taken.' : 'Marked as ' . $status . '.');

        return view('admin/enquiries/_table', $this->listData());
    }

    /**
     * Moves the spot count on the departure the enquiry was for, or — when no
     * departure was chosen — on the package itself. Only touches counts that
     * are actually being managed (non-null); auto-flips a package to sold-out
     * at zero and back to available when a booking is reversed.
     */
    private function adjustSpots(array $enq, int $delta): void
    {
        if (! empty($enq['departure_id'])) {
            $dm  = new PackageDepartureModel();
            $dep = $dm->find((int) $enq['departure_id']);
            if ($dep !== null && $dep['spots'] !== null) {
                $dm->update($dep['id'], ['spots' => max(0, (int) $dep['spots'] + $delta)]);
            }

            return;
        }

        if (empty($enq['package_id'])) {
            return;
        }

        $pm      = new PackageModel();
        $package = $pm->find((int) $enq['package_id']);
        if ($package === null || $package['spots_available'] === null) {
            return;
        }

        $spots  = max(0, (int) $package['spots_available'] + $delta);
        $update = ['spots_available' => $spots];
        if ($spots === 0) {
            $update['availability'] = 'sold_out';
        } elseif ($package['availability'] === 'sold_out') {
            $update['availability'] = 'available';
        }

        $pm->update((int) $enq['package_id'], $update);
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
