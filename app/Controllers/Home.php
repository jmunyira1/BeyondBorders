<?php

namespace App\Controllers;

use App\Models\CategoryModel;
use App\Models\GalleryImageModel;
use App\Models\PackageModel;
use App\Models\PostModel;
use App\Models\TestimonialModel;

class Home extends BaseController
{
    public function index(): string
    {
        return view('pages/home', [
            'title'        => site('companyName') . ' — Kenya Safaris, Tours & Travel',
            'activeNav'    => 'home',
            'categories'   => (new CategoryModel())->withPackageCounts(),
            'featured'     => (new PackageModel())->featured(6),
            // Embedded so search-as-you-type runs with no network round-trip.
            'searchIndex'  => (new PackageModel())->searchIndex(),
            'testimonials' => (new TestimonialModel())->active(3),
            'gallery'      => (new GalleryImageModel())->active(4),
            'posts'        => (new PostModel())->recent(3),
        ]);
    }
}
