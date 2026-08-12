<?php

namespace App\Controllers;

use App\Models\CategoryModel;
use App\Models\DestinationModel;
use App\Models\GalleryImageModel;
use App\Models\PackageModel;
use App\Models\PostModel;
use App\Models\TestimonialModel;

class Home extends BaseController
{
    public function index(): string
    {
        // Real counts only — the stat band shows what's genuinely in the
        // catalogue, never invented "500+ happy travellers" numbers.
        $stats = [
            'packages'     => (new PackageModel())->where('is_active', 1)->countAllResults(),
            'categories'   => (new CategoryModel())->where('is_active', 1)->countAllResults(),
            'destinations' => (new DestinationModel())->where('is_active', 1)->countAllResults(),
            'reviews'      => (new TestimonialModel())->where('is_active', 1)->countAllResults(),
        ];

        return view('pages/home', [
            'title'        => site('companyName') . ' — Kenya Safaris, Tours & Travel',
            'activeNav'    => 'home',
            'categories'   => (new CategoryModel())->withPackageCounts(),
            'featured'     => (new PackageModel())->featured(3),
            // Embedded so search-as-you-type runs with no network round-trip.
            'searchIndex'  => (new PackageModel())->searchIndex(),
            'testimonials' => (new TestimonialModel())->active(3),
            'gallery'      => (new GalleryImageModel())->active(8),
            // Hero slideshow: admin-flagged images if any, else falls back to the
            // active gallery (handled in the view).
            'heroImages'   => (new GalleryImageModel())->forHero(6),
            'posts'        => (new PostModel())->recent(3),
            'stats'        => $stats,
        ]);
    }
}
