<?php

namespace App\Controllers\Admin;

use App\Models\EnquiryModel;
use App\Models\GalleryImageModel;
use App\Models\PackageModel;
use App\Models\PostModel;
use App\Models\TestimonialModel;

class Dashboard extends AdminController
{
    public function index(): string
    {
        $enquiries = new EnquiryModel();
        $packages  = new PackageModel();

        return view('admin/dashboard', $this->layout([
            'title'        => 'Dashboard',
            'heading'      => 'Dashboard',
            'subheading'   => 'What has happened on the site lately.',
            'activeAdmin'  => 'dashboard',
            'stats'        => [
                'new'      => $enquiries->where('status', 'new')->countAllResults(),
                'week'     => $enquiries->where('created_at >=', date('Y-m-d H:i:s', strtotime('-7 days')))->countAllResults(),
                'packages' => $packages->where('is_active', 1)->countAllResults(),
                'posts'    => (new PostModel())->where('is_published', 1)->countAllResults(),
            ],
            'counts' => [
                'gallery'      => (new GalleryImageModel())->countAllResults(),
                'testimonials' => (new TestimonialModel())->countAllResults(),
                'inactive'     => (new PackageModel())->where('is_active', 0)->countAllResults(),
            ],
            'latest'   => $enquiries->inbox()->findAll(8),
            'byType'   => $this->enquiryCountsByType(),
        ]));
    }

    /** Totals per form type, for the small breakdown card. */
    private function enquiryCountsByType(): array
    {
        $rows = (new EnquiryModel())
            ->select('type, COUNT(*) AS n')
            ->groupBy('type')
            ->findAll();

        $counts = array_fill_keys(array_keys(EnquiryModel::LABELS), 0);

        foreach ($rows as $row) {
            $counts[$row['type']] = (int) $row['n'];
        }

        return $counts;
    }
}
