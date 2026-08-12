<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * "Show in homepage hero" flag for gallery images.
 *
 * When one or more active images are flagged, the homepage hero slideshow uses
 * exactly those; otherwise it falls back to the active gallery (the previous
 * behaviour). Defaulted to 0, so it is safe on a live table with existing rows.
 */
class AddIsHeroToGallery extends Migration
{
    public function up(): void
    {
        $this->forge->addColumn('gallery_images', [
            'is_hero' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0, 'after' => 'is_active'],
        ]);
    }

    public function down(): void
    {
        $this->forge->dropColumn('gallery_images', 'is_hero');
    }
}
